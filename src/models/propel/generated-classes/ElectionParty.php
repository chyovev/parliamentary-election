<?php

use Base\ElectionParty as BaseElectionParty;

/**
 * Skeleton subclass for representing a row from the 'elections_parties' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ElectionParty extends BaseElectionParty implements HareNiemeyerInterface
{

    use HareNiemeyerTrait;

    /**
     * predefined colors to assign to parties in ascending order
     * @var array
     */
    const COLORS = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4',
                    '#f032e6', '#bfef45', '#fabed4', '#469990', '#dcbeff', '#9A6324', '#fffac8',
                    '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#a9a9a9'];

    /**
     * when an ElectionParty object gets created, mark it as having received 0 local mandates
     * (this is used for local distribution of mandates)
     */
    public function __construct() {
        $this->setVirtualColumn(HareNiemeyerInterface::LOCAL_MANDATES_COLUMN, 0);
        parent::__construct();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getLocalMandates(): int {
        return $this->getVirtualColumn(HareNiemeyerInterface::LOCAL_MANDATES_COLUMN);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function setLocalMandates(int $mandates): self {
        $this->setVirtualColumn(HareNiemeyerInterface::LOCAL_MANDATES_COLUMN, $mandates);

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function incrementLocalMandatesBy(int $number): self {
        $current = $this->getLocalMandates();
        $this->setLocalMandates($current + $number);

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function decrementLocalMandatesBy(int $number): self {
        $current = $this->getLocalMandates();
        $this->setLocalMandates($current - $number);

        return $this;
    }

    /**
     * a shortcut which ensures Hare-Niemeyer compatibility
     * @return int
     */
    public function getVotes(): int {
        return $this->getTotalVotes();
    }

    /**
     * when generating new parties, automatically assign a color to them
     * if it doesn't already have a color
     */
    public function setPartyColorAutomatically(int $iterator): self {
        if ( ! $this->getPartyColor()) {
            // use the Nth predefined colors (N = $iterator),
            // on exhaustion generate a random color
            $color = self::COLORS[$iterator] ?? $this->generateRandomColor();

            $this->setPartyColor($color);
        }

        return $this;
    }

    /**
     * keep generating new random color until there's no collision
     * with the predefined colors
     */
    private function generateRandomColor(): string {
        do {
            $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
        while (in_array($color, self::COLORS));

        return $color;
    }

}
