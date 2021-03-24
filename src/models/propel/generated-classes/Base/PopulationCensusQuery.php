<?php

namespace Base;

use \PopulationCensus as ChildPopulationCensus;
use \PopulationCensusQuery as ChildPopulationCensusQuery;
use \Exception;
use \PDO;
use Map\PopulationCensusTableMap;
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
 * @method     ChildPopulationCensusQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPopulationCensusQuery orderByYear($order = Criteria::ASC) Order by the year column
 *
 * @method     ChildPopulationCensusQuery groupById() Group by the id column
 * @method     ChildPopulationCensusQuery groupByYear() Group by the year column
 *
 * @method     ChildPopulationCensusQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPopulationCensusQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPopulationCensusQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPopulationCensusQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPopulationCensusQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPopulationCensusQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPopulationCensusQuery leftJoinConstituencyCensus($relationAlias = null) Adds a LEFT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildPopulationCensusQuery rightJoinConstituencyCensus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildPopulationCensusQuery innerJoinConstituencyCensus($relationAlias = null) Adds a INNER JOIN clause to the query using the ConstituencyCensus relation
 *
 * @method     ChildPopulationCensusQuery joinWithConstituencyCensus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ConstituencyCensus relation
 *
 * @method     ChildPopulationCensusQuery leftJoinWithConstituencyCensus() Adds a LEFT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildPopulationCensusQuery rightJoinWithConstituencyCensus() Adds a RIGHT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildPopulationCensusQuery innerJoinWithConstituencyCensus() Adds a INNER JOIN clause and with to the query using the ConstituencyCensus relation
 *
 * @method     ChildPopulationCensusQuery leftJoinElection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Election relation
 * @method     ChildPopulationCensusQuery rightJoinElection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Election relation
 * @method     ChildPopulationCensusQuery innerJoinElection($relationAlias = null) Adds a INNER JOIN clause to the query using the Election relation
 *
 * @method     ChildPopulationCensusQuery joinWithElection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Election relation
 *
 * @method     ChildPopulationCensusQuery leftJoinWithElection() Adds a LEFT JOIN clause and with to the query using the Election relation
 * @method     ChildPopulationCensusQuery rightJoinWithElection() Adds a RIGHT JOIN clause and with to the query using the Election relation
 * @method     ChildPopulationCensusQuery innerJoinWithElection() Adds a INNER JOIN clause and with to the query using the Election relation
 *
 * @method     \ConstituencyCensusQuery|\ElectionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPopulationCensus findOne(ConnectionInterface $con = null) Return the first ChildPopulationCensus matching the query
 * @method     ChildPopulationCensus findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPopulationCensus matching the query, or a new ChildPopulationCensus object populated from the query conditions when no match is found
 *
 * @method     ChildPopulationCensus findOneById(int $id) Return the first ChildPopulationCensus filtered by the id column
 * @method     ChildPopulationCensus findOneByYear(int $year) Return the first ChildPopulationCensus filtered by the year column *

 * @method     ChildPopulationCensus requirePk($key, ConnectionInterface $con = null) Return the ChildPopulationCensus by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPopulationCensus requireOne(ConnectionInterface $con = null) Return the first ChildPopulationCensus matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPopulationCensus requireOneById(int $id) Return the first ChildPopulationCensus filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPopulationCensus requireOneByYear(int $year) Return the first ChildPopulationCensus filtered by the year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPopulationCensus[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPopulationCensus objects based on current ModelCriteria
 * @method     ChildPopulationCensus[]|ObjectCollection findById(int $id) Return ChildPopulationCensus objects filtered by the id column
 * @method     ChildPopulationCensus[]|ObjectCollection findByYear(int $year) Return ChildPopulationCensus objects filtered by the year column
 * @method     ChildPopulationCensus[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PopulationCensusQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PopulationCensusQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PopulationCensus', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPopulationCensusQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPopulationCensusQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPopulationCensusQuery) {
            return $criteria;
        }
        $query = new ChildPopulationCensusQuery();
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
     * @return ChildPopulationCensus|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PopulationCensusTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PopulationCensusTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPopulationCensus A model object, or null if the key is not found
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
            /** @var ChildPopulationCensus $obj */
            $obj = new ChildPopulationCensus();
            $obj->hydrate($row);
            PopulationCensusTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPopulationCensus|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (is_array($year)) {
            $useMinMax = false;
            if (isset($year['min'])) {
                $this->addUsingAlias(PopulationCensusTableMap::COL_YEAR, $year['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($year['max'])) {
                $this->addUsingAlias(PopulationCensusTableMap::COL_YEAR, $year['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PopulationCensusTableMap::COL_YEAR, $year, $comparison);
    }

    /**
     * Filter the query by a related \ConstituencyCensus object
     *
     * @param \ConstituencyCensus|ObjectCollection $constituencyCensus the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterByConstituencyCensus($constituencyCensus, $comparison = null)
    {
        if ($constituencyCensus instanceof \ConstituencyCensus) {
            return $this
                ->addUsingAlias(PopulationCensusTableMap::COL_ID, $constituencyCensus->getPopulationCensusId(), $comparison);
        } elseif ($constituencyCensus instanceof ObjectCollection) {
            return $this
                ->useConstituencyCensusQuery()
                ->filterByPrimaryKeys($constituencyCensus->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByConstituencyCensus() only accepts arguments of type \ConstituencyCensus or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ConstituencyCensus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function joinConstituencyCensus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ConstituencyCensus');

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
            $this->addJoinObject($join, 'ConstituencyCensus');
        }

        return $this;
    }

    /**
     * Use the ConstituencyCensus relation ConstituencyCensus object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ConstituencyCensusQuery A secondary query class using the current class as primary query
     */
    public function useConstituencyCensusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConstituencyCensus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ConstituencyCensus', '\ConstituencyCensusQuery');
    }

    /**
     * Filter the query by a related \Election object
     *
     * @param \Election|ObjectCollection $election the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function filterByElection($election, $comparison = null)
    {
        if ($election instanceof \Election) {
            return $this
                ->addUsingAlias(PopulationCensusTableMap::COL_ID, $election->getPopulationCensusId(), $comparison);
        } elseif ($election instanceof ObjectCollection) {
            return $this
                ->useElectionQuery()
                ->filterByPrimaryKeys($election->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElection() only accepts arguments of type \Election or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Election relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function joinElection($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Election');

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
            $this->addJoinObject($join, 'Election');
        }

        return $this;
    }

    /**
     * Use the Election relation Election object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionQuery A secondary query class using the current class as primary query
     */
    public function useElectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Election', '\ElectionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPopulationCensus $populationCensus Object to remove from the list of results
     *
     * @return $this|ChildPopulationCensusQuery The current query, for fluid interface
     */
    public function prune($populationCensus = null)
    {
        if ($populationCensus) {
            $this->addUsingAlias(PopulationCensusTableMap::COL_ID, $populationCensus->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(PopulationCensusTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PopulationCensusTableMap::clearInstancePool();
            PopulationCensusTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PopulationCensusTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PopulationCensusTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PopulationCensusTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PopulationCensusTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PopulationCensusQuery
