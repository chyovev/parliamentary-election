<?php

namespace Base;

use \AssemblyType as ChildAssemblyType;
use \AssemblyTypeQuery as ChildAssemblyTypeQuery;
use \Exception;
use \PDO;
use Map\AssemblyTypeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'assembly_types' table.
 *
 *
 *
 * @method     ChildAssemblyTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAssemblyTypeQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildAssemblyTypeQuery orderByMinimumConstituencyMandates($order = Criteria::ASC) Order by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypeQuery orderByTotalMandates($order = Criteria::ASC) Order by the total_mandates column
 *
 * @method     ChildAssemblyTypeQuery groupById() Group by the id column
 * @method     ChildAssemblyTypeQuery groupByTitle() Group by the title column
 * @method     ChildAssemblyTypeQuery groupByMinimumConstituencyMandates() Group by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypeQuery groupByTotalMandates() Group by the total_mandates column
 *
 * @method     ChildAssemblyTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAssemblyTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAssemblyTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAssemblyTypeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAssemblyTypeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAssemblyTypeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAssemblyTypeQuery leftJoinElection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Election relation
 * @method     ChildAssemblyTypeQuery rightJoinElection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Election relation
 * @method     ChildAssemblyTypeQuery innerJoinElection($relationAlias = null) Adds a INNER JOIN clause to the query using the Election relation
 *
 * @method     ChildAssemblyTypeQuery joinWithElection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Election relation
 *
 * @method     ChildAssemblyTypeQuery leftJoinWithElection() Adds a LEFT JOIN clause and with to the query using the Election relation
 * @method     ChildAssemblyTypeQuery rightJoinWithElection() Adds a RIGHT JOIN clause and with to the query using the Election relation
 * @method     ChildAssemblyTypeQuery innerJoinWithElection() Adds a INNER JOIN clause and with to the query using the Election relation
 *
 * @method     \ElectionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAssemblyType findOne(ConnectionInterface $con = null) Return the first ChildAssemblyType matching the query
 * @method     ChildAssemblyType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAssemblyType matching the query, or a new ChildAssemblyType object populated from the query conditions when no match is found
 *
 * @method     ChildAssemblyType findOneById(int $id) Return the first ChildAssemblyType filtered by the id column
 * @method     ChildAssemblyType findOneByTitle(string $title) Return the first ChildAssemblyType filtered by the title column
 * @method     ChildAssemblyType findOneByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return the first ChildAssemblyType filtered by the minimum_constituency_mandates column
 * @method     ChildAssemblyType findOneByTotalMandates(int $total_mandates) Return the first ChildAssemblyType filtered by the total_mandates column *

 * @method     ChildAssemblyType requirePk($key, ConnectionInterface $con = null) Return the ChildAssemblyType by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyType requireOne(ConnectionInterface $con = null) Return the first ChildAssemblyType matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssemblyType requireOneById(int $id) Return the first ChildAssemblyType filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyType requireOneByTitle(string $title) Return the first ChildAssemblyType filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyType requireOneByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return the first ChildAssemblyType filtered by the minimum_constituency_mandates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyType requireOneByTotalMandates(int $total_mandates) Return the first ChildAssemblyType filtered by the total_mandates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssemblyType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAssemblyType objects based on current ModelCriteria
 * @method     ChildAssemblyType[]|ObjectCollection findById(int $id) Return ChildAssemblyType objects filtered by the id column
 * @method     ChildAssemblyType[]|ObjectCollection findByTitle(string $title) Return ChildAssemblyType objects filtered by the title column
 * @method     ChildAssemblyType[]|ObjectCollection findByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return ChildAssemblyType objects filtered by the minimum_constituency_mandates column
 * @method     ChildAssemblyType[]|ObjectCollection findByTotalMandates(int $total_mandates) Return ChildAssemblyType objects filtered by the total_mandates column
 * @method     ChildAssemblyType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AssemblyTypeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\AssemblyTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\AssemblyType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAssemblyTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAssemblyTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAssemblyTypeQuery) {
            return $criteria;
        }
        $query = new ChildAssemblyTypeQuery();
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
     * @return ChildAssemblyType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AssemblyTypeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AssemblyTypeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAssemblyType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, minimum_constituency_mandates, total_mandates FROM assembly_types WHERE id = :p0';
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
            /** @var ChildAssemblyType $obj */
            $obj = new ChildAssemblyType();
            $obj->hydrate($row);
            AssemblyTypeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAssemblyType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the minimum_constituency_mandates column
     *
     * Example usage:
     * <code>
     * $query->filterByMinimumConstituencyMandates(1234); // WHERE minimum_constituency_mandates = 1234
     * $query->filterByMinimumConstituencyMandates(array(12, 34)); // WHERE minimum_constituency_mandates IN (12, 34)
     * $query->filterByMinimumConstituencyMandates(array('min' => 12)); // WHERE minimum_constituency_mandates > 12
     * </code>
     *
     * @param     mixed $minimumConstituencyMandates The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByMinimumConstituencyMandates($minimumConstituencyMandates = null, $comparison = null)
    {
        if (is_array($minimumConstituencyMandates)) {
            $useMinMax = false;
            if (isset($minimumConstituencyMandates['min'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minimumConstituencyMandates['max'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates, $comparison);
    }

    /**
     * Filter the query on the total_mandates column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalMandates(1234); // WHERE total_mandates = 1234
     * $query->filterByTotalMandates(array(12, 34)); // WHERE total_mandates IN (12, 34)
     * $query->filterByTotalMandates(array('min' => 12)); // WHERE total_mandates > 12
     * </code>
     *
     * @param     mixed $totalMandates The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByTotalMandates($totalMandates = null, $comparison = null)
    {
        if (is_array($totalMandates)) {
            $useMinMax = false;
            if (isset($totalMandates['min'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_TOTAL_MANDATES, $totalMandates['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalMandates['max'])) {
                $this->addUsingAlias(AssemblyTypeTableMap::COL_TOTAL_MANDATES, $totalMandates['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypeTableMap::COL_TOTAL_MANDATES, $totalMandates, $comparison);
    }

    /**
     * Filter the query by a related \Election object
     *
     * @param \Election|ObjectCollection $election the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function filterByElection($election, $comparison = null)
    {
        if ($election instanceof \Election) {
            return $this
                ->addUsingAlias(AssemblyTypeTableMap::COL_ID, $election->getAssemblyTypeId(), $comparison);
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
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
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
     * @param   ChildAssemblyType $assemblyType Object to remove from the list of results
     *
     * @return $this|ChildAssemblyTypeQuery The current query, for fluid interface
     */
    public function prune($assemblyType = null)
    {
        if ($assemblyType) {
            $this->addUsingAlias(AssemblyTypeTableMap::COL_ID, $assemblyType->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the assembly_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AssemblyTypeTableMap::clearInstancePool();
            AssemblyTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AssemblyTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AssemblyTypeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AssemblyTypeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AssemblyTypeQuery
