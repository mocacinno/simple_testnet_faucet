<?php
require_once('includes/easybitcoin.php');
require_once('includes/config.inc.php');
$bitcoin = new Bitcoin($rpcuser,$rpcpass,$rpchost,$rpcport);
$info= $bitcoin->getinfo();
echo "<h1>info about the wallet!</h1><pre>";
print_r($info);
echo "</pre>";
echo "<h1>pending claims</h1><br>";
$stmt = $conn->prepare("select sum(amount) as sum from faucet where payed = 0");
$stmt->execute();
$stmt->bind_result($sum);
$stmt->fetch();
if(!is_numeric($sum))
{
	$sum = 0;
}
echo $sum . " testnet satoshi's<br>";
$stmt->close();
$conn->close();
?>
<br>
<h1>make a claim</h1>
<form action="claim.php" method="post">
Address: <input type="text" name="address"><br>
<input type="submit" name="submit" value="claim"><br>
</form>

<?php
echo $disclaimer;
?>
