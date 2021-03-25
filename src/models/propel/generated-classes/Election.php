<?php

use Base\Election as BaseElection;
use \PopulationCensusQuery as ChildPopulationCensusQuery;

/**
 * Skeleton subclass for representing a row from the 'elections' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Election extends BaseElection
{

    /**
     * Calculates election activity in percentage using valid votes + invalid votes
     * and dividing by the active suffrage (the amount of people entitled to vote)
     *
     * @return float
     */
    public function getActivity(): float {
        $entitledToVote    = $this->getActiveSuffrage();
        $totalValidVotes   = $this->getTotalValidVotes();
        $totalInvalidVotes = $this->getTotalInvalidVotes();
        
        if ($entitledToVote === 0) {
            return 0;
        }

        return ($totalValidVotes + $totalInvalidVotes) / $entitledToVote * 100;
    }

    /**
     * Gets the population census associated with the election,
     * executing a JOIN to the constituencies censuses table to SUM their populations
     *
     * @return PopulationCensus
     */
    public function getPopulationCensusWithPopulation(): ?PopulationCensus {
        if ($this->aPopulationCensus === null && ($this->population_census_id != 0)) {
            $this->aPopulationCensus = ChildPopulationCensusQuery::create()
                                    ->useConstituencyCensusQuery()
                                        ->addAsColumn('population', 'SUM(population)')
                                    ->endUse()
                                    ->groupById()
                                    ->findPk($this->population_census_id);
        }

        return $this->aPopulationCensus;
    }

}
