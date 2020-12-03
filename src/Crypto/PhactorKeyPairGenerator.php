<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

use Phactor\Key as PhactorKey;

final class PhactorKeyPairGenerator implements KeyPairGeneratorInterface
{
    public function __construct()
    {
        if (!class_exists('Phactor\Key')) {
            throw new \RuntimeException('Phactor is not available');
        }
    }

    public function generate(): KeyPairInterface
    {
        $info = (new PhactorKey())->GenerateKeypair();

        return new KeyPair($info['public_key_compressed'], $info['private_key_hex']);
    }
}
