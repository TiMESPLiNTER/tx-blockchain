<?php

declare(strict_types=1);

namespace Timesplinter\TxBlockchain\Strateg\ProofOfStake;

use Timesplinter\Blockchain\BlockInterface;
use Timesplinter\Blockchain\StrategyInterface;
use Timesplinter\TxBlockchain\Strategy\ProofOfStake\Stakeholder;
use Timesplinter\TxBlockchain\TransactionBlockchainInterface;

final class ProofOfStakeStrategy implements StrategyInterface
{

    /**
     * @var array<Stakeholder>|Stakeholder[]
     */
    private $stakeholders = [];

    /**
     * @var float
     */
    private $minimumStake;

    /**
     * @var float
     */
    private $blockAcceptanceRatio;

    /**
     * @var int
     */
    private $minimumStakeholderVotes;

    /**
     * @var TransactionBlockchainInterface
     */
    private $blockchain;

    /**
     * @param array<string> $stakeholders A list public addresses of stakeholders allowed to vote
     * @param float $minimumStake Minimum balance a stakeholder needs to have for being allowed to vote
     * @param float $blockAcceptanceRatio Minimum positivity ration that needs be fulfilled that the block counts as valid
     * @param int $minimumStakeholderVotes Minimum total votes that a block is counted as valid
     * @param TransactionBlockchainInterface $blockchain The blockchain instance
     */
    public function __construct(
        array $stakeholders,
        float $minimumStake,
        float $blockAcceptanceRatio,
        int $minimumStakeholderVotes,
        TransactionBlockchainInterface $blockchain
    ) {
        foreach ($stakeholders as $stakeholderPublicKey) {
            $this->stakeholders[] = new Stakeholder($stakeholderPublicKey);
        }

        $this->minimumStake = $minimumStake;
        $this->blockAcceptanceRatio = $blockAcceptanceRatio;
        $this->minimumStakeholderVotes = $minimumStakeholderVotes;
        $this->blockchain = $blockchain;
    }

    /**
     * Every stakeholder can bail with its stake for the block being valid or invalid. If the total of stake voted for
     * a valid block exceeds the minimum block acceptance ratio the block is accepted as valid and therefore is mined.
     * @param BlockInterface $block
     * @return bool
     */
    public function mine(BlockInterface $block): bool
    {
        $totalStakeholdersVoted = 0;
        $totalVoteStake = 0.0;
        $validVoteStake = 0.0;

        foreach ($this->stakeholders as $stakeholder) {
            $balance = $this->blockchain->getBalanceForAddress($stakeholder->getPublicAddress());
            $stakeholder->setStake($balance);

            if ($balance < $this->minimumStake) {
                continue;
            }

            $totalVoteStake += $balance;

            ++$totalStakeholdersVoted;

            if (true === $stakeholder->vote($block)) {
                $validVoteStake += $balance;
            }
        }

        return $totalVoteStake >= $this->minimumStakeholderVotes &&
            ($validVoteStake / $totalVoteStake) >= $this->blockAcceptanceRatio;
    }
}
