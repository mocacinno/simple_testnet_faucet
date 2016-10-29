<?php
require_once('../includes/easybitcoin.php');
require_once('../includes/config.inc.php');
$bitcoin = new Bitcoin($rpcuser,$rpcpass,$rpchost,$rpcport);
$stmt = $conn->prepare("select address, (sum(amount))/100000000 as amount from faucet where payed = 0 group by address having sum(amount) > ?");
$stmt->bind_param("s", $minsend);
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
foreach ($paydb as $key => $value)
{
	$stmt = $conn->prepare("update faucet set txid = ? where payed = 0 and address = ?");
	$stmt->bind_param("ss", $tx, $key);
	$stmt->execute();
	$stmt->close();
	$stmt = $conn->prepare("update faucet set payed = 1 where payed = 0 and address = ?");
	$stmt->bind_param("s", $key);
	$stmt->execute();
	$stmt->close();
}
$conn->close();
?>
