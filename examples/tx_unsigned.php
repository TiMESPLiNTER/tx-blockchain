<?php

namespace Timesplinter\TxBlockchain\Tests;

use Timesplinter\Blockchain\Blockchain;
use Timesplinter\Blockchain\Storage\InMemory\InMemoryStorage;
use Timesplinter\Blockchain\Strategy\ProofOfWork\ProofOfWorkBlock as Block;
use Timesplinter\Blockchain\Strategy\ProofOfWork\ProofOfWorkStrategy;
use Timesplinter\TxBlockchain\SignedTransaction;
use Timesplinter\TxBlockchain\Transaction;
use Timesplinter\TxBlockchain\TransactionBlockchain;
use Timesplinter\TxBlockchain\TransactionSignatureException;

require __DIR__ . '/../vendor/autoload.php';

//
// Blockchain stuff
//
$blockchain = new Blockchain(
    new ProofOfWorkStrategy(2),
    new InMemoryStorage(),
    new Block('This is the genesis block', new \DateTime('1970-01-01'))
);

$txBlockchain = new TransactionBlockchain($blockchain);

$start = microtime(true);

// Mine block 1
$txBlockchain->addBlock($block1 = new Block([new Transaction(null, 'pascal', 200)], new \DateTime('2018-01-01')));
echo 'Block 1 successfully mined. Hash: ' , $block1->getHash() , PHP_EOL;

// Mine block 2
$txBlockchain->addBlock($block2 = new Block([], new \DateTime('2018-01-22')));
echo 'Block 2 successfully mined. Hash: ' , $block2->getHash() , PHP_EOL;

echo 'Duration: ' , round(microtime(true) - $start, 4) , ' seconds' , PHP_EOL;

// Check if blockchain is still valid
echo 'Blockchain valid: ' , print_r($blockchain->isValid(), true) , PHP_EOL;

// Add (unsigned) transaction to tx pool
while (true) {
    $tx = new Transaction('pascal', 'nick', 10);

    echo 'Transaction valid: ', ((int) $txBlockchain->addTransaction($tx)), PHP_EOL;

    echo 'There are now ', count($txBlockchain->getPendingTransactions()), ' pending transactions', PHP_EOL;

    $txBlockchain->addBlock($block3 = new Block([$tx], new \DateTime()));
    echo 'Block 3 successfully mined. Hash: ', $block3->getHash(), PHP_EOL;

    echo 'There are now ', count($txBlockchain->getPendingTransactions()), ' pending transactions', PHP_EOL;

    echo 'Nick\'s balance: ', $txBlockchain->getBalanceForAddress('nick'), PHP_EOL;
    echo 'Pascal\'s balance: ', $txBlockchain->getBalanceForAddress('pascal'), PHP_EOL;
}