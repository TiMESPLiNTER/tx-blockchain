<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Crypto\PhpEcc;

use Mdanter\Ecc\Crypto\Signature\Signer;
use Mdanter\Ecc\Crypto\Signature\SignHasher;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Random\RandomGeneratorFactory;
use Mdanter\Ecc\Serializer\Point\CompressedPointSerializer;
use Mdanter\Ecc\Serializer\Signature\DerSignatureSerializer;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer\HexPrivateKeySerializer;
use Timesplinter\TxBlockchain\Crypto\PhpEcc\Serializer\HexPublicKeySerializer;
use Timesplinter\TxBlockchain\Crypto\TransactionSignatureProviderInterface;
use Timesplinter\TxBlockchain\SignedTransaction;

final class PhpEccTransactionSignatureProvider implements TransactionSignatureProviderInterface
{

    private GmpMathInterface $adapter;

    private GeneratorPoint $generator;

    private string $algorithm;

    public function __construct()
    {
        // ECDSA domain is defined by curve/generator/hash algorithm,
        // which a verifier must be aware of.
        $this->adapter = EccFactory::getAdapter();
        $this->generator = EccFactory::getSecgCurves()->generator256k1();
        $this->algorithm = 'sha256';
    }

    public function sign(SignedTransaction $transaction, string $privateKey): void
    {
        ## You'll be restoring from a key, as opposed to generating one.
        $serializer = new HexPrivateKeySerializer($this->adapter, $this->generator);
        $key = $serializer->parse($privateKey);

        $hasher = new SignHasher($this->algorithm, $this->adapter);
        $hash = $hasher->makeHash(json_encode($transaction), $this->generator);

        # Derandomized signatures are not necessary, but is avoids
        # the risk of a low entropy RNG, causing accidental reuse
        # of a k value for a different message, which leaks the
        # private key.
        $random = RandomGeneratorFactory::getHmacRandomGenerator($key, $hash, $this->algorithm);

        $randomK = $random->generate($this->generator->getOrder());

        $signer = new Signer($this->adapter);
        $signature = $signer->sign($key, $hash, $randomK);

        $serializer = new DerSignatureSerializer();
        $serializedSig = $serializer->serialize($signature);

        $signatureStr = bin2hex($serializedSig);

        $transaction->setSignature($signatureStr);
    }

    public function verify(SignedTransaction $transaction, string $publicKey): bool
    {
        $sigData = $transaction->getSignature();

        // Parse signature
        $sigSerializer = new DerSignatureSerializer();
        $sig = $sigSerializer->parse(hex2bin($sigData));

        // Parse public key
        $publicKeySerializer = new HexPublicKeySerializer(
            new CompressedPointSerializer($this->adapter),
            $this->adapter,
            $this->generator
        );

        $key = $publicKeySerializer->parse($publicKey);

        $hasher = new SignHasher($this->algorithm);
        $hash = $hasher->makeHash(json_encode($transaction), $this->generator);

        $signer = new Signer($this->adapter);

        return $signer->verify($key, $sig, $hash);
    }
}
