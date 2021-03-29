<?php

use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;
use FieldManager as FM;

class ResultsController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function preliminary() {
        $election = $this->populateElection('session');

        // if no election could be loaded from the session,
        // redirect to homepage
        if ( ! $election) {
            Router::redirect(['controller' => 'home', 'action' => 'index'], 302);
        }

        $assembly        = $election->getAssemblyType();
        $census          = $election->getPopulationCensusWithPopulation();
        $electionParties = $election->getElectionParties();
        $passedParties   = $this->getPassedPartiesAsArray($election);

        // if there are any passed parties, set constituencies' data to draw a map
        if ($passedParties) {
            $this->setMapConstituencies();
        }

        $viewVars = [
            'title'           => 'Стъпка 2: Предварителни резултати',
            'election'        => $election->toArray(TableMap::TYPE_FIELDNAME),
            'assembly'        => $assembly->toArray(TableMap::TYPE_FIELDNAME),
            'census'          => $census->toArray(TableMap::TYPE_FIELDNAME),
            'electionParties' => $electionParties,
            'passedParties'   => $passedParties,
            'candidates'      => $this->groupIndependentCandidatesByConstituencies($election),
            'partiesVotes'    => $this->groupVotesByConstituencies($electionParties), 
        ];

        $this->displayFullPage('results.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function definitive() {

    }





    ///////////////////////////////////////////////////////////////////////////
    private function getPassedPartiesAsArray(Election $election): array {
        $passedParties = $election->getPassedParties();
        $totalMandates = $election->getAssemblyType()->getTotalMandates();

        // roughly calculate how many mandates all passing parties would receive
        // if there are no independent candidates elected
        if ($passedParties->count()) {
            $hareNiemeyer = new HareNiemeyer();
            $hareNiemeyer->distributeMandates($passedParties, $totalMandates);
        }

        $result = [];

        foreach ($passedParties as $item) {
            $party    = $item->getParty();
            $result[] = [
                FM::PARTY_ID       => $item->getPartyId(),
                'title'            => $party->getTitle(),
                'abbreviation'     => $party->getAbbreviation(),
                'votes'            => $item->getTotalVotes(),
                'votes_percentage' => $item->getVotesPercentage(),
                'mandates'         => $item->getHareNiemeyerMandates(),
                FM::PARTY_COLOR    => $item->getPartyColor(),
            ];
        }

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setMapConstituencies(): void {
        $constituencies  = ConstituencyQuery::create()->find()->toArray(NULL, false, TableMap::TYPE_FIELDNAME);
        $coordinates     = $constituencies;

        // sort constituencies in reversed order when printing coordinates
        // to avoid overlapping of Plovdiv and Plovdiv City
        krsort($coordinates);

        $this->setVars([
            'constituencies' => $constituencies,
            'coordinates'    => $coordinates,
        ]);
    }


    ///////////////////////////////////////////////////////////////////////////
    private function groupIndependentCandidatesByConstituencies(Election $election): array {
        $candidates = $election->getIndependentCandidates();
        $result     = [];

        foreach ($candidates as $item) {
            $constId            = $item->getConstituencyId();
            $result[$constId][] = $item->toArray(TableMap::TYPE_FIELDNAME);
        }

        $this->setVar('independentCount', $candidates->count());

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////
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

}