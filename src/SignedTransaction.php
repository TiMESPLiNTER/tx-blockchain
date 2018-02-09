<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain;

use Phactor\Signature;

class SignedTransaction extends Transaction implements SignedTransactionInterface, \JsonSerializable
{

    /**
     * @var string
     */
    private $signature;

    /**
     * @param string $privateKey
     * @return void
     * @throws TransactionSignatureException
     */
    public function sign(string $privateKey): void
    {
        try {
            $this->signature = (new Signature())->Generate(json_encode($this), $privateKey);
        } catch (\Exception $e) {
            throw new TransactionSignatureException('Could not sign transaction', 0, $e);
        }
    }

    /**
     * @return bool
     * @throws TransactionSignatureException
     */
    public function valid(): bool
    {
        if (false === parent::valid()) {
            return false;
        }

        return $this->isSignatureValid();
    }

    /**
     * @return bool
     * @throws TransactionSignatureException
     */
    public function isSignatureValid(): bool
    {
        if (null === $from = $this->getFrom()) {
            return true;
        }

        if (null === $this->signature) {
            return false;
        }

        try {
            return (new Signature())->Verify($this->signature, json_encode($this), $from);
        } catch (\Exception $e) {
            throw new TransactionSignatureException('Could not verify transaction signature', 0, $e);
        }
    }


    /**
     * @return null|string
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
            'amount' => $this->getAmount(),
            'timestamp' => $this->getTimestamp()->format('c')
        ];
    }
}
