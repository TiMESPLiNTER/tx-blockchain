<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

/**
 * @author Pascal Muenst <pascal@timesplinter.ch>
 */
interface SignedTransactionInterface
{
    /**
     * @param string $privateKey
     * @return void
     * @throws TransactionSignatureException
     */
    public function sign(string $privateKey): void;

    /**
     * @return bool
     */
    public function isSignatureValid(): bool;
}
