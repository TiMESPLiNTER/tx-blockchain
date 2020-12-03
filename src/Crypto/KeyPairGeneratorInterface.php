<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

interface KeyPairGeneratorInterface
{
    public function generate(): KeyPairInterface;
}
