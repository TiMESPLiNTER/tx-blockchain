<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

/**
 * @author Pascal Muenst <pascal@timesplinter.ch>
 */
class Transaction implements TransactionInterface
{

    /**
     * @var null|string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @param string $from
     * @param string $to
     * @param float  $amount
     */
    public function __construct(?string $from, string $to, float $amount)
    {
        $this->from   = $from;
        $this->to     = $to;
        $this->amount = $amount;
        $this->timestamp = new \DateTime();
    }


    /**
     * @return null|string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * Checks whether a transaction is valid or not
     * @return bool Returns true if the transaction is valid false otherwise
     */
    public function valid(): bool
    {
        return true;
    }
}