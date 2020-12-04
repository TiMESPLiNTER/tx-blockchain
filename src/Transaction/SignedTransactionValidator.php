<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Transaction;

use Timesplinter\TxBlockchain\Crypto\TransactionSignatureProviderInterface;
use Timesplinter\TxBlockchain\SignedTransaction;
use Timesplinter\TxBlockchain\SignedTransactionInterface;
use Timesplinter\TxBlockchain\TransactionBlockchainInterface;
use Timesplinter\TxBlockchain\TransactionInterface;

final class SignedTransactionValidator implements TransactionValidatorInterface
{
    private TransactionSignatureProviderInterface $transactionSignatureProvider;

    private TransactionValidatorInterface $transactionValidator;

    public function __construct(
        TransactionValidatorInterface $transactionValidator,
        TransactionSignatureProviderInterface $transactionSignatureProvider
    ) {
        $this->transactionSignatureProvider = $transactionSignatureProvider;
        $this->transactionValidator = $transactionValidator;
    }

    public function validate(TransactionInterface $transaction, TransactionBlockchainInterface $blockchain): bool
    {
        if (false === $transaction instanceof SignedTransactionInterface) {
            throw new \InvalidArgumentException(sprintf('Transaction must be of type %s', SignedTransaction::class));
        }

        /** @var SignedTransaction $transaction */

        if (null !== $transaction->getFrom() && (
            false === $transaction->isSigned() ||
            false === $this->transactionSignatureProvider->verify($transaction, $transaction->getFrom())
        )) {
            return false;
        }

        return $this->transactionValidator->validate($transaction, $blockchain);
    }
}
