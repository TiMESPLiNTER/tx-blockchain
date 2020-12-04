<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc;

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Serializer\Point\CompressedPointSerializer;
use Mdanter\Ecc\Serializer\PrivateKey\PrivateKeySerializerInterface;
use Mdanter\Ecc\Serializer\PublicKey\PublicKeySerializerInterface;
use Timesplinter\TxBlockchain\Crypto\KeyPair;
use Timesplinter\TxBlockchain\Crypto\KeyPairGeneratorInterface;
use Timesplinter\TxBlockchain\Crypto\KeyPairInterface;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer\HexPrivateKeySerializer;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer\HexPublicKeySerializer;

final class PhpEccKeyPairGenerator implements KeyPairGeneratorInterface
{
    private GmpMathInterface $adapter;

    private GeneratorPoint $generator;

    private PrivateKeySerializerInterface $privateKeySerializer;

    private PublicKeySerializerInterface $publicKeySerializer;

    public function __construct()
    {
        $this->adapter = EccFactory::getAdapter();
        $this->generator = EccFactory::getSecgCurves()->generator256k1();

        $this->privateKeySerializer = new HexPrivateKeySerializer($this->adapter, $this->generator);
        $this->publicKeySerializer = new HexPublicKeySerializer(
            new CompressedPointSerializer($this->adapter),
            $this->adapter,
            $this->generator
        );
    }

    public function generate(): KeyPairInterface
    {
        $privateKey = $this->generator->createPrivateKey();

        $hexPrivateKey = $this->privateKeySerializer->serialize($privateKey);
        $hexPublicKey = $this->publicKeySerializer->serialize($privateKey->getPublicKey());

        return new KeyPair($hexPublicKey, $hexPrivateKey);
    }
}
