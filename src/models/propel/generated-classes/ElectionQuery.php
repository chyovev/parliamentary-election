<?php

use Base\ElectionQuery as BaseElectionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'elections' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ElectionQuery extends BaseElectionQuery
{

    public function slugExists(string $slug): bool {
        return self::create()->filterBySlug($slug)->count();
    }

}
