<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

use Timesplinter\Blockchain\BlockchainInterface;

/**
 * @author Pascal Muenst <pascal@timesplinter.ch>
 */
interface TransactionBlockchainInterface extends BlockchainInterface
{
    /**
     * Calculates the balance for a given address based on the transactions stored in the blockchain
     * @param string $address
     * @return float The balance for the given address
     */
    public function getBalanceForAddress(string $address): float;

    /**
     * Adds a transaction to the pool of pending transactions
     * @param TransactionInterface $transaction
     * @return bool True if transaction is valid and successfully added to the pool otherwise false
     */
    public function addTransaction(TransactionInterface $transaction): bool;

    /**
     * Returns all pending transactions which haven't been included in a mined block yet
     * @return array|TransactionInterface[]
     */
    public function getPendingTransactions(): array;
}
