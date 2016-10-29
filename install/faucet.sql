CREATE TABLE faucet (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ip VARCHAR(30) NOT NULL,
address VARCHAR(255) NOT NULL,
amount int(20),
timestamp int(30) NOT NULL,
payed int(1),
txid varchar(255)
)
