<?php

use Propel\Runtime\Collection\ObjectCollection;

class ResultsController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function preliminary() {
        if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
            Router::redirect(['controller' => 'home', 'action' => 'index']);
        }

        try {
            $election        = $this->generateNewElection();
            $electionParties = $this->getPartiesFromRequest();
            $passedParties   = $this->getPassedPartiesAsArray($election, $electionParties);

            $viewVars = [
                'electionParties' => $electionParties,
                'passedParties'   => $passedParties,
            ];
        }
        catch (IncorrectInputDataException $e) {
            // TODO: log exception
            $viewVars = [
                'error' => $e->getMessage(),
            ];
        }

        $this->displayFullPage('results.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function generateNewElection(): Election {
        $assemblyTypeId      = (int) ($_POST['assembly_type']        ?? 0);
        $populationCensusId  = (int) ($_POST['population_census']    ?? 0);
        $activeSuffrage      = (int) ($_POST['active_suffrage']      ?? 0);
        $thresholdPercentage = (int) ($_POST['threshold_percentage'] ?? 0);
        $totalValidVotes     = (int) ($_POST['total_valid_votes']    ?? 0);
        $totalInvalidVotes   = (int) ($_POST['total_invalid_votes']  ?? 0);

        $election = new Election();

        $election->setAssemblyTypeId($assemblyTypeId)
                 ->setPopulationCensusId($populationCensusId)
                 ->setActiveSuffrage($activeSuffrage)
                 ->setThresholdPercentage($thresholdPercentage)
                 ->setTotalValidVotes($totalValidVotes)
                 ->setTotalInvalidVotes($totalInvalidVotes);

        $this->validateElectionData($election);

        return $election;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validateElectionData(Election $election) {
        $assembly          = $election->getAssemblyType();
        $census            = $election->getPopulationCensusWithPopulation();

        $totalValidVotes   = $election->getTotalValidVotes();
        $totalInvalidVotes = $election->getTotalInvalidVotes();
        $activeSuffrage    = $election->getActiveSuffrage();

        if ( ! $assembly) {
            $exception = 'No such assembly';
        }

        if ( ! $census) {
            $exception = 'No such population census';
        }

        // if there are more votes than people entitled to vote, abort
        if ($totalValidVotes + $totalInvalidVotes > $activeSuffrage) {
            $exception = 'Dead souls trying to vote.';
        }

        if (isset($exception)) {
            throw new IncorrectInputDataException($exception);
        }

        $this->setVars([
            'election' => $election,
            'assembly' => $assembly,
            'census'   => $census,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPartiesFromRequest(): ObjectCollection {
        $parties = new ObjectCollection();

        if (isset($_POST['parties'])) {
            $i = 0;
            foreach ($_POST['parties'] as $item) {
                $party = new ElectionParty();

                $party->setPartyId($item['party_id'])
                      ->setTotalVotes($item['total_votes'])
                      ->setOrd($item['ord'])
                      ->setRandomColor($i);

                $parties->append($party);
                $i++;
            }
        }

        return $parties;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getPassedPartiesAsArray(Election $election, ObjectCollection $parties): array {
        $totalValidVotes     = $election->getTotalValidVotes();
        $thresholdPercentage = $election->getThresholdPercentage();
        $totalMandates       = $election->getAssemblyType()->getTotalMandates();
        
        // filter parties based on their votes and the threshold
        $passedParties       = $this->filterPassedParties($parties, $totalValidVotes, $thresholdPercentage);

        // roughly calculate how many mandates all passing parties would receive
        // if there are no independent candidates elected
        try {
            $hareNiemeyer = new HareNiemeyer();
            $hareNiemeyer->distributeMandates($passedParties, $totalMandates);
        }
        // if an airthmetic error occurs (too few parties), don't do anything
        catch (ArithmeticError $e) {
        }

        $result = [];

        foreach ($passedParties as $item) {
            $party    = $item->getParty();
            $result[] = [
                'party'            => $party->getTitle(),
                'abbreviation'     => $party->getAbbreviation(),
                'votes'            => $item->getTotalVotes(),
                'votes_percentage' => $item->getVotesPercentage(),
                'mandates'         => $item->getHareNiemeyerMandates(),
                'color'            => $item->getPartyColor(),
            ];
        }

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function filterPassedParties(ObjectCollection $parties, int $totalVotes, int $thresholdPercentage): ObjectCollection {
        $passed = new ObjectCollection();

        foreach ($parties as $party) {
            $partyVotes      = $party->getTotalVotes();
            $partyPercentage = $this->calculatePartyPercentage($partyVotes, $totalVotes);

            if ($partyPercentage >= $thresholdPercentage) {
                $this->checkIfPartyExists($party);
                $party->setVotesPercentage($partyPercentage);
                $passed->append($party);
            }
        }

        return $passed;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function calculatePartyPercentage(int $partyVotes, int $totalVotes): float {
        if ($totalVotes === 0) {
            return 0;
        }

        return $partyVotes / $totalVotes * 100;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    private function checkIfPartyExists(ElectionParty $party): void {
        if ( ! $party->getParty()) {
            throw new IncorrectInputDataException('No such party: ' . $party->getPartyId());
        }
    }
}