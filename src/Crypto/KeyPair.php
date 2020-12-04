<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

final class KeyPair implements KeyPairInterface
{
    private string $public;

    private string $private;

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
