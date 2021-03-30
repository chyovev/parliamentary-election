<?php

use Base\ConstituencyCensusQuery as BaseConstituencyCensusQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Skeleton subclass for performing query and update operations on the 'constituencies_censuses' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ConstituencyCensusQuery extends BaseConstituencyCensusQuery
{

    /**
     * 
     */
    public static function getPopulationAndTitleByCensusId(int $populationCensusId): ?ObjectCollection {
        return self::create()
            ->useConstituencyQuery()
                ->addAsColumn('title', 'title')
            ->endUse()
            ->findByPopulationCensusId($populationCensusId);
    }
}
