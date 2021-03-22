<?php

namespace Base;

use \AssemblyTypes as ChildAssemblyTypes;
use \AssemblyTypesQuery as ChildAssemblyTypesQuery;
use \Exception;
use \PDO;
use Map\AssemblyTypesTableMap;
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
 * @method     ChildAssemblyTypesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAssemblyTypesQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildAssemblyTypesQuery orderByMinimumConstituencyMandates($order = Criteria::ASC) Order by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypesQuery orderByTotalMandates($order = Criteria::ASC) Order by the total_mandates column
 * @method     ChildAssemblyTypesQuery orderByThresholdPercentage($order = Criteria::ASC) Order by the threshold_percentage column
 *
 * @method     ChildAssemblyTypesQuery groupById() Group by the id column
 * @method     ChildAssemblyTypesQuery groupByTitle() Group by the title column
 * @method     ChildAssemblyTypesQuery groupByMinimumConstituencyMandates() Group by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypesQuery groupByTotalMandates() Group by the total_mandates column
 * @method     ChildAssemblyTypesQuery groupByThresholdPercentage() Group by the threshold_percentage column
 *
 * @method     ChildAssemblyTypesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAssemblyTypesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAssemblyTypesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAssemblyTypesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAssemblyTypesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAssemblyTypesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAssemblyTypesQuery leftJoinElections($relationAlias = null) Adds a LEFT JOIN clause to the query using the Elections relation
 * @method     ChildAssemblyTypesQuery rightJoinElections($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Elections relation
 * @method     ChildAssemblyTypesQuery innerJoinElections($relationAlias = null) Adds a INNER JOIN clause to the query using the Elections relation
 *
 * @method     ChildAssemblyTypesQuery joinWithElections($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Elections relation
 *
 * @method     ChildAssemblyTypesQuery leftJoinWithElections() Adds a LEFT JOIN clause and with to the query using the Elections relation
 * @method     ChildAssemblyTypesQuery rightJoinWithElections() Adds a RIGHT JOIN clause and with to the query using the Elections relation
 * @method     ChildAssemblyTypesQuery innerJoinWithElections() Adds a INNER JOIN clause and with to the query using the Elections relation
 *
 * @method     \ElectionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAssemblyTypes findOne(ConnectionInterface $con = null) Return the first ChildAssemblyTypes matching the query
 * @method     ChildAssemblyTypes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAssemblyTypes matching the query, or a new ChildAssemblyTypes object populated from the query conditions when no match is found
 *
 * @method     ChildAssemblyTypes findOneById(int $id) Return the first ChildAssemblyTypes filtered by the id column
 * @method     ChildAssemblyTypes findOneByTitle(string $title) Return the first ChildAssemblyTypes filtered by the title column
 * @method     ChildAssemblyTypes findOneByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return the first ChildAssemblyTypes filtered by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypes findOneByTotalMandates(int $total_mandates) Return the first ChildAssemblyTypes filtered by the total_mandates column
 * @method     ChildAssemblyTypes findOneByThresholdPercentage(int $threshold_percentage) Return the first ChildAssemblyTypes filtered by the threshold_percentage column *

 * @method     ChildAssemblyTypes requirePk($key, ConnectionInterface $con = null) Return the ChildAssemblyTypes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyTypes requireOne(ConnectionInterface $con = null) Return the first ChildAssemblyTypes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssemblyTypes requireOneById(int $id) Return the first ChildAssemblyTypes filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyTypes requireOneByTitle(string $title) Return the first ChildAssemblyTypes filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyTypes requireOneByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return the first ChildAssemblyTypes filtered by the minimum_constituency_mandates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyTypes requireOneByTotalMandates(int $total_mandates) Return the first ChildAssemblyTypes filtered by the total_mandates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssemblyTypes requireOneByThresholdPercentage(int $threshold_percentage) Return the first ChildAssemblyTypes filtered by the threshold_percentage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssemblyTypes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAssemblyTypes objects based on current ModelCriteria
 * @method     ChildAssemblyTypes[]|ObjectCollection findById(int $id) Return ChildAssemblyTypes objects filtered by the id column
 * @method     ChildAssemblyTypes[]|ObjectCollection findByTitle(string $title) Return ChildAssemblyTypes objects filtered by the title column
 * @method     ChildAssemblyTypes[]|ObjectCollection findByMinimumConstituencyMandates(int $minimum_constituency_mandates) Return ChildAssemblyTypes objects filtered by the minimum_constituency_mandates column
 * @method     ChildAssemblyTypes[]|ObjectCollection findByTotalMandates(int $total_mandates) Return ChildAssemblyTypes objects filtered by the total_mandates column
 * @method     ChildAssemblyTypes[]|ObjectCollection findByThresholdPercentage(int $threshold_percentage) Return ChildAssemblyTypes objects filtered by the threshold_percentage column
 * @method     ChildAssemblyTypes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AssemblyTypesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\AssemblyTypesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\AssemblyTypes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAssemblyTypesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAssemblyTypesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAssemblyTypesQuery) {
            return $criteria;
        }
        $query = new ChildAssemblyTypesQuery();
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
     * @return ChildAssemblyTypes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AssemblyTypesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AssemblyTypesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAssemblyTypes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, minimum_constituency_mandates, total_mandates, threshold_percentage FROM assembly_types WHERE id = :p0';
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
            /** @var ChildAssemblyTypes $obj */
            $obj = new ChildAssemblyTypes();
            $obj->hydrate($row);
            AssemblyTypesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAssemblyTypes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByMinimumConstituencyMandates($minimumConstituencyMandates = null, $comparison = null)
    {
        if (is_array($minimumConstituencyMandates)) {
            $useMinMax = false;
            if (isset($minimumConstituencyMandates['min'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minimumConstituencyMandates['max'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_MINIMUM_CONSTITUENCY_MANDATES, $minimumConstituencyMandates, $comparison);
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByTotalMandates($totalMandates = null, $comparison = null)
    {
        if (is_array($totalMandates)) {
            $useMinMax = false;
            if (isset($totalMandates['min'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_TOTAL_MANDATES, $totalMandates['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalMandates['max'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_TOTAL_MANDATES, $totalMandates['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_TOTAL_MANDATES, $totalMandates, $comparison);
    }

    /**
     * Filter the query on the threshold_percentage column
     *
     * Example usage:
     * <code>
     * $query->filterByThresholdPercentage(1234); // WHERE threshold_percentage = 1234
     * $query->filterByThresholdPercentage(array(12, 34)); // WHERE threshold_percentage IN (12, 34)
     * $query->filterByThresholdPercentage(array('min' => 12)); // WHERE threshold_percentage > 12
     * </code>
     *
     * @param     mixed $thresholdPercentage The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByThresholdPercentage($thresholdPercentage = null, $comparison = null)
    {
        if (is_array($thresholdPercentage)) {
            $useMinMax = false;
            if (isset($thresholdPercentage['min'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($thresholdPercentage['max'])) {
                $this->addUsingAlias(AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssemblyTypesTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage, $comparison);
    }

    /**
     * Filter the query by a related \Elections object
     *
     * @param \Elections|ObjectCollection $elections the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function filterByElections($elections, $comparison = null)
    {
        if ($elections instanceof \Elections) {
            return $this
                ->addUsingAlias(AssemblyTypesTableMap::COL_ID, $elections->getAssemblyTypeId(), $comparison);
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
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
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
     * @param   ChildAssemblyTypes $assemblyTypes Object to remove from the list of results
     *
     * @return $this|ChildAssemblyTypesQuery The current query, for fluid interface
     */
    public function prune($assemblyTypes = null)
    {
        if ($assemblyTypes) {
            $this->addUsingAlias(AssemblyTypesTableMap::COL_ID, $assemblyTypes->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AssemblyTypesTableMap::clearInstancePool();
            AssemblyTypesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AssemblyTypesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AssemblyTypesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AssemblyTypesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AssemblyTypesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AssemblyTypesQuery
