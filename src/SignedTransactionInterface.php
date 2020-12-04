<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

/**
 * @author Pascal Muenst <pascal@timesplinter.ch>
 */
interface SignedTransactionInterface
{
    public function getSignature(): ?string;

    public function setSignature(string $signature): void;

    public function isSigned(): bool;
}
