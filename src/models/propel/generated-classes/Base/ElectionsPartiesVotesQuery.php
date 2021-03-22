<?php

namespace Base;

use \ElectionsPartiesVotes as ChildElectionsPartiesVotes;
use \ElectionsPartiesVotesQuery as ChildElectionsPartiesVotesQuery;
use \Exception;
use \PDO;
use Map\ElectionsPartiesVotesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'elections_parties_votes' table.
 *
 *
 *
 * @method     ChildElectionsPartiesVotesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionsPartiesVotesQuery orderByElectionPartyId($order = Criteria::ASC) Order by the election_party_id column
 * @method     ChildElectionsPartiesVotesQuery orderByConstituencyId($order = Criteria::ASC) Order by the constituency_id column
 * @method     ChildElectionsPartiesVotesQuery orderByVotes($order = Criteria::ASC) Order by the votes column
 *
 * @method     ChildElectionsPartiesVotesQuery groupById() Group by the id column
 * @method     ChildElectionsPartiesVotesQuery groupByElectionPartyId() Group by the election_party_id column
 * @method     ChildElectionsPartiesVotesQuery groupByConstituencyId() Group by the constituency_id column
 * @method     ChildElectionsPartiesVotesQuery groupByVotes() Group by the votes column
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionsPartiesVotesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionsPartiesVotesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionsPartiesVotesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionsPartiesVotesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoinConstituencies($relationAlias = null) Adds a LEFT JOIN clause to the query using the Constituencies relation
 * @method     ChildElectionsPartiesVotesQuery rightJoinConstituencies($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Constituencies relation
 * @method     ChildElectionsPartiesVotesQuery innerJoinConstituencies($relationAlias = null) Adds a INNER JOIN clause to the query using the Constituencies relation
 *
 * @method     ChildElectionsPartiesVotesQuery joinWithConstituencies($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Constituencies relation
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoinWithConstituencies() Adds a LEFT JOIN clause and with to the query using the Constituencies relation
 * @method     ChildElectionsPartiesVotesQuery rightJoinWithConstituencies() Adds a RIGHT JOIN clause and with to the query using the Constituencies relation
 * @method     ChildElectionsPartiesVotesQuery innerJoinWithConstituencies() Adds a INNER JOIN clause and with to the query using the Constituencies relation
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoinElectionsParties($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsParties relation
 * @method     ChildElectionsPartiesVotesQuery rightJoinElectionsParties($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsParties relation
 * @method     ChildElectionsPartiesVotesQuery innerJoinElectionsParties($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsParties relation
 *
 * @method     ChildElectionsPartiesVotesQuery joinWithElectionsParties($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsParties relation
 *
 * @method     ChildElectionsPartiesVotesQuery leftJoinWithElectionsParties() Adds a LEFT JOIN clause and with to the query using the ElectionsParties relation
 * @method     ChildElectionsPartiesVotesQuery rightJoinWithElectionsParties() Adds a RIGHT JOIN clause and with to the query using the ElectionsParties relation
 * @method     ChildElectionsPartiesVotesQuery innerJoinWithElectionsParties() Adds a INNER JOIN clause and with to the query using the ElectionsParties relation
 *
 * @method     \ConstituenciesQuery|\ElectionsPartiesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElectionsPartiesVotes findOne(ConnectionInterface $con = null) Return the first ChildElectionsPartiesVotes matching the query
 * @method     ChildElectionsPartiesVotes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElectionsPartiesVotes matching the query, or a new ChildElectionsPartiesVotes object populated from the query conditions when no match is found
 *
 * @method     ChildElectionsPartiesVotes findOneById(int $id) Return the first ChildElectionsPartiesVotes filtered by the id column
 * @method     ChildElectionsPartiesVotes findOneByElectionPartyId(int $election_party_id) Return the first ChildElectionsPartiesVotes filtered by the election_party_id column
 * @method     ChildElectionsPartiesVotes findOneByConstituencyId(int $constituency_id) Return the first ChildElectionsPartiesVotes filtered by the constituency_id column
 * @method     ChildElectionsPartiesVotes findOneByVotes(int $votes) Return the first ChildElectionsPartiesVotes filtered by the votes column *

 * @method     ChildElectionsPartiesVotes requirePk($key, ConnectionInterface $con = null) Return the ChildElectionsPartiesVotes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsPartiesVotes requireOne(ConnectionInterface $con = null) Return the first ChildElectionsPartiesVotes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionsPartiesVotes requireOneById(int $id) Return the first ChildElectionsPartiesVotes filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsPartiesVotes requireOneByElectionPartyId(int $election_party_id) Return the first ChildElectionsPartiesVotes filtered by the election_party_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsPartiesVotes requireOneByConstituencyId(int $constituency_id) Return the first ChildElectionsPartiesVotes filtered by the constituency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsPartiesVotes requireOneByVotes(int $votes) Return the first ChildElectionsPartiesVotes filtered by the votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionsPartiesVotes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElectionsPartiesVotes objects based on current ModelCriteria
 * @method     ChildElectionsPartiesVotes[]|ObjectCollection findById(int $id) Return ChildElectionsPartiesVotes objects filtered by the id column
 * @method     ChildElectionsPartiesVotes[]|ObjectCollection findByElectionPartyId(int $election_party_id) Return ChildElectionsPartiesVotes objects filtered by the election_party_id column
 * @method     ChildElectionsPartiesVotes[]|ObjectCollection findByConstituencyId(int $constituency_id) Return ChildElectionsPartiesVotes objects filtered by the constituency_id column
 * @method     ChildElectionsPartiesVotes[]|ObjectCollection findByVotes(int $votes) Return ChildElectionsPartiesVotes objects filtered by the votes column
 * @method     ChildElectionsPartiesVotes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionsPartiesVotesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionsPartiesVotesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ElectionsPartiesVotes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionsPartiesVotesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionsPartiesVotesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionsPartiesVotesQuery) {
            return $criteria;
        }
        $query = new ChildElectionsPartiesVotesQuery();
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
     * @return ChildElectionsPartiesVotes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionsPartiesVotesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionsPartiesVotesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElectionsPartiesVotes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, election_party_id, constituency_id, votes FROM elections_parties_votes WHERE id = :p0';
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
            /** @var ChildElectionsPartiesVotes $obj */
            $obj = new ChildElectionsPartiesVotes();
            $obj->hydrate($row);
            ElectionsPartiesVotesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElectionsPartiesVotes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the election_party_id column
     *
     * Example usage:
     * <code>
     * $query->filterByElectionPartyId(1234); // WHERE election_party_id = 1234
     * $query->filterByElectionPartyId(array(12, 34)); // WHERE election_party_id IN (12, 34)
     * $query->filterByElectionPartyId(array('min' => 12)); // WHERE election_party_id > 12
     * </code>
     *
     * @see       filterByElectionsParties()
     *
     * @param     mixed $electionPartyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByElectionPartyId($electionPartyId = null, $comparison = null)
    {
        if (is_array($electionPartyId)) {
            $useMinMax = false;
            if (isset($electionPartyId['min'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ELECTION_PARTY_ID, $electionPartyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($electionPartyId['max'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ELECTION_PARTY_ID, $electionPartyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ELECTION_PARTY_ID, $electionPartyId, $comparison);
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
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByConstituencyId($constituencyId = null, $comparison = null)
    {
        if (is_array($constituencyId)) {
            $useMinMax = false;
            if (isset($constituencyId['min'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_CONSTITUENCY_ID, $constituencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($constituencyId['max'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_CONSTITUENCY_ID, $constituencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_CONSTITUENCY_ID, $constituencyId, $comparison);
    }

    /**
     * Filter the query on the votes column
     *
     * Example usage:
     * <code>
     * $query->filterByVotes(1234); // WHERE votes = 1234
     * $query->filterByVotes(array(12, 34)); // WHERE votes IN (12, 34)
     * $query->filterByVotes(array('min' => 12)); // WHERE votes > 12
     * </code>
     *
     * @param     mixed $votes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByVotes($votes = null, $comparison = null)
    {
        if (is_array($votes)) {
            $useMinMax = false;
            if (isset($votes['min'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_VOTES, $votes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($votes['max'])) {
                $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_VOTES, $votes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_VOTES, $votes, $comparison);
    }

    /**
     * Filter the query by a related \Constituencies object
     *
     * @param \Constituencies|ObjectCollection $constituencies The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByConstituencies($constituencies, $comparison = null)
    {
        if ($constituencies instanceof \Constituencies) {
            return $this
                ->addUsingAlias(ElectionsPartiesVotesTableMap::COL_CONSTITUENCY_ID, $constituencies->getId(), $comparison);
        } elseif ($constituencies instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsPartiesVotesTableMap::COL_CONSTITUENCY_ID, $constituencies->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
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
     * Filter the query by a related \ElectionsParties object
     *
     * @param \ElectionsParties|ObjectCollection $electionsParties The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function filterByElectionsParties($electionsParties, $comparison = null)
    {
        if ($electionsParties instanceof \ElectionsParties) {
            return $this
                ->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ELECTION_PARTY_ID, $electionsParties->getId(), $comparison);
        } elseif ($electionsParties instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ELECTION_PARTY_ID, $electionsParties->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByElectionsParties() only accepts arguments of type \ElectionsParties or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ElectionsParties relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function joinElectionsParties($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ElectionsParties');

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
            $this->addJoinObject($join, 'ElectionsParties');
        }

        return $this;
    }

    /**
     * Use the ElectionsParties relation ElectionsParties object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionsPartiesQuery A secondary query class using the current class as primary query
     */
    public function useElectionsPartiesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElectionsParties($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ElectionsParties', '\ElectionsPartiesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildElectionsPartiesVotes $electionsPartiesVotes Object to remove from the list of results
     *
     * @return $this|ChildElectionsPartiesVotesQuery The current query, for fluid interface
     */
    public function prune($electionsPartiesVotes = null)
    {
        if ($electionsPartiesVotes) {
            $this->addUsingAlias(ElectionsPartiesVotesTableMap::COL_ID, $electionsPartiesVotes->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the elections_parties_votes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesVotesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionsPartiesVotesTableMap::clearInstancePool();
            ElectionsPartiesVotesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesVotesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionsPartiesVotesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionsPartiesVotesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionsPartiesVotesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ElectionsPartiesVotesQuery
