<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

/**
 * @author Pascal Muenst <pascal@timesplinter.ch>
 */
interface TransactionInterface
{

    /**
     * Returns the address from the transaction issuer
     * @return null|string
     */
    public function getFrom(): ?string;

    /**
     * Returns the address from the transaction receiver
     * @return string
     */
    public function getTo(): string;

    /**
     * Returns the amount of this transaction
     * @return float
     */
    public function getAmount(): float;

    /**
     * Returns the timestamp at which the transaction has been created
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime;
}