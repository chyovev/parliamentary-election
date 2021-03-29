<?php

use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Map\TableMap;

class HomeController extends AppController {

    private $allParties;

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $assemblies       = AssemblyTypeQuery::create()->find();
        $censuses         = PopulationCensusQuery::create()->getAllTypesWithPopulation();
        $this->allParties = PartyQuery::create()->addAscendingOrderByColumn('title')->find();

        $this->loadElectionFromSession();

        $viewVars = [
            'title'      => 'Стъпка 1: Обща информация',
            'assemblies' => $assemblies->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'censuses'   => $censuses->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'allParties' => $this->allParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
        ];

        $this->displayFullPage('home.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function loadElectionFromSession(): void {
        $election = $this->populateElection('session');

        if ( ! $election) {
            return;
        }

        $selectedParties = $this->extractSelectedParties($election);

        $this->setVars([
            'election'        => $election->toArray(TableMap::TYPE_FIELDNAME),
            'selectedParties' => $selectedParties,
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function extractSelectedParties(Election $election): array {
        $allPartiesWithIdAsKey     = $this->allParties->toKeyIndex();

        // holds party id and votes, but the actual party is also needed
        $selectedPartiesReferences = $election->getElectionParties()->toKeyIndex('partyId');

        $selectedParties = [];

        foreach ($selectedPartiesReferences as $partyId => $item) {
            $party = $allPartiesWithIdAsKey[$partyId]->toArray(TableMap::TYPE_FIELDNAME);

            $party['total_votes'] = $item->getTotalVotes();
            $party['party_color'] = $item->getPartyColor();

            $selectedParties[$partyId] = $party;
        }

        return $selectedParties;
    }

}