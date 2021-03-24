<?php
class HomeController extends AppController {

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $assemblies = AssemblyTypeQuery::create()->find();
        $censuses   = PopulationCensusQuery::create()->getAllTypesWithPopulation();
        $allParties = PartyQuery::create()->addAscendingOrderByColumn('title')->find();

        $viewVars = [
            'assemblies' => $assemblies,
            'censuses'   => $censuses,
            'allParties' => $allParties,
        ];

        $this->displayFullPage('home.tpl', $viewVars);
    }
}