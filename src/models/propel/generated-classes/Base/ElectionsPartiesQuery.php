<?php

namespace Base;

use \ElectionsParties as ChildElectionsParties;
use \ElectionsPartiesQuery as ChildElectionsPartiesQuery;
use \Exception;
use \PDO;
use Map\ElectionsPartiesTableMap;
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
 * @method     ChildElectionsPartiesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionsPartiesQuery orderByElectionId($order = Criteria::ASC) Order by the election_id column
 * @method     ChildElectionsPartiesQuery orderByListNumber($order = Criteria::ASC) Order by the list_number column
 * @method     ChildElectionsPartiesQuery orderByPartyId($order = Criteria::ASC) Order by the party_id column
 * @method     ChildElectionsPartiesQuery orderByPartyColor($order = Criteria::ASC) Order by the party_color column
 * @method     ChildElectionsPartiesQuery orderByTotalVotes($order = Criteria::ASC) Order by the total_votes column
 * @method     ChildElectionsPartiesQuery orderByOrd($order = Criteria::ASC) Order by the ord column
 *
 * @method     ChildElectionsPartiesQuery groupById() Group by the id column
 * @method     ChildElectionsPartiesQuery groupByElectionId() Group by the election_id column
 * @method     ChildElectionsPartiesQuery groupByListNumber() Group by the list_number column
 * @method     ChildElectionsPartiesQuery groupByPartyId() Group by the party_id column
 * @method     ChildElectionsPartiesQuery groupByPartyColor() Group by the party_color column
 * @method     ChildElectionsPartiesQuery groupByTotalVotes() Group by the total_votes column
 * @method     ChildElectionsPartiesQuery groupByOrd() Group by the ord column
 *
 * @method     ChildElectionsPartiesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionsPartiesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionsPartiesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionsPartiesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionsPartiesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionsPartiesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionsPartiesQuery leftJoinElections($relationAlias = null) Adds a LEFT JOIN clause to the query using the Elections relation
 * @method     ChildElectionsPartiesQuery rightJoinElections($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Elections relation
 * @method     ChildElectionsPartiesQuery innerJoinElections($relationAlias = null) Adds a INNER JOIN clause to the query using the Elections relation
 *
 * @method     ChildElectionsPartiesQuery joinWithElections($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Elections relation
 *
 * @method     ChildElectionsPartiesQuery leftJoinWithElections() Adds a LEFT JOIN clause and with to the query using the Elections relation
 * @method     ChildElectionsPartiesQuery rightJoinWithElections() Adds a RIGHT JOIN clause and with to the query using the Elections relation
 * @method     ChildElectionsPartiesQuery innerJoinWithElections() Adds a INNER JOIN clause and with to the query using the Elections relation
 *
 * @method     ChildElectionsPartiesQuery leftJoinParties($relationAlias = null) Adds a LEFT JOIN clause to the query using the Parties relation
 * @method     ChildElectionsPartiesQuery rightJoinParties($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Parties relation
 * @method     ChildElectionsPartiesQuery innerJoinParties($relationAlias = null) Adds a INNER JOIN clause to the query using the Parties relation
 *
 * @method     ChildElectionsPartiesQuery joinWithParties($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Parties relation
 *
 * @method     ChildElectionsPartiesQuery leftJoinWithParties() Adds a LEFT JOIN clause and with to the query using the Parties relation
 * @method     ChildElectionsPartiesQuery rightJoinWithParties() Adds a RIGHT JOIN clause and with to the query using the Parties relation
 * @method     ChildElectionsPartiesQuery innerJoinWithParties() Adds a INNER JOIN clause and with to the query using the Parties relation
 *
 * @method     ChildElectionsPartiesQuery leftJoinElectionsPartiesVotes($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsPartiesVotes relation
 * @method     ChildElectionsPartiesQuery rightJoinElectionsPartiesVotes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsPartiesVotes relation
 * @method     ChildElectionsPartiesQuery innerJoinElectionsPartiesVotes($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsPartiesVotes relation
 *
 * @method     ChildElectionsPartiesQuery joinWithElectionsPartiesVotes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsPartiesVotes relation
 *
 * @method     ChildElectionsPartiesQuery leftJoinWithElectionsPartiesVotes() Adds a LEFT JOIN clause and with to the query using the ElectionsPartiesVotes relation
 * @method     ChildElectionsPartiesQuery rightJoinWithElectionsPartiesVotes() Adds a RIGHT JOIN clause and with to the query using the ElectionsPartiesVotes relation
 * @method     ChildElectionsPartiesQuery innerJoinWithElectionsPartiesVotes() Adds a INNER JOIN clause and with to the query using the ElectionsPartiesVotes relation
 *
 * @method     \ElectionsQuery|\PartiesQuery|\ElectionsPartiesVotesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElectionsParties findOne(ConnectionInterface $con = null) Return the first ChildElectionsParties matching the query
 * @method     ChildElectionsParties findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElectionsParties matching the query, or a new ChildElectionsParties object populated from the query conditions when no match is found
 *
 * @method     ChildElectionsParties findOneById(int $id) Return the first ChildElectionsParties filtered by the id column
 * @method     ChildElectionsParties findOneByElectionId(int $election_id) Return the first ChildElectionsParties filtered by the election_id column
 * @method     ChildElectionsParties findOneByListNumber(int $list_number) Return the first ChildElectionsParties filtered by the list_number column
 * @method     ChildElectionsParties findOneByPartyId(int $party_id) Return the first ChildElectionsParties filtered by the party_id column
 * @method     ChildElectionsParties findOneByPartyColor(string $party_color) Return the first ChildElectionsParties filtered by the party_color column
 * @method     ChildElectionsParties findOneByTotalVotes(int $total_votes) Return the first ChildElectionsParties filtered by the total_votes column
 * @method     ChildElectionsParties findOneByOrd(int $ord) Return the first ChildElectionsParties filtered by the ord column *

 * @method     ChildElectionsParties requirePk($key, ConnectionInterface $con = null) Return the ChildElectionsParties by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOne(ConnectionInterface $con = null) Return the first ChildElectionsParties matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionsParties requireOneById(int $id) Return the first ChildElectionsParties filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByElectionId(int $election_id) Return the first ChildElectionsParties filtered by the election_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByListNumber(int $list_number) Return the first ChildElectionsParties filtered by the list_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByPartyId(int $party_id) Return the first ChildElectionsParties filtered by the party_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByPartyColor(string $party_color) Return the first ChildElectionsParties filtered by the party_color column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByTotalVotes(int $total_votes) Return the first ChildElectionsParties filtered by the total_votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElectionsParties requireOneByOrd(int $ord) Return the first ChildElectionsParties filtered by the ord column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElectionsParties[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElectionsParties objects based on current ModelCriteria
 * @method     ChildElectionsParties[]|ObjectCollection findById(int $id) Return ChildElectionsParties objects filtered by the id column
 * @method     ChildElectionsParties[]|ObjectCollection findByElectionId(int $election_id) Return ChildElectionsParties objects filtered by the election_id column
 * @method     ChildElectionsParties[]|ObjectCollection findByListNumber(int $list_number) Return ChildElectionsParties objects filtered by the list_number column
 * @method     ChildElectionsParties[]|ObjectCollection findByPartyId(int $party_id) Return ChildElectionsParties objects filtered by the party_id column
 * @method     ChildElectionsParties[]|ObjectCollection findByPartyColor(string $party_color) Return ChildElectionsParties objects filtered by the party_color column
 * @method     ChildElectionsParties[]|ObjectCollection findByTotalVotes(int $total_votes) Return ChildElectionsParties objects filtered by the total_votes column
 * @method     ChildElectionsParties[]|ObjectCollection findByOrd(int $ord) Return ChildElectionsParties objects filtered by the ord column
 * @method     ChildElectionsParties[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionsPartiesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionsPartiesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ElectionsParties', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionsPartiesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionsPartiesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionsPartiesQuery) {
            return $criteria;
        }
        $query = new ChildElectionsPartiesQuery();
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
     * @return ChildElectionsParties|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionsPartiesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionsPartiesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElectionsParties A model object, or null if the key is not found
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
            /** @var ChildElectionsParties $obj */
            $obj = new ChildElectionsParties();
            $obj->hydrate($row);
            ElectionsPartiesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElectionsParties|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $id, $comparison);
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
     * @see       filterByElections()
     *
     * @param     mixed $electionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByElectionId($electionId = null, $comparison = null)
    {
        if (is_array($electionId)) {
            $useMinMax = false;
            if (isset($electionId['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ELECTION_ID, $electionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($electionId['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ELECTION_ID, $electionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_ELECTION_ID, $electionId, $comparison);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByListNumber($listNumber = null, $comparison = null)
    {
        if (is_array($listNumber)) {
            $useMinMax = false;
            if (isset($listNumber['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_LIST_NUMBER, $listNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listNumber['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_LIST_NUMBER, $listNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_LIST_NUMBER, $listNumber, $comparison);
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
     * @see       filterByParties()
     *
     * @param     mixed $partyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByPartyId($partyId = null, $comparison = null)
    {
        if (is_array($partyId)) {
            $useMinMax = false;
            if (isset($partyId['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_ID, $partyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partyId['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_ID, $partyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_ID, $partyId, $comparison);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByPartyColor($partyColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($partyColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_COLOR, $partyColor, $comparison);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByTotalVotes($totalVotes = null, $comparison = null)
    {
        if (is_array($totalVotes)) {
            $useMinMax = false;
            if (isset($totalVotes['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_TOTAL_VOTES, $totalVotes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalVotes['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_TOTAL_VOTES, $totalVotes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_TOTAL_VOTES, $totalVotes, $comparison);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByOrd($ord = null, $comparison = null)
    {
        if (is_array($ord)) {
            $useMinMax = false;
            if (isset($ord['min'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ORD, $ord['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ord['max'])) {
                $this->addUsingAlias(ElectionsPartiesTableMap::COL_ORD, $ord['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsPartiesTableMap::COL_ORD, $ord, $comparison);
    }

    /**
     * Filter the query by a related \Elections object
     *
     * @param \Elections|ObjectCollection $elections The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByElections($elections, $comparison = null)
    {
        if ($elections instanceof \Elections) {
            return $this
                ->addUsingAlias(ElectionsPartiesTableMap::COL_ELECTION_ID, $elections->getId(), $comparison);
        } elseif ($elections instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsPartiesTableMap::COL_ELECTION_ID, $elections->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
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
     * Filter the query by a related \Parties object
     *
     * @param \Parties|ObjectCollection $parties The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByParties($parties, $comparison = null)
    {
        if ($parties instanceof \Parties) {
            return $this
                ->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_ID, $parties->getId(), $comparison);
        } elseif ($parties instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsPartiesTableMap::COL_PARTY_ID, $parties->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParties() only accepts arguments of type \Parties or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Parties relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function joinParties($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Parties');

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
            $this->addJoinObject($join, 'Parties');
        }

        return $this;
    }

    /**
     * Use the Parties relation Parties object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PartiesQuery A secondary query class using the current class as primary query
     */
    public function usePartiesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinParties($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Parties', '\PartiesQuery');
    }

    /**
     * Filter the query by a related \ElectionsPartiesVotes object
     *
     * @param \ElectionsPartiesVotes|ObjectCollection $electionsPartiesVotes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function filterByElectionsPartiesVotes($electionsPartiesVotes, $comparison = null)
    {
        if ($electionsPartiesVotes instanceof \ElectionsPartiesVotes) {
            return $this
                ->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $electionsPartiesVotes->getElectionPartyId(), $comparison);
        } elseif ($electionsPartiesVotes instanceof ObjectCollection) {
            return $this
                ->useElectionsPartiesVotesQuery()
                ->filterByPrimaryKeys($electionsPartiesVotes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElectionsPartiesVotes() only accepts arguments of type \ElectionsPartiesVotes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ElectionsPartiesVotes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function joinElectionsPartiesVotes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ElectionsPartiesVotes');

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
            $this->addJoinObject($join, 'ElectionsPartiesVotes');
        }

        return $this;
    }

    /**
     * Use the ElectionsPartiesVotes relation ElectionsPartiesVotes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionsPartiesVotesQuery A secondary query class using the current class as primary query
     */
    public function useElectionsPartiesVotesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElectionsPartiesVotes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ElectionsPartiesVotes', '\ElectionsPartiesVotesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildElectionsParties $electionsParties Object to remove from the list of results
     *
     * @return $this|ChildElectionsPartiesQuery The current query, for fluid interface
     */
    public function prune($electionsParties = null)
    {
        if ($electionsParties) {
            $this->addUsingAlias(ElectionsPartiesTableMap::COL_ID, $electionsParties->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionsPartiesTableMap::clearInstancePool();
            ElectionsPartiesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionsPartiesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionsPartiesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionsPartiesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ElectionsPartiesQuery
