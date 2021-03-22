<?php

namespace Base;

use \Elections as ChildElections;
use \ElectionsParties as ChildElectionsParties;
use \ElectionsPartiesQuery as ChildElectionsPartiesQuery;
use \ElectionsPartiesVotes as ChildElectionsPartiesVotes;
use \ElectionsPartiesVotesQuery as ChildElectionsPartiesVotesQuery;
use \ElectionsQuery as ChildElectionsQuery;
use \Parties as ChildParties;
use \PartiesQuery as ChildPartiesQuery;
use \Exception;
use \PDO;
use Map\ElectionsPartiesTableMap;
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
 * Base class that represents a row from the 'elections_parties' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class ElectionsParties implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ElectionsPartiesTableMap';


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
     * The value for the election_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $election_id;

    /**
     * The value for the list_number field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $list_number;

    /**
     * The value for the party_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $party_id;

    /**
     * The value for the party_color field.
     *
     * @var        string
     */
    protected $party_color;

    /**
     * The value for the total_votes field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $total_votes;

    /**
     * The value for the ord field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $ord;

    /**
     * @var        ChildElections
     */
    protected $aElections;

    /**
     * @var        ChildParties
     */
    protected $aParties;

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
     * @var ObjectCollection|ChildElectionsPartiesVotes[]
     */
    protected $electionsPartiesVotessScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->election_id = 0;
        $this->list_number = 0;
        $this->party_id = 0;
        $this->total_votes = 0;
        $this->ord = 0;
    }

    /**
     * Initializes internal state of Base\ElectionsParties object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>ElectionsParties</code> instance.  If
     * <code>obj</code> is an instance of <code>ElectionsParties</code>, delegates to
     * <code>equals(ElectionsParties)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|ElectionsParties The current object, for fluid interface
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
     * Get the [election_id] column value.
     *
     * @return int
     */
    public function getElectionId()
    {
        return $this->election_id;
    }

    /**
     * Get the [list_number] column value.
     *
     * @return int
     */
    public function getListNumber()
    {
        return $this->list_number;
    }

    /**
     * Get the [party_id] column value.
     *
     * @return int
     */
    public function getPartyId()
    {
        return $this->party_id;
    }

    /**
     * Get the [party_color] column value.
     *
     * @return string
     */
    public function getPartyColor()
    {
        return $this->party_color;
    }

    /**
     * Get the [total_votes] column value.
     *
     * @return int
     */
    public function getTotalVotes()
    {
        return $this->total_votes;
    }

    /**
     * Get the [ord] column value.
     *
     * @return int
     */
    public function getOrd()
    {
        return $this->ord;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [election_id] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setElectionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->election_id !== $v) {
            $this->election_id = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_ELECTION_ID] = true;
        }

        if ($this->aElections !== null && $this->aElections->getId() !== $v) {
            $this->aElections = null;
        }

        return $this;
    } // setElectionId()

    /**
     * Set the value of [list_number] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setListNumber($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->list_number !== $v) {
            $this->list_number = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_LIST_NUMBER] = true;
        }

        return $this;
    } // setListNumber()

    /**
     * Set the value of [party_id] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setPartyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->party_id !== $v) {
            $this->party_id = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_PARTY_ID] = true;
        }

        if ($this->aParties !== null && $this->aParties->getId() !== $v) {
            $this->aParties = null;
        }

        return $this;
    } // setPartyId()

    /**
     * Set the value of [party_color] column.
     *
     * @param string $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setPartyColor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->party_color !== $v) {
            $this->party_color = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_PARTY_COLOR] = true;
        }

        return $this;
    } // setPartyColor()

    /**
     * Set the value of [total_votes] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setTotalVotes($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->total_votes !== $v) {
            $this->total_votes = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_TOTAL_VOTES] = true;
        }

        return $this;
    } // setTotalVotes()

    /**
     * Set the value of [ord] column.
     *
     * @param int $v new value
     * @return $this|\ElectionsParties The current object (for fluent API support)
     */
    public function setOrd($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->ord !== $v) {
            $this->ord = $v;
            $this->modifiedColumns[ElectionsPartiesTableMap::COL_ORD] = true;
        }

        return $this;
    } // setOrd()

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
            if ($this->election_id !== 0) {
                return false;
            }

            if ($this->list_number !== 0) {
                return false;
            }

            if ($this->party_id !== 0) {
                return false;
            }

            if ($this->total_votes !== 0) {
                return false;
            }

            if ($this->ord !== 0) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ElectionsPartiesTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ElectionsPartiesTableMap::translateFieldName('ElectionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->election_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ElectionsPartiesTableMap::translateFieldName('ListNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->list_number = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ElectionsPartiesTableMap::translateFieldName('PartyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->party_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ElectionsPartiesTableMap::translateFieldName('PartyColor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->party_color = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ElectionsPartiesTableMap::translateFieldName('TotalVotes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->total_votes = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ElectionsPartiesTableMap::translateFieldName('Ord', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ord = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = ElectionsPartiesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ElectionsParties'), 0, $e);
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
        if ($this->aElections !== null && $this->election_id !== $this->aElections->getId()) {
            $this->aElections = null;
        }
        if ($this->aParties !== null && $this->party_id !== $this->aParties->getId()) {
            $this->aParties = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(ElectionsPartiesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildElectionsPartiesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aElections = null;
            $this->aParties = null;
            $this->collElectionsPartiesVotess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ElectionsParties::setDeleted()
     * @see ElectionsParties::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildElectionsPartiesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsPartiesTableMap::DATABASE_NAME);
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
                ElectionsPartiesTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aElections !== null) {
                if ($this->aElections->isModified() || $this->aElections->isNew()) {
                    $affectedRows += $this->aElections->save($con);
                }
                $this->setElections($this->aElections);
            }

            if ($this->aParties !== null) {
                if ($this->aParties->isModified() || $this->aParties->isNew()) {
                    $affectedRows += $this->aParties->save($con);
                }
                $this->setParties($this->aParties);
            }

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

        $this->modifiedColumns[ElectionsPartiesTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ElectionsPartiesTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ELECTION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'election_id';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_LIST_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'list_number';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_PARTY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'party_id';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_PARTY_COLOR)) {
            $modifiedColumns[':p' . $index++]  = 'party_color';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_TOTAL_VOTES)) {
            $modifiedColumns[':p' . $index++]  = 'total_votes';
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ORD)) {
            $modifiedColumns[':p' . $index++]  = 'ord';
        }

        $sql = sprintf(
            'INSERT INTO elections_parties (%s) VALUES (%s)',
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
                    case 'election_id':
                        $stmt->bindValue($identifier, $this->election_id, PDO::PARAM_INT);
                        break;
                    case 'list_number':
                        $stmt->bindValue($identifier, $this->list_number, PDO::PARAM_INT);
                        break;
                    case 'party_id':
                        $stmt->bindValue($identifier, $this->party_id, PDO::PARAM_INT);
                        break;
                    case 'party_color':
                        $stmt->bindValue($identifier, $this->party_color, PDO::PARAM_STR);
                        break;
                    case 'total_votes':
                        $stmt->bindValue($identifier, $this->total_votes, PDO::PARAM_INT);
                        break;
                    case 'ord':
                        $stmt->bindValue($identifier, $this->ord, PDO::PARAM_INT);
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
        $pos = ElectionsPartiesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getElectionId();
                break;
            case 2:
                return $this->getListNumber();
                break;
            case 3:
                return $this->getPartyId();
                break;
            case 4:
                return $this->getPartyColor();
                break;
            case 5:
                return $this->getTotalVotes();
                break;
            case 6:
                return $this->getOrd();
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

        if (isset($alreadyDumpedObjects['ElectionsParties'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ElectionsParties'][$this->hashCode()] = true;
        $keys = ElectionsPartiesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getElectionId(),
            $keys[2] => $this->getListNumber(),
            $keys[3] => $this->getPartyId(),
            $keys[4] => $this->getPartyColor(),
            $keys[5] => $this->getTotalVotes(),
            $keys[6] => $this->getOrd(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aElections) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'elections';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections';
                        break;
                    default:
                        $key = 'Elections';
                }

                $result[$key] = $this->aElections->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aParties) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'parties';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'parties';
                        break;
                    default:
                        $key = 'Parties';
                }

                $result[$key] = $this->aParties->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\ElectionsParties
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ElectionsPartiesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ElectionsParties
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setElectionId($value);
                break;
            case 2:
                $this->setListNumber($value);
                break;
            case 3:
                $this->setPartyId($value);
                break;
            case 4:
                $this->setPartyColor($value);
                break;
            case 5:
                $this->setTotalVotes($value);
                break;
            case 6:
                $this->setOrd($value);
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
        $keys = ElectionsPartiesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setElectionId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setListNumber($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPartyId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPartyColor($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTotalVotes($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setOrd($arr[$keys[6]]);
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
     * @return $this|\ElectionsParties The current object, for fluid interface
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
        $criteria = new Criteria(ElectionsPartiesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ID)) {
            $criteria->add(ElectionsPartiesTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ELECTION_ID)) {
            $criteria->add(ElectionsPartiesTableMap::COL_ELECTION_ID, $this->election_id);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_LIST_NUMBER)) {
            $criteria->add(ElectionsPartiesTableMap::COL_LIST_NUMBER, $this->list_number);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_PARTY_ID)) {
            $criteria->add(ElectionsPartiesTableMap::COL_PARTY_ID, $this->party_id);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_PARTY_COLOR)) {
            $criteria->add(ElectionsPartiesTableMap::COL_PARTY_COLOR, $this->party_color);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_TOTAL_VOTES)) {
            $criteria->add(ElectionsPartiesTableMap::COL_TOTAL_VOTES, $this->total_votes);
        }
        if ($this->isColumnModified(ElectionsPartiesTableMap::COL_ORD)) {
            $criteria->add(ElectionsPartiesTableMap::COL_ORD, $this->ord);
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
        $criteria = ChildElectionsPartiesQuery::create();
        $criteria->add(ElectionsPartiesTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \ElectionsParties (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setElectionId($this->getElectionId());
        $copyObj->setListNumber($this->getListNumber());
        $copyObj->setPartyId($this->getPartyId());
        $copyObj->setPartyColor($this->getPartyColor());
        $copyObj->setTotalVotes($this->getTotalVotes());
        $copyObj->setOrd($this->getOrd());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

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
     * @return \ElectionsParties Clone of current object.
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
     * Declares an association between this object and a ChildElections object.
     *
     * @param  ChildElections $v
     * @return $this|\ElectionsParties The current object (for fluent API support)
     * @throws PropelException
     */
    public function setElections(ChildElections $v = null)
    {
        if ($v === null) {
            $this->setElectionId(0);
        } else {
            $this->setElectionId($v->getId());
        }

        $this->aElections = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildElections object, it will not be re-added.
        if ($v !== null) {
            $v->addElectionsParties($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildElections object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildElections The associated ChildElections object.
     * @throws PropelException
     */
    public function getElections(ConnectionInterface $con = null)
    {
        if ($this->aElections === null && ($this->election_id != 0)) {
            $this->aElections = ChildElectionsQuery::create()->findPk($this->election_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aElections->addElectionsPartiess($this);
             */
        }

        return $this->aElections;
    }

    /**
     * Declares an association between this object and a ChildParties object.
     *
     * @param  ChildParties $v
     * @return $this|\ElectionsParties The current object (for fluent API support)
     * @throws PropelException
     */
    public function setParties(ChildParties $v = null)
    {
        if ($v === null) {
            $this->setPartyId(0);
        } else {
            $this->setPartyId($v->getId());
        }

        $this->aParties = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildParties object, it will not be re-added.
        if ($v !== null) {
            $v->addElectionsParties($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildParties object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildParties The associated ChildParties object.
     * @throws PropelException
     */
    public function getParties(ConnectionInterface $con = null)
    {
        if ($this->aParties === null && ($this->party_id != 0)) {
            $this->aParties = ChildPartiesQuery::create()->findPk($this->party_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aParties->addElectionsPartiess($this);
             */
        }

        return $this->aParties;
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
        if ('ElectionsPartiesVotes' == $relationName) {
            $this->initElectionsPartiesVotess();
            return;
        }
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
     * If this ChildElectionsParties is new, it will return
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
                    ->filterByElectionsParties($this)
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
     * @return $this|ChildElectionsParties The current object (for fluent API support)
     */
    public function setElectionsPartiesVotess(Collection $electionsPartiesVotess, ConnectionInterface $con = null)
    {
        /** @var ChildElectionsPartiesVotes[] $electionsPartiesVotessToDelete */
        $electionsPartiesVotessToDelete = $this->getElectionsPartiesVotess(new Criteria(), $con)->diff($electionsPartiesVotess);


        $this->electionsPartiesVotessScheduledForDeletion = $electionsPartiesVotessToDelete;

        foreach ($electionsPartiesVotessToDelete as $electionsPartiesVotesRemoved) {
            $electionsPartiesVotesRemoved->setElectionsParties(null);
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
                ->filterByElectionsParties($this)
                ->count($con);
        }

        return count($this->collElectionsPartiesVotess);
    }

    /**
     * Method called to associate a ChildElectionsPartiesVotes object to this object
     * through the ChildElectionsPartiesVotes foreign key attribute.
     *
     * @param  ChildElectionsPartiesVotes $l ChildElectionsPartiesVotes
     * @return $this|\ElectionsParties The current object (for fluent API support)
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
        $electionsPartiesVotes->setElectionsParties($this);
    }

    /**
     * @param  ChildElectionsPartiesVotes $electionsPartiesVotes The ChildElectionsPartiesVotes object to remove.
     * @return $this|ChildElectionsParties The current object (for fluent API support)
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
            $electionsPartiesVotes->setElectionsParties(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ElectionsParties is new, it will return
     * an empty collection; or if this ElectionsParties has previously
     * been saved, it will retrieve related ElectionsPartiesVotess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ElectionsParties.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionsPartiesVotes[] List of ChildElectionsPartiesVotes objects
     */
    public function getElectionsPartiesVotessJoinConstituencies(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionsPartiesVotesQuery::create(null, $criteria);
        $query->joinWith('Constituencies', $joinBehavior);

        return $this->getElectionsPartiesVotess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aElections) {
            $this->aElections->removeElectionsParties($this);
        }
        if (null !== $this->aParties) {
            $this->aParties->removeElectionsParties($this);
        }
        $this->id = null;
        $this->election_id = null;
        $this->list_number = null;
        $this->party_id = null;
        $this->party_color = null;
        $this->total_votes = null;
        $this->ord = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collElectionsPartiesVotess) {
                foreach ($this->collElectionsPartiesVotess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collElectionsPartiesVotess = null;
        $this->aElections = null;
        $this->aParties = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ElectionsPartiesTableMap::DEFAULT_STRING_FORMAT);
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
