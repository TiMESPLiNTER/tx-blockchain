<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

use Timesplinter\TxBlockchain\SignedTransaction;

interface TransactionSignatureProviderInterface
{
    public function sign(SignedTransaction $transaction, string $privateKey): void;
    
    public function verify(SignedTransaction $transaction, string $publicKey): bool;
}
