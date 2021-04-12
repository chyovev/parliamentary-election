<?php

use Base\Election as BaseElection;
use \PopulationCensusQuery as ChildPopulationCensusQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
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
    private $passedParties;

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
                                            ->addAsColumn('constituency_census_id', 'ConstituencyCensus.id')
                                        ->endUse()
                                        ->find();
        }


        $constituencies = $this->constituencies;


        if ($constituencies->count()) {
            $this->addTotalVotes($constituencies);
        }

        return $this->constituencies;
    }

    /**
     * For all constituencies add virtual field
     * total_valid_votes = number specified in step 1
     * Still, votes are not directly associated with the constituencies,
     * so first all constituencies need to be loaded
     * to use their constituency_census_id association
     *
     * @param ObjectCollection $constituencies
     */
    private function addTotalVotes(ObjectCollection $constituencies): void {
        $constituenciesArray = $constituencies->toKeyIndex('constituency_census_id');
        $totalVotes          = $this->getElectionConstituencies();

        foreach ($totalVotes as $item) {
            $censusId       = $item->getConstituencyCensusId();
            $votes          = $item->getTotalValidVotes();


            $constituenciesArray[$censusId]->setVirtualColumn('total_valid_votes', $votes);
        }
    }

    /**
     * Extend base method by adding default order by `ord` column
     */
    public function getElectionParties(Criteria $criteria = null, ConnectionInterface $con = null) {
        if ( ! $criteria && $this->collElectionParties === NULL) {
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn('ord');
        }

        return parent::getElectionParties($criteria, $con);
    }

    /**
     * if the passed parties property is empty,
     * from all election parties filter the ones which
     * have surpassed the threshold percentage
     *
     * @return ObjectCollection $passedParties
     */
    public function getPassedParties(): ObjectCollection {
        if ( ! $this->passedParties) {
            $totalVotes          = $this->getTotalValidVotes();
            $thresholdPercentage = $this->getThresholdPercentage();
            $electionParties     = $this->getElectionParties();

            // trust no one votes should not influence the passing threshold for parties
            $trustNoOneVotes     = $this->getTrustNoOneVotes();
            $passingVotes        = $totalVotes - $trustNoOneVotes;

            $this->passedParties = new ObjectCollection();

            // loop through all election parties,
            // calculate their percentage and compare it to the threshold
            foreach ($electionParties as $item) {
                $partyVotes      = $item->getTotalVotes();
                $partyPercentage = $this->calculatePartyPercentage($partyVotes, $passingVotes);

                // load additional party information (title, abbreviation, percentage) for passed parties
                if ($partyPercentage >= $thresholdPercentage) {
                    $party = $item->getParty();
                    $item
                         ->setVirtualColumn('votes_percentage',   $partyPercentage)
                         ->setVirtualColumn('party_title',        $party->getTitle())
                         ->setVirtualColumn('party_abbreviation', $party->getAbbreviation());

                    $this->passedParties->append($item);
                }
            }

        }

        return $this->passedParties;
    }

    /**
     * Calculates the activity of an election percentage-wise
     * @return float
     */
    public function calculateElectionActivity(): float {
        $totalValidVotes   = $this->getTotalValidVotes();
        $totalInvalidVotes = $this->getTotalInvalidVotes();
        $activeSuffrage    = $this->getActiveSuffrage();

        if ($activeSuffrage === 0) {
            return 0;
        }

        return ($totalValidVotes + $totalInvalidVotes) / $activeSuffrage * 100;
    }

    /**
     * Calculates what the threshold actually is number-wise
     * (excluding the trust-no-one votes)
     *
     * @return int
     */
    public function calculateThresholdVotes(): int {
        $totalValidVotes     = $this->getTotalValidVotes();
        $trustNoOneVotes     = $this->getTrustNoOneVotes();
        $thresholdPercentage = $this->getThresholdPercentage();

        return floor($thresholdPercentage * ($totalValidVotes - $trustNoOneVotes) / 100);
    }

    /**
     * generate slug
     */
    public function preSave(ConnectionInterface $con = null) {
        // official results cannot be overridden
        if ($this->isOfficial()) {
            return false;
        }

        // generate random slug and make sure
        // there are no collisions
        if ( ! $this->getSlug()) {
            do {
                $slug = uniqid();
            }
            while (ElectionQuery::slugExists($slug));

            $this->setSlug($slug);
        }

        return true;
    }

    /**
     * Calculates the percentage of votes a party has received in comparison to all votes
     *
     * @param  int   $partyVotes
     * @param  int   $totalVotes
     * @return float percentage
     */
    private function calculatePartyPercentage(int $partyVotes, int $totalVotes): float {
        if ($totalVotes === 0) {
            return 0;
        }

        return $partyVotes / $totalVotes * 100;
    }

}
