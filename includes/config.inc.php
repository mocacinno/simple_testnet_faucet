<?php
$rpcuser = 'user';
$rpcpass = 'pass';
$rpchost = 'localhost';
$rpcport = '18332';

$mysqluser = 'user';
$mysqlpass = 'pass';
$mysqlhost = 'localhost';
$mysqldb = 'faucet';

$mintimebetweenclaims = 36;
$claimamount = 1000000;
$payouttime = "sunday afternoon at 4:02 UTC";
$account="";
$minsend = 2000000;

//do not edit below this line
$conn = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqldb);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$disclaimer="this script was written as a TESTNET faucet script by mocacinno, it does not have the necessary security features to be run on the main net!!! Tipjar (BTC): 1MocACiWLM8bYn8pCrYjy6uHq4U3CkxLaa";
?>
