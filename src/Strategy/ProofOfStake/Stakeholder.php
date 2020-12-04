<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Strategy\ProofOfStake;

use Timesplinter\Blockchain\BlockInterface;

final class Stakeholder
{
    /**
     * @var string
     */
    private $publicAddress;

    /**
     * @var float
     */
    private $stake;

    public function __construct(string $publicAddress)
    {
        $this->publicAddress = $publicAddress;
    }

    public function vote(BlockInterface $block): bool
    {
        return true;
    }

    public function getPublicAddress(): string
    {
        return $this->publicAddress;
    }

    public function getStake(): float
    {
        return $this->stake;
    }

    public function setStake(float $stake): void
    {
        $this->stake = $stake;
    }
}
