<?php

use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;
use FieldManager as FM;

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
        $requestParams  = Router::getCurrentRequestParams();
        $constituencyId = $requestParams['id'] ?? NULL;

        try {
            $election = $this->getElectionFromSession();
            $this->populateIndependentCandidatesFromData($election, $_POST);

            $passedParties = $election->getVirtualColumn(FM::PASSED_PARTIES);
            foreach ($passedParties as $party) {
                $this->populateElectionPartyVotesFromData($party, $_POST);
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
            throw new IncorrectInputDataException([FM::GLOBAL_CONSTITUENCY_MESSAGE, 'Сесията ви е изтекла. Моля, презаредете страницата.']);
        }

        return $election;
    }

    /**
     * Cycles through all passed parties and updates their color
     * using $_POST data
     *
     * @param Election $election – Object from which passed parties get extracted
     */
    private function updatePartiesColors(Election $election): void {
        $passedParties = $election->getVirtualColumn(FM::PASSED_PARTIES);
        $data          = $_POST[FM::PARTIES_FIELD] ?? [];

        foreach ($passedParties as $item) {
            $partyId = $item->getPartyId();
            $color   = $data[$partyId][FM::PARTY_COLOR] ?? NULL; 

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
        $this->validateElectionConstituencies($election);

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
            $this->addValidationError(FM::ASSEMBLY_FIELD, 'Моля, изберете тип на парламентарни избори.');
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
            $this->addValidationError(FM::CENSUS_FIELD, 'Моля, изберете население по данни на НСИ.');
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
        $activeSuffrage    = $election->getActiveSuffrage();
        $census            = $election->getPopulationCensusWithPopulation();

        if ($activeSuffrage <= 0) {
            $this->addValidationError(FM::SUFFRAGE_FIELD, 'Моля, въведете брой души, имащи право на глас.');
        }

        if ($totalValidVotes <= 0) {
            $this->addValidationError(FM::VALID_VOTES_FIELD, 'Моля, въведете брой действителни гласове.');
        }

        // if the active suffrage is greater than the population
        if ($census && $activeSuffrage > $census->getPopulation()) {
            $this->addValidationError(FM::SUFFRAGE_FIELD, 'Не може броят на имащите право на глас да надвишава броя на населението.');
        }

        // if there are more votes than people entitled to vote, abort
        if ($totalValidVotes + $totalInvalidVotes > $activeSuffrage) {
            $this->addValidationError(FM::VALID_VOTES_FIELD, 'Не може общият брой гласове да надвишава броя на имащите право на глас.');
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
            $this->addValidationError(FM::THRESHOLD_FIELD, 'Долната граница за представителство трябва да е в диапазона [1,99]');
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
            $this->addValidationError(FM::PARTIES_FIELD, implode('<br />', $partiesErrors));
        }
        elseif ($electionParties->count() < $minParties) {
            $this->addValidationError(FM::PARTIES_FIELD, 'Моля, изберете поне две партии.');
        }
        else {
            $this->validateElectionPartiesVotes($election);
        }
    }

    /**
     * Validates election's associated constituencies (votes, IDs)
     * and adds validation errors on failure
     * 
     * @param Election $election    – Object from which passed election parties get extracted
     * @param int      $minParties
     */
    private function validateElectionConstituencies(Election $election): void {
        $populationCensusId     = $election->getPopulationCensusId();
        $constituencyCensuses   = ConstituencyCensusQuery::getPopulationAndTitleByCensusId($populationCensusId)->toKeyIndex('constituencyId');
        $electionConstituencies = $election->getElectionConstituencies();
        $constituencyErrors     = false;

        foreach ($electionConstituencies as $item) {
            $constituencyId = $item->getVirtualColumn(FM::VOTES_CONST_FIELD);
            $fieldId        = sprintf('%s-%s', FieldManager::CONSTITUENCY_VOTES, $constituencyId);
            $this->checkIfConstituencyExists($constituencyCensuses, $constituencyId);

            $votes      = $item->getTotalValidVotes();
            $population = $constituencyCensuses[$constituencyId]->getPopulation();
            $title      = $constituencyCensuses[$constituencyId]->getTitle();

            if ($votes <= 0) {
                $this->addValidationError($fieldId, 'Моля, въведете валиден брой гласове за района.');
                $constituencyErrors = true;
            }
            elseif ($votes > $population) {
                $this->addValidationError($fieldId, sprintf('Броят гласове надвишава броя на населението за района: %s.', $population));
                $this->addValidationError(FM::GLOBAL_CONSTITUENCY_MESSAGE, sprintf('Броят гласове в МИР %s надвишава броя на населението за района: %s.', $title, $population));
            }
        }

        if ($constituencyErrors) {
            $this->addValidationError(FM::GLOBAL_CONSTITUENCY_MESSAGE, 'Моля, отстранете нередностите по посочените избирателни райони.');
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
            $this->addValidationError(FM::PARTIES_FIELD, 'Поне една партия трябва да има повече от 0 гласа.');
        }
        elseif ($partiesVotesSum < 0 || $partiesVotesSum > $totalValidVotes) {
            $this->addValidationError(FM::PARTIES_FIELD, 'Общият брой гласове на партиите надвишава броя на действителните гласове.');
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
        $populationCensusId     = $election->getPopulationCensusId();
        $constituencyCensuses   = ConstituencyCensusQuery::getPopulationAndTitleByCensusId($populationCensusId)->toKeyIndex('constituencyId');
        $groupedByConstituency  = $this->groupPartiesVotesAndCandidatesByConstituency($election, $constituencyCensuses);
        $electionConstituencies = $election->getElectionConstituencies()->toArray(FM::VOTES_CONST_FIELD, false, TableMap::TYPE_FIELDNAME);

        // if there's a constituency id specified,
        // overwrite all groups using only its data
        if ($constituencyId) {
            $this->checkIfConstituencyExists($constituencyCensuses, $constituencyId);
            $groupedByConstituency = [(int) $constituencyId => $groupedByConstituency[$constituencyId] ?? []];
        }

        // otherwise check all constituencies
        foreach ($groupedByConstituency as $constId => $group) {
            $totalConstituencyPopulation = $constituencyCensuses[$constId]->getPopulation();
            $this->validateSingleConstituencyVotes($group, $constId, $totalConstituencyPopulation);
        }

        // if there were any validation errors, throw na exception
        if ($this->validationErrors) {

            // if all constituencies were checked (no constituency_id parameter was psased),
            // add another validation which prints a global message
            if ( ! $constituencyId) {
                $this->addValidationError(FM::GLOBAL_CONSTITUENCY_MESSAGE, 'Моля, отстранете нередностите по посочените избирателни райони.');
            }

            $this->throwValidationErrors();
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
        $electionParties       = $election->getElectionParties();
        
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
        foreach ($electionParties as $party) {
            $votes = $party->getElectionPartyVotes();

            foreach ($votes as $item) {
                $constituencyId = $item->getConstituencyId();
                $this->checkIfConstituencyExists($constituencies, $constituencyId);

                $groupedByConstituency[$constituencyId]['parties'][] = $item;
            }
        }

        return $groupedByConstituency;
    }

    /**
     * Validates all parties' and independent candidates' votes for current constituency
     * 
     * @param  array $group          – array with all votes grouped respectively by 'candidates' and 'parties'
     * @param  int   $constituencyId – which constituency's votes are being validated
     * @param  int   $constPopulation – population fo the constituency for the selected population census
     */
    private function validateSingleConstituencyVotes(array $group = [], int $constituencyId, int $constPopulation): void {
        $constitutionErrorMessageId = sprintf('const_%s', $constituencyId);
        $candidatesVotesSum         = 0;
        $partiesVotesSum            = 0;
        $allFieldsErrorIds          = []; // if multiple constituencies fields fail, add the constituency id to this array
        $markAllFields              = false; // whether to set invalid field class to all fields

        // validate independent candidates (if any)
        if (isset($group['candidates'])) {
            foreach ($group['candidates'] as $item) {

                // validate candidate name (and break out of cycle on error)
                if ( ! $item->getName()) {
                    $this->addValidationError($constitutionErrorMessageId, 'Моля, въведете имената на независимите кандидати.');
                    return;
                }

                // validate candidate votes (and break out of cycle on error)
                if ($item->getVotes() < 0) {
                    $this->addValidationError($constitutionErrorMessageId, 'Моля, въведете гласове за независимите кандидати.');
                    return;
                }
                else {
                    $candidatesVotesSum += $item->getVotes();
                }
            }
        }

        // validate parties votes (if any)
        if (isset($group['parties'])) {
            foreach ($group['parties'] as $item) {
                $partyId = $item->getElectionParty()->getPartyId();
                $inputId = sprintf('party-field-%s-%s', $constituencyId, $partyId);
                $allFieldsErrorIds[] = $inputId;

                // validate party votes (and break out of cycle on error)
                if ($item->getVotes() < 0) {
                    $this->addValidationError($inputId, 'Грешка.');
                    $this->addValidationError($constitutionErrorMessageId, 'Моля, въведете нормално количество гласове за отбелязаните партии.');
                    return;
                }
                else {
                    $partiesVotesSum += $item->getVotes();
                }
            }
        }

        $totalVotes = $candidatesVotesSum + $partiesVotesSum;

        if ($totalVotes > $constPopulation) {
            $markAllFields = sprintf('Общият брой гласове (%s) надвишава броя на населението в района: %s', $totalVotes, $constPopulation);
        }

        // if all votes sum is equal to 0 OR if there were
        // no candidates and parties at all in the constituency, add validation error
        if ($totalVotes === 0 || ( ! isset($group['candidates']) &&  ! isset($group['parties']))) {
            $markAllFields = 'Моля, въведете гласове в района.';
        }

        if ($markAllFields) {
            foreach ($allFieldsErrorIds as $inputId) {
                $this->addValidationError($inputId, '');
            }
            $this->addValidationError($constitutionErrorMessageId, $markAllFields);
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
            throw new IncorrectInputDataException([FM::GLOBAL_CONSTITUENCY_MESSAGE, sprintf('Несъществуващ многомандатен избирателен район: %s', $constituencyId)]);
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
        $parties               = [];
        $candidates            = [];
        $partiesVotes          = [];
        $constituencyTotalVotes= [];

        foreach ($electionParties as $item) {
            $parties[] = [
                FM::PARTY_ID          => $item->getPartyId(),
                FM::PARTY_TOTAL_VOTES => $item->getTotalVotes(),
                FM::PARTY_ORD         => $item->getOrd(),
                FM::PARTY_COLOR       => $item->getPartyColor(),
            ];

            $partiesConstituenciesVotes = $item->getElectionPartyVotes();

            foreach ($partiesConstituenciesVotes as $subitem) {
                $partiesVotes[ $item->getPartyId() ][ $subitem->getConstituencyId() ] = $subitem->getVotes();
            }
        }

        foreach ($independentCandidates as $item) {
            $candidates[] = [
                FM::CAND_NAME_FIELD  => $item->getName(),
                FM::CAND_VOTES_FIELD => $item->getVotes(),
                FM::CAND_CONST_FIELD => $item->getConstituencyId(),
            ];
        }

        foreach ($electionConstituencies as $item) {
            $constituencyTotalVotes[] = [
                FM::VOTES_CONST_FIELD => $item->getVirtualColumn(FM::VOTES_CONST_FIELD),
                FM::VALID_VOTES_FIELD => $item->getTotalValidVotes(),
            ];
        }

        $_SESSION = [
            FM::ASSEMBLY_FIELD      => $election->getAssemblyTypeId(),
            FM::CENSUS_FIELD        => $election->getPopulationCensusId(),
            FM::SUFFRAGE_FIELD      => $election->getActiveSuffrage(),
            FM::THRESHOLD_FIELD     => $election->getThresholdPercentage(),
            FM::VALID_VOTES_FIELD   => $election->getTotalValidVotes(),
            FM::INVALID_VOTES_FIELD => $election->getTotalInvalidVotes(),
            FM::PARTIES_FIELD       => $parties,
            FM::CANDIDATES_FIELD    => $candidates,
            FM::VOTES_FIELD         => $partiesVotes,
            FM::CONSTITUENCY_VOTES  => $constituencyTotalVotes,
        ];
    }

}