<?php

namespace Base;

use \Election as ChildElection;
use \ElectionQuery as ChildElectionQuery;
use \Exception;
use \PDO;
use Map\ElectionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'elections' table.
 *
 *
 *
 * @method     ChildElectionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method     ChildElectionQuery orderByAssemblyTypeId($order = Criteria::ASC) Order by the assembly_type_id column
 * @method     ChildElectionQuery orderByPopulationCensusId($order = Criteria::ASC) Order by the population_census_id column
 * @method     ChildElectionQuery orderByActiveSuffrage($order = Criteria::ASC) Order by the active_suffrage column
 * @method     ChildElectionQuery orderByThresholdPercentage($order = Criteria::ASC) Order by the threshold_percentage column
 * @method     ChildElectionQuery orderByTotalValidVotes($order = Criteria::ASC) Order by the total_valid_votes column
 * @method     ChildElectionQuery orderByTotalInvalidVotes($order = Criteria::ASC) Order by the total_invalid_votes column
 * @method     ChildElectionQuery orderByOfficial($order = Criteria::ASC) Order by the official column
 * @method     ChildElectionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildElectionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildElectionQuery groupById() Group by the id column
 * @method     ChildElectionQuery groupBySlug() Group by the slug column
 * @method     ChildElectionQuery groupByAssemblyTypeId() Group by the assembly_type_id column
 * @method     ChildElectionQuery groupByPopulationCensusId() Group by the population_census_id column
 * @method     ChildElectionQuery groupByActiveSuffrage() Group by the active_suffrage column
 * @method     ChildElectionQuery groupByThresholdPercentage() Group by the threshold_percentage column
 * @method     ChildElectionQuery groupByTotalValidVotes() Group by the total_valid_votes column
 * @method     ChildElectionQuery groupByTotalInvalidVotes() Group by the total_invalid_votes column
 * @method     ChildElectionQuery groupByOfficial() Group by the official column
 * @method     ChildElectionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildElectionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildElectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionQuery leftJoinAssemblyType($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssemblyType relation
 * @method     ChildElectionQuery rightJoinAssemblyType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssemblyType relation
 * @method     ChildElectionQuery innerJoinAssemblyType($relationAlias = null) Adds a INNER JOIN clause to the query using the AssemblyType relation
 *
 * @method     ChildElectionQuery joinWithAssemblyType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssemblyType relation
 *
 * @method     ChildElectionQuery leftJoinWithAssemblyType() Adds a LEFT JOIN clause and with to the query using the AssemblyType relation
 * @method     ChildElectionQuery rightJoinWithAssemblyType() Adds a RIGHT JOIN clause and with to the query using the AssemblyType relation
 * @method     ChildElectionQuery innerJoinWithAssemblyType() Adds a INNER JOIN clause and with to the query using the AssemblyType relation
 *
 * @method     ChildElectionQuery leftJoinPopulationCensus($relationAlias = null) Adds a LEFT JOIN clause to the query using the PopulationCensus relation
 * @method     ChildElectionQuery rightJoinPopulationCensus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PopulationCensus relation
 * @method     ChildElectionQuery innerJoinPopulationCensus($relationAlias = null) Adds a INNER JOIN clause to the query using the PopulationCensus relation
 *
 * @method     ChildElectionQuery joinWithPopulationCensus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PopulationCensus relation
 *
 * @method     ChildElectionQuery leftJoinWithPopulationCensus() Adds a LEFT JOIN clause and with to the query using the PopulationCensus relation
 * @method     ChildElectionQuery rightJoinWithPopulationCensus() Adds a RIGHT JOIN clause and with to the query using the PopulationCensus relation
 * @method     ChildElectionQuery innerJoinWithPopulationCensus() Adds a INNER JOIN clause and with to the query using the PopulationCensus relation
 *
 * @method     ChildElectionQuery leftJoinIndependentCandidate($relationAlias = null) Adds a LEFT JOIN clause to the query using the IndependentCandidate relation
 * @method     ChildElectionQuery rightJoinIndependentCandidate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IndependentCandidate relation
 * @method     ChildElectionQuery innerJoinIndependentCandidate($relationAlias = null) Adds a INNER JOIN clause to the query using the IndependentCandidate relation
 *
 * @method     ChildElectionQuery joinWithIndependentCandidate($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the IndependentCandidate relation
 *
 * @method     ChildElectionQuery leftJoinWithIndependentCandidate() Adds a LEFT JOIN clause and with to the query using the IndependentCandidate relation
 * @method     ChildElectionQuery rightJoinWithIndependentCandidate() Adds a RIGHT JOIN clause and with to the query using the IndependentCandidate relation
 * @method     ChildElectionQuery innerJoinWithIndependentCandidate() Adds a INNER JOIN clause and with to the query using the IndependentCandidate relation
 *
 * @method     ChildElectionQuery leftJoinElectionParty($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionParty relation
 * @method     ChildElectionQuery rightJoinElectionParty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionParty relation
 * @method     ChildElectionQuery innerJoinElectionParty($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionParty relation
 *
 * @method     ChildElectionQuery joinWithElectionParty($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionParty relation
 *
 * @method     ChildElectionQuery leftJoinWithElectionParty() Adds a LEFT JOIN clause and with to the query using the ElectionParty relation
 * @method     ChildElectionQuery rightJoinWithElectionParty() Adds a RIGHT JOIN clause and with to the query using the ElectionParty relation
 * @method     ChildElectionQuery innerJoinWithElectionParty() Adds a INNER JOIN clause and with to the query using the ElectionParty relation
 *
 * @method     \AssemblyTypeQuery|\PopulationCensusQuery|\IndependentCandidateQuery|\ElectionPartyQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElection findOne(ConnectionInterface $con = null) Return the first ChildElection matching the query
 * @method     ChildElection findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElection matching the query, or a new ChildElection object populated from the query conditions when no match is found
 *
 * @method     ChildElection findOneById(int $id) Return the first ChildElection filtered by the id column
 * @method     ChildElection findOneBySlug(string $slug) Return the first ChildElection filtered by the slug column
 * @method     ChildElection findOneByAssemblyTypeId(int $assembly_type_id) Return the first ChildElection filtered by the assembly_type_id column
 * @method     ChildElection findOneByPopulationCensusId(int $population_census_id) Return the first ChildElection filtered by the population_census_id column
 * @method     ChildElection findOneByActiveSuffrage(int $active_suffrage) Return the first ChildElection filtered by the active_suffrage column
 * @method     ChildElection findOneByThresholdPercentage(int $threshold_percentage) Return the first ChildElection filtered by the threshold_percentage column
 * @method     ChildElection findOneByTotalValidVotes(int $total_valid_votes) Return the first ChildElection filtered by the total_valid_votes column
 * @method     ChildElection findOneByTotalInvalidVotes(int $total_invalid_votes) Return the first ChildElection filtered by the total_invalid_votes column
 * @method     ChildElection findOneByOfficial(boolean $official) Return the first ChildElection filtered by the official column
 * @method     ChildElection findOneByCreatedAt(string $created_at) Return the first ChildElection filtered by the created_at column
 * @method     ChildElection findOneByUpdatedAt(string $updated_at) Return the first ChildElection filtered by the updated_at column *

 * @method     ChildElection requirePk($key, ConnectionInterface $con = null) Return the ChildElection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOne(ConnectionInterface $con = null) Return the first ChildElection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElection requireOneById(int $id) Return the first ChildElection filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneBySlug(string $slug) Return the first ChildElection filtered by the slug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByAssemblyTypeId(int $assembly_type_id) Return the first ChildElection filtered by the assembly_type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByPopulationCensusId(int $population_census_id) Return the first ChildElection filtered by the population_census_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByActiveSuffrage(int $active_suffrage) Return the first ChildElection filtered by the active_suffrage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByThresholdPercentage(int $threshold_percentage) Return the first ChildElection filtered by the threshold_percentage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByTotalValidVotes(int $total_valid_votes) Return the first ChildElection filtered by the total_valid_votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByTotalInvalidVotes(int $total_invalid_votes) Return the first ChildElection filtered by the total_invalid_votes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByOfficial(boolean $official) Return the first ChildElection filtered by the official column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByCreatedAt(string $created_at) Return the first ChildElection filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElection requireOneByUpdatedAt(string $updated_at) Return the first ChildElection filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElection[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElection objects based on current ModelCriteria
 * @method     ChildElection[]|ObjectCollection findById(int $id) Return ChildElection objects filtered by the id column
 * @method     ChildElection[]|ObjectCollection findBySlug(string $slug) Return ChildElection objects filtered by the slug column
 * @method     ChildElection[]|ObjectCollection findByAssemblyTypeId(int $assembly_type_id) Return ChildElection objects filtered by the assembly_type_id column
 * @method     ChildElection[]|ObjectCollection findByPopulationCensusId(int $population_census_id) Return ChildElection objects filtered by the population_census_id column
 * @method     ChildElection[]|ObjectCollection findByActiveSuffrage(int $active_suffrage) Return ChildElection objects filtered by the active_suffrage column
 * @method     ChildElection[]|ObjectCollection findByThresholdPercentage(int $threshold_percentage) Return ChildElection objects filtered by the threshold_percentage column
 * @method     ChildElection[]|ObjectCollection findByTotalValidVotes(int $total_valid_votes) Return ChildElection objects filtered by the total_valid_votes column
 * @method     ChildElection[]|ObjectCollection findByTotalInvalidVotes(int $total_invalid_votes) Return ChildElection objects filtered by the total_invalid_votes column
 * @method     ChildElection[]|ObjectCollection findByOfficial(boolean $official) Return ChildElection objects filtered by the official column
 * @method     ChildElection[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildElection objects filtered by the created_at column
 * @method     ChildElection[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildElection objects filtered by the updated_at column
 * @method     ChildElection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Election', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionQuery) {
            return $criteria;
        }
        $query = new ChildElectionQuery();
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
     * @return ChildElection|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, slug, assembly_type_id, population_census_id, active_suffrage, threshold_percentage, total_valid_votes, total_invalid_votes, official, created_at, updated_at FROM elections WHERE id = :p0';
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
            /** @var ChildElection $obj */
            $obj = new ChildElection();
            $obj->hydrate($row);
            ElectionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElection|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%', Criteria::LIKE); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the assembly_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAssemblyTypeId(1234); // WHERE assembly_type_id = 1234
     * $query->filterByAssemblyTypeId(array(12, 34)); // WHERE assembly_type_id IN (12, 34)
     * $query->filterByAssemblyTypeId(array('min' => 12)); // WHERE assembly_type_id > 12
     * </code>
     *
     * @see       filterByAssemblyType()
     *
     * @param     mixed $assemblyTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByAssemblyTypeId($assemblyTypeId = null, $comparison = null)
    {
        if (is_array($assemblyTypeId)) {
            $useMinMax = false;
            if (isset($assemblyTypeId['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($assemblyTypeId['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId, $comparison);
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
     * @see       filterByPopulationCensus()
     *
     * @param     mixed $populationCensusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByPopulationCensusId($populationCensusId = null, $comparison = null)
    {
        if (is_array($populationCensusId)) {
            $useMinMax = false;
            if (isset($populationCensusId['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($populationCensusId['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId, $comparison);
    }

    /**
     * Filter the query on the active_suffrage column
     *
     * Example usage:
     * <code>
     * $query->filterByActiveSuffrage(1234); // WHERE active_suffrage = 1234
     * $query->filterByActiveSuffrage(array(12, 34)); // WHERE active_suffrage IN (12, 34)
     * $query->filterByActiveSuffrage(array('min' => 12)); // WHERE active_suffrage > 12
     * </code>
     *
     * @param     mixed $activeSuffrage The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByActiveSuffrage($activeSuffrage = null, $comparison = null)
    {
        if (is_array($activeSuffrage)) {
            $useMinMax = false;
            if (isset($activeSuffrage['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activeSuffrage['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage, $comparison);
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
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByThresholdPercentage($thresholdPercentage = null, $comparison = null)
    {
        if (is_array($thresholdPercentage)) {
            $useMinMax = false;
            if (isset($thresholdPercentage['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($thresholdPercentage['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_THRESHOLD_PERCENTAGE, $thresholdPercentage, $comparison);
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
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByTotalValidVotes($totalValidVotes = null, $comparison = null)
    {
        if (is_array($totalValidVotes)) {
            $useMinMax = false;
            if (isset($totalValidVotes['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalValidVotes['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_TOTAL_VALID_VOTES, $totalValidVotes, $comparison);
    }

    /**
     * Filter the query on the total_invalid_votes column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalInvalidVotes(1234); // WHERE total_invalid_votes = 1234
     * $query->filterByTotalInvalidVotes(array(12, 34)); // WHERE total_invalid_votes IN (12, 34)
     * $query->filterByTotalInvalidVotes(array('min' => 12)); // WHERE total_invalid_votes > 12
     * </code>
     *
     * @param     mixed $totalInvalidVotes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByTotalInvalidVotes($totalInvalidVotes = null, $comparison = null)
    {
        if (is_array($totalInvalidVotes)) {
            $useMinMax = false;
            if (isset($totalInvalidVotes['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_TOTAL_INVALID_VOTES, $totalInvalidVotes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalInvalidVotes['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_TOTAL_INVALID_VOTES, $totalInvalidVotes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_TOTAL_INVALID_VOTES, $totalInvalidVotes, $comparison);
    }

    /**
     * Filter the query on the official column
     *
     * Example usage:
     * <code>
     * $query->filterByOfficial(true); // WHERE official = true
     * $query->filterByOfficial('yes'); // WHERE official = true
     * </code>
     *
     * @param     boolean|string $official The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByOfficial($official = null, $comparison = null)
    {
        if (is_string($official)) {
            $official = in_array(strtolower($official), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ElectionTableMap::COL_OFFICIAL, $official, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ElectionTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ElectionTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \AssemblyType object
     *
     * @param \AssemblyType|ObjectCollection $assemblyType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionQuery The current query, for fluid interface
     */
    public function filterByAssemblyType($assemblyType, $comparison = null)
    {
        if ($assemblyType instanceof \AssemblyType) {
            return $this
                ->addUsingAlias(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyType->getId(), $comparison);
        } elseif ($assemblyType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAssemblyType() only accepts arguments of type \AssemblyType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssemblyType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function joinAssemblyType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssemblyType');

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
            $this->addJoinObject($join, 'AssemblyType');
        }

        return $this;
    }

    /**
     * Use the AssemblyType relation AssemblyType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \AssemblyTypeQuery A secondary query class using the current class as primary query
     */
    public function useAssemblyTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssemblyType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssemblyType', '\AssemblyTypeQuery');
    }

    /**
     * Filter the query by a related \PopulationCensus object
     *
     * @param \PopulationCensus|ObjectCollection $populationCensus The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionQuery The current query, for fluid interface
     */
    public function filterByPopulationCensus($populationCensus, $comparison = null)
    {
        if ($populationCensus instanceof \PopulationCensus) {
            return $this
                ->addUsingAlias(ElectionTableMap::COL_POPULATION_CENSUS_ID, $populationCensus->getId(), $comparison);
        } elseif ($populationCensus instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionTableMap::COL_POPULATION_CENSUS_ID, $populationCensus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPopulationCensus() only accepts arguments of type \PopulationCensus or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PopulationCensus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function joinPopulationCensus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PopulationCensus');

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
            $this->addJoinObject($join, 'PopulationCensus');
        }

        return $this;
    }

    /**
     * Use the PopulationCensus relation PopulationCensus object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PopulationCensusQuery A secondary query class using the current class as primary query
     */
    public function usePopulationCensusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPopulationCensus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PopulationCensus', '\PopulationCensusQuery');
    }

    /**
     * Filter the query by a related \IndependentCandidate object
     *
     * @param \IndependentCandidate|ObjectCollection $independentCandidate the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionQuery The current query, for fluid interface
     */
    public function filterByIndependentCandidate($independentCandidate, $comparison = null)
    {
        if ($independentCandidate instanceof \IndependentCandidate) {
            return $this
                ->addUsingAlias(ElectionTableMap::COL_ID, $independentCandidate->getElectionId(), $comparison);
        } elseif ($independentCandidate instanceof ObjectCollection) {
            return $this
                ->useIndependentCandidateQuery()
                ->filterByPrimaryKeys($independentCandidate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIndependentCandidate() only accepts arguments of type \IndependentCandidate or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IndependentCandidate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function joinIndependentCandidate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IndependentCandidate');

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
            $this->addJoinObject($join, 'IndependentCandidate');
        }

        return $this;
    }

    /**
     * Use the IndependentCandidate relation IndependentCandidate object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \IndependentCandidateQuery A secondary query class using the current class as primary query
     */
    public function useIndependentCandidateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIndependentCandidate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IndependentCandidate', '\IndependentCandidateQuery');
    }

    /**
     * Filter the query by a related \ElectionParty object
     *
     * @param \ElectionParty|ObjectCollection $electionParty the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionQuery The current query, for fluid interface
     */
    public function filterByElectionParty($electionParty, $comparison = null)
    {
        if ($electionParty instanceof \ElectionParty) {
            return $this
                ->addUsingAlias(ElectionTableMap::COL_ID, $electionParty->getElectionId(), $comparison);
        } elseif ($electionParty instanceof ObjectCollection) {
            return $this
                ->useElectionPartyQuery()
                ->filterByPrimaryKeys($electionParty->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElectionParty() only accepts arguments of type \ElectionParty or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ElectionParty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function joinElectionParty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ElectionParty');

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
            $this->addJoinObject($join, 'ElectionParty');
        }

        return $this;
    }

    /**
     * Use the ElectionParty relation ElectionParty object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionPartyQuery A secondary query class using the current class as primary query
     */
    public function useElectionPartyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElectionParty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ElectionParty', '\ElectionPartyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildElection $election Object to remove from the list of results
     *
     * @return $this|ChildElectionQuery The current query, for fluid interface
     */
    public function prune($election = null)
    {
        if ($election) {
            $this->addUsingAlias(ElectionTableMap::COL_ID, $election->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the elections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionTableMap::clearInstancePool();
            ElectionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ElectionTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ElectionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ElectionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ElectionTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ElectionTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildElectionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ElectionTableMap::COL_CREATED_AT);
    }

} // ElectionQuery
