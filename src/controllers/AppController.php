<?php

use Propel\Runtime\Collection\ObjectCollection;

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
        $assemblyTypeId      = $source['assembly_type_id']     ?? NULL;
        $populationCensusId  = $source['population_census_id'] ?? NULL;
        $activeSuffrage      = $source['active_suffrage']      ?? NULL;
        $thresholdPercentage = $source['threshold_percentage'] ?? NULL;
        $totalValidVotes     = $source['total_valid_votes']    ?? NULL;
        $totalInvalidVotes   = $source['total_invalid_votes']  ?? NULL;

        $election = new Election();

        $election->setAssemblyTypeId($assemblyTypeId)
                 ->setPopulationCensusId($populationCensusId)
                 ->setActiveSuffrage($activeSuffrage)
                 ->setThresholdPercentage($thresholdPercentage)
                 ->setTotalValidVotes($totalValidVotes)
                 ->setTotalInvalidVotes($totalInvalidVotes);

        $election
                 ->setVirtualColumn('activity', $this->calculateElectionActivity((int) $totalValidVotes, (int) $totalInvalidVotes, (int) $activeSuffrage))
                 ->setVirtualColumn('threshold_votes', $this->calculateThresholdVotes((int) $thresholdPercentage, (int) $totalValidVotes));

        $this->populateConstituencyValidVotes($election, $source);
        $this->populateElectionPartiesFromData($election, $source);
        $this->populateIndependentCandidatesFromData($election, $source);

        return $election;
    }

    /**
     * Calculate election activity percentage-wise
     *
     * @param  int $totalValidVotes
     * @param  int $totalInvalidVotes
     * @param  int $activeSuffrage
     * @return float percent
     */
    private function calculateElectionActivity(int $totalValidVotes, int $totalInvalidVotes, int $activeSuffrage) {
        if ($activeSuffrage === 0) {
            return 0;
        }

        return ($totalValidVotes + $totalInvalidVotes) / $activeSuffrage * 100;
    }

    /**
     * Calculates what the threshold actually is number-wise
     *
     * @return int
     */
    public function calculateThresholdVotes(int $thresholdPercentage, int $totalValidVotes) {
        return floor($thresholdPercentage * $totalValidVotes / 100);
    }

    /**
     * Populates a collection of IndependentCandidate objects from $source
     * @param  Election $eleciton – object which independent candidates should be assigned to
     * @param  array    $source   – should be either $_POST or $_SESSION
     */
    protected function populateIndependentCandidatesFromData(Election $election, array $source): void {
        $data       = $source['independent_candidates'] ?? [];
        $candidates = new ObjectCollection();

        foreach ($data as $item) {
            $candidate = new IndependentCandidate();
            $candidate->setName($item['name'])
                      ->setVotes($item['votes'])
                      ->setConstituencyId($item['constituency_id']);
            
            $candidates->append($candidate);
        }

        $election->setIndependentCandidates($candidates);
        $election->setVirtualColumn('independent_candidates_count', $candidates->count());
    }

    /**
     * Populates a collection of ElectionConstituency objects from $source
     * @param  Election $eleciton – object which election constituencies should be assigned to
     * @param  array    $source   – should be either $_POST or $_SESSION
     */
    private function populateConstituencyValidVotes(Election $election, array $source): void {
        $data                   = $source['constituency_votes'] ?? [];
        $electionConstituencies = new ObjectCollection();

        foreach ($data as $item) {
            $electionConstituency = new ElectionConstituency();

            $electionConstituency->setTotalValidVotes($item['total_valid_votes'])
                                 ->setVirtualColumn('constituency_id', $item['constituency_id']);

            $electionConstituencies->append($electionConstituency);
        }

        $election->setElectionConstituencies($electionConstituencies);
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

        $data = $source['parties'] ?? [];
        $i    = 0; // used solely for the random color

        // TODO: get previosly stored election parties and remove elements which are not in $source
        // instead of overwriting from scratch
        
        $electionParties = new ObjectCollection();
        $passedParties   = new ObjectCollection();

        foreach ($data as $item) {
            $partyVotes      = $item['total_votes'];
            $partyPercentage = $this->calculatePartyPercentage($partyVotes, $totalVotes);

            $electionParty = new ElectionParty();

            $electionParty->setPartyId($item['party_id'])
                          ->setTotalVotes($partyVotes)
                          ->setOrd($item['ord']);

            if (isset($item['party_color'])) {
                $electionParty->setPartyColor($item['party_color']);
            }
            else {
                $electionParty->setPartyColorAutomatically($i);
                $i++;
            }

            if ($partyPercentage >= $thresholdPercentage) {
                // load additional party information (title, abbreviation, percentage) for passed parties
                $party = $electionParty->getParty();
                $electionParty
                              ->setVirtualColumn('votes_percentage', $partyPercentage)
                              ->setVirtualColumn('party_title', $party->getTitle())
                              ->setVirtualColumn('party_abbreviation', $party->getAbbreviation());

                $this->populateElectionPartyVotesFromData($electionParty, $source);
                $passedParties->append($electionParty);
            }

            $electionParties->append($electionParty);
        }

        $election->setElectionParties($electionParties);
        $election->setVirtualColumn('election_parties_count', $electionParties->count());
        $election->setVirtualColumn('passed_parties', $passedParties);
    }

    /**
     * Populates a collection of ElectionPartyVote objects from $source
     * and stores it in the passed $party parameter
     *
     * @param  ElectionParty $party – object which the votes should be assigned to
     * @param  array $source        – should be either $_POST or $_SESSION
     */
    protected function populateElectionPartyVotesFromData(ElectionParty $party, array $source): void {
        $data    = $source['parties_votes'] ?? [];
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