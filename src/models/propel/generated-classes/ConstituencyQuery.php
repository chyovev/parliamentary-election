<?php

use Base\ConstituencyQuery as BaseConstituencyQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Skeleton subclass for performing query and update operations on the 'constituencies' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ConstituencyQuery extends BaseConstituencyQuery
{

    /**
     * Get all constituencies with their respective populations according to selected census
     *
     * @param  $populationCensusId
     * @return ObjectCollection
     */
    public static function getConstituenciesWithPopulation(int $populationCensusId): ?ObjectCollection {
        return self::create()
            ->useConstituencyCensusQuery()
                ->filterByPopulationCensusId($populationCensusId)
                ->addAsColumn('population', 'population')
            ->endUse()
            ->find();
    }

}
