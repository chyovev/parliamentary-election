<?php

namespace Map;

use \AssemblyTypes;
use \AssemblyTypesQuery;
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
 * This class defines the structure of the 'assembly_types' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AssemblyTypesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.AssemblyTypesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'assembly_types';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\AssemblyTypes';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'AssemblyTypes';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the id field
     */
    const COL_ID = 'assembly_types.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'assembly_types.title';

    /**
     * the column name for the minimum_constituency_mandates field
     */
    const COL_MINIMUM_CONSTITUENCY_MANDATES = 'assembly_types.minimum_constituency_mandates';

    /**
     * the column name for the total_mandates field
     */
    const COL_TOTAL_MANDATES = 'assembly_types.total_mandates';

    /**
     * the column name for the threshold_percentage field
     */
    const COL_THRESHOLD_PERCENTAGE = 'assembly_types.threshold_percentage';

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
        self::TYPE_PHPNAME       => array('Id', 'Title', 'MinimumConstituencyMandates', 'TotalMandates', 'ThresholdPercentage', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'minimumConstituencyMandates', 'totalMandates', 'thresholdPercentage', ),
        self::TYPE_COLNAME       => array(AssemblyTypesTableMap::COL_ID, AssemblyTypesTableMap::COL_TITLE, AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, AssemblyTypesTableMap::COL_TOTAL_MANDATES, AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'minimum_constituency_mandates', 'total_mandates', 'threshold_percentage', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'MinimumConstituencyMandates' => 2, 'TotalMandates' => 3, 'ThresholdPercentage' => 4, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'minimumConstituencyMandates' => 2, 'totalMandates' => 3, 'thresholdPercentage' => 4, ),
        self::TYPE_COLNAME       => array(AssemblyTypesTableMap::COL_ID => 0, AssemblyTypesTableMap::COL_TITLE => 1, AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES => 2, AssemblyTypesTableMap::COL_TOTAL_MANDATES => 3, AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE => 4, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'minimum_constituency_mandates' => 2, 'total_mandates' => 3, 'threshold_percentage' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('assembly_types');
        $this->setPhpName('AssemblyTypes');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\AssemblyTypes');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 50, null);
        $this->addColumn('minimum_constituency_mandates', 'MinimumConstituencyMandates', 'INTEGER', true, null, 0);
        $this->addColumn('total_mandates', 'TotalMandates', 'INTEGER', true, null, 0);
        $this->addColumn('threshold_percentage', 'ThresholdPercentage', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Elections', '\\Elections', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':assembly_type_id',
    1 => ':id',
  ),
), null, 'CASCADE', 'Electionss', false);
    } // buildRelations()

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
        return $withPrefix ? AssemblyTypesTableMap::CLASS_DEFAULT : AssemblyTypesTableMap::OM_CLASS;
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
     * @return array           (AssemblyTypes object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AssemblyTypesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AssemblyTypesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AssemblyTypesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AssemblyTypesTableMap::OM_CLASS;
            /** @var AssemblyTypes $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AssemblyTypesTableMap::addInstanceToPool($obj, $key);
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
            $key = AssemblyTypesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AssemblyTypesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AssemblyTypes $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AssemblyTypesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AssemblyTypesTableMap::COL_ID);
            $criteria->addSelectColumn(AssemblyTypesTableMap::COL_TITLE);
            $criteria->addSelectColumn(AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES);
            $criteria->addSelectColumn(AssemblyTypesTableMap::COL_TOTAL_MANDATES);
            $criteria->addSelectColumn(AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.minimum_constituency_mandates');
            $criteria->addSelectColumn($alias . '.total_mandates');
            $criteria->addSelectColumn($alias . '.threshold_percentage');
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
        return Propel::getServiceContainer()->getDatabaseMap(AssemblyTypesTableMap::DATABASE_NAME)->getTable(AssemblyTypesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AssemblyTypesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AssemblyTypesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AssemblyTypesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AssemblyTypes or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AssemblyTypes object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \AssemblyTypes) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AssemblyTypesTableMap::DATABASE_NAME);
            $criteria->add(AssemblyTypesTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AssemblyTypesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AssemblyTypesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AssemblyTypesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the assembly_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AssemblyTypesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AssemblyTypes or Criteria object.
     *
     * @param mixed               $criteria Criteria or AssemblyTypes object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AssemblyTypes object
        }

        if ($criteria->containsKey(AssemblyTypesTableMap::COL_ID) && $criteria->keyContainsValue(AssemblyTypesTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AssemblyTypesTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AssemblyTypesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AssemblyTypesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AssemblyTypesTableMap::buildTableMap();
