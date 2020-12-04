<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer;

use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Serializer\PrivateKey\PrivateKeySerializerInterface;

final class HexPrivateKeySerializer implements PrivateKeySerializerInterface
{
    private GmpMathInterface $adapter;

    private GeneratorPoint $generator;

    public function __construct(GmpMathInterface $adapter, GeneratorPoint $generator)
    {
        $this->adapter = $adapter;
        $this->generator = $generator;
    }

    /**
     * @inheritDoc
     */
    public function serialize(PrivateKeyInterface $key): string
    {
        return '0x' . $this->adapter->decHex($this->adapter->toString($key->getSecret()));
    }

    /**
     * @inheritDoc
     */
    public function parse(string $formattedKey): PrivateKeyInterface
    {
        if (66 === strlen($formattedKey)) {
            $formattedKey = substr($formattedKey, 2);
        }

        // maybe -> $this->adapter->hexDec($formattedKey); not sure if gmp can handle hex representation (0-9a-f)
        return $this->generator->getPrivateKeyFrom(gmp_init($formattedKey, 16));
    }
}
