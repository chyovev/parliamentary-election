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

    ///////////////////////////////////////////////////////////////////////////
    protected function loadElection() {
        $slug     = Router::getRequestParam('year');
        $election = $this->populateElection('session');

        if ($election) {
            return $election;
        }

        // if no election could be loaded from session and there is a slug,
        // try to load from database (throw exception on fail)
        // on success, save data to session and then load it from there
        if ($slug) {
            $election = ElectionQuery::create()->findOneBySlug($slug);

            if ( ! $election) {
                throw new Exception('No such year: ' . $slug);
            }

            $this->setSessionElection($election);

            return $this->populateElectionFromData('session');
        }
    }

    /**
     * Tries to populate a Propel Election object from a specified source
     * 
     * @param  string $type – which source to use ('post' or 'session')
     * @return Election|null
     */
    protected function populateElection(string $type): ?Election {
        $source = $this->selectDataSource($type);

        // if there is nothing to load from session, return NULL
        if ($type === 'session' && ! $source) {
            return NULL;
        }

        $election = $this->populateElectionFromData($type);

        return $election;
    }

    /**
     * Populates an Election object using data from $source
     * @param  string $type – should be compatible with $dataPools class property
     * @return Election
     */
    private function populateElectionFromData(string $type): Election {
        $source              = $this->selectDataSource($type);
        $assemblyTypeId      = $source['assembly_type_id']     ?? NULL;
        $populationCensusId  = $source['population_census_id'] ?? NULL;
        $activeSuffrage      = $source['active_suffrage']      ?? NULL;
        $thresholdPercentage = $source['threshold_percentage'] ?? NULL;
        $totalValidVotes     = $source['total_valid_votes']    ?? NULL;
        $totalInvalidVotes   = $source['total_invalid_votes']  ?? NULL;

        // on post request, instead of creating a new object,
        // override the one stored in session (if any)
        $election = ($type === 'post')
                  ? $this->populateElection('session') ?? new Election()
                  : new Election();

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
        $this->populateElectionPartiesFromData($election, $type);
        $this->populateElectionPartiesVotesFromData($election, $type);
        $this->populateIndependentCandidatesFromData($election, $type);

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
     * @param  string   $type     – should be compatible with $dataPools class property
     */
    protected function populateIndependentCandidatesFromData(Election $election, string $type): void {
        $source     = $this->selectDataSource($type);
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
        // election constituencies need to be associated
        // with the constituency census they belong to
        $constituencies = $election->getConstituenciesWithPopulation();
        if ($constituencies->count()) {
            $constituenciesArray = $constituencies->toArray('id');
        }

        $data                   = $source['constituency_votes'] ?? [];
        $electionConstituencies = new ObjectCollection();

        foreach ($data as $constituencyId => $votes) {
            $electionConstituency = new ElectionConstituency();

            $electionConstituency->setTotalValidVotes($votes);

            // if the constituency censuses were loaded, set ID
            if ($constituencies->count()) {
                $electionConstituency->setConstituencyCensusId($constituenciesArray[$constituencyId]['constituency_census_id']);
            }

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
     * @param  string   $type     – should be compatible with $dataPools class property
     */
    private function populateElectionPartiesFromData(Election $election, string $type): void {
        $source              = $this->selectDataSource($type);
        $totalVotes          = $election->getTotalValidVotes();
        $thresholdPercentage = $election->getThresholdPercentage();

        $data = $source['parties'] ?? [];
        $i    = 0; // used solely for the random color

        // TODO: get previosly stored election parties and remove elements which are not in $source
        // instead of overwriting from scratch
        
        $electionParties   = new ObjectCollection();
        
        foreach ($data as $item) {
            $partyId       = $item['party_id'];
            $partyVotes    = $item['total_votes'];
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

            $electionParties->append($electionParty);
        }

        $election->setElectionParties($electionParties);
        $election->setVirtualColumn('election_parties_count', $electionParties->count());
    }

    /**
     * Get passed parties, loop through all of them,
     * for each party loop through data, create
     * ElectionPartyVotes collection, and store it
     * as a $party association
     *
     * @param Election $election – object which holds passed parties
     * @param string   $type     – should be compatible with $dataPools class property
     */
    protected function populateElectionPartiesVotesFromData(Election $election, string $type): void {
        $source        = $this->selectDataSource($type);
        $passedParties = $election->getPassedParties();
        $data          = $source['parties_votes'] ?? [];

        foreach ($passedParties as $party) {
            $partyId    = $party->getPartyId();
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
    }

    /**
     * Transform all Election information into an associative array
     * and store it in a $_SESSION variable;
     * then use this array to regenerate those objects when reading from $_SESSSION
     * (PHP doesn't like it when whole objects get stored in sessions)
     *
     * @param Election $election – object from which information gets extracted
     */
    protected function setSessionElection(Election $election): void {
        $electionParties       = $election->getElectionParties();
        $independentCandidates = $election->getIndependentCandidates();
        $electionConstituencies= $election->getElectionConstituencies();
        $constituencies        = $election->getConstituenciesWithPopulation();
        $constituenciesArray   = $constituencies->toArray('constituency_census_id');
        $parties               = [];
        $candidates            = [];
        $partiesVotes          = [];
        $constituencyTotalVotes= [];

        foreach ($electionParties as $item) {
            $parties[] = [
                'party_id'    => $item->getPartyId(),
                'total_votes' => $item->getTotalVotes(),
                'ord'         => $item->getOrd(),
                'party_color' => $item->getPartyColor(),
            ];

            $partiesConstituenciesVotes = $item->getElectionPartyVotes();

            foreach ($partiesConstituenciesVotes as $subitem) {
                $partiesVotes[ $item->getPartyId() ][ $subitem->getConstituencyId() ] = $subitem->getVotes();
            }
        }

        foreach ($independentCandidates as $item) {
            $candidates[] = [
                'name'            => $item->getName(),
                'votes'           => $item->getVotes(),
                'constituency_id' => $item->getConstituencyId(),
            ];
        }

        foreach ($electionConstituencies as $item) {
            $censusId       = $item->getConstituencyCensusId();
            $constituencyId = $constituenciesArray[$censusId]['Id'];

            $constituencyTotalVotes[$constituencyId] = $item->getTotalValidVotes();
        }

        // used for navigation between the pages
        $reachedStep = $partiesVotes ? 3 : 2;

        $_SESSION = [
            'assembly_type_id'       => $election->getAssemblyTypeId(),
            'population_census_id'   => $election->getPopulationCensusId(),
            'active_suffrage'        => $election->getActiveSuffrage(),
            'threshold_percentage'   => $election->getThresholdPercentage(),
            'total_valid_votes'      => $election->getTotalValidVotes(),
            'total_invalid_votes'    => $election->getTotalInvalidVotes(),
            'parties'                => $parties,
            'independent_candidates' => $candidates,
            'parties_votes'          => $partiesVotes,
            'constituency_votes'     => $constituencyTotalVotes,
            'reached_step'           => $reachedStep,
        ];
    }

    /**
     * find out how many steps the user can navigate between
     * @return int
     */
    protected function getReachedStep(): int {
        return $_SESSION['reached_step'] ?? 1;
    }

    /**
     * set current and last steps used for navigation
     */
    protected function setProgressSteps(int $currentStep): void {
        $reachedStep = $this->getReachedStep();

        if ($currentStep === 1) {
            $prevStepUrl = false;
            $nextStepUrl = ($currentStep < $reachedStep) ? Router::url(['controller' => 'results', 'action' => 'preliminary']) : false;
        }
        elseif ($currentStep === 2) {
            $prevStepUrl = Router::url(['controller' => 'home', 'action' => 'index']);
            $nextStepUrl = ($currentStep < $reachedStep) ? Router::url(['controller' => 'results', 'action' => 'definitive']) : false;
        }
        elseif ($currentStep === 3) {
            $prevStepUrl = Router::url(['controller' => 'results', 'action' => 'preliminary']);
            $nextStepUrl = false;
        }

        $this->setVars([
            'currentStep' => $currentStep,
            'reachedStep' => $reachedStep,
            'prevStepUrl' => $prevStepUrl,
            'nextStepUrl' => $nextStepUrl,
        ]);

    }

    /**
     * select by type the data source to populate propel objects
     *
     * @param  string $type
     * @return array  data
     */
    private function selectDataSource(string $type): array {
        if ($type === 'post') {
            return $_POST;
        }
        elseif ($type === 'session') { 
            return $_SESSION;
        }
        else {
            throw new Exception ("No such data source type");
        }
    }

}