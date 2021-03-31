<?php

use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;

class ResultsController extends AppController {

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
     * @return array    $result   – key: constituion_id; value1..N: candidate array
     */
    private function groupIndependentCandidatesByConstituency(Election $election): array {
        $candidates = $election->getIndependentCandidates();
        $result     = [];

        foreach ($candidates as $item) {
            $constId            = $item->getConstituencyId();
            $result[$constId][] = $item->toArray(TableMap::TYPE_FIELDNAME);
        }

        return $result;
    }

    /**
     * Parties votes are stored as ElectionParties' associations,
     * but for easier data pre-fill, votes are regrouped in a multidimensional array:
     * first level by constituency_id; second level by party_id
     *
     * @param  Election $election
     * @return array    $result
     */
    private function groupVotesByConstituencies(ObjectCollection $electionParties): array {
        $result = [];

        foreach ($electionParties as $party) {
            $partyVotes = $party->getElectionPartyVotes();
            $partyId    = $party->getPartyId();

            foreach ($partyVotes as $item) {
                $constituencyId = $item->getConstituencyId();

                $result[$constituencyId][$partyId] = $item->getVotes();
            }
        }

        return $result;
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
        $constituenciesArray = $constituencies->toKeyIndex();

        foreach ($candidates as $candidate) {
            $constId      = $candidate->getConstituencyId();
            $constituency = $constituenciesArray[$constId];
            $votes        = $candidate->getVotes();
            $quota        = $constituency->getVirtualColumn('quota');
            $status       = (bool) ($votes >= $quota || $constId == 1); // NB! Remove fake condition

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


    ///////////////////////////////////////////////////////////////////////////
    // NB! Sum parties votes in constituency, don't include independent candidates' votes
    private function distributeMandatesLocally(ObjectCollection $passedParties, ObjectCollection $constituencies): void {
        $partiesConstieuncyVotes  = $this->groupVotesByConstituencies($passedParties);

        $result = [];

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
            foreach ($localPassedParties as $index => $localParty) {
                $localMandates = $localParty->getHareNiemeyerMandates();

                $passedParty   = $passedParties[$index];
                $passedParty->incrementLocalMandatesBy($localMandates);
            }

            // TODO: add local_hare_niemeyer_mandates to original party

            $descendingRemainders = $this->getPartiesArrayByDescendingRemainder($localPassedParties);

            $totalPartyVotes = array_sum($partiesConstieuncyVotes[$constituencyId]);

            $result[$constituencyId] = [
                'parties'    => $localPassedParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
                'quota'      => $hareNiemeyer->getQuota(),
                'votes'      => $totalPartyVotes,
                'remainders' => $descendingRemainders,
                'lotting'    => $lottingDistribution,
            ];

        }

        $this->setVar('localMandateDistribution', $result);
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
        $sourceData               = $party->toArray();
        $virtualColumns           = $party->getVirtualColumns();

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
     * @return array            $remainderArray; key: $remainder, value: election party array
     */
    private function getPartiesArrayByDescendingRemainder(ObjectCollection $parties): array {
        $remainderArray = [];

        foreach ($parties as $item) {
            $remainder = (string) $item->getHareNiemeyerRemainder();
            $remainderArray[$remainder] = $item->toArray(TableMap::TYPE_FIELDNAME);
        }

        krsort($remainderArray);

        return $remainderArray;
    }

}