<?php

namespace Map;

use \Election;
use \ElectionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'elections' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ElectionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ElectionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'elections';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Election';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Election';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'elections.id';

    /**
     * the column name for the slug field
     */
    const COL_SLUG = 'elections.slug';

    /**
     * the column name for the assembly_type_id field
     */
    const COL_ASSEMBLY_TYPE_ID = 'elections.assembly_type_id';

    /**
     * the column name for the population_census_id field
     */
    const COL_POPULATION_CENSUS_ID = 'elections.population_census_id';

    /**
     * the column name for the active_suffrage field
     */
    const COL_ACTIVE_SUFFRAGE = 'elections.active_suffrage';

    /**
     * the column name for the threshold_percentage field
     */
    const COL_THRESHOLD_PERCENTAGE = 'elections.threshold_percentage';

    /**
     * the column name for the total_valid_votes field
     */
    const COL_TOTAL_VALID_VOTES = 'elections.total_valid_votes';

    /**
     * the column name for the trust_no_one_votes field
     */
    const COL_TRUST_NO_ONE_VOTES = 'elections.trust_no_one_votes';

    /**
     * the column name for the total_invalid_votes field
     */
    const COL_TOTAL_INVALID_VOTES = 'elections.total_invalid_votes';

    /**
     * the column name for the official field
     */
    const COL_OFFICIAL = 'elections.official';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'elections.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'elections.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Slug', 'AssemblyTypeId', 'PopulationCensusId', 'ActiveSuffrage', 'ThresholdPercentage', 'TotalValidVotes', 'TrustNoOneVotes', 'TotalInvalidVotes', 'Official', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'slug', 'assemblyTypeId', 'populationCensusId', 'activeSuffrage', 'thresholdPercentage', 'totalValidVotes', 'trustNoOneVotes', 'totalInvalidVotes', 'official', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ElectionTableMap::COL_ID, ElectionTableMap::COL_SLUG, ElectionTableMap::COL_ASSEMBLY_TYPE_ID, ElectionTableMap::COL_POPULATION_CENSUS_ID, ElectionTableMap::COL_ACTIVE_SUFFRAGE, ElectionTableMap::COL_THRESHOLD_PERCENTAGE, ElectionTableMap::COL_TOTAL_VALID_VOTES, ElectionTableMap::COL_TRUST_NO_ONE_VOTES, ElectionTableMap::COL_TOTAL_INVALID_VOTES, ElectionTableMap::COL_OFFICIAL, ElectionTableMap::COL_CREATED_AT, ElectionTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'slug', 'assembly_type_id', 'population_census_id', 'active_suffrage', 'threshold_percentage', 'total_valid_votes', 'trust_no_one_votes', 'total_invalid_votes', 'official', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Slug' => 1, 'AssemblyTypeId' => 2, 'PopulationCensusId' => 3, 'ActiveSuffrage' => 4, 'ThresholdPercentage' => 5, 'TotalValidVotes' => 6, 'TrustNoOneVotes' => 7, 'TotalInvalidVotes' => 8, 'Official' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'slug' => 1, 'assemblyTypeId' => 2, 'populationCensusId' => 3, 'activeSuffrage' => 4, 'thresholdPercentage' => 5, 'totalValidVotes' => 6, 'trustNoOneVotes' => 7, 'totalInvalidVotes' => 8, 'official' => 9, 'createdAt' => 10, 'updatedAt' => 11, ),
        self::TYPE_COLNAME       => array(ElectionTableMap::COL_ID => 0, ElectionTableMap::COL_SLUG => 1, ElectionTableMap::COL_ASSEMBLY_TYPE_ID => 2, ElectionTableMap::COL_POPULATION_CENSUS_ID => 3, ElectionTableMap::COL_ACTIVE_SUFFRAGE => 4, ElectionTableMap::COL_THRESHOLD_PERCENTAGE => 5, ElectionTableMap::COL_TOTAL_VALID_VOTES => 6, ElectionTableMap::COL_TRUST_NO_ONE_VOTES => 7, ElectionTableMap::COL_TOTAL_INVALID_VOTES => 8, ElectionTableMap::COL_OFFICIAL => 9, ElectionTableMap::COL_CREATED_AT => 10, ElectionTableMap::COL_UPDATED_AT => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'slug' => 1, 'assembly_type_id' => 2, 'population_census_id' => 3, 'active_suffrage' => 4, 'threshold_percentage' => 5, 'total_valid_votes' => 6, 'trust_no_one_votes' => 7, 'total_invalid_votes' => 8, 'official' => 9, 'created_at' => 10, 'updated_at' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('elections');
        $this->setPhpName('Election');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Election');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', true, 255, null);
        $this->addForeignKey('assembly_type_id', 'AssemblyTypeId', 'INTEGER', 'assembly_types', 'id', true, null, 0);
        $this->addForeignKey('population_census_id', 'PopulationCensusId', 'INTEGER', 'population_censuses', 'id', true, null, 0);
        $this->addColumn('active_suffrage', 'ActiveSuffrage', 'INTEGER', true, null, 0);
        $this->addColumn('threshold_percentage', 'ThresholdPercentage', 'INTEGER', true, null, 0);
        $this->addColumn('total_valid_votes', 'TotalValidVotes', 'INTEGER', true, null, 0);
        $this->addColumn('trust_no_one_votes', 'TrustNoOneVotes', 'INTEGER', true, null, 0);
        $this->addColumn('total_invalid_votes', 'TotalInvalidVotes', 'INTEGER', true, null, 0);
        $this->addColumn('official', 'Official', 'BOOLEAN', true, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('AssemblyType', '\\AssemblyType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':assembly_type_id',
    1 => ':id',
  ),
), null, 'CASCADE', null, false);
        $this->addRelation('PopulationCensus', '\\PopulationCensus', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':population_census_id',
    1 => ':id',
  ),
), null, 'CASCADE', null, false);
        $this->addRelation('ElectionConstituency', '\\ElectionConstituency', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':election_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'ElectionConstituencies', false);
        $this->addRelation('IndependentCandidate', '\\IndependentCandidate', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':election_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'IndependentCandidates', false);
        $this->addRelation('ElectionParty', '\\ElectionParty', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':election_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'ElectionParties', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to elections     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ElectionConstituencyTableMap::clearInstancePool();
        IndependentCandidateTableMap::clearInstancePool();
        ElectionPartyTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ElectionTableMap::CLASS_DEFAULT : ElectionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Election object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ElectionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ElectionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ElectionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ElectionTableMap::OM_CLASS;
            /** @var Election $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ElectionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ElectionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ElectionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Election $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ElectionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ElectionTableMap::COL_ID);
            $criteria->addSelectColumn(ElectionTableMap::COL_SLUG);
            $criteria->addSelectColumn(ElectionTableMap::COL_ASSEMBLY_TYPE_ID);
            $criteria->addSelectColumn(ElectionTableMap::COL_POPULATION_CENSUS_ID);
            $criteria->addSelectColumn(ElectionTableMap::COL_ACTIVE_SUFFRAGE);
            $criteria->addSelectColumn(ElectionTableMap::COL_THRESHOLD_PERCENTAGE);
            $criteria->addSelectColumn(ElectionTableMap::COL_TOTAL_VALID_VOTES);
            $criteria->addSelectColumn(ElectionTableMap::COL_TRUST_NO_ONE_VOTES);
            $criteria->addSelectColumn(ElectionTableMap::COL_TOTAL_INVALID_VOTES);
            $criteria->addSelectColumn(ElectionTableMap::COL_OFFICIAL);
            $criteria->addSelectColumn(ElectionTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ElectionTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.assembly_type_id');
            $criteria->addSelectColumn($alias . '.population_census_id');
            $criteria->addSelectColumn($alias . '.active_suffrage');
            $criteria->addSelectColumn($alias . '.threshold_percentage');
            $criteria->addSelectColumn($alias . '.total_valid_votes');
            $criteria->addSelectColumn($alias . '.trust_no_one_votes');
            $criteria->addSelectColumn($alias . '.total_invalid_votes');
            $criteria->addSelectColumn($alias . '.official');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ElectionTableMap::DATABASE_NAME)->getTable(ElectionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ElectionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ElectionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ElectionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Election or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Election object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Election) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ElectionTableMap::DATABASE_NAME);
            $criteria->add(ElectionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ElectionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ElectionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ElectionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the elections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ElectionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Election or Criteria object.
     *
     * @param mixed               $criteria Criteria or Election object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Election object
        }


        // Set the correct dbName
        $query = ElectionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ElectionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ElectionTableMap::buildTableMap();
