<?php

namespace Base;

use \PopulationCensuses as ChildPopulationCensuses;
use \PopulationCensusesQuery as ChildPopulationCensusesQuery;
use \Exception;
use \PDO;
use Map\PopulationCensusesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'population_censuses' table.
 *
 *
 *
 * @method     ChildPopulationCensusesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPopulationCensusesQuery orderByYear($order = Criteria::ASC) Order by the year column
 *
 * @method     ChildPopulationCensusesQuery groupById() Group by the id column
 * @method     ChildPopulationCensusesQuery groupByYear() Group by the year column
 *
 * @method     ChildPopulationCensusesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPopulationCensusesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPopulationCensusesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPopulationCensusesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPopulationCensusesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPopulationCensusesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPopulationCensusesQuery leftJoinConstituenciesCensuses($relationAlias = null) Adds a LEFT JOIN clause to the query using the ConstituenciesCensuses relation
 * @method     ChildPopulationCensusesQuery rightJoinConstituenciesCensuses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ConstituenciesCensuses relation
 * @method     ChildPopulationCensusesQuery innerJoinConstituenciesCensuses($relationAlias = null) Adds a INNER JOIN clause to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildPopulationCensusesQuery joinWithConstituenciesCensuses($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildPopulationCensusesQuery leftJoinWithConstituenciesCensuses() Adds a LEFT JOIN clause and with to the query using the ConstituenciesCensuses relation
 * @method     ChildPopulationCensusesQuery rightJoinWithConstituenciesCensuses() Adds a RIGHT JOIN clause and with to the query using the ConstituenciesCensuses relation
 * @method     ChildPopulationCensusesQuery innerJoinWithConstituenciesCensuses() Adds a INNER JOIN clause and with to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildPopulationCensusesQuery leftJoinElections($relationAlias = null) Adds a LEFT JOIN clause to the query using the Elections relation
 * @method     ChildPopulationCensusesQuery rightJoinElections($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Elections relation
 * @method     ChildPopulationCensusesQuery innerJoinElections($relationAlias = null) Adds a INNER JOIN clause to the query using the Elections relation
 *
 * @method     ChildPopulationCensusesQuery joinWithElections($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Elections relation
 *
 * @method     ChildPopulationCensusesQuery leftJoinWithElections() Adds a LEFT JOIN clause and with to the query using the Elections relation
 * @method     ChildPopulationCensusesQuery rightJoinWithElections() Adds a RIGHT JOIN clause and with to the query using the Elections relation
 * @method     ChildPopulationCensusesQuery innerJoinWithElections() Adds a INNER JOIN clause and with to the query using the Elections relation
 *
 * @method     \ConstituenciesCensusesQuery|\ElectionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPopulationCensuses findOne(ConnectionInterface $con = null) Return the first ChildPopulationCensuses matching the query
 * @method     ChildPopulationCensuses findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPopulationCensuses matching the query, or a new ChildPopulationCensuses object populated from the query conditions when no match is found
 *
 * @method     ChildPopulationCensuses findOneById(int $id) Return the first ChildPopulationCensuses filtered by the id column
 * @method     ChildPopulationCensuses findOneByYear(int $year) Return the first ChildPopulationCensuses filtered by the year column *

 * @method     ChildPopulationCensuses requirePk($key, ConnectionInterface $con = null) Return the ChildPopulationCensuses by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPopulationCensuses requireOne(ConnectionInterface $con = null) Return the first ChildPopulationCensuses matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPopulationCensuses requireOneById(int $id) Return the first ChildPopulationCensuses filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPopulationCensuses requireOneByYear(int $year) Return the first ChildPopulationCensuses filtered by the year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPopulationCensuses[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPopulationCensuses objects based on current ModelCriteria
 * @method     ChildPopulationCensuses[]|ObjectCollection findById(int $id) Return ChildPopulationCensuses objects filtered by the id column
 * @method     ChildPopulationCensuses[]|ObjectCollection findByYear(int $year) Return ChildPopulationCensuses objects filtered by the year column
 * @method     ChildPopulationCensuses[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PopulationCensusesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PopulationCensusesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PopulationCensuses', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPopulationCensusesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPopulationCensusesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPopulationCensusesQuery) {
            return $criteria;
        }
        $query = new ChildPopulationCensusesQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPopulationCensuses|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PopulationCensusesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PopulationCensusesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPopulationCensuses A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, year FROM population_censuses WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPopulationCensuses $obj */
            $obj = new ChildPopulationCensuses();
            $obj->hydrate($row);
            PopulationCensusesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPopulationCensuses|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the year column
     *
     * Example usage:
     * <code>
     * $query->filterByYear(1234); // WHERE year = 1234
     * $query->filterByYear(array(12, 34)); // WHERE year IN (12, 34)
     * $query->filterByYear(array('min' => 12)); // WHERE year > 12
     * </code>
     *
     * @param     mixed $year The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (is_array($year)) {
            $useMinMax = false;
            if (isset($year['min'])) {
                $this->addUsingAlias(PopulationCensusesTableMap::COL_YEAR, $year['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($year['max'])) {
                $this->addUsingAlias(PopulationCensusesTableMap::COL_YEAR, $year['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PopulationCensusesTableMap::COL_YEAR, $year, $comparison);
    }

    /**
     * Filter the query by a related \ConstituenciesCensuses object
     *
     * @param \ConstituenciesCensuses|ObjectCollection $constituenciesCensuses the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterByConstituenciesCensuses($constituenciesCensuses, $comparison = null)
    {
        if ($constituenciesCensuses instanceof \ConstituenciesCensuses) {
            return $this
                ->addUsingAlias(PopulationCensusesTableMap::COL_ID, $constituenciesCensuses->getPopulationCensusId(), $comparison);
        } elseif ($constituenciesCensuses instanceof ObjectCollection) {
            return $this
                ->useConstituenciesCensusesQuery()
                ->filterByPrimaryKeys($constituenciesCensuses->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByConstituenciesCensuses() only accepts arguments of type \ConstituenciesCensuses or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ConstituenciesCensuses relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function joinConstituenciesCensuses($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ConstituenciesCensuses');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ConstituenciesCensuses');
        }

        return $this;
    }

    /**
     * Use the ConstituenciesCensuses relation ConstituenciesCensuses object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ConstituenciesCensusesQuery A secondary query class using the current class as primary query
     */
    public function useConstituenciesCensusesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConstituenciesCensuses($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ConstituenciesCensuses', '\ConstituenciesCensusesQuery');
    }

    /**
     * Filter the query by a related \Elections object
     *
     * @param \Elections|ObjectCollection $elections the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function filterByElections($elections, $comparison = null)
    {
        if ($elections instanceof \Elections) {
            return $this
                ->addUsingAlias(PopulationCensusesTableMap::COL_ID, $elections->getPopulationCensusId(), $comparison);
        } elseif ($elections instanceof ObjectCollection) {
            return $this
                ->useElectionsQuery()
                ->filterByPrimaryKeys($elections->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElections() only accepts arguments of type \Elections or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Elections relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function joinElections($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Elections');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Elections');
        }

        return $this;
    }

    /**
     * Use the Elections relation Elections object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionsQuery A secondary query class using the current class as primary query
     */
    public function useElectionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElections($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Elections', '\ElectionsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPopulationCensuses $populationCensuses Object to remove from the list of results
     *
     * @return $this|ChildPopulationCensusesQuery The current query, for fluid interface
     */
    public function prune($populationCensuses = null)
    {
        if ($populationCensuses) {
            $this->addUsingAlias(PopulationCensusesTableMap::COL_ID, $populationCensuses->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the population_censuses table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PopulationCensusesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PopulationCensusesTableMap::clearInstancePool();
            PopulationCensusesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PopulationCensusesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PopulationCensusesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PopulationCensusesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PopulationCensusesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PopulationCensusesQuery
