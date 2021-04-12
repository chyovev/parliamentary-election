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
        $slug = Router::getRequestParam('slug');

        // if there is a slug, always fetch data from the DB
        // and store it as a session
        if ($slug) {
            $election = ElectionQuery::create()->findOneBySlug($slug);

            if ( ! $election) {
                throw new Exception('No such saved election: ' . $slug);
            }

            $this->setSessionElection($election);
        }

        return $this->populateElection('session');
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
        $trustNoOneVotes     = $source['trust_no_one_votes' ]  ?? NULL;

        // on post request, instead of creating a new object,
        // override the one stored in session (if any)
        $election = ($type === 'post')
                  ? $this->populateElection('session') ?? new Election()
                  : new Election();

        $election->setAssemblyTypeId($assemblyTypeId)
                 ->setPopulationCensusId($populationCensusId)
                 ->setActiveSuffrage($activeSuffrage)
                 ->setThresholdPercentage($thresholdPercentage)
                 ->setTrustNoOneVotes($trustNoOneVotes)
                 ->setTotalValidVotes($totalValidVotes)
                 ->setTotalInvalidVotes($totalInvalidVotes);

        // when loading from session, set id
        // which will then be used on results' save
        if ($type === 'session') {
            $electionId = $source['election_id'] ?? NULL;
            $election->setId($electionId);
        }

        $election
                 ->setVirtualColumn('activity',        $election->calculateElectionActivity())
                 ->setVirtualColumn('threshold_votes', $election->calculateThresholdVotes());

        $this->populateConstituencyValidVotes($election, $source);
        $this->populateElectionPartiesFromData($election, $type);
        $this->populateElectionPartiesVotesFromData($election, $type);
        $this->populateIndependentCandidatesFromData($election, $type);

        return $election;
    }

    /**
     * Populates a collection of IndependentCandidate objects from $source
     * @param  Election $eleciton – object which independent candidates should be assigned to
     * @param  string   $type     – should be compatible with $dataPools class property
     */
    protected function populateIndependentCandidatesFromData(Election $election, string $type): void {
        $source     = $this->selectDataSource($type);
        $data       = $source['independent_candidates'] ?? [];

        $candidates = ($type === 'post')
                    ? $this->mergePostAndSessionIndependentCandidates($election, $data)
                    : $this->getIndependentCandidatesFromSession($data);


        $election->setIndependentCandidates($candidates);
        $election->setVirtualColumn('independent_candidates_count', $candidates->count());
    }

    /**
     * New candidates should be added alongside old candidates stored in session.
     * To do this, both old and new candidates get grouped by constituency.
     * Then, the new candidates' group gets cycled through and added to the old group,
     * Finally, the old group gets reverted back to an ObjectCollection.
     *
     * @param  Election         $election   – object which holds the candidates
     * @param  array            $data       – post data holding new candidates 
     * @return ObjectCollection $candidates – merged new and old candidates
     */
    private function mergePostAndSessionIndependentCandidates(Election $election, array $data): ObjectCollection {
        $oldCandidates = $election->getIndependentCandidates();
        $oldGroup      = [];
        $newGroup      = [];
        $candidates    = new ObjectCollection();

        // group old candidates
        foreach ($oldCandidates as $item) {
            $oldGroup[$item->getConstituencyId()][] = $item->toArray('fieldName');
        }

        // group new candidates
        foreach ($data as $item) {
            $newGroup[$item['constituency_id']][] = [
                'name'            => $item['name'],
                'votes'           => $item['votes'],
                'constituency_id' => $item['constituency_id'],
            ];
        }

        // merging old and new groups
        $mergedGroup = $newGroup + $oldGroup;

        // loop group, create objects and append them to the collection
        foreach ($mergedGroup as $constituencyId => $group) {
            foreach ($group as $item) {
                $candidate = new IndependentCandidate();
                $candidate->fromArray($item, 'fieldName');
                $candidates->append($candidate);
            }
        }

        return $candidates;
    }

    /**
     * As opposite to merging data from post and session,
     * when candidates get loaded from session, there are no previous
     * candidates which need to be preserved, so overwrite away.
     *
     * @param  array            $data – data about the candidates
     * @return ObjectCollection $candidates
     */
    private function getIndependentCandidatesFromSession(array $data): ObjectCollection {
        $candidates = new ObjectCollection();

        foreach ($data as $item) {
            $candidate = new IndependentCandidate();
            $candidate->setName($item['name'])
                      ->setVotes($item['votes'])
                      ->setConstituencyId($item['constituency_id']);
            
            $candidates->append($candidate);
        }

        return $candidates;
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

        // load passed parties from session and overwrite only new information
        // instead of a full wipe-out
        if ($type === 'post') {
            $passedParties = $election->getPassedParties()->toKeyIndex('partyId');
        }
        
        $electionParties   = new ObjectCollection();
        
        foreach ($data as $item) {
            $partyId       = $item['party_id'];
            $partyVotes    = $item['total_votes'];
            $electionParty = $passedParties[$partyId] ?? new ElectionParty();

            $electionParty->setPartyId($item['party_id'])
                          ->setTotalVotes($partyVotes)
                          ->setOrd($item['ord']);

            if (isset($item['party_color'])) {
                $electionParty->setPartyColor($item['party_color']);
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

        // for post requests load votes from session and work on top of them,
        // instead of wiping them completely
        foreach ($passedParties as $party) {
            $partyId    = $party->getPartyId();
            $partyVotes = ($type === 'post') ? $party->getElectionPartyVotes() : new ObjectCollection();

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
     *
     * @param Election $election – object from which information gets extracted
     * @return array $electionData
     */
    protected function convertElectionToArray(Election $election): array {
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
                'party_color' => $item->getPartyColor() ? strtolower($item->getPartyColor()) : NULL, // lowercase color is needed for election comparison on save
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

        $electionData = [
            'election_id'            => $election->getId(),
            'assembly_type_id'       => $election->getAssemblyTypeId(),
            'population_census_id'   => $election->getPopulationCensusId(),
            'active_suffrage'        => $election->getActiveSuffrage(),
            'threshold_percentage'   => $election->getThresholdPercentage(),
            'total_valid_votes'      => $election->getTotalValidVotes(),
            'total_invalid_votes'    => $election->getTotalInvalidVotes(),
            'trust_no_one_votes'     => $election->getTrustNoOneVotes(),
            'parties'                => $parties,
            'independent_candidates' => $candidates,
            'parties_votes'          => $partiesVotes,
            'constituency_votes'     => $constituencyTotalVotes,
        ];

        return $electionData;
    }

    /**
     * Convert Election object to an associative array
     * and store it in a $_SESSION variable;
     * then use this array to regenerate those objects when reading from $_SESSSION
     * (PHP doesn't like it when whole objects get stored in sessions)
     */
    protected function setSessionElection(Election $election): void {
        $data = $this->convertElectionToArray($election);

        // used for navigation between the pages
        $data['reached_step'] = $data['parties_votes'] ? 3 : 2;

        $_SESSION = $data;
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
        $slug        = Router::getRequestParam('slug');
        $reachedStep = $this->getReachedStep();

        if ($currentStep === 1) {
            $prevStepUrl = false;
            $nextStepUrl = ($currentStep < $reachedStep) ? Router::url(['controller' => 'results', 'action' => 'preliminary', 'slug' => $slug]) : false;
        }
        elseif ($currentStep === 2) {
            $prevStepUrl = Router::url(['controller' => 'home', 'action' => 'index', 'slug' => $slug]);
            $nextStepUrl = ($currentStep < $reachedStep) ? Router::url(['controller' => 'results', 'action' => 'definitive', 'slug' => $slug]) : false;
        }
        elseif ($currentStep === 3) {
            $prevStepUrl = Router::url(['controller' => 'results', 'action' => 'preliminary', 'slug' => $slug]);
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