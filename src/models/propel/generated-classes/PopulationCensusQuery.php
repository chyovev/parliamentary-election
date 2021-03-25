<?php

use Base\PopulationCensusQuery as BasePopulationCensusQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'population_censuses' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PopulationCensusQuery extends BasePopulationCensusQuery
{
    ///////////////////////////////////////////////////////////////////////////
    public function getAllTypesWithPopulation() {
        $types = $this
                    ->useConstituencyCensusQuery()
                        ->addAsColumn('population', 'SUM(population)')
                    ->endUse()
                    ->groupById()
                    ->find();

        return $types;
    }

}
