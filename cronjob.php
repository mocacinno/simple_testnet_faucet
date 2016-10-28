<?php
require_once('easybitcoin.php');
require_once('config.inc.php');
$bitcoin = new Bitcoin($rpcuser,$rpcpass,$rpchost,$rpcport);


$info= $bitcoin->getinfo();
echo "<h1>info about the wallet!</h1><pre>";
print_r($info);
echo "</pre>";


$stmt = $conn->prepare("select address, (sum(amount))/100000000 as amount from faucet where payed = 0 group by address");
$stmt->execute();
$stmt->bind_result($address, $amount);
$paydb = array();
while ($stmt->fetch()) {
	$paydb[$address] = $amount;
}

$stmt->close();

foreach ($paydb as $address => $amount)
{
	$amount = (double) $amount;
	$balance = $bitcoin->getbalance();
	if($amount > $balance){
	    echo "empty wallet<br>\n";	
	    return false;
	}
	$isValid = $bitcoin->validateaddress($address);
	$txid = $bitcoin->sendtoaddress($address, $amount);
	if($bitcoin->error){
 	   echo "ERROR: " . $bitcoin->error; 
	}
	else
	{
	   echo "payment of $amount sent to $address <br>\n";
	}
	$stmt = $conn->prepare("update faucet set txid = ? where address = ? and payed = 0");
	$stmt->bind_param("ss", $txid, $address);
	$stmt->execute();
	$stmt->close();
	$stmt = $conn->prepare("update faucet set payed = 1 where address = ? and payed = 0");
	$stmt->bind_param("s", $address);
        $stmt->execute();
        $stmt->close();
}
$conn->close();
?>
