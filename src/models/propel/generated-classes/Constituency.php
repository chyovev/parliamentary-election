<?php

use Base\Constituency as BaseConstituency;

/**
 * Skeleton subclass for representing a row from the 'constituencies' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Constituency extends BaseConstituency implements HareNiemeyerInterface
{

    use HareNiemeyerTrait;

    /**
     * shortcut function for Hare-Niemeyer-class compatibility
     *
     * @return int
     */
    public function getVotes(): int {
        return $this->getVirtualColumn('total_valid_votes');
    }
}
