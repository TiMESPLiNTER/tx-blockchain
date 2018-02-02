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
     * @param string $address
     * @return float
     */
    public function getBalanceForAddress(string $address): float;

    /**
     * @param TransactionInterface $transaction
     * @return bool
     */
    public function addTransaction(TransactionInterface $transaction): bool;

    /**
     * Returns all pending transactions which haven't been included in a mined block yet
     * @return array|TransactionInterface[]
     */
    public function getPendingTransactions(): array;
}
