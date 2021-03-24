<?php

namespace Base;

use \Constituency as ChildConstituency;
use \ConstituencyCensus as ChildConstituencyCensus;
use \ConstituencyCensusQuery as ChildConstituencyCensusQuery;
use \ConstituencyQuery as ChildConstituencyQuery;
use \ElectionPartyVote as ChildElectionPartyVote;
use \ElectionPartyVoteQuery as ChildElectionPartyVoteQuery;
use \IndependentCandidate as ChildIndependentCandidate;
use \IndependentCandidateQuery as ChildIndependentCandidateQuery;
use \Exception;
use \PDO;
use Map\ConstituencyCensusTableMap;
use Map\ConstituencyTableMap;
use Map\ElectionPartyVoteTableMap;
use Map\IndependentCandidateTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'constituencies' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Constituency implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ConstituencyTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the coordinates field.
     *
     * @var        string
     */
    protected $coordinates;

    /**
     * @var        ObjectCollection|ChildConstituencyCensus[] Collection to store aggregation of ChildConstituencyCensus objects.
     */
    protected $collConstituencyCensuses;
    protected $collConstituencyCensusesPartial;

    /**
     * @var        ObjectCollection|ChildIndependentCandidate[] Collection to store aggregation of ChildIndependentCandidate objects.
     */
    protected $collIndependentCandidates;
    protected $collIndependentCandidatesPartial;

    /**
     * @var        ObjectCollection|ChildElectionPartyVote[] Collection to store aggregation of ChildElectionPartyVote objects.
     */
    protected $collElectionPartyVotes;
    protected $collElectionPartyVotesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildConstituencyCensus[]
     */
    protected $constituencyCensusesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildIndependentCandidate[]
     */
    protected $independentCandidatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionPartyVote[]
     */
    protected $electionPartyVotesScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Constituency object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Constituency</code> instance.  If
     * <code>obj</code> is an instance of <code>Constituency</code>, delegates to
     * <code>equals(Constituency)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Constituency The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [coordinates] column value.
     *
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ConstituencyTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[ConstituencyTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [coordinates] column.
     *
     * @param string $v new value
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function setCoordinates($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->coordinates !== $v) {
            $this->coordinates = $v;
            $this->modifiedColumns[ConstituencyTableMap::COL_COORDINATES] = true;
        }

        return $this;
    } // setCoordinates()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ConstituencyTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ConstituencyTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ConstituencyTableMap::translateFieldName('Coordinates', TableMap::TYPE_PHPNAME, $indexType)];
            $this->coordinates = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = ConstituencyTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Constituency'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildConstituencyQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collConstituencyCensuses = null;

            $this->collIndependentCandidates = null;

            $this->collElectionPartyVotes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Constituency::setDeleted()
     * @see Constituency::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildConstituencyQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ConstituencyTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->constituencyCensusesScheduledForDeletion !== null) {
                if (!$this->constituencyCensusesScheduledForDeletion->isEmpty()) {
                    \ConstituencyCensusQuery::create()
                        ->filterByPrimaryKeys($this->constituencyCensusesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->constituencyCensusesScheduledForDeletion = null;
                }
            }

            if ($this->collConstituencyCensuses !== null) {
                foreach ($this->collConstituencyCensuses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->independentCandidatesScheduledForDeletion !== null) {
                if (!$this->independentCandidatesScheduledForDeletion->isEmpty()) {
                    \IndependentCandidateQuery::create()
                        ->filterByPrimaryKeys($this->independentCandidatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->independentCandidatesScheduledForDeletion = null;
                }
            }

            if ($this->collIndependentCandidates !== null) {
                foreach ($this->collIndependentCandidates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->electionPartyVotesScheduledForDeletion !== null) {
                if (!$this->electionPartyVotesScheduledForDeletion->isEmpty()) {
                    \ElectionPartyVoteQuery::create()
                        ->filterByPrimaryKeys($this->electionPartyVotesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionPartyVotesScheduledForDeletion = null;
                }
            }

            if ($this->collElectionPartyVotes !== null) {
                foreach ($this->collElectionPartyVotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ConstituencyTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ConstituencyTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ConstituencyTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ConstituencyTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(ConstituencyTableMap::COL_COORDINATES)) {
            $modifiedColumns[':p' . $index++]  = 'coordinates';
        }

        $sql = sprintf(
            'INSERT INTO constituencies (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'title':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'coordinates':
                        $stmt->bindValue($identifier, $this->coordinates, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ConstituencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitle();
                break;
            case 2:
                return $this->getCoordinates();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Constituency'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Constituency'][$this->hashCode()] = true;
        $keys = ConstituencyTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getCoordinates(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collConstituencyCensuses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'constituencyCensuses';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'constituencies_censusess';
                        break;
                    default:
                        $key = 'ConstituencyCensuses';
                }

                $result[$key] = $this->collConstituencyCensuses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIndependentCandidates) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'independentCandidates';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_independent_candidatess';
                        break;
                    default:
                        $key = 'IndependentCandidates';
                }

                $result[$key] = $this->collIndependentCandidates->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collElectionPartyVotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionPartyVotes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_parties_votess';
                        break;
                    default:
                        $key = 'ElectionPartyVotes';
                }

                $result[$key] = $this->collElectionPartyVotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Constituency
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ConstituencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Constituency
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setCoordinates($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ConstituencyTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCoordinates($arr[$keys[2]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Constituency The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ConstituencyTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ConstituencyTableMap::COL_ID)) {
            $criteria->add(ConstituencyTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ConstituencyTableMap::COL_TITLE)) {
            $criteria->add(ConstituencyTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(ConstituencyTableMap::COL_COORDINATES)) {
            $criteria->add(ConstituencyTableMap::COL_COORDINATES, $this->coordinates);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildConstituencyQuery::create();
        $criteria->add(ConstituencyTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Constituency (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCoordinates($this->getCoordinates());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getConstituencyCensuses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addConstituencyCensus($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIndependentCandidates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIndependentCandidate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getElectionPartyVotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionPartyVote($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Constituency Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ConstituencyCensus' == $relationName) {
            $this->initConstituencyCensuses();
            return;
        }
        if ('IndependentCandidate' == $relationName) {
            $this->initIndependentCandidates();
            return;
        }
        if ('ElectionPartyVote' == $relationName) {
            $this->initElectionPartyVotes();
            return;
        }
    }

    /**
     * Clears out the collConstituencyCensuses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addConstituencyCensuses()
     */
    public function clearConstituencyCensuses()
    {
        $this->collConstituencyCensuses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collConstituencyCensuses collection loaded partially.
     */
    public function resetPartialConstituencyCensuses($v = true)
    {
        $this->collConstituencyCensusesPartial = $v;
    }

    /**
     * Initializes the collConstituencyCensuses collection.
     *
     * By default this just sets the collConstituencyCensuses collection to an empty array (like clearcollConstituencyCensuses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initConstituencyCensuses($overrideExisting = true)
    {
        if (null !== $this->collConstituencyCensuses && !$overrideExisting) {
            return;
        }

        $collectionClassName = ConstituencyCensusTableMap::getTableMap()->getCollectionClassName();

        $this->collConstituencyCensuses = new $collectionClassName;
        $this->collConstituencyCensuses->setModel('\ConstituencyCensus');
    }

    /**
     * Gets an array of ChildConstituencyCensus objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildConstituencyCensus[] List of ChildConstituencyCensus objects
     * @throws PropelException
     */
    public function getConstituencyCensuses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collConstituencyCensusesPartial && !$this->isNew();
        if (null === $this->collConstituencyCensuses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collConstituencyCensuses) {
                // return empty collection
                $this->initConstituencyCensuses();
            } else {
                $collConstituencyCensuses = ChildConstituencyCensusQuery::create(null, $criteria)
                    ->filterByConstituency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collConstituencyCensusesPartial && count($collConstituencyCensuses)) {
                        $this->initConstituencyCensuses(false);

                        foreach ($collConstituencyCensuses as $obj) {
                            if (false == $this->collConstituencyCensuses->contains($obj)) {
                                $this->collConstituencyCensuses->append($obj);
                            }
                        }

                        $this->collConstituencyCensusesPartial = true;
                    }

                    return $collConstituencyCensuses;
                }

                if ($partial && $this->collConstituencyCensuses) {
                    foreach ($this->collConstituencyCensuses as $obj) {
                        if ($obj->isNew()) {
                            $collConstituencyCensuses[] = $obj;
                        }
                    }
                }

                $this->collConstituencyCensuses = $collConstituencyCensuses;
                $this->collConstituencyCensusesPartial = false;
            }
        }

        return $this->collConstituencyCensuses;
    }

    /**
     * Sets a collection of ChildConstituencyCensus objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $constituencyCensuses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function setConstituencyCensuses(Collection $constituencyCensuses, ConnectionInterface $con = null)
    {
        /** @var ChildConstituencyCensus[] $constituencyCensusesToDelete */
        $constituencyCensusesToDelete = $this->getConstituencyCensuses(new Criteria(), $con)->diff($constituencyCensuses);


        $this->constituencyCensusesScheduledForDeletion = $constituencyCensusesToDelete;

        foreach ($constituencyCensusesToDelete as $constituencyCensusRemoved) {
            $constituencyCensusRemoved->setConstituency(null);
        }

        $this->collConstituencyCensuses = null;
        foreach ($constituencyCensuses as $constituencyCensus) {
            $this->addConstituencyCensus($constituencyCensus);
        }

        $this->collConstituencyCensuses = $constituencyCensuses;
        $this->collConstituencyCensusesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ConstituencyCensus objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ConstituencyCensus objects.
     * @throws PropelException
     */
    public function countConstituencyCensuses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collConstituencyCensusesPartial && !$this->isNew();
        if (null === $this->collConstituencyCensuses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collConstituencyCensuses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getConstituencyCensuses());
            }

            $query = ChildConstituencyCensusQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituency($this)
                ->count($con);
        }

        return count($this->collConstituencyCensuses);
    }

    /**
     * Method called to associate a ChildConstituencyCensus object to this object
     * through the ChildConstituencyCensus foreign key attribute.
     *
     * @param  ChildConstituencyCensus $l ChildConstituencyCensus
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function addConstituencyCensus(ChildConstituencyCensus $l)
    {
        if ($this->collConstituencyCensuses === null) {
            $this->initConstituencyCensuses();
            $this->collConstituencyCensusesPartial = true;
        }

        if (!$this->collConstituencyCensuses->contains($l)) {
            $this->doAddConstituencyCensus($l);

            if ($this->constituencyCensusesScheduledForDeletion and $this->constituencyCensusesScheduledForDeletion->contains($l)) {
                $this->constituencyCensusesScheduledForDeletion->remove($this->constituencyCensusesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildConstituencyCensus $constituencyCensus The ChildConstituencyCensus object to add.
     */
    protected function doAddConstituencyCensus(ChildConstituencyCensus $constituencyCensus)
    {
        $this->collConstituencyCensuses[]= $constituencyCensus;
        $constituencyCensus->setConstituency($this);
    }

    /**
     * @param  ChildConstituencyCensus $constituencyCensus The ChildConstituencyCensus object to remove.
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function removeConstituencyCensus(ChildConstituencyCensus $constituencyCensus)
    {
        if ($this->getConstituencyCensuses()->contains($constituencyCensus)) {
            $pos = $this->collConstituencyCensuses->search($constituencyCensus);
            $this->collConstituencyCensuses->remove($pos);
            if (null === $this->constituencyCensusesScheduledForDeletion) {
                $this->constituencyCensusesScheduledForDeletion = clone $this->collConstituencyCensuses;
                $this->constituencyCensusesScheduledForDeletion->clear();
            }
            $this->constituencyCensusesScheduledForDeletion[]= clone $constituencyCensus;
            $constituencyCensus->setConstituency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituency is new, it will return
     * an empty collection; or if this Constituency has previously
     * been saved, it will retrieve related ConstituencyCensuses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildConstituencyCensus[] List of ChildConstituencyCensus objects
     */
    public function getConstituencyCensusesJoinPopulationCensus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildConstituencyCensusQuery::create(null, $criteria);
        $query->joinWith('PopulationCensus', $joinBehavior);

        return $this->getConstituencyCensuses($query, $con);
    }

    /**
     * Clears out the collIndependentCandidates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addIndependentCandidates()
     */
    public function clearIndependentCandidates()
    {
        $this->collIndependentCandidates = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collIndependentCandidates collection loaded partially.
     */
    public function resetPartialIndependentCandidates($v = true)
    {
        $this->collIndependentCandidatesPartial = $v;
    }

    /**
     * Initializes the collIndependentCandidates collection.
     *
     * By default this just sets the collIndependentCandidates collection to an empty array (like clearcollIndependentCandidates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIndependentCandidates($overrideExisting = true)
    {
        if (null !== $this->collIndependentCandidates && !$overrideExisting) {
            return;
        }

        $collectionClassName = IndependentCandidateTableMap::getTableMap()->getCollectionClassName();

        $this->collIndependentCandidates = new $collectionClassName;
        $this->collIndependentCandidates->setModel('\IndependentCandidate');
    }

    /**
     * Gets an array of ChildIndependentCandidate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildIndependentCandidate[] List of ChildIndependentCandidate objects
     * @throws PropelException
     */
    public function getIndependentCandidates(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collIndependentCandidatesPartial && !$this->isNew();
        if (null === $this->collIndependentCandidates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIndependentCandidates) {
                // return empty collection
                $this->initIndependentCandidates();
            } else {
                $collIndependentCandidates = ChildIndependentCandidateQuery::create(null, $criteria)
                    ->filterByConstituency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collIndependentCandidatesPartial && count($collIndependentCandidates)) {
                        $this->initIndependentCandidates(false);

                        foreach ($collIndependentCandidates as $obj) {
                            if (false == $this->collIndependentCandidates->contains($obj)) {
                                $this->collIndependentCandidates->append($obj);
                            }
                        }

                        $this->collIndependentCandidatesPartial = true;
                    }

                    return $collIndependentCandidates;
                }

                if ($partial && $this->collIndependentCandidates) {
                    foreach ($this->collIndependentCandidates as $obj) {
                        if ($obj->isNew()) {
                            $collIndependentCandidates[] = $obj;
                        }
                    }
                }

                $this->collIndependentCandidates = $collIndependentCandidates;
                $this->collIndependentCandidatesPartial = false;
            }
        }

        return $this->collIndependentCandidates;
    }

    /**
     * Sets a collection of ChildIndependentCandidate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $independentCandidates A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function setIndependentCandidates(Collection $independentCandidates, ConnectionInterface $con = null)
    {
        /** @var ChildIndependentCandidate[] $independentCandidatesToDelete */
        $independentCandidatesToDelete = $this->getIndependentCandidates(new Criteria(), $con)->diff($independentCandidates);


        $this->independentCandidatesScheduledForDeletion = $independentCandidatesToDelete;

        foreach ($independentCandidatesToDelete as $independentCandidateRemoved) {
            $independentCandidateRemoved->setConstituency(null);
        }

        $this->collIndependentCandidates = null;
        foreach ($independentCandidates as $independentCandidate) {
            $this->addIndependentCandidate($independentCandidate);
        }

        $this->collIndependentCandidates = $independentCandidates;
        $this->collIndependentCandidatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related IndependentCandidate objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related IndependentCandidate objects.
     * @throws PropelException
     */
    public function countIndependentCandidates(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collIndependentCandidatesPartial && !$this->isNew();
        if (null === $this->collIndependentCandidates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIndependentCandidates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIndependentCandidates());
            }

            $query = ChildIndependentCandidateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituency($this)
                ->count($con);
        }

        return count($this->collIndependentCandidates);
    }

    /**
     * Method called to associate a ChildIndependentCandidate object to this object
     * through the ChildIndependentCandidate foreign key attribute.
     *
     * @param  ChildIndependentCandidate $l ChildIndependentCandidate
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function addIndependentCandidate(ChildIndependentCandidate $l)
    {
        if ($this->collIndependentCandidates === null) {
            $this->initIndependentCandidates();
            $this->collIndependentCandidatesPartial = true;
        }

        if (!$this->collIndependentCandidates->contains($l)) {
            $this->doAddIndependentCandidate($l);

            if ($this->independentCandidatesScheduledForDeletion and $this->independentCandidatesScheduledForDeletion->contains($l)) {
                $this->independentCandidatesScheduledForDeletion->remove($this->independentCandidatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildIndependentCandidate $independentCandidate The ChildIndependentCandidate object to add.
     */
    protected function doAddIndependentCandidate(ChildIndependentCandidate $independentCandidate)
    {
        $this->collIndependentCandidates[]= $independentCandidate;
        $independentCandidate->setConstituency($this);
    }

    /**
     * @param  ChildIndependentCandidate $independentCandidate The ChildIndependentCandidate object to remove.
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function removeIndependentCandidate(ChildIndependentCandidate $independentCandidate)
    {
        if ($this->getIndependentCandidates()->contains($independentCandidate)) {
            $pos = $this->collIndependentCandidates->search($independentCandidate);
            $this->collIndependentCandidates->remove($pos);
            if (null === $this->independentCandidatesScheduledForDeletion) {
                $this->independentCandidatesScheduledForDeletion = clone $this->collIndependentCandidates;
                $this->independentCandidatesScheduledForDeletion->clear();
            }
            $this->independentCandidatesScheduledForDeletion[]= clone $independentCandidate;
            $independentCandidate->setConstituency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituency is new, it will return
     * an empty collection; or if this Constituency has previously
     * been saved, it will retrieve related IndependentCandidates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildIndependentCandidate[] List of ChildIndependentCandidate objects
     */
    public function getIndependentCandidatesJoinElection(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildIndependentCandidateQuery::create(null, $criteria);
        $query->joinWith('Election', $joinBehavior);

        return $this->getIndependentCandidates($query, $con);
    }

    /**
     * Clears out the collElectionPartyVotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionPartyVotes()
     */
    public function clearElectionPartyVotes()
    {
        $this->collElectionPartyVotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionPartyVotes collection loaded partially.
     */
    public function resetPartialElectionPartyVotes($v = true)
    {
        $this->collElectionPartyVotesPartial = $v;
    }

    /**
     * Initializes the collElectionPartyVotes collection.
     *
     * By default this just sets the collElectionPartyVotes collection to an empty array (like clearcollElectionPartyVotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionPartyVotes($overrideExisting = true)
    {
        if (null !== $this->collElectionPartyVotes && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionPartyVoteTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionPartyVotes = new $collectionClassName;
        $this->collElectionPartyVotes->setModel('\ElectionPartyVote');
    }

    /**
     * Gets an array of ChildElectionPartyVote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionPartyVote[] List of ChildElectionPartyVote objects
     * @throws PropelException
     */
    public function getElectionPartyVotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionPartyVotesPartial && !$this->isNew();
        if (null === $this->collElectionPartyVotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionPartyVotes) {
                // return empty collection
                $this->initElectionPartyVotes();
            } else {
                $collElectionPartyVotes = ChildElectionPartyVoteQuery::create(null, $criteria)
                    ->filterByConstituency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionPartyVotesPartial && count($collElectionPartyVotes)) {
                        $this->initElectionPartyVotes(false);

                        foreach ($collElectionPartyVotes as $obj) {
                            if (false == $this->collElectionPartyVotes->contains($obj)) {
                                $this->collElectionPartyVotes->append($obj);
                            }
                        }

                        $this->collElectionPartyVotesPartial = true;
                    }

                    return $collElectionPartyVotes;
                }

                if ($partial && $this->collElectionPartyVotes) {
                    foreach ($this->collElectionPartyVotes as $obj) {
                        if ($obj->isNew()) {
                            $collElectionPartyVotes[] = $obj;
                        }
                    }
                }

                $this->collElectionPartyVotes = $collElectionPartyVotes;
                $this->collElectionPartyVotesPartial = false;
            }
        }

        return $this->collElectionPartyVotes;
    }

    /**
     * Sets a collection of ChildElectionPartyVote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionPartyVotes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function setElectionPartyVotes(Collection $electionPartyVotes, ConnectionInterface $con = null)
    {
        /** @var ChildElectionPartyVote[] $electionPartyVotesToDelete */
        $electionPartyVotesToDelete = $this->getElectionPartyVotes(new Criteria(), $con)->diff($electionPartyVotes);


        $this->electionPartyVotesScheduledForDeletion = $electionPartyVotesToDelete;

        foreach ($electionPartyVotesToDelete as $electionPartyVoteRemoved) {
            $electionPartyVoteRemoved->setConstituency(null);
        }

        $this->collElectionPartyVotes = null;
        foreach ($electionPartyVotes as $electionPartyVote) {
            $this->addElectionPartyVote($electionPartyVote);
        }

        $this->collElectionPartyVotes = $electionPartyVotes;
        $this->collElectionPartyVotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionPartyVote objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionPartyVote objects.
     * @throws PropelException
     */
    public function countElectionPartyVotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionPartyVotesPartial && !$this->isNew();
        if (null === $this->collElectionPartyVotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionPartyVotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionPartyVotes());
            }

            $query = ChildElectionPartyVoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituency($this)
                ->count($con);
        }

        return count($this->collElectionPartyVotes);
    }

    /**
     * Method called to associate a ChildElectionPartyVote object to this object
     * through the ChildElectionPartyVote foreign key attribute.
     *
     * @param  ChildElectionPartyVote $l ChildElectionPartyVote
     * @return $this|\Constituency The current object (for fluent API support)
     */
    public function addElectionPartyVote(ChildElectionPartyVote $l)
    {
        if ($this->collElectionPartyVotes === null) {
            $this->initElectionPartyVotes();
            $this->collElectionPartyVotesPartial = true;
        }

        if (!$this->collElectionPartyVotes->contains($l)) {
            $this->doAddElectionPartyVote($l);

            if ($this->electionPartyVotesScheduledForDeletion and $this->electionPartyVotesScheduledForDeletion->contains($l)) {
                $this->electionPartyVotesScheduledForDeletion->remove($this->electionPartyVotesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionPartyVote $electionPartyVote The ChildElectionPartyVote object to add.
     */
    protected function doAddElectionPartyVote(ChildElectionPartyVote $electionPartyVote)
    {
        $this->collElectionPartyVotes[]= $electionPartyVote;
        $electionPartyVote->setConstituency($this);
    }

    /**
     * @param  ChildElectionPartyVote $electionPartyVote The ChildElectionPartyVote object to remove.
     * @return $this|ChildConstituency The current object (for fluent API support)
     */
    public function removeElectionPartyVote(ChildElectionPartyVote $electionPartyVote)
    {
        if ($this->getElectionPartyVotes()->contains($electionPartyVote)) {
            $pos = $this->collElectionPartyVotes->search($electionPartyVote);
            $this->collElectionPartyVotes->remove($pos);
            if (null === $this->electionPartyVotesScheduledForDeletion) {
                $this->electionPartyVotesScheduledForDeletion = clone $this->collElectionPartyVotes;
                $this->electionPartyVotesScheduledForDeletion->clear();
            }
            $this->electionPartyVotesScheduledForDeletion[]= clone $electionPartyVote;
            $electionPartyVote->setConstituency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituency is new, it will return
     * an empty collection; or if this Constituency has previously
     * been saved, it will retrieve related ElectionPartyVotes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionPartyVote[] List of ChildElectionPartyVote objects
     */
    public function getElectionPartyVotesJoinElectionParty(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionPartyVoteQuery::create(null, $criteria);
        $query->joinWith('ElectionParty', $joinBehavior);

        return $this->getElectionPartyVotes($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->coordinates = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collConstituencyCensuses) {
                foreach ($this->collConstituencyCensuses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIndependentCandidates) {
                foreach ($this->collIndependentCandidates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collElectionPartyVotes) {
                foreach ($this->collElectionPartyVotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collConstituencyCensuses = null;
        $this->collIndependentCandidates = null;
        $this->collElectionPartyVotes = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ConstituencyTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
