<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

use Timesplinter\Blockchain\BlockchainInterface;
use Timesplinter\Blockchain\BlockInterface;
use Timesplinter\Blockchain\Storage\StorageInterface;

final class TransactionBlockchain implements TransactionBlockchainInterface
{

    /**
     * @var BlockchainInterface
     */
    private $blockchain;

    /**
     * @var array|TransactionInterface[]
     */
    private $pool = [];

    /**
     * @param BlockchainInterface $blockchain
     */
    public function __construct(BlockchainInterface $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    /**
     * @param TransactionInterface $transaction
     * @return bool
     */
    public function addTransaction(TransactionInterface $transaction): bool
    {
        if (false === $this->isTransactionValid($transaction)) {
            return false;
        }

        $this->pool[] = $transaction;

        return true;
    }

    /**
     * @param TransactionInterface $transaction
     * @return bool
     */
    public function isTransactionValid(TransactionInterface $transaction): bool
    {
        if (false === $transaction->valid()) {
            return false;
        }

        if (null !== $from = $transaction->getFrom()) {
            // Check if balance of sender is too low
            $balanceOfSender = $this->getBalanceForAddress($from);

            return $balanceOfSender >= $transaction->getAmount();
        }

        return true;
    }

    /**
     * @param string $address
     * @return float
     */
    public function getBalanceForAddress(string $address): float
    {
        $balance = 0.0;

        foreach ($this->blockchain as $block) {
            if (false === is_array($block->getData())) {
                continue;
            }

            foreach ($block->getData() as $transaction) {
                if (false === $transaction instanceof TransactionInterface) {
                    continue;
                }

                /** @var TransactionInterface $transaction */

                if ($address === $transaction->getFrom()) {
                    $balance -= $transaction->getAmount();
                } elseif ($address === $transaction->getTo()) {
                    $balance += $transaction->getAmount();
                }
            }
        }

        return $balance;
    }

    /**
     * Adds new block to the chain
     * @param BlockInterface $block
     * @return void
     */
    public function addBlock(BlockInterface $block): void
    {
        if (false === $this->isBlockDataValid($block)) {
            throw new \RuntimeException(
                sprintf('Data of block "%s" is not valid', $block->getHash())
            );
        }

        $this->blockchain->addBlock($block);

        // Remove all transactions from the pool which are included in the minded block
        $pendingTransactions = [];

        foreach ($this->pool as $transaction) {
            if (false === in_array($transaction, $block->getData(), true)) {
                $pendingTransactions[] = $transaction;
            }
        }

        $this->pool = $pendingTransactions;
    }

    /**
     * Returns latest block of the chain
     * @return BlockInterface
     */
    public function getLatestBlock(): BlockInterface
    {
        return $this->blockchain->getLatestBlock();
    }

    /**
     * Checks if the blockchain is in a valid state
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->blockchain->isValid();
    }

    /**
     * Checks if the block only contains valid transaction objects
     * @param BlockInterface $block
     * @return bool
     */
    private function isBlockDataValid(BlockInterface $block): bool
    {
        if (false === is_array($block->getData())) {
            return false;
        }

        foreach ($block->getData() as $transaction) {
            if (false === $transaction instanceof TransactionInterface) {
                return false;
            }

            /** @var TransactionInterface $transaction */

            if (false === $this->isTransactionValid($transaction)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return StorageInterface
     */
    public function getIterator(): StorageInterface
    {
        return $this->blockchain->getIterator();
    }

    /**
     * Returns all pending transactions which haven't been included in a mined block yet
     * @return array|TransactionInterface[]
     */
    public function getPendingTransactions(): array
    {
        return $this->pool;
    }

    /**
     * Returns the block at the specified position
     * @param int $position
     * @return BlockInterface
     * @throws \OutOfBoundsException
     */
    public function getBlock(int $position): BlockInterface
    {
        return $this->blockchain->getBlock($position);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->blockchain->count();
    }
}
