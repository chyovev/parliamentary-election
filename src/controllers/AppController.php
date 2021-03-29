<?php

use Propel\Runtime\Collection\ObjectCollection;
use FieldManager as FM;

abstract class AppController {

    private $smarty;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(ExtendedSmarty $smarty) {
        $this->smarty = $smarty;
    }

    ///////////////////////////////////////////////////////////////////////////
    // render full page as string and show it on screen
    protected function displayFullPage(string $template, array $viewVars = []): void {
        $this->smarty->displayFullPage($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    // render full page as string
    protected function renderFullPage(string $template, array $viewVars = []): string {
        return $this->smarty->renderFullPage($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function renderTemplate(string $template, array $viewVars = []): string {
        return $this->smarty->render($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function setVar($var, $value): void {
        $this->smarty->assign($var, $value);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function setVars(array $vars): void {
        foreach ($vars as $key => $value) {
            $this->setVar($key, $value);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function renderJSONContent($array): void {
        if (!is_array($array)) {
            $array = [$array];
        }

        header('Content-Type: application/json');
        print(json_encode($array));
        exit;
    }

    /**
     * Tries to populate a Propel Election object from a specified source
     * 
     * @param  string $type – which source to use ('post' or 'session')
     * @return Election|null
     */
    protected function populateElection(string $type): ?Election {
        $types = [
            'session' => $_SESSION,
            'post'    => $_POST,
        ];
        $source = $types[$type];

        // if there is nothing to load from session, return NULL
        if ($type === 'session' && ! $source) {
            return NULL;
        }

        $election = $this->populateElectionFromData($source);

        return $election;
    }

    /**
     * Populates an Election object using data from $source
     * @param  array $source – should be either $_POST or $_SESSION
     * @return Election
     */
    private function populateElectionFromData(array $source = []): Election {
        $assemblyTypeId        = $source[FM::ASSEMBLY_FIELD]      ?? NULL;
        $populationCensusId    = $source[FM::CENSUS_FIELD]        ?? NULL;
        $activeSuffrage        = $source[FM::SUFFRAGE_FIELD]      ?? NULL;
        $thresholdPercentage   = $source[FM::THRESHOLD_FIELD]     ?? NULL;
        $totalValidVotes       = $source[FM::VALID_VOTES_FIELD]   ?? NULL;
        $totalInvalidVotes     = $source[FM::INVALID_VOTES_FIELD] ?? NULL;

        $election = new Election();

        $election->setAssemblyTypeId($assemblyTypeId)
                 ->setPopulationCensusId($populationCensusId)
                 ->setActiveSuffrage($activeSuffrage)
                 ->setThresholdPercentage($thresholdPercentage)
                 ->setTotalValidVotes($totalValidVotes)
                 ->setTotalInvalidVotes($totalInvalidVotes);

        $this->populateElectionPartiesFromData($election, $source);
        $this->populateIndependentCandidatesFromData($election, $source);

        return $election;
    }

    /**
     * Populates a collection of IndependentCandidate objects from $source
     * @param  Election $eleciton – object which independent candidates should be assigned to
     * @param  array    $source   – should be either $_POST or $_SESSION
     */
    protected function populateIndependentCandidatesFromData(Election $election, array $source): void {
        $data       = $source[FM::CANDIDATES_FIELD] ?? [];
        $candidates = new ObjectCollection();

        foreach ($data as $item) {
            $candidate = new IndependentCandidate();
            $candidate->setName($item[FM::CAND_NAME_FIELD])
                      ->setVotes($item[FM::CAND_VOTES_FIELD])
                      ->setConstituencyId($item[FM::CAND_CONST_FIELD]);
            
            $candidates->append($candidate);
        }

        $election->setIndependentCandidates($candidates);
    }

    /**
     * Populates a collection of ElectionParty objects from $source
     * and stores it in the passed $election alongside
     * a separate collection which holds only those parties
     * which have surpassed the vote threshold
     *
     * @param  Election $election – object which the parties should be assigned to
     * @param  array $source      – should be either $_POST or $_SESSION
     */
    private function populateElectionPartiesFromData(Election $election, array $source): void {
        $totalVotes          = $election->getTotalValidVotes();
        $thresholdPercentage = $election->getThresholdPercentage();

        $data = $source[FM::PARTIES_FIELD] ?? [];
        $i    = 0; // used solely for the random color

        // TODO: get previosly stored election parties and remove elements which are not in $source
        // instead of overwriting from scratch
        
        $electionParties = new ObjectCollection();
        $passedParties   = new ObjectCollection();

        foreach ($data as $item) {
            $partyVotes      = $item[FM::PARTY_TOTAL_VOTES];
            $partyPercentage = $this->calculatePartyPercentage($partyVotes, $totalVotes);

            $party = new ElectionParty();

            $party->setPartyId($item[FM::PARTY_ID])
                  ->setTotalVotes($partyVotes)
                  ->setVotesPercentage($partyPercentage)
                  ->setOrd($item[FM::PARTY_ORD]);

            if (isset($item[FM::PARTY_COLOR])) {
                $party->setPartyColor($item[FM::PARTY_COLOR]);
            }
            else {
                $party->setPartyColorAutomatically($i);
                $i++;
            }

            if ($partyPercentage >= $thresholdPercentage) {
                $this->populateElectionPartyVotesFromData($party, $source);
                $passedParties->append($party);
            }

            $electionParties->append($party);
        }

        $election->setElectionParties($electionParties);
        $election->setPassedParties($passedParties);
    }

    /**
     * Populates a collection of ElectionPartyVote objects from $source
     * and stores it in the passed $party parameter
     *
     * @param  ElectionParty $party – object which the votes should be assigned to
     * @param  array $source        – should be either $_POST or $_SESSION
     */
    protected function populateElectionPartyVotesFromData(ElectionParty $party, array $source): void {
        $data    = $source[FM::VOTES_FIELD] ?? [];
        $partyId = $party->getPartyId();

        $partyVotes = new ObjectCollection();

        if (isset($data[$partyId])) {
            foreach ($data[$partyId] as $constId => $votes) {
                $partyVote = new ElectionPartyVote();

                $partyVote->setElectionPartyId($partyId)
                          ->setConstituencyId($constId)
                          ->setVotes($votes);

                $partyVotes->append($partyVote);
            }
        }

        $party->setElectionPartyVotes($partyVotes);
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