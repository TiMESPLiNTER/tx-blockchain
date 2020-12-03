<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

final class KeyPair implements KeyPairInterface
{
    /**
     * @var string
     */
    private $public;

    /**
     * @var string
     */
    private $private;

    public function __construct(string $public, string $private)
    {
        $this->public = $public;
        $this->private = $private;
    }

    public function getPublic(): string
    {
        return $this->public;
    }

    public function getPrivate(): string
    {
        return $this->private;
    }
}
