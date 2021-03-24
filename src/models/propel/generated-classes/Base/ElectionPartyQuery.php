<?php

namespace Base;

use \ElectionParty as ChildElectionParty;
use \ElectionPartyQuery as ChildElectionPartyQuery;
use \Exception;
use \PDO;
use Map\ElectionPartyTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'elections_parties' table.
 *
 *
 *
 * @method     ChildElectionPartyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionPartyQuery orderByElectionId($order = Criteria::ASC) Order by the election_id column
 * @method     ChildElectionPartyQuery orderByListNumber($order = Criteria::ASC) Order by the list_number column
 * @method     ChildElectionPartyQuery orderByPartyId($order = Criteria::ASC) Order by the party_id column
 * @method     ChildElectionPartyQuery orderByPartyColor($order = Criteria::ASC) Order by the party_color column
 * @method     ChildElectionPartyQuery orderByTotalVotes($order = Criteria::ASC) Order by the total_votes column
 * @method     ChildElectionPartyQuery orderByOrd($order = Criteria::ASC) Order by the ord column
 *
 * @method     ChildElectionPartyQuery groupById() Group by the id column
 * @method     ChildElectionPartyQuery groupByElectionId() Group by the election_id column
 * @method     ChildElectionPartyQuery groupByListNumber() Group by the list_number column
 * @method     ChildElectionPartyQuery groupByPartyId() Group by the party_id column
 * @method     ChildElectionPartyQuery groupByPartyColor() Group by the party_color column
 * @method     ChildElectionPartyQuery groupByTotalVotes() Group by the total_votes column
 * @method     ChildElectionPartyQuery groupByOrd() Group by the ord column
 *
 * @method     ChildElectionPartyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionPartyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionPartyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionPartyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionPartyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionPartyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionPartyQuery leftJoinElection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Election relation
 * @method     ChildElectionPartyQuery rightJoinElection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Election relation
 * @method     ChildElectionPartyQuery innerJoinElection($relationAlias = null) Adds a INNER JOIN clause to the query using the Election relation
 *
 * @method     ChildElectionPartyQuery joinWithElection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Election relation
 *
 * @method     ChildElectionPartyQuery leftJoinWithElection() Adds a LEFT JOIN clause and with to the query using the Election relation
 * @method     ChildElectionPartyQuery rightJoinWithElection() Adds a RIGHT JOIN clause and with to the query using the Election relation
 * @method     ChildElectionPartyQuery innerJoinWithElection() Adds a INNER JOIN clause and with to the query using the Election relation
 *
 * @method     ChildElectionPartyQuery leftJoinParty($relationAlias = null) Adds a LEFT JOIN clause to the query using the Party relation
 * @method     ChildElectionPartyQuery rightJoinParty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Party relation
 * @method     ChildElectionPartyQuery innerJoinParty($relationAlias = null) Adds a INNER JOIN clause to the query using the Party relation
 *
 * @method     ChildElectionPartyQuery joinWithParty($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Party relation
 *
 * @method     ChildElectionPartyQuery leftJoinWithParty() Adds a LEFT JOIN clause and with to the query using the Party relation
 * @method     ChildElectionPartyQuery rightJoinWithParty() Adds a RIGHT JOIN clause and with to the query using the Party relation
 * @method     ChildElectionPartyQuery innerJoinWithParty() Adds a INNER JOIN clause and with to the query using the Party relation
 *
 * @method     ChildElectionPartyQuery leftJoinElectionPartyVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionPartyVote relation
 * @method     ChildElectionPartyQuery rightJoinElectionPartyVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionPartyVote relation
 * @method     ChildElectionPartyQuery innerJoinElectionPartyVote($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionPartyVote relation
 *
 * @method     ChildElectionPartyQuery joinWithElectionPartyVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionPartyVote relation
 *
 * @method     ChildElectionPartyQuery leftJoinWithElectionPartyVote() Adds a LEFT JOIN clause and with to the query using the ElectionPartyVote relation
 * @method     ChildElectionPartyQuery rightJoinWithElectionPartyVote() Adds a RIGHT JOIN clause and with to the query using the ElectionPartyVote relation
 * @method     ChildElectionPartyQuery innerJoinWithElectionPartyVote() Adds a INNER JOIN clause and with to the query using the ElectionPartyVote relation
 *
 * @method     \ElectionQuery|\PartyQuery|\ElectionPartyVoteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElectionParty findOne(ConnectionInterface $con = null) Return the first ChildElectionParty matching the query
 * @method     ChildElectionParty findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElectionParty matching the query, or a new ChildElectionParty object populated from the query conditions when no match is found
 *
 * @method     ChildElectionParty findOneById(int $id) Return the first ChildElectionParty filtered by the id column
 * @method     ChildElectionParty findOneByElectionId(int $election_id) Return the first ChildElectionParty filtered by the election_id column
 * @method     ChildElectionParty findOneByListNumber(int $list_number) Return the first ChildElectionParty filtered by the list_number column
 * @method     ChildElectionParty findOneByPartyId(int $party_id) Return the first ChildElectionParty filtered by the party_id column
 * @method     ChildElectionParty findOneByPartyColor(string $party_color) Return the first ChildElectionParty filtered by the party_color column
 * @method     ChildElectionParty findOneByTotalVotes(int $total_votes) Return the first ChildElectionParty filtered by the total_votes column
 * @method     ChildElectionParty findOneByOrd(int $ord) Return the first ChildElectionParty filtered by the ord column *

 * @method     ChildElectionParty requirePk($key, ConnectionInterface $con = null) Return the ChildElectionParty by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOne(ConnectionInterface $con = null) Return the first ChildElectionParty matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionParty requireOneById(int $id) Return the first ChildElectionParty filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByElectionId(int $election_id) Return the first ChildElectionParty filtered by the election_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByListNumber(int $list_number) Return the first ChildElectionParty filtered by the list_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByPartyId(int $party_id) Return the first ChildElectionParty filtered by the party_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByPartyColor(string $party_color) Return the first ChildElectionParty filtered by the party_color column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByTotalVotes(int $total_votes) Return the first ChildElectionParty filtered by the total_votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionParty requireOneByOrd(int $ord) Return the first ChildElectionParty filtered by the ord column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionParty[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElectionParty objects based on current ModelCriteria
 * @method     ChildElectionParty[]|ObjectCollection findById(int $id) Return ChildElectionParty objects filtered by the id column
 * @method     ChildElectionParty[]|ObjectCollection findByElectionId(int $election_id) Return ChildElectionParty objects filtered by the election_id column
 * @method     ChildElectionParty[]|ObjectCollection findByListNumber(int $list_number) Return ChildElectionParty objects filtered by the list_number column
 * @method     ChildElectionParty[]|ObjectCollection findByPartyId(int $party_id) Return ChildElectionParty objects filtered by the party_id column
 * @method     ChildElectionParty[]|ObjectCollection findByPartyColor(string $party_color) Return ChildElectionParty objects filtered by the party_color column
 * @method     ChildElectionParty[]|ObjectCollection findByTotalVotes(int $total_votes) Return ChildElectionParty objects filtered by the total_votes column
 * @method     ChildElectionParty[]|ObjectCollection findByOrd(int $ord) Return ChildElectionParty objects filtered by the ord column
 * @method     ChildElectionParty[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionPartyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionPartyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ElectionParty', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionPartyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionPartyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionPartyQuery) {
            return $criteria;
        }
        $query = new ChildElectionPartyQuery();
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
     * @return ChildElectionParty|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionPartyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionPartyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElectionParty A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, election_id, list_number, party_id, party_color, total_votes, ord FROM elections_parties WHERE id = :p0';
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
            /** @var ChildElectionParty $obj */
            $obj = new ChildElectionParty();
            $obj->hydrate($row);
            ElectionPartyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElectionParty|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByElectionId($electionId = null, $comparison = null)
    {
        if (is_array($electionId)) {
            $useMinMax = false;
            if (isset($electionId['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ELECTION_ID, $electionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($electionId['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ELECTION_ID, $electionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_ELECTION_ID, $electionId, $comparison);
    }

    /**
     * Filter the query on the list_number column
     *
     * Example usage:
     * <code>
     * $query->filterByListNumber(1234); // WHERE list_number = 1234
     * $query->filterByListNumber(array(12, 34)); // WHERE list_number IN (12, 34)
     * $query->filterByListNumber(array('min' => 12)); // WHERE list_number > 12
     * </code>
     *
     * @param     mixed $listNumber The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByListNumber($listNumber = null, $comparison = null)
    {
        if (is_array($listNumber)) {
            $useMinMax = false;
            if (isset($listNumber['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_LIST_NUMBER, $listNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listNumber['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_LIST_NUMBER, $listNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_LIST_NUMBER, $listNumber, $comparison);
    }

    /**
     * Filter the query on the party_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPartyId(1234); // WHERE party_id = 1234
     * $query->filterByPartyId(array(12, 34)); // WHERE party_id IN (12, 34)
     * $query->filterByPartyId(array('min' => 12)); // WHERE party_id > 12
     * </code>
     *
     * @see       filterByParty()
     *
     * @param     mixed $partyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByPartyId($partyId = null, $comparison = null)
    {
        if (is_array($partyId)) {
            $useMinMax = false;
            if (isset($partyId['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_PARTY_ID, $partyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partyId['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_PARTY_ID, $partyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_PARTY_ID, $partyId, $comparison);
    }

    /**
     * Filter the query on the party_color column
     *
     * Example usage:
     * <code>
     * $query->filterByPartyColor('fooValue');   // WHERE party_color = 'fooValue'
     * $query->filterByPartyColor('%fooValue%', Criteria::LIKE); // WHERE party_color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $partyColor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByPartyColor($partyColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($partyColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_PARTY_COLOR, $partyColor, $comparison);
    }

    /**
     * Filter the query on the total_votes column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalVotes(1234); // WHERE total_votes = 1234
     * $query->filterByTotalVotes(array(12, 34)); // WHERE total_votes IN (12, 34)
     * $query->filterByTotalVotes(array('min' => 12)); // WHERE total_votes > 12
     * </code>
     *
     * @param     mixed $totalVotes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByTotalVotes($totalVotes = null, $comparison = null)
    {
        if (is_array($totalVotes)) {
            $useMinMax = false;
            if (isset($totalVotes['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_TOTAL_VOTES, $totalVotes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalVotes['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_TOTAL_VOTES, $totalVotes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_TOTAL_VOTES, $totalVotes, $comparison);
    }

    /**
     * Filter the query on the ord column
     *
     * Example usage:
     * <code>
     * $query->filterByOrd(1234); // WHERE ord = 1234
     * $query->filterByOrd(array(12, 34)); // WHERE ord IN (12, 34)
     * $query->filterByOrd(array('min' => 12)); // WHERE ord > 12
     * </code>
     *
     * @param     mixed $ord The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByOrd($ord = null, $comparison = null)
    {
        if (is_array($ord)) {
            $useMinMax = false;
            if (isset($ord['min'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ORD, $ord['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ord['max'])) {
                $this->addUsingAlias(ElectionPartyTableMap::COL_ORD, $ord['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionPartyTableMap::COL_ORD, $ord, $comparison);
    }

    /**
     * Filter the query by a related \Election object
     *
     * @param \Election|ObjectCollection $election The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByElection($election, $comparison = null)
    {
        if ($election instanceof \Election) {
            return $this
                ->addUsingAlias(ElectionPartyTableMap::COL_ELECTION_ID, $election->getId(), $comparison);
        } elseif ($election instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionPartyTableMap::COL_ELECTION_ID, $election->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
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
     * Filter the query by a related \Party object
     *
     * @param \Party|ObjectCollection $party The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByParty($party, $comparison = null)
    {
        if ($party instanceof \Party) {
            return $this
                ->addUsingAlias(ElectionPartyTableMap::COL_PARTY_ID, $party->getId(), $comparison);
        } elseif ($party instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionPartyTableMap::COL_PARTY_ID, $party->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParty() only accepts arguments of type \Party or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Party relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function joinParty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Party');

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
            $this->addJoinObject($join, 'Party');
        }

        return $this;
    }

    /**
     * Use the Party relation Party object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PartyQuery A secondary query class using the current class as primary query
     */
    public function usePartyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinParty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Party', '\PartyQuery');
    }

    /**
     * Filter the query by a related \ElectionPartyVote object
     *
     * @param \ElectionPartyVote|ObjectCollection $electionPartyVote the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionPartyQuery The current query, for fluid interface
     */
    public function filterByElectionPartyVote($electionPartyVote, $comparison = null)
    {
        if ($electionPartyVote instanceof \ElectionPartyVote) {
            return $this
                ->addUsingAlias(ElectionPartyTableMap::COL_ID, $electionPartyVote->getElectionPartyId(), $comparison);
        } elseif ($electionPartyVote instanceof ObjectCollection) {
            return $this
                ->useElectionPartyVoteQuery()
                ->filterByPrimaryKeys($electionPartyVote->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElectionPartyVote() only accepts arguments of type \ElectionPartyVote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ElectionPartyVote relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function joinElectionPartyVote($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ElectionPartyVote');

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
            $this->addJoinObject($join, 'ElectionPartyVote');
        }

        return $this;
    }

    /**
     * Use the ElectionPartyVote relation ElectionPartyVote object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionPartyVoteQuery A secondary query class using the current class as primary query
     */
    public function useElectionPartyVoteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElectionPartyVote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ElectionPartyVote', '\ElectionPartyVoteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildElectionParty $electionParty Object to remove from the list of results
     *
     * @return $this|ChildElectionPartyQuery The current query, for fluid interface
     */
    public function prune($electionParty = null)
    {
        if ($electionParty) {
            $this->addUsingAlias(ElectionPartyTableMap::COL_ID, $electionParty->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the elections_parties table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionPartyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionPartyTableMap::clearInstancePool();
            ElectionPartyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionPartyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionPartyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionPartyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionPartyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ElectionPartyQuery
