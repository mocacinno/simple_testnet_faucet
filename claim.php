<?php
require_once('includes/config.inc.php');
require_once('includes/easybitcoin.php');
$bitcoin = new Bitcoin($rpcuser,$rpcpass,$rpchost,$rpcport);
$time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$address = $_POST['address'];
$isvalid = $bitcoin->validateaddress($address);
if (!$isvalid['isvalid'])
{
	die("invalid address format");
}
$stmt = $conn->prepare("select timestamp from faucet where ip = ? order by timestamp desc limit 1");
$stmt->bind_param("s", $ip); 
$stmt->execute();
$stmt->bind_result($timestamp);
$stmt->fetch();
if (($timestamp + $mintimebetweenclaims)>$time)
{
	die("at least $mintimebetweenclaims seconds are needed between two claims, please retry later");
}
$stmt->close();

$stmt = $conn->prepare("insert into faucet(ip, address, amount, timestamp, payed) values (?,?,?,?,0)");
$stmt->bind_param("ssss", $ip, $address, $claimamount, $time);
$stmt->execute();
$stmt->close();
echo "claim succesfully sheduled, payouts happen every $payouttime";


$conn->close();
?>
