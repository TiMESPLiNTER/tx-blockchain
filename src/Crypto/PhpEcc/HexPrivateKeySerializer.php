<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc;

use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Serializer\PrivateKey\PrivateKeySerializerInterface;

final class HexPrivateKeySerializer implements PrivateKeySerializerInterface
{
    /**
     * @var GmpMathInterface
     */
    private $adapter;

    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
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
        throw new \RuntimeException('Parsing a hex private key is not supported.');
    }
}
