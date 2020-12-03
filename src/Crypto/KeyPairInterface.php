<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

interface KeyPairInterface
{
    public function getPublic(): string;

    public function getPrivate(): string;
}
