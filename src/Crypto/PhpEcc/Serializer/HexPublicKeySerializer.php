<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer;

use Mdanter\Ecc\Crypto\Key\PublicKeyInterface;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Serializer\Point\PointSerializerInterface;
use Mdanter\Ecc\Serializer\PublicKey\PublicKeySerializerInterface;

final class HexPublicKeySerializer implements PublicKeySerializerInterface
{
    private GmpMathInterface $adapter;

    private GeneratorPoint $generator;

    private PointSerializerInterface $pointSerializer;

    public function __construct(
        PointSerializerInterface $pointSerializer,
        GmpMathInterface $adapter,
        GeneratorPoint $generator
    ) {
        $this->pointSerializer = $pointSerializer;
        $this->adapter = $adapter;
        $this->generator = $generator;
    }

    /**
     * @inheritDoc
     */
    public function serialize(PublicKeyInterface $key): string
    {
        return $this->pointSerializer->serialize($key->getPoint());
    }

    /**
     * @inheritDoc
     */
    public function parse(string $formattedKey): PublicKeyInterface
    {
        $point = $this->pointSerializer->unserialize($this->generator->getCurve(), $formattedKey);

        return $this->generator->getPublicKeyFrom($point->getX(), $point->getY());
    }
}
