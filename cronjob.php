<?php
require_once('easybitcoin.php');
require_once('config.inc.php');
$bitcoin = new Bitcoin($rpcuser,$rpcpass,$rpchost,$rpcport);
$stmt = $conn->prepare("select address, (sum(amount))/100000000 as amount from faucet where payed = 0 group by address");
$stmt->execute();
$stmt->bind_result($address, $amount);
$totaal = 0;
while ($stmt->fetch()) 
{
	$paydb[$address] = $amount;
	$totaal += $amount;
}
$stmt->close();
$balance = $bitcoin->getbalance();
if($totaal > $balance)
{
	echo "not enough in wallet<br>\n";
	return false;
}
$tx  = $bitcoin->sendmany($account,$paydb);
if($bitcoin->error)
{
	echo "ERROR: " . $bitcoin->error;
}
else
{
	echo "payments done with tx $tx<br>\n";
}
$stmt = $conn->prepare("update faucet set txid = ? where payed = 0");
$stmt->bind_param("s", $tx);
$stmt->execute();
$stmt->close();
$stmt = $conn->prepare("update faucet set payed = 1 where payed = 0");
$stmt->execute();
$stmt->close();
$conn->close();
?>
