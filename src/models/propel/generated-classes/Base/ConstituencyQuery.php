<?php

namespace Base;

use \Constituency as ChildConstituency;
use \ConstituencyQuery as ChildConstituencyQuery;
use \Exception;
use \PDO;
use Map\ConstituencyTableMap;
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
 * @method     ChildConstituencyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildConstituencyQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildConstituencyQuery orderByCoordinates($order = Criteria::ASC) Order by the coordinates column
 *
 * @method     ChildConstituencyQuery groupById() Group by the id column
 * @method     ChildConstituencyQuery groupByTitle() Group by the title column
 * @method     ChildConstituencyQuery groupByCoordinates() Group by the coordinates column
 *
 * @method     ChildConstituencyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConstituencyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConstituencyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConstituencyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildConstituencyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildConstituencyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildConstituencyQuery leftJoinConstituencyCensus($relationAlias = null) Adds a LEFT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildConstituencyQuery rightJoinConstituencyCensus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ConstituencyCensus relation
 * @method     ChildConstituencyQuery innerJoinConstituencyCensus($relationAlias = null) Adds a INNER JOIN clause to the query using the ConstituencyCensus relation
 *
 * @method     ChildConstituencyQuery joinWithConstituencyCensus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ConstituencyCensus relation
 *
 * @method     ChildConstituencyQuery leftJoinWithConstituencyCensus() Adds a LEFT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildConstituencyQuery rightJoinWithConstituencyCensus() Adds a RIGHT JOIN clause and with to the query using the ConstituencyCensus relation
 * @method     ChildConstituencyQuery innerJoinWithConstituencyCensus() Adds a INNER JOIN clause and with to the query using the ConstituencyCensus relation
 *
 * @method     ChildConstituencyQuery leftJoinIndependentCandidate($relationAlias = null) Adds a LEFT JOIN clause to the query using the IndependentCandidate relation
 * @method     ChildConstituencyQuery rightJoinIndependentCandidate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IndependentCandidate relation
 * @method     ChildConstituencyQuery innerJoinIndependentCandidate($relationAlias = null) Adds a INNER JOIN clause to the query using the IndependentCandidate relation
 *
 * @method     ChildConstituencyQuery joinWithIndependentCandidate($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the IndependentCandidate relation
 *
 * @method     ChildConstituencyQuery leftJoinWithIndependentCandidate() Adds a LEFT JOIN clause and with to the query using the IndependentCandidate relation
 * @method     ChildConstituencyQuery rightJoinWithIndependentCandidate() Adds a RIGHT JOIN clause and with to the query using the IndependentCandidate relation
 * @method     ChildConstituencyQuery innerJoinWithIndependentCandidate() Adds a INNER JOIN clause and with to the query using the IndependentCandidate relation
 *
 * @method     ChildConstituencyQuery leftJoinElectionPartyVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the ElectionPartyVote relation
 * @method     ChildConstituencyQuery rightJoinElectionPartyVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ElectionPartyVote relation
 * @method     ChildConstituencyQuery innerJoinElectionPartyVote($relationAlias = null) Adds a INNER JOIN clause to the query using the ElectionPartyVote relation
 *
 * @method     ChildConstituencyQuery joinWithElectionPartyVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ElectionPartyVote relation
 *
 * @method     ChildConstituencyQuery leftJoinWithElectionPartyVote() Adds a LEFT JOIN clause and with to the query using the ElectionPartyVote relation
 * @method     ChildConstituencyQuery rightJoinWithElectionPartyVote() Adds a RIGHT JOIN clause and with to the query using the ElectionPartyVote relation
 * @method     ChildConstituencyQuery innerJoinWithElectionPartyVote() Adds a INNER JOIN clause and with to the query using the ElectionPartyVote relation
 *
 * @method     \ConstituencyCensusQuery|\IndependentCandidateQuery|\ElectionPartyVoteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildConstituency findOne(ConnectionInterface $con = null) Return the first ChildConstituency matching the query
 * @method     ChildConstituency findOneOrCreate(ConnectionInterface $con = null) Return the first ChildConstituency matching the query, or a new ChildConstituency object populated from the query conditions when no match is found
 *
 * @method     ChildConstituency findOneById(int $id) Return the first ChildConstituency filtered by the id column
 * @method     ChildConstituency findOneByTitle(string $title) Return the first ChildConstituency filtered by the title column
 * @method     ChildConstituency findOneByCoordinates(string $coordinates) Return the first ChildConstituency filtered by the coordinates column *

 * @method     ChildConstituency requirePk($key, ConnectionInterface $con = null) Return the ChildConstituency by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituency requireOne(ConnectionInterface $con = null) Return the first ChildConstituency matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituency requireOneById(int $id) Return the first ChildConstituency filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituency requireOneByTitle(string $title) Return the first ChildConstituency filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConstituency requireOneByCoordinates(string $coordinates) Return the first ChildConstituency filtered by the coordinates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConstituency[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildConstituency objects based on current ModelCriteria
 * @method     ChildConstituency[]|ObjectCollection findById(int $id) Return ChildConstituency objects filtered by the id column
 * @method     ChildConstituency[]|ObjectCollection findByTitle(string $title) Return ChildConstituency objects filtered by the title column
 * @method     ChildConstituency[]|ObjectCollection findByCoordinates(string $coordinates) Return ChildConstituency objects filtered by the coordinates column
 * @method     ChildConstituency[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ConstituencyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ConstituencyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Constituency', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConstituencyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConstituencyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildConstituencyQuery) {
            return $criteria;
        }
        $query = new ChildConstituencyQuery();
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
     * @return ChildConstituency|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ConstituencyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildConstituency A model object, or null if the key is not found
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
            /** @var ChildConstituency $obj */
            $obj = new ChildConstituency();
            $obj->hydrate($row);
            ConstituencyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildConstituency|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ConstituencyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ConstituencyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ConstituencyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ConstituencyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituencyTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituencyTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByCoordinates($coordinates = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($coordinates)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConstituencyTableMap::COL_COORDINATES, $coordinates, $comparison);
    }

    /**
     * Filter the query by a related \ConstituencyCensus object
     *
     * @param \ConstituencyCensus|ObjectCollection $constituencyCensus the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByConstituencyCensus($constituencyCensus, $comparison = null)
    {
        if ($constituencyCensus instanceof \ConstituencyCensus) {
            return $this
                ->addUsingAlias(ConstituencyTableMap::COL_ID, $constituencyCensus->getConstituencyId(), $comparison);
        } elseif ($constituencyCensus instanceof ObjectCollection) {
            return $this
                ->useConstituencyCensusQuery()
                ->filterByPrimaryKeys($constituencyCensus->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
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
     * Filter the query by a related \IndependentCandidate object
     *
     * @param \IndependentCandidate|ObjectCollection $independentCandidate the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByIndependentCandidate($independentCandidate, $comparison = null)
    {
        if ($independentCandidate instanceof \IndependentCandidate) {
            return $this
                ->addUsingAlias(ConstituencyTableMap::COL_ID, $independentCandidate->getConstituencyId(), $comparison);
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
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
     * Filter the query by a related \ElectionPartyVote object
     *
     * @param \ElectionPartyVote|ObjectCollection $electionPartyVote the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildConstituencyQuery The current query, for fluid interface
     */
    public function filterByElectionPartyVote($electionPartyVote, $comparison = null)
    {
        if ($electionPartyVote instanceof \ElectionPartyVote) {
            return $this
                ->addUsingAlias(ConstituencyTableMap::COL_ID, $electionPartyVote->getConstituencyId(), $comparison);
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
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
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
     * @param   ChildConstituency $constituency Object to remove from the list of results
     *
     * @return $this|ChildConstituencyQuery The current query, for fluid interface
     */
    public function prune($constituency = null)
    {
        if ($constituency) {
            $this->addUsingAlias(ConstituencyTableMap::COL_ID, $constituency->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConstituencyTableMap::clearInstancePool();
            ConstituencyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConstituencyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConstituencyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConstituencyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ConstituencyQuery
