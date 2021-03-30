<?php

namespace Base;

use \ElectionConstituency as ChildElectionConstituency;
use \ElectionConstituencyQuery as ChildElectionConstituencyQuery;
use \Exception;
use \PDO;
use Map\ElectionConstituencyTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'elections_constituencies_censuses' table.
 *
 *
 *
 * @method     ChildElectionConstituencyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionConstituencyQuery orderByElectionId($order = Criteria::ASC) Order by the election_id column
 * @method     ChildElectionConstituencyQuery orderByConstituencyCensusId($order = Criteria::ASC) Order by the constituency_census_id column
 * @method     ChildElectionConstituencyQuery orderByTotalValidVotes($order = Criteria::ASC) Order by the total_valid_votes column
 *
 * @method     ChildElectionConstituencyQuery groupById() Group by the id column
 * @method     ChildElectionConstituencyQuery groupByElectionId() Group by the election_id column
 * @method     ChildElectionConstituencyQuery groupByConstituencyCensusId() Group by the constituency_census_id column
 * @method     ChildElectionConstituencyQuery groupByTotalValidVotes() Group by the total_valid_votes column
 *
 * @method     ChildElectionConstituencyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionConstituencyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionConstituencyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionConstituencyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionConstituencyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionConstituencyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionConstituencyQuery leftJoinElection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Election relation
 * @method     ChildElectionConstituencyQuery rightJoinElection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Election relation
 * @method     ChildElectionConstituencyQuery innerJoinElection($relationAlias = null) Adds a INNER JOIN clause to the query using the Election relation
 *
 * @method     ChildElectionConstituencyQuery joinWithElection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Election relation
 *
 * @method     ChildElectionConstituencyQuery leftJoinWithElection() Adds a LEFT JOIN clause and with to the query using the Election relation
 * @method     ChildElectionConstituencyQuery rightJoinWithElection() Adds a RIGHT JOIN clause and with to the query using the Election relation
 * @method     ChildElectionConstituencyQuery innerJoinWithElection() Adds a INNER JOIN clause and with to the query using the Election relation
 *
 * @method     ChildElectionConstituencyQuery leftJoinConstituencyCensus($relationAlias = null) Adds a LEFT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildElectionConstituencyQuery rightJoinConstituencyCensus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildElectionConstituencyQuery innerJoinConstituencyCensus($relationAlias = null) Adds a INNER JOIN clause to the query using the ConstituencyCensus relation
 *
 * @method     ChildElectionConstituencyQuery joinWithConstituencyCensus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ConstituencyCensus relation
 *
 * @method     ChildElectionConstituencyQuery leftJoinWithConstituencyCensus() Adds a LEFT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildElectionConstituencyQuery rightJoinWithConstituencyCensus() Adds a RIGHT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildElectionConstituencyQuery innerJoinWithConstituencyCensus() Adds a INNER JOIN clause and with to the query using the ConstituencyCensus relation
 *
 * @method     \ElectionQuery|\ConstituencyCensusQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElectionConstituency findOne(ConnectionInterface $con = null) Return the first ChildElectionConstituency matching the query
 * @method     ChildElectionConstituency findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElectionConstituency matching the query, or a new ChildElectionConstituency object populated from the query conditions when no match is found
 *
 * @method     ChildElectionConstituency findOneById(int $id) Return the first ChildElectionConstituency filtered by the id column
 * @method     ChildElectionConstituency findOneByElectionId(int $election_id) Return the first ChildElectionConstituency filtered by the election_id column
 * @method     ChildElectionConstituency findOneByConstituencyCensusId(int $constituency_census_id) Return the first ChildElectionConstituency filtered by the constituency_census_id column
 * @method     ChildElectionConstituency findOneByTotalValidVotes(int $total_valid_votes) Return the first ChildElectionConstituency filtered by the total_valid_votes column *

 * @method     ChildElectionConstituency requirePk($key, ConnectionInterface $con = null) Return the ChildElectionConstituency by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionConstituency requireOne(ConnectionInterface $con = null) Return the first ChildElectionConstituency matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionConstituency requireOneById(int $id) Return the first ChildElectionConstituency filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionConstituency requireOneByElectionId(int $election_id) Return the first ChildElectionConstituency filtered by the election_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionConstituency requireOneByConstituencyCensusId(int $constituency_census_id) Return the first ChildElectionConstituency filtered by the constituency_census_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionConstituency requireOneByTotalValidVotes(int $total_valid_votes) Return the first ChildElectionConstituency filtered by the total_valid_votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionConstituency[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElectionConstituency objects based on current ModelCriteria
 * @method     ChildElectionConstituency[]|ObjectCollection findById(int $id) Return ChildElectionConstituency objects filtered by the id column
 * @method     ChildElectionConstituency[]|ObjectCollection findByElectionId(int $election_id) Return ChildElectionConstituency objects filtered by the election_id column
 * @method     ChildElectionConstituency[]|ObjectCollection findByConstituencyCensusId(int $constituency_census_id) Return ChildElectionConstituency objects filtered by the constituency_census_id column
 * @method     ChildElectionConstituency[]|ObjectCollection findByTotalValidVotes(int $total_valid_votes) Return ChildElectionConstituency objects filtered by the total_valid_votes column
 * @method     ChildElectionConstituency[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionConstituencyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionConstituencyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ElectionConstituency', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionConstituencyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionConstituencyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionConstituencyQuery) {
            return $criteria;
        }
        $query = new ChildElectionConstituencyQuery();
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
     * @return ChildElectionConstituency|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionConstituencyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionConstituencyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElectionConstituency A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, election_id, constituency_census_id, total_valid_votes FROM elections_constituencies_censuses WHERE id = :p0';
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
            /** @var ChildElectionConstituency $obj */
            $obj = new ChildElectionConstituency();
            $obj->hydrate($row);
            ElectionConstituencyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElectionConstituency|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the election_id column
     *
     * Example usage:
     * <code>
     * $query->filterByElectionId(1234); // WHERE election_id = 1234
     * $query->filterByElectionId(array(12, 34)); // WHERE election_id IN (12, 34)
     * $query->filterByElectionId(array('min' => 12)); // WHERE election_id > 12
     * </code>
     *
     * @see       filterByElection()
     *
     * @param     mixed $electionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByElectionId($electionId = null, $comparison = null)
    {
        if (is_array($electionId)) {
            $useMinMax = false;
            if (isset($electionId['min'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_ELECTION_ID, $electionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($electionId['max'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_ELECTION_ID, $electionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_ELECTION_ID, $electionId, $comparison);
    }

    /**
     * Filter the query on the constituency_census_id column
     *
     * Example usage:
     * <code>
     * $query->filterByConstituencyCensusId(1234); // WHERE constituency_census_id = 1234
     * $query->filterByConstituencyCensusId(array(12, 34)); // WHERE constituency_census_id IN (12, 34)
     * $query->filterByConstituencyCensusId(array('min' => 12)); // WHERE constituency_census_id > 12
     * </code>
     *
     * @see       filterByConstituencyCensus()
     *
     * @param     mixed $constituencyCensusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByConstituencyCensusId($constituencyCensusId = null, $comparison = null)
    {
        if (is_array($constituencyCensusId)) {
            $useMinMax = false;
            if (isset($constituencyCensusId['min'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_CONSTITUENCY_CENSUS_ID, $constituencyCensusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($constituencyCensusId['max'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_CONSTITUENCY_CENSUS_ID, $constituencyCensusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_CONSTITUENCY_CENSUS_ID, $constituencyCensusId, $comparison);
    }

    /**
     * Filter the query on the total_valid_votes column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalValidVotes(1234); // WHERE total_valid_votes = 1234
     * $query->filterByTotalValidVotes(array(12, 34)); // WHERE total_valid_votes IN (12, 34)
     * $query->filterByTotalValidVotes(array('min' => 12)); // WHERE total_valid_votes > 12
     * </code>
     *
     * @param     mixed $totalValidVotes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByTotalValidVotes($totalValidVotes = null, $comparison = null)
    {
        if (is_array($totalValidVotes)) {
            $useMinMax = false;
            if (isset($totalValidVotes['min'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalValidVotes['max'])) {
                $this->addUsingAlias(ElectionConstituencyTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionConstituencyTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes, $comparison);
    }

    /**
     * Filter the query by a related \Election object
     *
     * @param \Election|ObjectCollection $election The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByElection($election, $comparison = null)
    {
        if ($election instanceof \Election) {
            return $this
                ->addUsingAlias(ElectionConstituencyTableMap::COL_ELECTION_ID, $election->getId(), $comparison);
        } elseif ($election instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionConstituencyTableMap::COL_ELECTION_ID, $election->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
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
     * Filter the query by a related \ConstituencyCensus object
     *
     * @param \ConstituencyCensus|ObjectCollection $constituencyCensus The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function filterByConstituencyCensus($constituencyCensus, $comparison = null)
    {
        if ($constituencyCensus instanceof \ConstituencyCensus) {
            return $this
                ->addUsingAlias(ElectionConstituencyTableMap::COL_CONSTITUENCY_CENSUS_ID, $constituencyCensus->getId(), $comparison);
        } elseif ($constituencyCensus instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionConstituencyTableMap::COL_CONSTITUENCY_CENSUS_ID, $constituencyCensus->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildElectionConstituency $electionConstituency Object to remove from the list of results
     *
     * @return $this|ChildElectionConstituencyQuery The current query, for fluid interface
     */
    public function prune($electionConstituency = null)
    {
        if ($electionConstituency) {
            $this->addUsingAlias(ElectionConstituencyTableMap::COL_ID, $electionConstituency->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the elections_constituencies_censuses table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionConstituencyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionConstituencyTableMap::clearInstancePool();
            ElectionConstituencyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionConstituencyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionConstituencyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionConstituencyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionConstituencyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ElectionConstituencyQuery
