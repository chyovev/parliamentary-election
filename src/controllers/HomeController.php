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
        $officialResults  = ElectionQuery::create()->select(['slug'])->findByOfficial(1);

        $this->loadElectionProperties();
        $this->setProgressSteps(1);

        $viewVars = [
            'title'      => 'Стъпка 1: Обща информация',
            'assemblies' => $assemblies->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'censuses'   => $censuses->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'allParties' => $this->allParties->toArray(NULL, false, TableMap::TYPE_FIELDNAME),
            'official'   => $officialResults,
        ];

        $this->displayFullPage('home.tpl', $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function loadElectionProperties(): void {
        $election       = $this->loadElection();
        $constituencies = $election
                        ? $election->getConstituenciesWithPopulation()
                        : ConstituencyQuery::create()->find();

        $this->setVar('constituencies', $constituencies->toArray(NULL, false, TableMap::TYPE_FIELDNAME));

        if ( ! $election) {
            return; 
        }

        $selectedParties = $this->extractSelectedParties($election);

        $this->setVars([
            'election'        => $election->toArray(TableMap::TYPE_FIELDNAME),
            'selectedParties' => $selectedParties->toArray('id', false, TableMap::TYPE_FIELDNAME),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function extractSelectedParties(Election $election): ObjectCollection {
        $allPartiesWithIdAsKey     = $this->allParties->toKeyIndex();

        // holds party id and votes, but the actual party is also needed
        $selectedPartiesReferences = $election->getElectionParties()->toKeyIndex('partyId');

        $selectedParties = new ObjectCollection();;

        foreach ($selectedPartiesReferences as $partyId => $item) {
            $party = $allPartiesWithIdAsKey[$partyId];

            $totalVotes = $item->getTotalVotes();
            $partyColor = $item->getPartyColor();

            $party->setVirtualColumn('total_votes', $totalVotes);
            $party->setVirtualColumn('party_color', $partyColor);

            $selectedParties->append($party);
        }

        return $selectedParties;
    }

}