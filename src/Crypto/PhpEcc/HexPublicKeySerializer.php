<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc;

use Mdanter\Ecc\Crypto\Key\PublicKeyInterface;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Serializer\PublicKey\PublicKeySerializerInterface;

final class HexPublicKeySerializer implements PublicKeySerializerInterface
{

    public const MODE_COMPRESSED = 1;

    public const MODE_UNCOMPRESSED = 2;

    /**
     * @var int
     */
    private $mode;

    /**
     * @var GmpMathInterface
     */
    private $adapter;

    public function __construct(GmpMathInterface $adapter, int $mode)
    {
        $this->adapter = $adapter;
        $this->mode = $mode;
    }

    /**
     * @inheritDoc
     */
    public function serialize(PublicKeyInterface $key): string
    {
        $x = $key->getPoint()->getX();
        $y = $key->getPoint()->getY();

        $xHex = $this->adapter->decHex($this->adapter->toString($x));

        if (self::MODE_COMPRESSED === $this->mode) {
            $mod = gmp_init(2, 10);
            $publicKeyPrefix = ('1' === $this->adapter->toString($this->adapter->mod($y, $mod))) ? '03' : '02';
            return $publicKeyPrefix . $xHex;
        } elseif (self::MODE_UNCOMPRESSED === $this->mode) {
            $yHex = $this->adapter->decHex($this->adapter->toString($y));
            return sprintf('04%s%s', $xHex, $yHex);
        }

        throw new \RuntimeException(sprintf('Unsupported key representation mode: %d', $this->mode));
    }

    /**
     * @inheritDoc
     */
    public function parse(string $formattedKey): PublicKeyInterface
    {
        throw new \RuntimeException('Not implemented.');
    }
}
