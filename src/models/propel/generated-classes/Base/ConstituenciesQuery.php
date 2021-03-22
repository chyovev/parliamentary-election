<?php

namespace Base;

use \Constituencies as ChildConstituencies;
use \ConstituenciesQuery as ChildConstituenciesQuery;
use \Exception;
use \PDO;
use Map\ConstituenciesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'constituencies' table.
 *
 *
 *
 * @method     ChildConstituenciesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildConstituenciesQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildConstituenciesQuery orderByCoordinates($order = Criteria::ASC) Order by the coordinates column
 *
 * @method     ChildConstituenciesQuery groupById() Group by the id column
 * @method     ChildConstituenciesQuery groupByTitle() Group by the title column
 * @method     ChildConstituenciesQuery groupByCoordinates() Group by the coordinates column
 *
 * @method     ChildConstituenciesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConstituenciesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConstituenciesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConstituenciesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildConstituenciesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildConstituenciesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildConstituenciesQuery leftJoinConstituenciesCensuses($relationAlias = null) Adds a LEFT JOIN clause to the query using the ConstituenciesCensuses relation
 * @method     ChildConstituenciesQuery rightJoinConstituenciesCensuses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ConstituenciesCensuses relation
 * @method     ChildConstituenciesQuery innerJoinConstituenciesCensuses($relationAlias = null) Adds a INNER JOIN clause to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildConstituenciesQuery joinWithConstituenciesCensuses($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildConstituenciesQuery leftJoinWithConstituenciesCensuses() Adds a LEFT JOIN clause and with to the query using the ConstituenciesCensuses relation
 * @method     ChildConstituenciesQuery rightJoinWithConstituenciesCensuses() Adds a RIGHT JOIN clause and with to the query using the ConstituenciesCensuses relation
 * @method     ChildConstituenciesQuery innerJoinWithConstituenciesCensuses() Adds a INNER JOIN clause and with to the query using the ConstituenciesCensuses relation
 *
 * @method     ChildConstituenciesQuery leftJoinElectionsIndependentCandidates($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsIndependentCandidates relation
 * @method     ChildConstituenciesQuery rightJoinElectionsIndependentCandidates($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsIndependentCandidates relation
 * @method     ChildConstituenciesQuery innerJoinElectionsIndependentCandidates($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildConstituenciesQuery joinWithElectionsIndependentCandidates($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildConstituenciesQuery leftJoinWithElectionsIndependentCandidates() Adds a LEFT JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 * @method     ChildConstituenciesQuery rightJoinWithElectionsIndependentCandidates() Adds a RIGHT JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 * @method     ChildConstituenciesQuery innerJoinWithElectionsIndependentCandidates() Adds a INNER JOIN clause and with to the query using the ElectionsIndependentCandidates relation
 *
 * @method     ChildConstituenciesQuery leftJoinElectionsPartiesVotes($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionsPartiesVotes relation
 * @method     ChildConstituenciesQuery rightJoinElectionsPartiesVotes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionsPartiesVotes relation
 * @method     ChildConstituenciesQuery innerJoinElectionsPartiesVotes($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionsPartiesVotes relation
 *
 * @method     ChildConstituenciesQuery joinWithElectionsPartiesVotes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionsPartiesVotes relation
 *
 * @method     ChildConstituenciesQuery leftJoinWithElectionsPartiesVotes() Adds a LEFT JOIN clause and with to the query using the ElectionsPartiesVotes relation
 * @method     ChildConstituenciesQuery rightJoinWithElectionsPartiesVotes() Adds a RIGHT JOIN clause and with to the query using the ElectionsPartiesVotes relation
 * @method     ChildConstituenciesQuery innerJoinWithElectionsPartiesVotes() Adds a INNER JOIN clause and with to the query using the ElectionsPartiesVotes relation
 *
 * @method     \ConstituenciesCensusesQuery|\ElectionsIndependentCandidatesQuery|\ElectionsPartiesVotesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildConstituencies findOne(ConnectionInterface $con = null) Return the first ChildConstituencies matching the query
 * @method     ChildConstituencies findOneOrCreate(ConnectionInterface $con = null) Return the first ChildConstituencies matching the query, or a new ChildConstituencies object populated from the query conditions when no match is found
 *
 * @method     ChildConstituencies findOneById(int $id) Return the first ChildConstituencies filtered by the id column
 * @method     ChildConstituencies findOneByTitle(string $title) Return the first ChildConstituencies filtered by the title column
 * @method     ChildConstituencies findOneByCoordinates(string $coordinates) Return the first ChildConstituencies filtered by the coordinates column *

 * @method     ChildConstituencies requirePk($key, ConnectionInterface $con = null) Return the ChildConstituencies by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituencies requireOne(ConnectionInterface $con = null) Return the first ChildConstituencies matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituencies requireOneById(int $id) Return the first ChildConstituencies filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituencies requireOneByTitle(string $title) Return the first ChildConstituencies filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituencies requireOneByCoordinates(string $coordinates) Return the first ChildConstituencies filtered by the coordinates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituencies[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildConstituencies objects based on current ModelCriteria
 * @method     ChildConstituencies[]|ObjectCollection findById(int $id) Return ChildConstituencies objects filtered by the id column
 * @method     ChildConstituencies[]|ObjectCollection findByTitle(string $title) Return ChildConstituencies objects filtered by the title column
 * @method     ChildConstituencies[]|ObjectCollection findByCoordinates(string $coordinates) Return ChildConstituencies objects filtered by the coordinates column
 * @method     ChildConstituencies[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ConstituenciesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ConstituenciesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Constituencies', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConstituenciesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConstituenciesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildConstituenciesQuery) {
            return $criteria;
        }
        $query = new ChildConstituenciesQuery();
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
     * @return ChildConstituencies|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConstituenciesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ConstituenciesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildConstituencies A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, coordinates FROM constituencies WHERE id = :p0';
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
            /** @var ChildConstituencies $obj */
            $obj = new ChildConstituencies();
            $obj->hydrate($row);
            ConstituenciesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildConstituencies|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the coordinates column
     *
     * Example usage:
     * <code>
     * $query->filterByCoordinates('fooValue');   // WHERE coordinates = 'fooValue'
     * $query->filterByCoordinates('%fooValue%', Criteria::LIKE); // WHERE coordinates LIKE '%fooValue%'
     * </code>
     *
     * @param     string $coordinates The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByCoordinates($coordinates = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($coordinates)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituenciesTableMap::COL_COORDINATES, $coordinates, $comparison);
    }

    /**
     * Filter the query by a related \ConstituenciesCensuses object
     *
     * @param \ConstituenciesCensuses|ObjectCollection $constituenciesCensuses the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByConstituenciesCensuses($constituenciesCensuses, $comparison = null)
    {
        if ($constituenciesCensuses instanceof \ConstituenciesCensuses) {
            return $this
                ->addUsingAlias(ConstituenciesTableMap::COL_ID, $constituenciesCensuses->getConstituencyId(), $comparison);
        } elseif ($constituenciesCensuses instanceof ObjectCollection) {
            return $this
                ->useConstituenciesCensusesQuery()
                ->filterByPrimaryKeys($constituenciesCensuses->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByConstituenciesCensuses() only accepts arguments of type \ConstituenciesCensuses or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ConstituenciesCensuses relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function joinConstituenciesCensuses($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ConstituenciesCensuses');

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
            $this->addJoinObject($join, 'ConstituenciesCensuses');
        }

        return $this;
    }

    /**
     * Use the ConstituenciesCensuses relation ConstituenciesCensuses object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ConstituenciesCensusesQuery A secondary query class using the current class as primary query
     */
    public function useConstituenciesCensusesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConstituenciesCensuses($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ConstituenciesCensuses', '\ConstituenciesCensusesQuery');
    }

    /**
     * Filter the query by a related \ElectionsIndependentCandidates object
     *
     * @param \ElectionsIndependentCandidates|ObjectCollection $electionsIndependentCandidates the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByElectionsIndependentCandidates($electionsIndependentCandidates, $comparison = null)
    {
        if ($electionsIndependentCandidates instanceof \ElectionsIndependentCandidates) {
            return $this
                ->addUsingAlias(ConstituenciesTableMap::COL_ID, $electionsIndependentCandidates->getConstituencyId(), $comparison);
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
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
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
     * Filter the query by a related \ElectionsPartiesVotes object
     *
     * @param \ElectionsPartiesVotes|ObjectCollection $electionsPartiesVotes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituenciesQuery The current query, for fluid interface
     */
    public function filterByElectionsPartiesVotes($electionsPartiesVotes, $comparison = null)
    {
        if ($electionsPartiesVotes instanceof \ElectionsPartiesVotes) {
            return $this
                ->addUsingAlias(ConstituenciesTableMap::COL_ID, $electionsPartiesVotes->getConstituencyId(), $comparison);
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
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
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
     * @param   ChildConstituencies $constituencies Object to remove from the list of results
     *
     * @return $this|ChildConstituenciesQuery The current query, for fluid interface
     */
    public function prune($constituencies = null)
    {
        if ($constituencies) {
            $this->addUsingAlias(ConstituenciesTableMap::COL_ID, $constituencies->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the constituencies table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConstituenciesTableMap::clearInstancePool();
            ConstituenciesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConstituenciesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConstituenciesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConstituenciesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ConstituenciesQuery
