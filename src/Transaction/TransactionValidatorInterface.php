<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Transaction;

use Timesplinter\TxBlockchain\TransactionBlockchainInterface;
use Timesplinter\TxBlockchain\TransactionInterface;

interface TransactionValidatorInterface
{
    public function validate(TransactionInterface $transaction, TransactionBlockchainInterface $blockchain): bool;
}
