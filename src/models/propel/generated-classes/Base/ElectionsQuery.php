<?php

namespace Base;

use \Elections as ChildElections;
use \ElectionsQuery as ChildElectionsQuery;
use \Exception;
use \PDO;
use Map\ElectionsTableMap;
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
 * @method     ChildElectionsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildElectionsQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method     ChildElectionsQuery orderByAssemblyTypeId($order = Criteria::ASC) Order by the assembly_type_id column
 * @method     ChildElectionsQuery orderByPopulationCensusId($order = Criteria::ASC) Order by the population_census_id column
 * @method     ChildElectionsQuery orderByActiveSuffrage($order = Criteria::ASC) Order by the active_suffrage column
 * @method     ChildElectionsQuery orderByOfficial($order = Criteria::ASC) Order by the official column
 *
 * @method     ChildElectionsQuery groupById() Group by the id column
 * @method     ChildElectionsQuery groupBySlug() Group by the slug column
 * @method     ChildElectionsQuery groupByAssemblyTypeId() Group by the assembly_type_id column
 * @method     ChildElectionsQuery groupByPopulationCensusId() Group by the population_census_id column
 * @method     ChildElectionsQuery groupByActiveSuffrage() Group by the active_suffrage column
 * @method     ChildElectionsQuery groupByOfficial() Group by the official column
 *
 * @method     ChildElectionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildElectionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildElectionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildElectionsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildElectionsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildElectionsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildElectionsQuery leftJoinAssemblyTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssemblyTypes relation
 * @method     ChildElectionsQuery rightJoinAssemblyTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssemblyTypes relation
 * @method     ChildElectionsQuery innerJoinAssemblyTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the AssemblyTypes relation
 *
 * @method     ChildElectionsQuery joinWithAssemblyTypes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssemblyTypes relation
 *
 * @method     ChildElectionsQuery leftJoinWithAssemblyTypes() Adds a LEFT JOIN clause and with to the query using the AssemblyTypes relation
 * @method     ChildElectionsQuery rightJoinWithAssemblyTypes() Adds a RIGHT JOIN clause and with to the query using the AssemblyTypes relation
 * @method     ChildElectionsQuery innerJoinWithAssemblyTypes() Adds a INNER JOIN clause and with to the query using the AssemblyTypes relation
 *
 * @method     ChildElectionsQuery leftJoinPopulationCensuses($relationAlias = null) Adds a LEFT JOIN clause to the query using the PopulationCensuses relation
 * @method     ChildElectionsQuery rightJoinPopulationCensuses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PopulationCensuses relation
 * @method     ChildElectionsQuery innerJoinPopulationCensuses($relationAlias = null) Adds a INNER JOIN clause to the query using the PopulationCensuses relation
 *
 * @method     ChildElectionsQuery joinWithPopulationCensuses($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PopulationCensuses relation
 *
 * @method     ChildElectionsQuery leftJoinWithPopulationCensuses() Adds a LEFT JOIN clause and with to the query using the PopulationCensuses relation
 * @method     ChildElectionsQuery rightJoinWithPopulationCensuses() Adds a RIGHT JOIN clause and with to the query using the PopulationCensuses relation
 * @method     ChildElectionsQuery innerJoinWithPopulationCensuses() Adds a INNER JOIN clause and with to the query using the PopulationCensuses relation
 *
 * @method     ChildElectionsQuery leftJoinElectionsIndependentCandidates($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsIndependentCandidates relation
 * @method     ChildElectionsQuery rightJoinElectionsIndependentCandidates($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsIndependentCandidates relation
 * @method     ChildElectionsQuery innerJoinElectionsIndependentCandidates($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildElectionsQuery joinWithElectionsIndependentCandidates($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildElectionsQuery leftJoinWithElectionsIndependentCandidates() Adds a LEFT JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 * @method     ChildElectionsQuery rightJoinWithElectionsIndependentCandidates() Adds a RIGHT JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 * @method     ChildElectionsQuery innerJoinWithElectionsIndependentCandidates() Adds a INNER JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildElectionsQuery leftJoinElectionsParties($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsParties relation
 * @method     ChildElectionsQuery rightJoinElectionsParties($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsParties relation
 * @method     ChildElectionsQuery innerJoinElectionsParties($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsParties relation
 *
 * @method     ChildElectionsQuery joinWithElectionsParties($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsParties relation
 *
 * @method     ChildElectionsQuery leftJoinWithElectionsParties() Adds a LEFT JOIN clause and with to the query using the ElectionsParties relation
 * @method     ChildElectionsQuery rightJoinWithElectionsParties() Adds a RIGHT JOIN clause and with to the query using the ElectionsParties relation
 * @method     ChildElectionsQuery innerJoinWithElectionsParties() Adds a INNER JOIN clause and with to the query using the ElectionsParties relation
 *
 * @method     \AssemblyTypesQuery|\PopulationCensusesQuery|\ElectionsIndependentCandidatesQuery|\ElectionsPartiesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildElections findOne(ConnectionInterface $con = null) Return the first ChildElections matching the query
 * @method     ChildElections findOneOrCreate(ConnectionInterface $con = null) Return the first ChildElections matching the query, or a new ChildElections object populated from the query conditions when no match is found
 *
 * @method     ChildElections findOneById(int $id) Return the first ChildElections filtered by the id column
 * @method     ChildElections findOneBySlug(string $slug) Return the first ChildElections filtered by the slug column
 * @method     ChildElections findOneByAssemblyTypeId(int $assembly_type_id) Return the first ChildElections filtered by the assembly_type_id column
 * @method     ChildElections findOneByPopulationCensusId(int $population_census_id) Return the first ChildElections filtered by the population_census_id column
 * @method     ChildElections findOneByActiveSuffrage(int $active_suffrage) Return the first ChildElections filtered by the active_suffrage column
 * @method     ChildElections findOneByOfficial(boolean $official) Return the first ChildElections filtered by the official column *

 * @method     ChildElections requirePk($key, ConnectionInterface $con = null) Return the ChildElections by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOne(ConnectionInterface $con = null) Return the first ChildElections matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElections requireOneById(int $id) Return the first ChildElections filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOneBySlug(string $slug) Return the first ChildElections filtered by the slug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOneByAssemblyTypeId(int $assembly_type_id) Return the first ChildElections filtered by the assembly_type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOneByPopulationCensusId(int $population_census_id) Return the first ChildElections filtered by the population_census_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOneByActiveSuffrage(int $active_suffrage) Return the first ChildElections filtered by the active_suffrage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildElections requireOneByOfficial(boolean $official) Return the first ChildElections filtered by the official column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildElections[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildElections objects based on current ModelCriteria
 * @method     ChildElections[]|ObjectCollection findById(int $id) Return ChildElections objects filtered by the id column
 * @method     ChildElections[]|ObjectCollection findBySlug(string $slug) Return ChildElections objects filtered by the slug column
 * @method     ChildElections[]|ObjectCollection findByAssemblyTypeId(int $assembly_type_id) Return ChildElections objects filtered by the assembly_type_id column
 * @method     ChildElections[]|ObjectCollection findByPopulationCensusId(int $population_census_id) Return ChildElections objects filtered by the population_census_id column
 * @method     ChildElections[]|ObjectCollection findByActiveSuffrage(int $active_suffrage) Return ChildElections objects filtered by the active_suffrage column
 * @method     ChildElections[]|ObjectCollection findByOfficial(boolean $official) Return ChildElections objects filtered by the official column
 * @method     ChildElections[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ElectionsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ElectionsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Elections', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildElectionsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildElectionsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildElectionsQuery) {
            return $criteria;
        }
        $query = new ChildElectionsQuery();
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
     * @return ChildElections|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ElectionsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ElectionsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildElections A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, slug, assembly_type_id, population_census_id, active_suffrage, official FROM elections WHERE id = :p0';
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
            /** @var ChildElections $obj */
            $obj = new ChildElections();
            $obj->hydrate($row);
            ElectionsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildElections|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ElectionsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ElectionsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_SLUG, $slug, $comparison);
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
     * @see       filterByAssemblyTypes()
     *
     * @param     mixed $assemblyTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByAssemblyTypeId($assemblyTypeId = null, $comparison = null)
    {
        if (is_array($assemblyTypeId)) {
            $useMinMax = false;
            if (isset($assemblyTypeId['min'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($assemblyTypeId['max'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypeId, $comparison);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByPopulationCensusId($populationCensusId = null, $comparison = null)
    {
        if (is_array($populationCensusId)) {
            $useMinMax = false;
            if (isset($populationCensusId['min'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($populationCensusId['max'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $populationCensusId, $comparison);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByActiveSuffrage($activeSuffrage = null, $comparison = null)
    {
        if (is_array($activeSuffrage)) {
            $useMinMax = false;
            if (isset($activeSuffrage['min'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activeSuffrage['max'])) {
                $this->addUsingAlias(ElectionsTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_ACTIVE_SUFFRAGE, $activeSuffrage, $comparison);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByOfficial($official = null, $comparison = null)
    {
        if (is_string($official)) {
            $official = in_array(strtolower($official), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ElectionsTableMap::COL_OFFICIAL, $official, $comparison);
    }

    /**
     * Filter the query by a related \AssemblyTypes object
     *
     * @param \AssemblyTypes|ObjectCollection $assemblyTypes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByAssemblyTypes($assemblyTypes, $comparison = null)
    {
        if ($assemblyTypes instanceof \AssemblyTypes) {
            return $this
                ->addUsingAlias(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypes->getId(), $comparison);
        } elseif ($assemblyTypes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $assemblyTypes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAssemblyTypes() only accepts arguments of type \AssemblyTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssemblyTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function joinAssemblyTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssemblyTypes');

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
            $this->addJoinObject($join, 'AssemblyTypes');
        }

        return $this;
    }

    /**
     * Use the AssemblyTypes relation AssemblyTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \AssemblyTypesQuery A secondary query class using the current class as primary query
     */
    public function useAssemblyTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssemblyTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssemblyTypes', '\AssemblyTypesQuery');
    }

    /**
     * Filter the query by a related \PopulationCensuses object
     *
     * @param \PopulationCensuses|ObjectCollection $populationCensuses The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByPopulationCensuses($populationCensuses, $comparison = null)
    {
        if ($populationCensuses instanceof \PopulationCensuses) {
            return $this
                ->addUsingAlias(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $populationCensuses->getId(), $comparison);
        } elseif ($populationCensuses instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $populationCensuses->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
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
     * Filter the query by a related \ElectionsIndependentCandidates object
     *
     * @param \ElectionsIndependentCandidates|ObjectCollection $electionsIndependentCandidates the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByElectionsIndependentCandidates($electionsIndependentCandidates, $comparison = null)
    {
        if ($electionsIndependentCandidates instanceof \ElectionsIndependentCandidates) {
            return $this
                ->addUsingAlias(ElectionsTableMap::COL_ID, $electionsIndependentCandidates->getElectionId(), $comparison);
        } elseif ($electionsIndependentCandidates instanceof ObjectCollection) {
            return $this
                ->useElectionsIndependentCandidatesQuery()
                ->filterByPrimaryKeys($electionsIndependentCandidates->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByElectionsIndependentCandidates() only accepts arguments of type \ElectionsIndependentCandidates or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ElectionsIndependentCandidates relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function joinElectionsIndependentCandidates($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ElectionsIndependentCandidates');

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
            $this->addJoinObject($join, 'ElectionsIndependentCandidates');
        }

        return $this;
    }

    /**
     * Use the ElectionsIndependentCandidates relation ElectionsIndependentCandidates object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ElectionsIndependentCandidatesQuery A secondary query class using the current class as primary query
     */
    public function useElectionsIndependentCandidatesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinElectionsIndependentCandidates($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ElectionsIndependentCandidates', '\ElectionsIndependentCandidatesQuery');
    }

    /**
     * Filter the query by a related \ElectionsParties object
     *
     * @param \ElectionsParties|ObjectCollection $electionsParties the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildElectionsQuery The current query, for fluid interface
     */
    public function filterByElectionsParties($electionsParties, $comparison = null)
    {
        if ($electionsParties instanceof \ElectionsParties) {
            return $this
                ->addUsingAlias(ElectionsTableMap::COL_ID, $electionsParties->getElectionId(), $comparison);
        } elseif ($electionsParties instanceof ObjectCollection) {
            return $this
                ->useElectionsPartiesQuery()
                ->filterByPrimaryKeys($electionsParties->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildElectionsQuery The current query, for fluid interface
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
     * @param   ChildElections $elections Object to remove from the list of results
     *
     * @return $this|ChildElectionsQuery The current query, for fluid interface
     */
    public function prune($elections = null)
    {
        if ($elections) {
            $this->addUsingAlias(ElectionsTableMap::COL_ID, $elections->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ElectionsTableMap::clearInstancePool();
            ElectionsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ElectionsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ElectionsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ElectionsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ElectionsQuery
