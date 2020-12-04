<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\Phactor;

use Phactor\Signature;
use Timesplinter\TxBlockchain\Crypto\TransactionSignatureProviderInterface;
use Timesplinter\TxBlockchain\SignedTransaction;
use Timesplinter\TxBlockchain\TransactionSignerException;

final class PhactorTransactionSignatureProvider implements TransactionSignatureProviderInterface
{
    private Signature $signature;

    public function __construct()
    {
        $this->signature = new Signature();
    }

    public function sign(SignedTransaction $transaction, string $privateKey): void
    {
        try {
            $transaction->setSignature($this->signature->Generate(json_encode($transaction), $privateKey));
        } catch (\Exception $e) {
            throw new TransactionSignerException('Could not sign transaction', 0, $e);
        }
    }

    public function verify(SignedTransaction $transaction, string $publicKey): bool
    {
        if (null === $from = $transaction->getFrom()) {
            return true;
        }

        if (null === $transactionSignature = $transaction->getSignature()) {
            return false;
        }

        try {
            return $this->signature->Verify($transactionSignature, json_encode($transaction), $from);
        } catch (\Exception $e) {
            throw new TransactionSignerException('Could not verify transaction signature', 0, $e);
        }
    }
}
