<?php

namespace Base;

use \Constituencies as ChildConstituencies;
use \ConstituenciesCensuses as ChildConstituenciesCensuses;
use \ConstituenciesCensusesQuery as ChildConstituenciesCensusesQuery;
use \ConstituenciesQuery as ChildConstituenciesQuery;
use \ElectionsIndependentCandidates as ChildElectionsIndependentCandidates;
use \ElectionsIndependentCandidatesQuery as ChildElectionsIndependentCandidatesQuery;
use \ElectionsPartiesVotes as ChildElectionsPartiesVotes;
use \ElectionsPartiesVotesQuery as ChildElectionsPartiesVotesQuery;
use \Exception;
use \PDO;
use Map\ConstituenciesCensusesTableMap;
use Map\ConstituenciesTableMap;
use Map\ElectionsIndependentCandidatesTableMap;
use Map\ElectionsPartiesVotesTableMap;
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
abstract class Constituencies implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ConstituenciesTableMap';


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
     * @var        ObjectCollection|ChildConstituenciesCensuses[] Collection to store aggregation of ChildConstituenciesCensuses objects.
     */
    protected $collConstituenciesCensusess;
    protected $collConstituenciesCensusessPartial;

    /**
     * @var        ObjectCollection|ChildElectionsIndependentCandidates[] Collection to store aggregation of ChildElectionsIndependentCandidates objects.
     */
    protected $collElectionsIndependentCandidatess;
    protected $collElectionsIndependentCandidatessPartial;

    /**
     * @var        ObjectCollection|ChildElectionsPartiesVotes[] Collection to store aggregation of ChildElectionsPartiesVotes objects.
     */
    protected $collElectionsPartiesVotess;
    protected $collElectionsPartiesVotessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildConstituenciesCensuses[]
     */
    protected $constituenciesCensusessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionsIndependentCandidates[]
     */
    protected $electionsIndependentCandidatessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionsPartiesVotes[]
     */
    protected $electionsPartiesVotessScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Constituencies object.
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
     * Compares this with another <code>Constituencies</code> instance.  If
     * <code>obj</code> is an instance of <code>Constituencies</code>, delegates to
     * <code>equals(Constituencies)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Constituencies The current object, for fluid interface
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
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ConstituenciesTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[ConstituenciesTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [coordinates] column.
     *
     * @param string $v new value
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function setCoordinates($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->coordinates !== $v) {
            $this->coordinates = $v;
            $this->modifiedColumns[ConstituenciesTableMap::COL_COORDINATES] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ConstituenciesTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ConstituenciesTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ConstituenciesTableMap::translateFieldName('Coordinates', TableMap::TYPE_PHPNAME, $indexType)];
            $this->coordinates = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = ConstituenciesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Constituencies'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ConstituenciesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildConstituenciesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collConstituenciesCensusess = null;

            $this->collElectionsIndependentCandidatess = null;

            $this->collElectionsPartiesVotess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Constituencies::setDeleted()
     * @see Constituencies::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildConstituenciesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituenciesTableMap::DATABASE_NAME);
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
                ConstituenciesTableMap::addInstanceToPool($this);
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

            if ($this->constituenciesCensusessScheduledForDeletion !== null) {
                if (!$this->constituenciesCensusessScheduledForDeletion->isEmpty()) {
                    \ConstituenciesCensusesQuery::create()
                        ->filterByPrimaryKeys($this->constituenciesCensusessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->constituenciesCensusessScheduledForDeletion = null;
                }
            }

            if ($this->collConstituenciesCensusess !== null) {
                foreach ($this->collConstituenciesCensusess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->electionsIndependentCandidatessScheduledForDeletion !== null) {
                if (!$this->electionsIndependentCandidatessScheduledForDeletion->isEmpty()) {
                    \ElectionsIndependentCandidatesQuery::create()
                        ->filterByPrimaryKeys($this->electionsIndependentCandidatessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionsIndependentCandidatessScheduledForDeletion = null;
                }
            }

            if ($this->collElectionsIndependentCandidatess !== null) {
                foreach ($this->collElectionsIndependentCandidatess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->electionsPartiesVotessScheduledForDeletion !== null) {
                if (!$this->electionsPartiesVotessScheduledForDeletion->isEmpty()) {
                    \ElectionsPartiesVotesQuery::create()
                        ->filterByPrimaryKeys($this->electionsPartiesVotessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionsPartiesVotessScheduledForDeletion = null;
                }
            }

            if ($this->collElectionsPartiesVotess !== null) {
                foreach ($this->collElectionsPartiesVotess as $referrerFK) {
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

        $this->modifiedColumns[ConstituenciesTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ConstituenciesTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ConstituenciesTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ConstituenciesTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(ConstituenciesTableMap::COL_COORDINATES)) {
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
        $pos = ConstituenciesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Constituencies'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Constituencies'][$this->hashCode()] = true;
        $keys = ConstituenciesTableMap::getFieldNames($keyType);
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
            if (null !== $this->collConstituenciesCensusess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'constituenciesCensusess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'constituencies_censusess';
                        break;
                    default:
                        $key = 'ConstituenciesCensusess';
                }

                $result[$key] = $this->collConstituenciesCensusess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collElectionsIndependentCandidatess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionsIndependentCandidatess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_independent_candidatess';
                        break;
                    default:
                        $key = 'ElectionsIndependentCandidatess';
                }

                $result[$key] = $this->collElectionsIndependentCandidatess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collElectionsPartiesVotess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionsPartiesVotess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_parties_votess';
                        break;
                    default:
                        $key = 'ElectionsPartiesVotess';
                }

                $result[$key] = $this->collElectionsPartiesVotess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Constituencies
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ConstituenciesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Constituencies
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
        $keys = ConstituenciesTableMap::getFieldNames($keyType);

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
     * @return $this|\Constituencies The current object, for fluid interface
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
        $criteria = new Criteria(ConstituenciesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ConstituenciesTableMap::COL_ID)) {
            $criteria->add(ConstituenciesTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ConstituenciesTableMap::COL_TITLE)) {
            $criteria->add(ConstituenciesTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(ConstituenciesTableMap::COL_COORDINATES)) {
            $criteria->add(ConstituenciesTableMap::COL_COORDINATES, $this->coordinates);
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
        $criteria = ChildConstituenciesQuery::create();
        $criteria->add(ConstituenciesTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Constituencies (or compatible) type.
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

            foreach ($this->getConstituenciesCensusess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addConstituenciesCensuses($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getElectionsIndependentCandidatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionsIndependentCandidates($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getElectionsPartiesVotess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionsPartiesVotes($relObj->copy($deepCopy));
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
     * @return \Constituencies Clone of current object.
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
        if ('ConstituenciesCensuses' == $relationName) {
            $this->initConstituenciesCensusess();
            return;
        }
        if ('ElectionsIndependentCandidates' == $relationName) {
            $this->initElectionsIndependentCandidatess();
            return;
        }
        if ('ElectionsPartiesVotes' == $relationName) {
            $this->initElectionsPartiesVotess();
            return;
        }
    }

    /**
     * Clears out the collConstituenciesCensusess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addConstituenciesCensusess()
     */
    public function clearConstituenciesCensusess()
    {
        $this->collConstituenciesCensusess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collConstituenciesCensusess collection loaded partially.
     */
    public function resetPartialConstituenciesCensusess($v = true)
    {
        $this->collConstituenciesCensusessPartial = $v;
    }

    /**
     * Initializes the collConstituenciesCensusess collection.
     *
     * By default this just sets the collConstituenciesCensusess collection to an empty array (like clearcollConstituenciesCensusess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initConstituenciesCensusess($overrideExisting = true)
    {
        if (null !== $this->collConstituenciesCensusess && !$overrideExisting) {
            return;
        }

        $collectionClassName = ConstituenciesCensusesTableMap::getTableMap()->getCollectionClassName();

        $this->collConstituenciesCensusess = new $collectionClassName;
        $this->collConstituenciesCensusess->setModel('\ConstituenciesCensuses');
    }

    /**
     * Gets an array of ChildConstituenciesCensuses objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituencies is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildConstituenciesCensuses[] List of ChildConstituenciesCensuses objects
     * @throws PropelException
     */
    public function getConstituenciesCensusess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collConstituenciesCensusessPartial && !$this->isNew();
        if (null === $this->collConstituenciesCensusess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collConstituenciesCensusess) {
                // return empty collection
                $this->initConstituenciesCensusess();
            } else {
                $collConstituenciesCensusess = ChildConstituenciesCensusesQuery::create(null, $criteria)
                    ->filterByConstituencies($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collConstituenciesCensusessPartial && count($collConstituenciesCensusess)) {
                        $this->initConstituenciesCensusess(false);

                        foreach ($collConstituenciesCensusess as $obj) {
                            if (false == $this->collConstituenciesCensusess->contains($obj)) {
                                $this->collConstituenciesCensusess->append($obj);
                            }
                        }

                        $this->collConstituenciesCensusessPartial = true;
                    }

                    return $collConstituenciesCensusess;
                }

                if ($partial && $this->collConstituenciesCensusess) {
                    foreach ($this->collConstituenciesCensusess as $obj) {
                        if ($obj->isNew()) {
                            $collConstituenciesCensusess[] = $obj;
                        }
                    }
                }

                $this->collConstituenciesCensusess = $collConstituenciesCensusess;
                $this->collConstituenciesCensusessPartial = false;
            }
        }

        return $this->collConstituenciesCensusess;
    }

    /**
     * Sets a collection of ChildConstituenciesCensuses objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $constituenciesCensusess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function setConstituenciesCensusess(Collection $constituenciesCensusess, ConnectionInterface $con = null)
    {
        /** @var ChildConstituenciesCensuses[] $constituenciesCensusessToDelete */
        $constituenciesCensusessToDelete = $this->getConstituenciesCensusess(new Criteria(), $con)->diff($constituenciesCensusess);


        $this->constituenciesCensusessScheduledForDeletion = $constituenciesCensusessToDelete;

        foreach ($constituenciesCensusessToDelete as $constituenciesCensusesRemoved) {
            $constituenciesCensusesRemoved->setConstituencies(null);
        }

        $this->collConstituenciesCensusess = null;
        foreach ($constituenciesCensusess as $constituenciesCensuses) {
            $this->addConstituenciesCensuses($constituenciesCensuses);
        }

        $this->collConstituenciesCensusess = $constituenciesCensusess;
        $this->collConstituenciesCensusessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ConstituenciesCensuses objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ConstituenciesCensuses objects.
     * @throws PropelException
     */
    public function countConstituenciesCensusess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collConstituenciesCensusessPartial && !$this->isNew();
        if (null === $this->collConstituenciesCensusess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collConstituenciesCensusess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getConstituenciesCensusess());
            }

            $query = ChildConstituenciesCensusesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituencies($this)
                ->count($con);
        }

        return count($this->collConstituenciesCensusess);
    }

    /**
     * Method called to associate a ChildConstituenciesCensuses object to this object
     * through the ChildConstituenciesCensuses foreign key attribute.
     *
     * @param  ChildConstituenciesCensuses $l ChildConstituenciesCensuses
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function addConstituenciesCensuses(ChildConstituenciesCensuses $l)
    {
        if ($this->collConstituenciesCensusess === null) {
            $this->initConstituenciesCensusess();
            $this->collConstituenciesCensusessPartial = true;
        }

        if (!$this->collConstituenciesCensusess->contains($l)) {
            $this->doAddConstituenciesCensuses($l);

            if ($this->constituenciesCensusessScheduledForDeletion and $this->constituenciesCensusessScheduledForDeletion->contains($l)) {
                $this->constituenciesCensusessScheduledForDeletion->remove($this->constituenciesCensusessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildConstituenciesCensuses $constituenciesCensuses The ChildConstituenciesCensuses object to add.
     */
    protected function doAddConstituenciesCensuses(ChildConstituenciesCensuses $constituenciesCensuses)
    {
        $this->collConstituenciesCensusess[]= $constituenciesCensuses;
        $constituenciesCensuses->setConstituencies($this);
    }

    /**
     * @param  ChildConstituenciesCensuses $constituenciesCensuses The ChildConstituenciesCensuses object to remove.
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function removeConstituenciesCensuses(ChildConstituenciesCensuses $constituenciesCensuses)
    {
        if ($this->getConstituenciesCensusess()->contains($constituenciesCensuses)) {
            $pos = $this->collConstituenciesCensusess->search($constituenciesCensuses);
            $this->collConstituenciesCensusess->remove($pos);
            if (null === $this->constituenciesCensusessScheduledForDeletion) {
                $this->constituenciesCensusessScheduledForDeletion = clone $this->collConstituenciesCensusess;
                $this->constituenciesCensusessScheduledForDeletion->clear();
            }
            $this->constituenciesCensusessScheduledForDeletion[]= clone $constituenciesCensuses;
            $constituenciesCensuses->setConstituencies(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituencies is new, it will return
     * an empty collection; or if this Constituencies has previously
     * been saved, it will retrieve related ConstituenciesCensusess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituencies.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildConstituenciesCensuses[] List of ChildConstituenciesCensuses objects
     */
    public function getConstituenciesCensusessJoinPopulationCensuses(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildConstituenciesCensusesQuery::create(null, $criteria);
        $query->joinWith('PopulationCensuses', $joinBehavior);

        return $this->getConstituenciesCensusess($query, $con);
    }

    /**
     * Clears out the collElectionsIndependentCandidatess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionsIndependentCandidatess()
     */
    public function clearElectionsIndependentCandidatess()
    {
        $this->collElectionsIndependentCandidatess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionsIndependentCandidatess collection loaded partially.
     */
    public function resetPartialElectionsIndependentCandidatess($v = true)
    {
        $this->collElectionsIndependentCandidatessPartial = $v;
    }

    /**
     * Initializes the collElectionsIndependentCandidatess collection.
     *
     * By default this just sets the collElectionsIndependentCandidatess collection to an empty array (like clearcollElectionsIndependentCandidatess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionsIndependentCandidatess($overrideExisting = true)
    {
        if (null !== $this->collElectionsIndependentCandidatess && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionsIndependentCandidatesTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionsIndependentCandidatess = new $collectionClassName;
        $this->collElectionsIndependentCandidatess->setModel('\ElectionsIndependentCandidates');
    }

    /**
     * Gets an array of ChildElectionsIndependentCandidates objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituencies is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionsIndependentCandidates[] List of ChildElectionsIndependentCandidates objects
     * @throws PropelException
     */
    public function getElectionsIndependentCandidatess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsIndependentCandidatessPartial && !$this->isNew();
        if (null === $this->collElectionsIndependentCandidatess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionsIndependentCandidatess) {
                // return empty collection
                $this->initElectionsIndependentCandidatess();
            } else {
                $collElectionsIndependentCandidatess = ChildElectionsIndependentCandidatesQuery::create(null, $criteria)
                    ->filterByConstituencies($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionsIndependentCandidatessPartial && count($collElectionsIndependentCandidatess)) {
                        $this->initElectionsIndependentCandidatess(false);

                        foreach ($collElectionsIndependentCandidatess as $obj) {
                            if (false == $this->collElectionsIndependentCandidatess->contains($obj)) {
                                $this->collElectionsIndependentCandidatess->append($obj);
                            }
                        }

                        $this->collElectionsIndependentCandidatessPartial = true;
                    }

                    return $collElectionsIndependentCandidatess;
                }

                if ($partial && $this->collElectionsIndependentCandidatess) {
                    foreach ($this->collElectionsIndependentCandidatess as $obj) {
                        if ($obj->isNew()) {
                            $collElectionsIndependentCandidatess[] = $obj;
                        }
                    }
                }

                $this->collElectionsIndependentCandidatess = $collElectionsIndependentCandidatess;
                $this->collElectionsIndependentCandidatessPartial = false;
            }
        }

        return $this->collElectionsIndependentCandidatess;
    }

    /**
     * Sets a collection of ChildElectionsIndependentCandidates objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionsIndependentCandidatess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function setElectionsIndependentCandidatess(Collection $electionsIndependentCandidatess, ConnectionInterface $con = null)
    {
        /** @var ChildElectionsIndependentCandidates[] $electionsIndependentCandidatessToDelete */
        $electionsIndependentCandidatessToDelete = $this->getElectionsIndependentCandidatess(new Criteria(), $con)->diff($electionsIndependentCandidatess);


        $this->electionsIndependentCandidatessScheduledForDeletion = $electionsIndependentCandidatessToDelete;

        foreach ($electionsIndependentCandidatessToDelete as $electionsIndependentCandidatesRemoved) {
            $electionsIndependentCandidatesRemoved->setConstituencies(null);
        }

        $this->collElectionsIndependentCandidatess = null;
        foreach ($electionsIndependentCandidatess as $electionsIndependentCandidates) {
            $this->addElectionsIndependentCandidates($electionsIndependentCandidates);
        }

        $this->collElectionsIndependentCandidatess = $electionsIndependentCandidatess;
        $this->collElectionsIndependentCandidatessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionsIndependentCandidates objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionsIndependentCandidates objects.
     * @throws PropelException
     */
    public function countElectionsIndependentCandidatess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsIndependentCandidatessPartial && !$this->isNew();
        if (null === $this->collElectionsIndependentCandidatess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionsIndependentCandidatess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionsIndependentCandidatess());
            }

            $query = ChildElectionsIndependentCandidatesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituencies($this)
                ->count($con);
        }

        return count($this->collElectionsIndependentCandidatess);
    }

    /**
     * Method called to associate a ChildElectionsIndependentCandidates object to this object
     * through the ChildElectionsIndependentCandidates foreign key attribute.
     *
     * @param  ChildElectionsIndependentCandidates $l ChildElectionsIndependentCandidates
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function addElectionsIndependentCandidates(ChildElectionsIndependentCandidates $l)
    {
        if ($this->collElectionsIndependentCandidatess === null) {
            $this->initElectionsIndependentCandidatess();
            $this->collElectionsIndependentCandidatessPartial = true;
        }

        if (!$this->collElectionsIndependentCandidatess->contains($l)) {
            $this->doAddElectionsIndependentCandidates($l);

            if ($this->electionsIndependentCandidatessScheduledForDeletion and $this->electionsIndependentCandidatessScheduledForDeletion->contains($l)) {
                $this->electionsIndependentCandidatessScheduledForDeletion->remove($this->electionsIndependentCandidatessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionsIndependentCandidates $electionsIndependentCandidates The ChildElectionsIndependentCandidates object to add.
     */
    protected function doAddElectionsIndependentCandidates(ChildElectionsIndependentCandidates $electionsIndependentCandidates)
    {
        $this->collElectionsIndependentCandidatess[]= $electionsIndependentCandidates;
        $electionsIndependentCandidates->setConstituencies($this);
    }

    /**
     * @param  ChildElectionsIndependentCandidates $electionsIndependentCandidates The ChildElectionsIndependentCandidates object to remove.
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function removeElectionsIndependentCandidates(ChildElectionsIndependentCandidates $electionsIndependentCandidates)
    {
        if ($this->getElectionsIndependentCandidatess()->contains($electionsIndependentCandidates)) {
            $pos = $this->collElectionsIndependentCandidatess->search($electionsIndependentCandidates);
            $this->collElectionsIndependentCandidatess->remove($pos);
            if (null === $this->electionsIndependentCandidatessScheduledForDeletion) {
                $this->electionsIndependentCandidatessScheduledForDeletion = clone $this->collElectionsIndependentCandidatess;
                $this->electionsIndependentCandidatessScheduledForDeletion->clear();
            }
            $this->electionsIndependentCandidatessScheduledForDeletion[]= clone $electionsIndependentCandidates;
            $electionsIndependentCandidates->setConstituencies(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituencies is new, it will return
     * an empty collection; or if this Constituencies has previously
     * been saved, it will retrieve related ElectionsIndependentCandidatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituencies.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionsIndependentCandidates[] List of ChildElectionsIndependentCandidates objects
     */
    public function getElectionsIndependentCandidatessJoinElections(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionsIndependentCandidatesQuery::create(null, $criteria);
        $query->joinWith('Elections', $joinBehavior);

        return $this->getElectionsIndependentCandidatess($query, $con);
    }

    /**
     * Clears out the collElectionsPartiesVotess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionsPartiesVotess()
     */
    public function clearElectionsPartiesVotess()
    {
        $this->collElectionsPartiesVotess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionsPartiesVotess collection loaded partially.
     */
    public function resetPartialElectionsPartiesVotess($v = true)
    {
        $this->collElectionsPartiesVotessPartial = $v;
    }

    /**
     * Initializes the collElectionsPartiesVotess collection.
     *
     * By default this just sets the collElectionsPartiesVotess collection to an empty array (like clearcollElectionsPartiesVotess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionsPartiesVotess($overrideExisting = true)
    {
        if (null !== $this->collElectionsPartiesVotess && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionsPartiesVotesTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionsPartiesVotess = new $collectionClassName;
        $this->collElectionsPartiesVotess->setModel('\ElectionsPartiesVotes');
    }

    /**
     * Gets an array of ChildElectionsPartiesVotes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituencies is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionsPartiesVotes[] List of ChildElectionsPartiesVotes objects
     * @throws PropelException
     */
    public function getElectionsPartiesVotess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsPartiesVotessPartial && !$this->isNew();
        if (null === $this->collElectionsPartiesVotess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionsPartiesVotess) {
                // return empty collection
                $this->initElectionsPartiesVotess();
            } else {
                $collElectionsPartiesVotess = ChildElectionsPartiesVotesQuery::create(null, $criteria)
                    ->filterByConstituencies($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionsPartiesVotessPartial && count($collElectionsPartiesVotess)) {
                        $this->initElectionsPartiesVotess(false);

                        foreach ($collElectionsPartiesVotess as $obj) {
                            if (false == $this->collElectionsPartiesVotess->contains($obj)) {
                                $this->collElectionsPartiesVotess->append($obj);
                            }
                        }

                        $this->collElectionsPartiesVotessPartial = true;
                    }

                    return $collElectionsPartiesVotess;
                }

                if ($partial && $this->collElectionsPartiesVotess) {
                    foreach ($this->collElectionsPartiesVotess as $obj) {
                        if ($obj->isNew()) {
                            $collElectionsPartiesVotess[] = $obj;
                        }
                    }
                }

                $this->collElectionsPartiesVotess = $collElectionsPartiesVotess;
                $this->collElectionsPartiesVotessPartial = false;
            }
        }

        return $this->collElectionsPartiesVotess;
    }

    /**
     * Sets a collection of ChildElectionsPartiesVotes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionsPartiesVotess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function setElectionsPartiesVotess(Collection $electionsPartiesVotess, ConnectionInterface $con = null)
    {
        /** @var ChildElectionsPartiesVotes[] $electionsPartiesVotessToDelete */
        $electionsPartiesVotessToDelete = $this->getElectionsPartiesVotess(new Criteria(), $con)->diff($electionsPartiesVotess);


        $this->electionsPartiesVotessScheduledForDeletion = $electionsPartiesVotessToDelete;

        foreach ($electionsPartiesVotessToDelete as $electionsPartiesVotesRemoved) {
            $electionsPartiesVotesRemoved->setConstituencies(null);
        }

        $this->collElectionsPartiesVotess = null;
        foreach ($electionsPartiesVotess as $electionsPartiesVotes) {
            $this->addElectionsPartiesVotes($electionsPartiesVotes);
        }

        $this->collElectionsPartiesVotess = $electionsPartiesVotess;
        $this->collElectionsPartiesVotessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionsPartiesVotes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionsPartiesVotes objects.
     * @throws PropelException
     */
    public function countElectionsPartiesVotess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsPartiesVotessPartial && !$this->isNew();
        if (null === $this->collElectionsPartiesVotess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionsPartiesVotess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionsPartiesVotess());
            }

            $query = ChildElectionsPartiesVotesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituencies($this)
                ->count($con);
        }

        return count($this->collElectionsPartiesVotess);
    }

    /**
     * Method called to associate a ChildElectionsPartiesVotes object to this object
     * through the ChildElectionsPartiesVotes foreign key attribute.
     *
     * @param  ChildElectionsPartiesVotes $l ChildElectionsPartiesVotes
     * @return $this|\Constituencies The current object (for fluent API support)
     */
    public function addElectionsPartiesVotes(ChildElectionsPartiesVotes $l)
    {
        if ($this->collElectionsPartiesVotess === null) {
            $this->initElectionsPartiesVotess();
            $this->collElectionsPartiesVotessPartial = true;
        }

        if (!$this->collElectionsPartiesVotess->contains($l)) {
            $this->doAddElectionsPartiesVotes($l);

            if ($this->electionsPartiesVotessScheduledForDeletion and $this->electionsPartiesVotessScheduledForDeletion->contains($l)) {
                $this->electionsPartiesVotessScheduledForDeletion->remove($this->electionsPartiesVotessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionsPartiesVotes $electionsPartiesVotes The ChildElectionsPartiesVotes object to add.
     */
    protected function doAddElectionsPartiesVotes(ChildElectionsPartiesVotes $electionsPartiesVotes)
    {
        $this->collElectionsPartiesVotess[]= $electionsPartiesVotes;
        $electionsPartiesVotes->setConstituencies($this);
    }

    /**
     * @param  ChildElectionsPartiesVotes $electionsPartiesVotes The ChildElectionsPartiesVotes object to remove.
     * @return $this|ChildConstituencies The current object (for fluent API support)
     */
    public function removeElectionsPartiesVotes(ChildElectionsPartiesVotes $electionsPartiesVotes)
    {
        if ($this->getElectionsPartiesVotess()->contains($electionsPartiesVotes)) {
            $pos = $this->collElectionsPartiesVotess->search($electionsPartiesVotes);
            $this->collElectionsPartiesVotess->remove($pos);
            if (null === $this->electionsPartiesVotessScheduledForDeletion) {
                $this->electionsPartiesVotessScheduledForDeletion = clone $this->collElectionsPartiesVotess;
                $this->electionsPartiesVotessScheduledForDeletion->clear();
            }
            $this->electionsPartiesVotessScheduledForDeletion[]= clone $electionsPartiesVotes;
            $electionsPartiesVotes->setConstituencies(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Constituencies is new, it will return
     * an empty collection; or if this Constituencies has previously
     * been saved, it will retrieve related ElectionsPartiesVotess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Constituencies.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionsPartiesVotes[] List of ChildElectionsPartiesVotes objects
     */
    public function getElectionsPartiesVotessJoinElectionsParties(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionsPartiesVotesQuery::create(null, $criteria);
        $query->joinWith('ElectionsParties', $joinBehavior);

        return $this->getElectionsPartiesVotess($query, $con);
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
            if ($this->collConstituenciesCensusess) {
                foreach ($this->collConstituenciesCensusess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collElectionsIndependentCandidatess) {
                foreach ($this->collElectionsIndependentCandidatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collElectionsPartiesVotess) {
                foreach ($this->collElectionsPartiesVotess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collConstituenciesCensusess = null;
        $this->collElectionsIndependentCandidatess = null;
        $this->collElectionsPartiesVotess = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ConstituenciesTableMap::DEFAULT_STRING_FORMAT);
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
