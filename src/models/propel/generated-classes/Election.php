<?php

use Base\Election as BaseElection;
use \PopulationCensusQuery as ChildPopulationCensusQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;

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

    private $constituencies;

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

    /**
     * Get all constituencies with their respective populations according to selected census
     *
     * @return ObjectCollection
     */
    public function getConstituenciesWithPopulation(): ?ObjectCollection {
        if ($this->constituencies === null) {
            $populationCensusId = $this->getPopulationCensusId();

            $this->constituencies = ConstituencyQuery::create()->useConstituencyCensusQuery()
                                            ->filterByPopulationCensusId($populationCensusId)
                                            ->addAsColumn('population', 'population')
                                        ->endUse()
                                        ->find();
        }

        $constituencies = $this->constituencies;
        $this->addTotalVotes($constituencies);
        // TODO: add constituencies single votes

        return $this->constituencies;
    }

    /**
     * For all constituencies add virtual field
     * total_valid_votes = number specified in step 1
     *
     * @param ObjectCollection $constituencies
     */
    private function addTotalVotes(ObjectCollection $constituencies): void {
        $constituenciesArray = $constituencies->toKeyIndex();
        $totalVotes          = $this->getElectionConstituencies();

        foreach ($totalVotes as $item) {
            $constituencyId = $item->getVirtualColumn('constituency_id');
            $votes          = $item->getTotalValidVotes();

            $constituenciesArray[$constituencyId]->setVirtualColumn('total_valid_votes', $votes);
        }
    }

    /**
     * shortcut to virtual column passed_parties
     *
     * @return ObjectCollection $passedParties
     */
    public function getPassedParties(): ObjectCollection {
        return $this->getVirtualColumn('passed_parties');
    }

}
