<?php

use Propel\Runtime\Collection\ObjectCollection;

class ResultsController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function preliminary() {
        if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
            Router::redirect(['controller' => 'home', 'action' => 'index']);
        }

        $thresholdPercentage = (int) ($_POST['threshold_percentage'] ?? 4);
        $entitledToVote      = (int) ($_POST['active_suffrage']      ?? 0);
        $totalValidVotes     = (int) ($_POST['total_valid_votes']    ?? 0);
        $electionActivity    = $this->getElectionActivity($totalValidVotes, $entitledToVote);

        $assemblyType        = $this->getAssemblyTypeFromRequest();
        $census              = $this->getCensusFromRequest();
        $electionParties     = $this->getPartiesFromRequest();
        $passedParties       = $this->filterPassedParties($electionParties, $totalValidVotes, $thresholdPercentage);

        $viewVars = [
            'thresholdPercentage' => $thresholdPercentage,
            'entitledToVote'      => $entitledToVote,
            'electionActivity'    => $electionActivity,
            'totalValidVotes'     => $totalValidVotes,
            'assemblyType'        => $assemblyType,
            'census'              => $census,
            'electionParties'     => $electionParties,
            'passedParties'       => $passedParties,
        ];

        $this->displayFullPage('results.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getElectionActivity(int $totalValidVotes, int $entitledToVote): float {
        if ($entitledToVote === 0) {
            return 0;
        }

        return $totalValidVotes / $entitledToVote * 100;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getAssemblyTypeFromRequest(): ?AssemblyType {
        $assemblyTypeId = $_POST['assembly_type'] ?? NULL;

        return AssemblyTypeQuery::create()->findPk($assemblyTypeId);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getCensusFromRequest(): ?PopulationCensus {
        $populationCensusId = $_POST['population_census'] ?? NULL;

        return PopulationCensusQuery::create()->getTypeWithPopulation($populationCensusId);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPartiesFromRequest(): ObjectCollection {
        $parties = new ObjectCollection();

        if (isset($_POST['parties'])) {
            foreach ($_POST['parties'] as $item) {
                $party = new ElectionParty();

                $party->setPartyId($item['party_id'])
                      ->setTotalVotes($item['total_votes'])
                      ->setOrd($item['ord']);
                      
                $parties->append($party);
            }
        }

        return $parties;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function filterPassedParties(ObjectCollection $parties, int $totalVotes, int $thresholdPercentage): ObjectCollection {
        $passed = new ObjectCollection();

        foreach ($parties as $party) {
            $partyVotes      = $party->getTotalVotes();
            $partyPercentage = floor($partyVotes / $totalVotes * 100);

            if ($partyPercentage >= $thresholdPercentage) {
                $passed->append($party);
            }
        }

        return $passed;
    }

}