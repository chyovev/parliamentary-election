<?php

use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;

class ResultsController extends AppController {

    // NB! used for local redistribution of mandates
    private $allRemainders            = [];
    private $allPartiesIds            = [];
    private $allConstituenciesIds     = [];
    private $constituenciesRemainders = [];
    private $partiesObjectArray       = []; // array of ElectionParty objects which take part in the redistribution 
    private $constituenciesPartiesMandates = []; // how many mandates each party has received in each constituency

    ///////////////////////////////////////////////////////////////////////////
    public function preliminary() {
        $election = $this->populateElection('session');

        // if no election could be loaded from the session,
        // redirect to homepage
        if ( ! $election || ! $election->getAssemblyType()) {
            Router::redirect(['controller' => 'home', 'action' => 'index'], 302);
        }

        $this->setElectionSummaryData($election);

        $passedParties       = $this->getPassedPartiesWithPreliminaryMandates($election);
        $groupedCandidates   = $this->groupIndependentCandidatesByConstituency($election);
        $groupedPartiesVotes = $this->groupVotesByConstituencies($passedParties);
        $steps               = $passedParties->count() ? '3' : '2';

        $viewVars = [
            'title'         => sprintf('Стъпка 2/%s: Предварителни резултати', $steps),
            'passedParties' => $passedParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'candidates'    => $groupedCandidates,
            'partiesVotes'  => $groupedPartiesVotes,
        ];

        $this->displayFullPage('results.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function definitive() {
        $election = $this->populateElection('session');

        // if no election could be loaded from the session,
        // redirect to homepage
        if ( ! $election || ! $election->getAssemblyType()) {
            Router::redirect(['controller' => 'home', 'action' => 'index'], 302);
        }

        // if no parties votes could be loaded, redirect to preliminary results
        elseif ( ! $_SESSION['parties_votes']) {
            Router::redirect(['controller' => 'results', 'action' => 'preliminary'], 302);
        }

        $this->setElectionSummaryData($election);

        $constituencies = $this->getConstituenciesWithMandates($election);
        $totalMandates  = $election->getAssemblyType()->getTotalMandates();

        $passedParties  = $election->getPassedParties();
        $candidates     = $election->getIndependentCandidates();

        // check if any independent candidates were elected
        $this->filterElectedIndependentCandidates($candidates, $constituencies, $totalMandates);

        // distribute mandates between passed parties
        $hareNiemeyer = new HareNiemeyer();
        $hareNiemeyer->distributeMandates($passedParties, $totalMandates);

        // check if there are any lotting parties
        $lottingParties = $hareNiemeyer->getLottingParties();

        // if there were no lotting parties,
        // continue with the local mandates distribution between parties
        if ( ! $lottingParties->count()) {
            $this->distributeMandatesLocally($passedParties, $constituencies);
        }

        $viewVars = [
            'title'                   => 'Стъпка 3/3: Окончателни резултати',
            'candidates'              => $candidates->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'constituencies'          => $constituencies->toArray('Id', false, TableMap::TYPE_FIELDNAME),
            'globalHareNiemeyerQuota' => $hareNiemeyer->getQuota(),
            'passedParties'           => $passedParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'lottingParties'          => $lottingParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
        ];

        $this->displayFullPage('final.tpl', $viewVars);
    }





    /**
     * Data needed for the election summary presentation:
     *   – total (in)valid votes, threshold, etc.
     *   – type of assembly
     *   – constituencies
     *   ... etc.
     * @param Election
     */
    private function setElectionSummaryData(Election $election): void {
        $assembly       = $election->getAssemblyType();
        $census         = $election->getPopulationCensusWithPopulation();
        $constituencies = $election->getConstituenciesWithPopulation()->toArray(NULL, false, TableMap::TYPE_FIELDNAME);
        $coordinates    = array_reverse($constituencies, true); // reverse to avoid overlapping of Plovdiv and Plovdiv City

        $this->setVars([
            'election'       => $election->toArray(TableMap::TYPE_FIELDNAME),
            'assembly'       => $assembly->toArray(TableMap::TYPE_FIELDNAME),
            'census'         => $census->toArray(TableMap::TYPE_FIELDNAME),
            'constituencies' => $constituencies,
            'coordinates'    => $coordinates,
        ]);
    }

    /**
     * get the parties which have surpassed the threshold percentage
     * and roughly calculate how many mandates they would receive
     * if there were no independent candidates elected
     */
    private function getPassedPartiesWithPreliminaryMandates(Election $election): ObjectCollection {
        $passedParties = $election->getPassedParties();
        $totalMandates = $election->getAssemblyType()->getTotalMandates();

        if ($passedParties->count()) {
            $hareNiemeyer = new HareNiemeyer();
            $hareNiemeyer->distributeMandates($passedParties, $totalMandates);
        }

        return $passedParties;
    }

    /**
     * Independent candidates are stored as election's association,
     * but for easier data pre-fill, candidates are grouped by their constituency_id
     *
     * @param  Election $election
     * @return array    $data     – key: constituion_id; value1..N: candidate array
     */
    private function groupIndependentCandidatesByConstituency(Election $election): array {
        $candidates = $election->getIndependentCandidates();
        $data     = [];

        foreach ($candidates as $item) {
            $constId            = $item->getConstituencyId();
            $data[$constId][] = $item->toArray(TableMap::TYPE_FIELDNAME);
        }

        return $data;
    }

    /**
     * Parties votes are stored as ElectionParties' associations,
     * but for easier data pre-fill, votes are regrouped in a multidimensional array:
     * first level by constituency_id; second level by party_id
     *
     * @param  Election $election
     * @return array    $data
     */
    private function groupVotesByConstituencies(ObjectCollection $electionParties): array {
        $data = [];

        foreach ($electionParties as $party) {
            $partyVotes = $party->getElectionPartyVotes();
            $partyId    = $party->getPartyId();

            foreach ($partyVotes as $item) {
                $constituencyId = $item->getConstituencyId();

                $data[$constituencyId][$partyId] = $item->getVotes();
            }
        }

        return $data;
    }

    /**
     * Get constituencies according to the selected population census,
     * calculate how many many mandates each constituency should consist of
     * and the passing quota for independent candidates for each constituency
     *
     * NB! Constituency mandates data could easily be hard-coded in the database
     * as it changes approx. once every 10 years (on new population censuses),
     * but let's leave it as it is for now
     *
     * @param  Election $election – election object from which constituencies are fetched
     * @return ObjectCollection $constituencies
     */
    private function getConstituenciesWithMandates(Election $election): ObjectCollection {
        $assembly        = $election->getAssemblyType();
        $totalMandates   = $assembly->getTotalMandates();
        $minimumMandates = $assembly->getMinimumConstituencyMandates();

        $constituencies  = $election->getConstituenciesWithPopulation();

        // use ConstituencyMandatesDistributor (relying on the Hare-Niemeyer method)
        $distributor = new ConstituencyMandatesDistributor();
        $distributor->distribute($constituencies, $totalMandates, $minimumMandates);

        // set passing quota for each constituency
        foreach ($constituencies as $item) {
            $votes    = $item->getVirtualColumn('total_valid_votes');
            $mandates = $item->getHareNiemeyerMandates();
            $quota    = $votes / $mandates;
            $item->setVirtualColumn('quota', $quota);
        }

        return $constituencies;
    }

    /**
     * Go through all independent candidates (if any), check their votes
     * against the quota of the constituency they are competing in
     * and mark them as elected (if so), but also decrease by 1 the mandates
     * of the respective constituency AND the total mandates for the whole country
     *
     * @param ObjectCollection $candidates
     * @param ObjectColleciton $constituencies
     * @param int              $mandatesToDistribute – passed by reference
     */
    private function filterElectedIndependentCandidates(ObjectCollection $candidates, ObjectCollection $constituencies, int &$mandatesToDistribute): void {
        $constituenciesObjectArray = $constituencies->toKeyIndex();

        foreach ($candidates as $candidate) {
            $constId      = $candidate->getConstituencyId();
            $constituency = $constituenciesObjectArray[$constId];
            $votes        = $candidate->getVotes();
            $quota        = $constituency->getVirtualColumn('quota');
            $status       = (bool) ($votes >= $quota);

            // add virtual column which holds the status
            $candidate->setVirtualColumn('is_elected', $status);

            // if the candidate was indeed elected,
            // decrease the mandates of the constituency and the country
            if ($status) {
                $mandates = $constituency->getHareNiemeyerMandates();
                $constituency->setHareNiemeyerMandates($mandates - 1);
                $mandatesToDistribute--;
            }
        }
    }


    /**
     * Apply the Hare-Niemeyer method to all constituencies individually
     * using the mandates allocated to the respective constituency
     * and the valid votes of the passed parties in it
     * (excluding independent candidates' votes)
     *
     * @param ObjectCollection $passedParties
     * @param ObjectCollection $constituencies
     */
    private function distributeMandatesLocally(ObjectCollection $passedParties, ObjectCollection $constituencies): void {
        $partiesConstieuncyVotes  = $this->groupVotesByConstituencies($passedParties);

        $data = [];

        // cycle through all constituencies
        foreach ($constituencies as $item) {
            $constituencyId      = $item->getId();
            $localMandates       = $item->getHareNiemeyerMandates();

            $localPassedParties  = $this->getPassedPartiesLocalStand($passedParties, $constituencyId);

            // distribute mandates locally
            $hareNiemeyer        = new HareNiemeyer();
            $hareNiemeyer->distributeMandates($localPassedParties, $localMandates);
            
            // check if any remaining mandates have to be distributed by lot
            $lottingDistribution = $this->distributeLocalMandatesBetweenLottingParties($hareNiemeyer, $localMandates);

            // sum all mandates received by each party on a local level
            // and add the value to the original party object
            foreach ($localPassedParties as $index => $localParty) {
                $partyId       = $localParty->getPartyId();
                $localMandates = $localParty->getHareNiemeyerMandates();

                // store globally the local mandates count for each party
                $this->constituenciesPartiesMandates[$constituencyId][$partyId] = $localMandates;

                $passedParty   = $passedParties[$index];
                $passedParty->incrementLocalMandatesBy($localMandates);
            }

            // mark constituency with true or false
            $this->checkIfConstituencyShouldBeExcludedFromRedistribution($item, $localPassedParties);

            $descendingRemainders = $this->getPartiesArrayByDescendingRemainder($localPassedParties);

            $totalPartyVotes = array_sum($partiesConstieuncyVotes[$constituencyId]);

            // group data by constituency for easier iteration in the template
            $data[$constituencyId] = [
                'parties'    => $localPassedParties->toArray('partyId', false, TableMap::TYPE_FIELDNAME),
                'quota'      => $hareNiemeyer->getQuota(),
                'votes'      => $totalPartyVotes,
                'remainders' => $descendingRemainders,
                'lotting'    => $lottingDistribution,
            ];

        }

        $this->setVar('localDistribution', $data);

        if ($this->isMandatesRedistributionNeeded($passedParties)) {
            $this->redistributeMandatesLocally($passedParties, $constituencies, $data);
        }

        // set final definitive results by constituencies for each party
        $this->setVar('constituenciesMandates', $this->constituenciesPartiesMandates);
    }

    /**
     * Copies all passed parties into a separate collection
     * which holds information about the votes only for the respective constituency
     */
    private function getPassedPartiesLocalStand(ObjectCollection $passedParties, int $constituencyId): ObjectCollection {
        $localPassedParties = new ObjectCollection();

        foreach ($passedParties as $party) {
            $localParty = $this->copyPartyDataToObject($party);

            // set votes of current constituency as total votes for party
            $partyVotesByConstituency = $party->getElectionPartyVotes()->toKeyIndex('constituencyId');
            $localVotes               = $partyVotesByConstituency[$constituencyId]->getVotes();
            $localParty->setTotalVotes($localVotes);

            $localPassedParties->append($localParty);
        }

        return $localPassedParties;
    }

    /**
     * Copies party first-level data to a new object
     * (including virtual columns)
     *
     * @param  ElectionParty $party    – object from which to copy
     * @return ElectionParty $newParty – object with copied data
     */
    private function copyPartyDataToObject(ElectionParty $party): ElectionParty {
        $sourceData     = $party->toArray();
        $virtualColumns = $party->getVirtualColumns();
        
        $newParty   = new ElectionParty();
        $newParty->fromArray($sourceData);

        // copy virtual columns, too
        foreach ($virtualColumns as $key => $value) {
            $newParty->setVirtualColumn($key, $value);
        }

        return $newParty;
    }

    /**
     * If there are any lotting parties, give remaining mandates
     * to the party with smallest list number
     *
     * NB! Parties are sorted by ord in ascending order,
     * so smallest ord is considered smallest list number
     *
     * @param  HareNiemeyer $hareNiemeyer: holds information about lotting parties
     *                                     and distributed mandates
     * @param  int $localMandates:         how many mandates should have been districuted
     * @return bool $status:               whether any lotting took place in the constituency
     */
    private function distributeLocalMandatesBetweenLottingParties(HareNiemeyer $hareNiemeyer, int $localMandates): bool {
        $lottingParties    = $hareNiemeyer->getLottingParties();
        $remainingMandates = $localMandates - $hareNiemeyer->getDistributedMandates();

        // if there are no remaining mandates to distribute, there was no lotting
        if ($remainingMandates === 0) {
            return false;
        }

        // otherwise cycle through all lotting parties,
        // give them an additional mandate, and decrease the reamining mandates
        foreach ($lottingParties as $party) {
            if ( ! $remainingMandates) {
                break;
            }

            $partyMandates = $party->getHareNiemeyerMandates();
            $party->setHareNiemeyerMandates($partyMandates + 1)
                  ->setVirtualColumn('drawn_lot', true); // mark the party as having received a lot mandate

            $remainingMandates--;
        }

        return true;
    }

    /**
     * Extract all parties remainders, use them as key
     * and sort the array in descending order
     *
     * @param  ObjectCollection $parties
     * @return array            $data
     */
    private function getPartiesArrayByDescendingRemainder(ObjectCollection $parties): array {
        $data              = [];
        $partiesRemainders = [];

        // extract only parties remainders
        foreach ($parties as $index => $item) {
            $remainder = $item->getHareNiemeyerRemainder();
            $partiesRemainders[$index] = $remainder;
        }

        // sort remainders in descending order while keeping party_index as key
        arsort($partiesRemainders);

        // cycle through remainders and add parties in the same order
        foreach ($partiesRemainders as $index => $remainder) {
            $data[$index] = $parties[$index]->toArray(TableMap::TYPE_FIELDNAME);
        }

        return $data;
    }

    /**
     * if after local distribution there's even one party
     * which has different values for local and global mandates,
     * a redistribution is needed
     *
     * @param  ObjectCollection $passedParties
     * @return bool
     */
    private function isMandatesRedistributionNeeded(ObjectCollection $passedParties): bool {
        foreach ($passedParties as $item) {
             $party  = $item->toArray(TableMap::TYPE_FIELDNAME);
             $global = $party[HareNiemeyerInterface::MANDATES_COLUMN];
             $local  = $party[HareNiemeyerInterface::LOCAL_MANDATES_COLUMN];

             if ($global !== $local) {
                return true;
             }
        }

        return false;
    }

    /**
     * Constituencies where NO additional mandates were distributed on local level
     * should be excluded from the redistribution
     *
     * @param Constituency     $constituency
     * @param ObjectCollection $localPassedParties – parties which took part in the distribution
     */
    private function checkIfConstituencyShouldBeExcludedFromRedistribution(Constituency $constituency, ObjectCollection $localPassedParties): void {
        $exclude = true;

        foreach ($localPassedParties as $item) {
            if ($item->hasPartyReceivedAdditionalMandate()) {
                $exclude = false;
                break;
            }
        }

        $constituency->setVirtualColumn('excluded', $exclude);
    }
    
    /**
     * After the local distribution of mandates, some parties
     * have received more mandates (and some less) than they have
     * on a country-base, so these mandates need to be redistributed
     *
     * @param ObjectCollection $passedParties     – the parties with their current mandates' state
     * @param ObjectCollection $constituencies
     * @param array            $localDistribution – data grouped by constituency_id after the local distribution of mandates
     */
    private function redistributeMandatesLocally(ObjectCollection $passedParties, ObjectCollection $constituencies, array $localDistribution): void {
        $data = [];

        // passed parties' collection gets cloned,
        // otherwise the initial local mandates' distribution gets messed up
        $parties                  = $this->cloneCollection($passedParties);
        $this->partiesObjectArray = $parties->toKeyIndex('partyId');


        $constituenciesObjectArray = $constituencies->toKeyIndex();

        // NB! this function populates the class remainder private properties
        $this->regroupLocalDistributionData($localDistribution, $constituenciesObjectArray);

        // remainders are sorted in ascending order
        // since party with smallest remainder which has received an additional mandate
        // is going to give it to the party with the biggest remainder
        // which has NOT received an additional mandate in the respective constituency
        asort($this->allRemainders);

        $iteration = 1;

        // keep redistributing until the global and local mandates have been equalized
        // OR until there are no more remainders (in which case the the Election Commission
        // would have to make a decision)
        while ($this->isMandatesRedistributionNeeded($parties)) {
            if ( ! count($this->allRemainders)) {
                break;
            }

            // find the party with the smallest remainder in a non-excluded constituency
            // which has more local than global mandates and mark it as a “giving party”
            foreach ($this->allRemainders as $index => $remainder) {
                // consider the first party to be a match
                $givingPartyId     = $this->allPartiesIds[$index];
                $givingPartyObject = $this->partiesObjectArray[$givingPartyId];
                $givingPartyArray  = $this->partiesObjectArray[$givingPartyId]->toArray(TableMap::TYPE_FIELDNAME);
                
                $givingPartyLocalMandates  = $givingPartyObject->getLocalHareNiemeyerMandates();
                $givingPartyGlobalMandates = $givingPartyObject->getTotalHareNiemeyerMandates();

                // constituencies where no additional mandates were given
                // are excluded from the redistribution
                $constituencyId         = $this->allConstituenciesIds[$index];
                $isConstituencyExcluded = $constituenciesObjectArray[$constituencyId]->getVirtualColumn('excluded');

                // if the party has more local than global and the constituency is not excluded,
                // there's a match for the criteria; stop the cycle
                if ($givingPartyLocalMandates > $givingPartyGlobalMandates && ! $isConstituencyExcluded) {
                    break;
                }
            }

            // sort the remainders of all parties in this constituency in descending order
            arsort($this->constituenciesRemainders[$constituencyId]);

            // the party with the highest remainder with no additional mandates
            // should get a local mandate there
            $receivingPartyArray = $this->findReceivingPartyInConstituency($constituencyId, $localDistribution[$constituencyId]['parties']);


            // save a snapshot of all parties BEFORE current redistribution
            $data[$iteration] = [
                'parties'              => $parties->toArray('partyId', false, TableMap::TYPE_FIELDNAME),
                'constituency_id'      => $constituencyId,
                'giving_party_id'      => $givingPartyId,
                'receiving_party_id'   => $receivingPartyArray['party_id'] ?? false, // on some iterations there is no receiving party
                'iteration_remainders' => $this->getFirstNSmallestRemainders(15),
                'local_snapshot'       => $localDistribution[$constituencyId]['parties'], // snapshot of current constituency's parties' state
            ];

            // if there is a receiving party, proceed with the mandates' swap
            if ($receivingPartyArray) {

                // the giving party's remainder in the specific constituency is disregarded
                // so that the party can't receive a mandate in the same constituency it has given one
                unset($this->constituenciesRemainders[$constituencyId][$givingPartyId]);
                $givingPartyArray[HareNiemeyerInterface::REMAINDER_COLUMN] = '';

                // get the receiving party's object which keeps track of
                // all local mandates given to a party
                $receivingPartyId     = $receivingPartyArray['party_id'];
                $receivingPartyObject = $this->partiesObjectArray[$receivingPartyId];

                // mark locally the receiving party as having received a mandate
                // and the giving party as NOT having received a mandate (even if it doesn't participate anymore)
                $receivingPartyArray['has_received_hare_niemeyer_mandate'] = true;
                $givingPartyArray['has_received_hare_niemeyer_mandate']    = false;

                // locally increase/decrease the mandates count of both parties
                $receivingPartyArray['local_hare_niemeyer_mandates']++;
                $givingPartyArray['local_hare_niemeyer_mandates']--;

                // increase/decrase all mandates count for each party on constituency level
                $this->constituenciesPartiesMandates[$constituencyId][$receivingPartyId]++;
                $this->constituenciesPartiesMandates[$constituencyId][$givingPartyId]--;

                // globally increase/decrease the mandates count of both parties
                $givingPartyObject->decrementLocalMandatesBy(1);
                $receivingPartyObject->incrementLocalMandatesBy(1);

                // override both local parties' values to the $localDistribution array
                // as it will be on next iteration to find the new receiving party
                $localDistribution[$constituencyId]['parties'][$receivingPartyId] = $receivingPartyArray;
                $localDistribution[$constituencyId]['parties'][$givingPartyId]    = $givingPartyArray;

                // add the receiving party remainder to the array with all remainders
                // as the associated mandate may be further redistributed
                $this->allRemainders[]        = $receivingPartyArray['hare_niemeyer_remainder'];
                $this->allPartiesIds[]        = $receivingPartyArray['party_id'];
                $this->allConstituenciesIds[] = $constituencyId;

                // resort once again the remainders to keep the smallest on top
                asort($this->allRemainders);
            }

            // regardless of whether the mandate was redistributed,
            // the current remainder should no longer be used for further redistribution
            unset($this->allRemainders[$index]);
            unset($this->allPartiesIds[$index]);
            unset($this->allConstituenciesIds[$index]);


            $iteration++;
        }

        // if local/global mandates are still not equalized
        // and there are no remainders left (since we're out of the while loop),
        // the Election Commission needs to make a decision
        if ($this->isMandatesRedistributionNeeded($parties)) {
            $this->setVar('noMoreRemainders', true);
        }

        // otherwise add one last iteration which showes that
        // the local and global mandates were equalized
        else {
            $data[$iteration] = [
                'parties'            => $parties->toArray('partyId', false, TableMap::TYPE_FIELDNAME),
                'constituency_id'    => $constituencyId,
                'giving_party_id'    => false,
                'receiving_party_id' => false,
                'local_snapshot'     => $localDistribution[$constituencyId]['parties'],
                'final'              => true,
            ];
        }

        $this->setVar('redistribution', $data);
    }

    /**
     * “Cloning” of collection only first-level data
     * (the built-in clone method gets really greedy for associative objects)
     *
     * @param  ObjectCollection – source collection
     * @return ObjectCollection – cloned collection
     */
    private function cloneCollection(ObjectCollection $collection): ObjectCollection {
        $newCollection = new ObjectCollection();

        foreach ($collection as $item) {
            $newCollection->append($this->copyPartyDataToObject($item));
        }

        return $newCollection;
    }

    /**
     * The local distribution data needs to be reorganized for the redistribution;
     * remainders need to be stored in a single array.
     * However, since the remainders belong to parties and constituencies,
     * these associations need to be put in separate arrays using the same key.
     *
     * @param array $localDistribution         – data grouped in the local distribution by constituency
     * @param array $constituenciesObjectArray – all constituencies
     */
    private function regroupLocalDistributionData(array $localDistribution, array $constituenciesObjectArray): void {
        $i = 0;

        foreach ($localDistribution as $constituencyId => $data) {
            $constituency = $constituenciesObjectArray[$constituencyId];

            // loop through all parties for each constituency
            // and get their remainders
            foreach ($data['parties'] as $partyId => $party) {
                $remainder = $party['hare_niemeyer_remainder'];

                // the remainders of all parties get stored in the constituencies remainder array
                $this->constituenciesRemainders[$constituencyId][$partyId] = $remainder;

                // the mandate redistribution on the other hand relies
                // only on remainders of parties which have received an additional mandate
                $hasPartyReceivedAdditionalMandate = $party['has_received_hare_niemeyer_mandate'];

                // also, constituencies where no additional mandates were given
                // are excluded from the redistribution
                $isConstituencyExcluded = $constituency->getVirtualColumn('excluded');

                if ($hasPartyReceivedAdditionalMandate &&  !$isConstituencyExcluded) {
                    $this->allRemainders[$i]        = $remainder;
                    $this->allPartiesIds[$i]        = $partyId;
                    $this->allConstituenciesIds[$i] = $constituencyId;

                    $i++;
                }
            }
        }
    }

    /**
     * Remainders in the constituency are sorted in descending order
     * and have the structure array[party_id] = remainder.
     * Find the party with the highest remainder which has not received
     * an additional mandate in the constituency, and return it.
     * If multiple parties have the same highest remainder,
     * the one with the smallest list number is used (i.e. ord number)
     *
     * @param  array $constituencyId
     * @param  array $constituencyParties
     * @return array $selectedParty
     */
    private function findReceivingPartyInConstituency(int $constituencyId, array $constituencyParties): array {
        $selectedParty = [];

        foreach ($this->constituenciesRemainders[$constituencyId] as $partyId => $remainder) {
            $party = $constituencyParties[$partyId];

            // find the first party which has NOT received an additional mandate locally
            if ($party['has_received_hare_niemeyer_mandate']) {
                continue;
            }

            // there is still no selected party, assign the first matching party
            // as a selected one
            if ( ! $selectedParty) {
                $selectedParty = $party;
            }

            // if there is a selected party, and the next party has the same remainder,
            // use it as a selected IF its list number is smaller
            else {
                if ($remainder == $selectedParty['hare_niemeyer_remainder']) {
                    if ($party['ord'] < $selectedParty['ord']) {
                        $selectedParty = $party;
                    }
                }

                // if the remainder is not the same, don't keep looking
                else {
                    break;
                }
            }
        }

        return $selectedParty;
    }

    /**
     * Cycle through all parties remainders and get the first N remainders
     * of parties which have more local than global mandates
     *
     * @param int   $total              – how many mandates to extract
     */
    private function getFirstNSmallestRemainders(int $total = 15): array {
        $counter    = 0;
        $remainders = [];

        foreach ($this->allRemainders as $index => $remainder) {
            $partyId = $this->allPartiesIds[$index];
            $partyLocalMandates  = $this->partiesObjectArray[$partyId]->getLocalHareNiemeyerMandates();
            $partyGlobalMandates = $this->partiesObjectArray[$partyId]->getTotalHareNiemeyerMandates();

            if ($partyLocalMandates > $partyGlobalMandates) {
                $remainders[] = [
                    'party_id'        => $partyId,
                    'remainder'       => $remainder,
                    'constituency_id' => $this->allConstituenciesIds[$index],
                ];

                $counter++;

                if ($counter == $total) { break; }
            }
        }

        return $remainders;
    }

}