<?php

namespace Map;

use \ElectionParty;
use \ElectionPartyQuery;
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
 * This class defines the structure of the 'elections_parties' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ElectionPartyTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ElectionPartyTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'elections_parties';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ElectionParty';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ElectionParty';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'elections_parties.id';

    /**
     * the column name for the election_id field
     */
    const COL_ELECTION_ID = 'elections_parties.election_id';

    /**
     * the column name for the party_id field
     */
    const COL_PARTY_ID = 'elections_parties.party_id';

    /**
     * the column name for the party_color field
     */
    const COL_PARTY_COLOR = 'elections_parties.party_color';

    /**
     * the column name for the total_votes field
     */
    const COL_TOTAL_VOTES = 'elections_parties.total_votes';

    /**
     * the column name for the ord field
     */
    const COL_ORD = 'elections_parties.ord';

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
        self::TYPE_PHPNAME       => array('Id', 'ElectionId', 'PartyId', 'PartyColor', 'TotalVotes', 'Ord', ),
        self::TYPE_CAMELNAME     => array('id', 'electionId', 'partyId', 'partyColor', 'totalVotes', 'ord', ),
        self::TYPE_COLNAME       => array(ElectionPartyTableMap::COL_ID, ElectionPartyTableMap::COL_ELECTION_ID, ElectionPartyTableMap::COL_PARTY_ID, ElectionPartyTableMap::COL_PARTY_COLOR, ElectionPartyTableMap::COL_TOTAL_VOTES, ElectionPartyTableMap::COL_ORD, ),
        self::TYPE_FIELDNAME     => array('id', 'election_id', 'party_id', 'party_color', 'total_votes', 'ord', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ElectionId' => 1, 'PartyId' => 2, 'PartyColor' => 3, 'TotalVotes' => 4, 'Ord' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'electionId' => 1, 'partyId' => 2, 'partyColor' => 3, 'totalVotes' => 4, 'ord' => 5, ),
        self::TYPE_COLNAME       => array(ElectionPartyTableMap::COL_ID => 0, ElectionPartyTableMap::COL_ELECTION_ID => 1, ElectionPartyTableMap::COL_PARTY_ID => 2, ElectionPartyTableMap::COL_PARTY_COLOR => 3, ElectionPartyTableMap::COL_TOTAL_VOTES => 4, ElectionPartyTableMap::COL_ORD => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'election_id' => 1, 'party_id' => 2, 'party_color' => 3, 'total_votes' => 4, 'ord' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('elections_parties');
        $this->setPhpName('ElectionParty');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ElectionParty');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('election_id', 'ElectionId', 'INTEGER', 'elections', 'id', true, null, 0);
        $this->addForeignKey('party_id', 'PartyId', 'INTEGER', 'parties', 'id', true, null, 0);
        $this->addColumn('party_color', 'PartyColor', 'CHAR', false, 7, null);
        $this->addColumn('total_votes', 'TotalVotes', 'INTEGER', true, null, 0);
        $this->addColumn('ord', 'Ord', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Election', '\\Election', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':election_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', null, false);
        $this->addRelation('Party', '\\Party', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':party_id',
    1 => ':id',
  ),
), null, 'CASCADE', null, false);
        $this->addRelation('ElectionPartyVote', '\\ElectionPartyVote', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':election_party_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'ElectionPartyVotes', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to elections_parties     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ElectionPartyVoteTableMap::clearInstancePool();
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
        return $withPrefix ? ElectionPartyTableMap::CLASS_DEFAULT : ElectionPartyTableMap::OM_CLASS;
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
     * @return array           (ElectionParty object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ElectionPartyTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ElectionPartyTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ElectionPartyTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ElectionPartyTableMap::OM_CLASS;
            /** @var ElectionParty $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ElectionPartyTableMap::addInstanceToPool($obj, $key);
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
            $key = ElectionPartyTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ElectionPartyTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ElectionParty $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ElectionPartyTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_ID);
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_ELECTION_ID);
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_PARTY_ID);
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_PARTY_COLOR);
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_TOTAL_VOTES);
            $criteria->addSelectColumn(ElectionPartyTableMap::COL_ORD);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.election_id');
            $criteria->addSelectColumn($alias . '.party_id');
            $criteria->addSelectColumn($alias . '.party_color');
            $criteria->addSelectColumn($alias . '.total_votes');
            $criteria->addSelectColumn($alias . '.ord');
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
        return Propel::getServiceContainer()->getDatabaseMap(ElectionPartyTableMap::DATABASE_NAME)->getTable(ElectionPartyTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ElectionPartyTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ElectionPartyTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ElectionPartyTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ElectionParty or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ElectionParty object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionPartyTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ElectionParty) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ElectionPartyTableMap::DATABASE_NAME);
            $criteria->add(ElectionPartyTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ElectionPartyQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ElectionPartyTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ElectionPartyTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the elections_parties table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ElectionPartyQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ElectionParty or Criteria object.
     *
     * @param mixed               $criteria Criteria or ElectionParty object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionPartyTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ElectionParty object
        }

        if ($criteria->containsKey(ElectionPartyTableMap::COL_ID) && $criteria->keyContainsValue(ElectionPartyTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ElectionPartyTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ElectionPartyQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ElectionPartyTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ElectionPartyTableMap::buildTableMap();
