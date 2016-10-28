# simple_testnet_faucet
simple testnet faucet

This is a simple proof of concept testnet faucet using https://github.com/aceat64/EasyBitcoin-PHP


Since it's a proof of concept TESTNET faucet, there is little or no security, and the code isn't 100% clean. Do not run this faucet on the main net without completely rewriting it!!!


Installation is simple:

1) install bitcoind

2) install a bitcoin.conf file at ~/.bitcoin

3) run bitcoind on the testnet and let it sync "bitcoind -testnet -daemon"

4) create a new address "bitcoin-cli -testnet getnewaddress"

5) fund this address

6) clone this git

7) import the faucet.sql into your mysql db

8) edit config.inc.php

9) install a cronjob running "php cronjob.php"
