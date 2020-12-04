<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

final class SignedTransaction extends Transaction implements SignedTransactionInterface, \JsonSerializable
{

    /**
     * @var string|null
     */
    private $signature;

    /**
     * @return null|string
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
            'amount' => $this->getAmount(),
            'timestamp' => $this->getTimestamp()->format('c')
        ];
    }

    public function isSigned(): bool
    {
        return null !== $this->signature;
    }
}
