<?php

use Propel\Runtime\Exception\PropelException;

class ValidationController extends AppController {

    private $validationErrors = [];

    ///////////////////////////////////////////////////////////////////////////
    public function election() {
        try {
            $election = $this->populateElection('post');
            $this->validationStep1($election);
            $this->setSessionElection($election);

            $status = true;
            $errors = false;
        }
        catch (IncorrectInputDataException $e) {
            $status = false;
            $errors = $e->getFields();
        }

        $response = [
            'status' => $status,
            'errors' => $errors,
        ];
        $this->renderJSONContent($response);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function constituencies() {
        $constituencyId = Router::getRequestParam('id');

        try {
            $election = $this->getElectionFromSession();
            $this->populateIndependentCandidatesFromData($election, 'post');
            $this->populateElectionPartiesVotesFromData($election, 'post');
            $this->populateConstituencyValidVotes($election, 'post');

            if ($constituencyId) {
                $this->deleteIndependentCandidatesIfNecessary($election, $constituencyId);
            }

            $this->updatePartiesColors($election);

            $this->validationStep2($election, $constituencyId);
            $this->setSessionElection($election);

            $status = true;
        }
        catch (IncorrectInputDataException $e) {
            $status = false;
            $errors = $e->getFields();
        }

        $response = [
            'status' => $status,
            'errors' => $errors ?? false,
        ];
        $this->renderJSONContent($response);
    }

    ///////////////////////////////////////////////////////////////////////////
    // reset current page input fields
    public function reset() {
        $currentStep  = (int) Router::getRequestParam('step');
        $constituency = (int) Router::getRequestParam('constituency');

        // if it's just a single constituency,
        // loop through all parties and candidates stored in session
        // and unset their votes in said constituency
        if ($constituency) {
            if ($_SESSION['parties_votes']) {
                foreach ($_SESSION['parties_votes'] as $partyId => $group) {
                    unset($_SESSION['parties_votes'][$partyId][$constituency]);
                }
            }
            if ($_SESSION['independent_candidates']) {
                foreach ($_SESSION['independent_candidates'] as $key => $item) {
                    if ($item['constituency_id'] === $constituency) {
                        unset($_SESSION['independent_candidates'][$key]);
                    }
                }
            }

            // unset total valid votes for the constituency
            unset($_SESSION['constituency_votes'][$constituency]);

            // lower the reached step to avoid direct access to definitive results
            $_SESSION['reached_step'] = min(2, $this->getReachedStep());
        }

        // if it's a step, butcher pretty much everything past that step
        else {
            switch ($currentStep) {
                case 2:
                    $_SESSION['independent_candidates'] = [];
                    $_SESSION['parties_votes']          = [];
                    $_SESSION['constituency_votes']     = [];
                    $_SESSION['reached_step']           = $currentStep;
                    break;

                // if step 1 or something else, delete whole session
                default:
                    $_SESSION = [];
            }
        }

        $this->renderJSONContent(['status' => true]);
    }


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Tries to load Election from session (when validating constituencies)
     * and throws an exception on failure
     * 
     * @return $election
     *
     * @throws IncorrectInputDataException 
     */
    private function getElectionFromSession(): Election {
        $election = $this->populateElection('session');
        if ( ! $election) {
            throw new IncorrectInputDataException(['constituencies_fields', 'Сесията ви е изтекла. Моля, презаредете страницата.']);
        }

        return $election;
    }

    /**
     * New about-to-be-saved independent candidates are actually
     * merged with previous independent candidates (also from other constituencies)
     * in order not to wipe them out, but if all candidates get deleted
     * from a constituency, previously stored candidates there should also disappear.
     *
     * @param Election $election       – object from which to fetch the candidates
     * @param int      $constituencyId – process only candidates from this constituency
     */
    private function deleteIndependentCandidatesIfNecessary(Election $election, int $constituencyId): void {
        $data       = $_POST['independent_candidates'] ?? [];

        if ( ! $data) {
            $candidates = $election->getIndependentCandidates();

            foreach ($candidates as $index => $item) {
                if ($item->getConstituencyId() === $constituencyId) {
                    $candidates->removeObject($item);
                }
            }

            $election->setIndependentCandidates($candidates);
        }
    }

    /**
     * Cycles through all passed parties and updates their color
     * using $_POST data
     *
     * @param Election $election – Object from which passed parties get extracted
     */
    private function updatePartiesColors(Election $election): void {
        $passedParties = $election->getPassedParties();
        $data          = $_POST['parties'] ?? [];

        foreach ($passedParties as $item) {
            $partyId = $item->getPartyId();
            $color   = $data[$partyId]['party_color'] ?? NULL; 

            if ($color) {
                $item->setPartyColor($color);
            }
        }

    }

    /**
     * Checks if all data used in the election is valid
     * and throws an exception on failure
     * 
     * @param  Election $election
     * @throws IncorrectInputDataException
     */
    private function validationStep1(Election $election) {
        $this->validateAssembly($election);
        $this->validatePopulationCensus($election);
        $this->validateVotesInformation($election);
        $this->validateThreshold($election);
        $this->validateElectionParties($election, 2);

        if ($this->validationErrors) {
            $this->throwValidationErrors();
        }
    }

    /**
     * Validates election's associated assembly
     * and adds a validation error on failure
     * 
     * @param Election $election – Object from which passed assembly type gets extracted
     */
    private function validateAssembly(Election $election): void {
        if ( ! $election->getAssemblyType()) {
            $this->addValidationError('assembly_type_id', 'Моля, изберете тип на парламентарни избори.');
        }
    }

    /**
     * Validates election's associated population census
     * and adds a validation error on failure
     * 
     * @param Election $election – Object from which passed population census gets extracted
     */
    private function validatePopulationCensus(Election $election): void {
        if ( ! $election->getPopulationCensusWithPopulation()) {
            $this->addValidationError('population_census_id', 'Моля, изберете население по данни на НСИ.');
        }
    }

    /**
     * Validates election's votes (valid + invalid) and active suffrage
     * and adds validation errors on failure
     * 
     * @param Election $election – Object from which passed votes information gets extracted
     */
    private function validateVotesInformation(Election $election): void {
        $totalValidVotes   = $election->getTotalValidVotes();
        $totalInvalidVotes = $election->getTotalInvalidVotes();
        $trustNoOneVotes   = $election->getTrustNoOneVotes();
        $activeSuffrage    = $election->getActiveSuffrage();
        $census            = $election->getPopulationCensusWithPopulation();

        if ($activeSuffrage <= 0) {
            $this->addValidationError('active_suffrage', 'Моля, въведете брой души, имащи право на глас.');
        }

        if ($totalValidVotes <= 0) {
            $this->addValidationError('total_valid_votes', 'Моля, въведете брой действителни гласове.');
        }

        if ($trustNoOneVotes < 0) {
            $this->addValidationError('trust_no_one_votes', 'Моля, въведете брой гласове „Не подкрепям никого“.');
        }

        // if the active suffrage is greater than the population
        if ($census && $activeSuffrage > $census->getPopulation()) {
            $this->addValidationError('active_suffrage', 'Не може броят на имащите право на глас да надвишава броя на населението.');
        }

        // if there are more votes than people entitled to vote, abort
        if ($totalValidVotes + $totalInvalidVotes + $trustNoOneVotes > $activeSuffrage) {
            $this->addValidationError('total_valid_votes', 'Не може общият брой гласове да надвишава броя на имащите право на глас.');
        }

    }

    /**
     * Validates election's percentage threshold for passing parties
     * and adds a validation error on failure
     * 
     * @param Election $election – Object from which passed threshold percentage gets extracted
     */
    private function validateThreshold(Election $election): void {
        $threshold = $election->getThresholdPercentage();

        if ($threshold < 1 || $threshold > 99) {
            $this->addValidationError('threshold_percentage', 'Долната граница за представителство трябва да е в диапазона [1,99]');
        }
    }

    /**
     * Validates election's associated parties (votes, IDs, colors, parties count)
     * and adds validation errors on failure
     * 
     * @param Election $election    – Object from which passed election parties get extracted
     * @param int      $minParties
     */
    private function validateElectionParties(Election $election, int $minParties = 2): void {
        $electionParties = $election->getElectionParties();
        $allParties      = PartyQuery::create()->find()->toKeyIndex();
        $partiesErrors   = [];

        foreach ($electionParties as $i => $item) {
            $partyInList = $i + 1;
            $partyId     = $item->getPartyId();
            $color       = $item->getPartyColor();
            $votes       = $item->getTotalVotes();
            $ord         = $item->getOrd();

            if ( ! isset($allParties[$partyId])) {
                $partiesErrors[] = sprintf("Несъществуваща партия под номер #%s в списъка", $partyInList);
            }
            if ($votes < 0) {
                $partiesErrors[] = sprintf("Невалиден брой гласове за партия #%s в списъка", $partyInList);
            }
            if ($color && ! preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                $partiesErrors[] = sprintf("Невалиден цвят за партия #%s в списъка", $partyInList);
            }
        }

        if ($partiesErrors) {
            $this->addValidationError('parties', implode('<br />', $partiesErrors));
        }
        elseif ($electionParties->count() < $minParties) {
            $this->addValidationError('parties', 'Моля, изберете поне две партии.');
        }
        else {
            $this->validateElectionPartiesVotes($election);
        }
    }

    /**
     * Validates election's associated parties' votes
     * and adds validation errors on failure
     * 
     * @param Election $election – Object from which passed parties votes get extracted
     */
    private function validateElectionPartiesVotes(Election $election): void {
        $electionParties = $election->getElectionParties();
        $partiesVotes    = $electionParties->getColumnValues('TotalVotes');
        $partiesVotesSum = array_sum($partiesVotes);
        $totalValidVotes = $election->getTotalValidVotes();

        if ($partiesVotesSum === 0) {
            $this->addValidationError('parties', 'Поне една партия трябва да има повече от 0 гласа.');
        }
        elseif ($partiesVotesSum < 0 || $partiesVotesSum > $totalValidVotes) {
            $this->addValidationError('parties', sprintf('Общият брой гласове на партиите (%s) надвишава броя на действителните гласове (%s)', $partiesVotesSum, $totalValidVotes));
        }

    }

    /**
     * Validates all constituencies' passed votes
     * and adds a validation error on failure
     * 
     * @param Election $election       – Object from which passed parties' and independent candidates' votes get extracted
     * @param string   $constituencyId – type is string, as it gets extracted from the URL
     */
    private function validationStep2(Election $election, string $constituencyId = NULL): void {
        $constituencies        = $election->getConstituenciesWithPopulation()->toKeyIndex();
        $groupedByConstituency = $this->groupPartiesVotesAndCandidatesByConstituency($election, $constituencies);
        $exceededValidVotes    = false; // flag for whether total constituency valid votes exceed the active suffrage

        // if there's a constituency id specified,
        // overwrite all groups using only its data
        if ($constituencyId) {
            $this->checkIfConstituencyExists($constituencies, $constituencyId);
            $groupedByConstituency = [(int) $constituencyId => $groupedByConstituency[$constituencyId] ?? []];
        }

        // otherwise check all constituencies
        $constituenciesValidVotesSum = 0;

        foreach ($groupedByConstituency as $constId => $group) {
            $constituencyValidVotes      = $this->getConstituencyValidVotes($constituencies[$constId]);
            $totalConstituencyPopulation = $constituencies[$constId]->getPopulation();
            $this->validateSingleConstituencyVotes($group, $constId, $constituencyValidVotes, $totalConstituencyPopulation);

            $constituenciesValidVotesSum += $constituencyValidVotes;
        }

        // check if all votes exceed the people entitled to vote
        if ($constituenciesValidVotesSum > $election->getActiveSuffrage()) {
            $exceededValidVotes = true;
            $this->addValidationError('constituencies_fields', 'Броят гласове във всички МИР надвишава броя души, имащи право на глас.');
        }

        // if there were any validation errors, throw an exception
        if ($this->validationErrors) {

            // if all constituencies were checked (no constituency_id parameter was psased),
            // add another validation which prints a global message
            if ( ! $constituencyId && ! $exceededValidVotes) {
                $this->addValidationError('constituencies_fields', 'Моля, отстранете нередностите по посочените избирателни райони.');
            }

            $this->throwValidationErrors();
        }
    }

    /**
     * Tries to get the virtual column of a constituency
     * holding the total valid votes.
     * If it fails, show 0.
     *
     * @param  Constituency
     * @return int
     */
    private function getConstituencyValidVotes(Constituency $constituency) {
        try {
            return $constituency->getVirtualColumn('total_valid_votes');
        }
        catch (PropelException $e) {
            return 0;
        }
    }

    /**
     * Groups all parties votes and independent candidates by their constituency ID
     * 
     * @param  Election $election       – Object from which election parties and independent candidates get extracted
     * @param  array    $constituencies – all constituencies with their IDs as keys
     *
     * @return array    $groupedByConstituency
     */
    private function groupPartiesVotesAndCandidatesByConstituency(Election $election, array $constituencies): array {
        $candidates            = $election->getIndependentCandidates();
        $passedParties         = $election->getPassedParties();
        
        $groupedByConstituency = [];

        // initiate arrays for all constituencies
        foreach ($constituencies as $id => $item) {
            $groupedByConstituency[$id] = [];
        }

        // add all independent candidates to the respective constituencies
        foreach ($candidates as $item) {
            $constituencyId = $item->getConstituencyId();
            $this->checkIfConstituencyExists($constituencies, $constituencyId);

            $groupedByConstituency[$constituencyId]['candidates'][] = $item;
        }

        // add all parties' votes to the respective constituencies
        foreach ($passedParties as $party) {
            $votes = $party->getElectionPartyVotes();
            $partyId = $party->getPartyId();

            foreach ($votes as $item) {
                $constituencyId = $item->getConstituencyId();
                $this->checkIfConstituencyExists($constituencies, $constituencyId);

                $groupedByConstituency[$constituencyId]['parties'][$partyId] = $item;
            }
        }

        return $groupedByConstituency;
    }

    /**
     * Validates all parties' and independent candidates' votes for current constituency
     * 
     * @param  array $group          – array with all votes grouped respectively by 'candidates' and 'parties'
     * @param  int   $constituencyId – which constituency's votes are being validated
     * @param  int   $constValidVotes – how many valid votes there are in the constituency
     * @param  int   $constPopulation – population fo the constituency for the selected population census
     */
    private function validateSingleConstituencyVotes(array $group = [], int $constituencyId, int $constValidVotes, int $constPopulation): void {
        $constituencyErrorMessageId = sprintf('const_%s', $constituencyId);
        $candidatesVotesSum         = 0;
        $partiesVotesSum            = 0;
        $allFieldsErrorIds          = []; // if multiple constituencies fields fail, add the constituency id to this array
        $markAllFields              = false; // whether to set invalid field class to all fields

        // validate independent candidates (if any)
        if (isset($group['candidates'])) {
            foreach ($group['candidates'] as $item) {

                // validate candidate name (and break out of cycle on error)
                if ( ! $item->getName()) {
                    $this->addValidationError($constituencyErrorMessageId, 'Моля, въведете имената на независимите кандидати.');
                    return;
                }

                // validate candidate votes (and break out of cycle on error)
                if ($item->getVotes() < 0) {
                    $this->addValidationError($constituencyErrorMessageId, 'Моля, въведете гласове за независимите кандидати.');
                    return;
                }
                else {
                    $candidatesVotesSum += $item->getVotes();
                }
            }
        }

        // validate parties votes (if any)
        if (isset($group['parties'])) {
            foreach ($group['parties'] as $partyId => $item) {
                $inputId = sprintf('party-field-%s-%s', $constituencyId, $partyId);
                $allFieldsErrorIds[] = $inputId;

                // validate party votes (and break out of cycle on error)
                if ($item->getVotes() < 0) {
                    $this->addValidationError($inputId, 'Грешка.');
                    $this->addValidationError($constituencyErrorMessageId, 'Моля, въведете нормално количество гласове за отбелязаните партии.');
                    return;
                }
                else {
                    $partiesVotesSum += $item->getVotes();
                }
            }
        }

        $totalVotes = $candidatesVotesSum + $partiesVotesSum;

        if ($totalVotes > $constValidVotes) {
            $markAllFields = sprintf('Общият брой гласове (%s) надвишава броя на валидните гласове в района: %s', $totalVotes, $constValidVotes);
        }

        // if all votes sum is equal to 0 OR if there were
        // no candidates and parties at all in the constituency, add validation error
        if ($totalVotes === 0 || ( ! isset($group['candidates']) &&  ! isset($group['parties']))) {
            $markAllFields = 'Моля, въведете гласове в района.';
        }

        if ($markAllFields) {
            foreach ($allFieldsErrorIds as $inputId) {
                $this->addValidationError($inputId, 'Грешка.');
            }
            $this->addValidationError($constituencyErrorMessageId, $markAllFields);
        }

        // validate constituency total valid votes
        if ($constValidVotes <= 0) {
            $this->addValidationError('constituency_votes-' . $constituencyId, 'Грешка.');
            $this->addValidationError($constituencyErrorMessageId, 'Моля, въведете валиден общ брой гласове.');
        }
        elseif ($constValidVotes > $constPopulation) {
            $this->addValidationError('constituency_votes-' . $constituencyId, 'Грешка.');
            $this->addValidationError($constituencyErrorMessageId, sprintf('Броят валидни гласове (%s) надвишава броя на населението за района: %s.', $constValidVotes, $constPopulation));
        }
    }

    /**
     * Checks if constituency exists and throws an error if it doesn't
     * 
     * @param  mixed $constituencies – all constituencies with their IDs as key
     * @param  int   $constituencyId
     * @throws IncorrectInputDataException
     */
    private function checkIfConstituencyExists($constituencies, $constituencyId): void {
        $const = $constituencies[ $constituencyId ] ?? NULL;

        if ( ! $const) {
            throw new IncorrectInputDataException(['constituencies_fields', sprintf('Несъществуващ многомандатен избирателен район: %s', $constituencyId)]);
        }        
    }

    /**
     * Adds a validation error to the array which eventually gets thrown altogether
     * 
     * @param  string $field   – which field has failed
     * @param  string $message – what's its error message
     */
    private function addValidationError(string $field, string $message): void {
        $this->validationErrors[] = [$field, $message];
    }

    /**
     * Throws all gathered validation messages
     * 
     * @throws IncorrectInputDataException
     */
    private function throwValidationErrors() {
        throw new IncorrectInputDataException($this->validationErrors);
    }

}