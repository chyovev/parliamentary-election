<?php

namespace Base;

use \ConstituenciesCensuses as ChildConstituenciesCensuses;
use \ConstituenciesCensusesQuery as ChildConstituenciesCensusesQuery;
use \Exception;
use \PDO;
use Map\ConstituenciesCensusesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'constituencies_censuses' table.
 *
 *
 *
 * @method     ChildConstituenciesCensusesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildConstituenciesCensusesQuery orderByConstituencyId($order = Criteria::ASC) Order by the constituency_id column
 * @method     ChildConstituenciesCensusesQuery orderByPopulationCensusId($order = Criteria::ASC) Order by the population_census_id column
 * @method     ChildConstituenciesCensusesQuery orderByPopulation($order = Criteria::ASC) Order by the population column
 *
 * @method     ChildConstituenciesCensusesQuery groupById() Group by the id column
 * @method     ChildConstituenciesCensusesQuery groupByConstituencyId() Group by the constituency_id column
 * @method     ChildConstituenciesCensusesQuery groupByPopulationCensusId() Group by the population_census_id column
 * @method     ChildConstituenciesCensusesQuery groupByPopulation() Group by the population column
 *
 * @method     ChildConstituenciesCensusesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConstituenciesCensusesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConstituenciesCensusesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConstituenciesCensusesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildConstituenciesCensusesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildConstituenciesCensusesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildConstituenciesCensusesQuery leftJoinConstituencies($relationAlias = null) Adds a LEFT JOIN clause to the query using the Constituencies relation
 * @method     ChildConstituenciesCensusesQuery rightJoinConstituencies($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Constituencies relation
 * @method     ChildConstituenciesCensusesQuery innerJoinConstituencies($relationAlias = null) Adds a INNER JOIN clause to the query using the Constituencies relation
 *
 * @method     ChildConstituenciesCensusesQuery joinWithConstituencies($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Constituencies relation
 *
 * @method     ChildConstituenciesCensusesQuery leftJoinWithConstituencies() Adds a LEFT JOIN clause and with to the query using the Constituencies relation
 * @method     ChildConstituenciesCensusesQuery rightJoinWithConstituencies() Adds a RIGHT JOIN clause and with to the query using the Constituencies relation
 * @method     ChildConstituenciesCensusesQuery innerJoinWithConstituencies() Adds a INNER JOIN clause and with to the query using the Constituencies relation
 *
 * @method     ChildConstituenciesCensusesQuery leftJoinPopulationCensuses($relationAlias = null) Adds a LEFT JOIN clause to the query using the PopulationCensuses relation
 * @method     ChildConstituenciesCensusesQuery rightJoinPopulationCensuses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PopulationCensuses relation
 * @method     ChildConstituenciesCensusesQuery innerJoinPopulationCensuses($relationAlias = null) Adds a INNER JOIN clause to the query using the PopulationCensuses relation
 *
 * @method     ChildConstituenciesCensusesQuery joinWithPopulationCensuses($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PopulationCensuses relation
 *
 * @method     ChildConstituenciesCensusesQuery leftJoinWithPopulationCensuses() Adds a LEFT JOIN clause and with to the query using the PopulationCensuses relation
 * @method     ChildConstituenciesCensusesQuery rightJoinWithPopulationCensuses() Adds a RIGHT JOIN clause and with to the query using the PopulationCensuses relation
 * @method     ChildConstituenciesCensusesQuery innerJoinWithPopulationCensuses() Adds a INNER JOIN clause and with to the query using the PopulationCensuses relation
 *
 * @method     \ConstituenciesQuery|\PopulationCensusesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildConstituenciesCensuses findOne(ConnectionInterface $con = null) Return the first ChildConstituenciesCensuses matching the query
 * @method     ChildConstituenciesCensuses findOneOrCreate(ConnectionInterface $con = null) Return the first ChildConstituenciesCensuses matching the query, or a new ChildConstituenciesCensuses object populated from the query conditions when no match is found
 *
 * @method     ChildConstituenciesCensuses findOneById(int $id) Return the first ChildConstituenciesCensuses filtered by the id column
 * @method     ChildConstituenciesCensuses findOneByConstituencyId(int $constituency_id) Return the first ChildConstituenciesCensuses filtered by the constituency_id column
 * @method     ChildConstituenciesCensuses findOneByPopulationCensusId(int $population_census_id) Return the first ChildConstituenciesCensuses filtered by the population_census_id column
 * @method     ChildConstituenciesCensuses findOneByPopulation(int $population) Return the first ChildConstituenciesCensuses filtered by the population column *

 * @method     ChildConstituenciesCensuses requirePk($key, ConnectionInterface $con = null) Return the ChildConstituenciesCensuses by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituenciesCensuses requireOne(ConnectionInterface $con = null) Return the first ChildConstituenciesCensuses matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituenciesCensuses requireOneById(int $id) Return the first ChildConstituenciesCensuses filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituenciesCensuses requireOneByConstituencyId(int $constituency_id) Return the first ChildConstituenciesCensuses filtered by the constituency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituenciesCensuses requireOneByPopulationCensusId(int $population_census_id) Return the first ChildConstituenciesCensuses filtered by the population_census_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituenciesCensuses requireOneByPopulation(int $population) Return the first ChildConstituenciesCensuses filtered by the population column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituenciesCensuses[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildConstituenciesCensuses objects based on current ModelCriteria
 * @method     ChildConstituenciesCensuses[]|ObjectCollection findById(int $id) Return ChildConstituenciesCensuses objects filtered by the id column
 * @method     ChildConstituenciesCensuses[]|ObjectCollection findByConstituencyId(int $constituency_id) Return ChildConstituenciesCensuses objects filtered by the constituency_id column
 * @method     ChildConstituenciesCensuses[]|ObjectCollection findByPopulationCensusId(int $population_census_id) Return ChildConstituenciesCensuses objects filtered by the population_census_id column
 * @method     ChildConstituenciesCensuses[]|ObjectCollection findByPopulation(int $population) Return ChildConstituenciesCensuses objects filtered by the population column
 * @method     ChildConstituenciesCensuses[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ConstituenciesCensusesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ConstituenciesCensusesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ConstituenciesCensuses', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConstituenciesCensusesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConstituenciesCensusesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildConstituenciesCensusesQuery) {
            return $criteria;
        }
        $query = new ChildConstituenciesCensusesQuery();
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
     * @return ChildConstituenciesCensuses|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConstituenciesCensusesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ConstituenciesCensusesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildConstituenciesCensuses A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, constituency_id, population_census_id, population FROM constituencies_censuses WHERE id = :p0';
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
            /** @var ChildConstituenciesCensuses $obj */
            $obj = new ChildConstituenciesCensuses();
            $obj->hydrate($row);
            ConstituenciesCensusesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildConstituenciesCensuses|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the constituency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByConstituencyId(1234); // WHERE constituency_id = 1234
     * $query->filterByConstituencyId(array(12, 34)); // WHERE constituency_id IN (12, 34)
     * $query->filterByConstituencyId(array('min' => 12)); // WHERE constituency_id > 12
     * </code>
     *
     * @see       filterByConstituencies()
     *
     * @param     mixed $constituencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByConstituencyId($constituencyId = null, $comparison = null)
    {
        if (is_array($constituencyId)) {
            $useMinMax = false;
            if (isset($constituencyId['min'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_CONSTITUENCY_ID, $constituencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($constituencyId['max'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_CONSTITUENCY_ID, $constituencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_CONSTITUENCY_ID, $constituencyId, $comparison);
    }

    /**
     * Filter the query on the population_census_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPopulationCensusId(1234); // WHERE population_census_id = 1234
     * $query->filterByPopulationCensusId(array(12, 34)); // WHERE population_census_id IN (12, 34)
     * $query->filterByPopulationCensusId(array('min' => 12)); // WHERE population_census_id > 12
     * </code>
     *
     * @see       filterByPopulationCensuses()
     *
     * @param     mixed $populationCensusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByPopulationCensusId($populationCensusId = null, $comparison = null)
    {
        if (is_array($populationCensusId)) {
            $useMinMax = false;
            if (isset($populationCensusId['min'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($populationCensusId['max'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId, $comparison);
    }

    /**
     * Filter the query on the population column
     *
     * Example usage:
     * <code>
     * $query->filterByPopulation(1234); // WHERE population = 1234
     * $query->filterByPopulation(array(12, 34)); // WHERE population IN (12, 34)
     * $query->filterByPopulation(array('min' => 12)); // WHERE population > 12
     * </code>
     *
     * @param     mixed $population The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByPopulation($population = null, $comparison = null)
    {
        if (is_array($population)) {
            $useMinMax = false;
            if (isset($population['min'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION, $population['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($population['max'])) {
                $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION, $population['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION, $population, $comparison);
    }

    /**
     * Filter the query by a related \Constituencies object
     *
     * @param \Constituencies|ObjectCollection $constituencies The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByConstituencies($constituencies, $comparison = null)
    {
        if ($constituencies instanceof \Constituencies) {
            return $this
                ->addUsingAlias(ConstituenciesCensusesTableMap::COL_CONSTITUENCY_ID, $constituencies->getId(), $comparison);
        } elseif ($constituencies instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ConstituenciesCensusesTableMap::COL_CONSTITUENCY_ID, $constituencies->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByConstituencies() only accepts arguments of type \Constituencies or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Constituencies relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function joinConstituencies($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Constituencies');

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
            $this->addJoinObject($join, 'Constituencies');
        }

        return $this;
    }

    /**
     * Use the Constituencies relation Constituencies object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ConstituenciesQuery A secondary query class using the current class as primary query
     */
    public function useConstituenciesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConstituencies($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Constituencies', '\ConstituenciesQuery');
    }

    /**
     * Filter the query by a related \PopulationCensuses object
     *
     * @param \PopulationCensuses|ObjectCollection $populationCensuses The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function filterByPopulationCensuses($populationCensuses, $comparison = null)
    {
        if ($populationCensuses instanceof \PopulationCensuses) {
            return $this
                ->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION_CENSUS_ID, $populationCensuses->getId(), $comparison);
        } elseif ($populationCensuses instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ConstituenciesCensusesTableMap::COL_POPULATION_CENSUS_ID, $populationCensuses->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPopulationCensuses() only accepts arguments of type \PopulationCensuses or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PopulationCensuses relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function joinPopulationCensuses($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PopulationCensuses');

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
            $this->addJoinObject($join, 'PopulationCensuses');
        }

        return $this;
    }

    /**
     * Use the PopulationCensuses relation PopulationCensuses object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PopulationCensusesQuery A secondary query class using the current class as primary query
     */
    public function usePopulationCensusesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPopulationCensuses($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PopulationCensuses', '\PopulationCensusesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildConstituenciesCensuses $constituenciesCensuses Object to remove from the list of results
     *
     * @return $this|ChildConstituenciesCensusesQuery The current query, for fluid interface
     */
    public function prune($constituenciesCensuses = null)
    {
        if ($constituenciesCensuses) {
            $this->addUsingAlias(ConstituenciesCensusesTableMap::COL_ID, $constituenciesCensuses->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the constituencies_censuses table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesCensusesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConstituenciesCensusesTableMap::clearInstancePool();
            ConstituenciesCensusesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesCensusesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConstituenciesCensusesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConstituenciesCensusesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConstituenciesCensusesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ConstituenciesCensusesQuery
