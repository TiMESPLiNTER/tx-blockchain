<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Transaction;

use Timesplinter\TxBlockchain\TransactionBlockchainInterface;
use Timesplinter\TxBlockchain\TransactionInterface;

final class TransactionValidator implements TransactionValidatorInterface
{
    public function validate(TransactionInterface $transaction, TransactionBlockchainInterface $blockchain): bool
    {
        if (null !== $from = $transaction->getFrom()) {
            // Check if balance of sender is too low
            $balanceOfSender = $blockchain->getBalanceForAddress($from);

            return $balanceOfSender >= $transaction->getAmount();
        }

        return true;
    }
}
