<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto;

use Mdanter\Ecc\EccFactory;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\HexPrivateKeySerializer;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\HexPublicKeySerializer;

final class PhpEccKeyPairGenerator implements KeyPairGeneratorInterface
{
    public function __construct()
    {
        if (!function_exists('gmp_init')) {
            throw new \RuntimeException('GMP extension is required but missing');
        }
    }

    public function generate(): KeyPairInterface
    {
        $adapter = EccFactory::getAdapter();
        $generator = EccFactory::getSecgCurves()->generator256k1();
        $private = $generator->createPrivateKey();

        $privateKeySerializer = new HexPrivateKeySerializer($adapter);
        $hexPrivateKey = $privateKeySerializer->serialize($private);

        $publicKeySerializer = new HexPublicKeySerializer($adapter, HexPublicKeySerializer::MODE_COMPRESSED);
        $hexPublicKey = $publicKeySerializer->serialize($private->getPublicKey());

        return new KeyPair($hexPublicKey, $hexPrivateKey);
    }
}
