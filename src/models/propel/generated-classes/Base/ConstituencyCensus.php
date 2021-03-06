<?php

namespace Base;

use \Constituency as ChildConstituency;
use \ConstituencyCensus as ChildConstituencyCensus;
use \ConstituencyCensusQuery as ChildConstituencyCensusQuery;
use \ConstituencyQuery as ChildConstituencyQuery;
use \ElectionConstituency as ChildElectionConstituency;
use \ElectionConstituencyQuery as ChildElectionConstituencyQuery;
use \PopulationCensus as ChildPopulationCensus;
use \PopulationCensusQuery as ChildPopulationCensusQuery;
use \Exception;
use \PDO;
use Map\ConstituencyCensusTableMap;
use Map\ElectionConstituencyTableMap;
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
 * Base class that represents a row from the 'constituencies_censuses' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class ConstituencyCensus implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ConstituencyCensusTableMap';


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
     * The value for the constituency_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $constituency_id;

    /**
     * The value for the population_census_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $population_census_id;

    /**
     * The value for the population field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $population;

    /**
     * @var        ChildConstituency
     */
    protected $aConstituency;

    /**
     * @var        ChildPopulationCensus
     */
    protected $aPopulationCensus;

    /**
     * @var        ObjectCollection|ChildElectionConstituency[] Collection to store aggregation of ChildElectionConstituency objects.
     */
    protected $collElectionConstituencies;
    protected $collElectionConstituenciesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionConstituency[]
     */
    protected $electionConstituenciesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->constituency_id = 0;
        $this->population_census_id = 0;
        $this->population = 0;
    }

    /**
     * Initializes internal state of Base\ConstituencyCensus object.
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
     * Compares this with another <code>ConstituencyCensus</code> instance.  If
     * <code>obj</code> is an instance of <code>ConstituencyCensus</code>, delegates to
     * <code>equals(ConstituencyCensus)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|ConstituencyCensus The current object, for fluid interface
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
     * Get the [constituency_id] column value.
     *
     * @return int
     */
    public function getConstituencyId()
    {
        return $this->constituency_id;
    }

    /**
     * Get the [population_census_id] column value.
     *
     * @return int
     */
    public function getPopulationCensusId()
    {
        return $this->population_census_id;
    }

    /**
     * Get the [population] column value.
     *
     * @return int
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ConstituencyCensusTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [constituency_id] column.
     *
     * @param int $v new value
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     */
    public function setConstituencyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->constituency_id !== $v) {
            $this->constituency_id = $v;
            $this->modifiedColumns[ConstituencyCensusTableMap::COL_CONSTITUENCY_ID] = true;
        }

        if ($this->aConstituency !== null && $this->aConstituency->getId() !== $v) {
            $this->aConstituency = null;
        }

        return $this;
    } // setConstituencyId()

    /**
     * Set the value of [population_census_id] column.
     *
     * @param int $v new value
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     */
    public function setPopulationCensusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->population_census_id !== $v) {
            $this->population_census_id = $v;
            $this->modifiedColumns[ConstituencyCensusTableMap::COL_POPULATION_CENSUS_ID] = true;
        }

        if ($this->aPopulationCensus !== null && $this->aPopulationCensus->getId() !== $v) {
            $this->aPopulationCensus = null;
        }

        return $this;
    } // setPopulationCensusId()

    /**
     * Set the value of [population] column.
     *
     * @param int $v new value
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     */
    public function setPopulation($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->population !== $v) {
            $this->population = $v;
            $this->modifiedColumns[ConstituencyCensusTableMap::COL_POPULATION] = true;
        }

        return $this;
    } // setPopulation()

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
            if ($this->constituency_id !== 0) {
                return false;
            }

            if ($this->population_census_id !== 0) {
                return false;
            }

            if ($this->population !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ConstituencyCensusTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ConstituencyCensusTableMap::translateFieldName('ConstituencyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->constituency_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ConstituencyCensusTableMap::translateFieldName('PopulationCensusId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->population_census_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ConstituencyCensusTableMap::translateFieldName('Population', TableMap::TYPE_PHPNAME, $indexType)];
            $this->population = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = ConstituencyCensusTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ConstituencyCensus'), 0, $e);
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
        if ($this->aConstituency !== null && $this->constituency_id !== $this->aConstituency->getId()) {
            $this->aConstituency = null;
        }
        if ($this->aPopulationCensus !== null && $this->population_census_id !== $this->aPopulationCensus->getId()) {
            $this->aPopulationCensus = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(ConstituencyCensusTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildConstituencyCensusQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aConstituency = null;
            $this->aPopulationCensus = null;
            $this->collElectionConstituencies = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ConstituencyCensus::setDeleted()
     * @see ConstituencyCensus::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyCensusTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildConstituencyCensusQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ConstituencyCensusTableMap::DATABASE_NAME);
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
                ConstituencyCensusTableMap::addInstanceToPool($this);
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

            if ($this->aConstituency !== null) {
                if ($this->aConstituency->isModified() || $this->aConstituency->isNew()) {
                    $affectedRows += $this->aConstituency->save($con);
                }
                $this->setConstituency($this->aConstituency);
            }

            if ($this->aPopulationCensus !== null) {
                if ($this->aPopulationCensus->isModified() || $this->aPopulationCensus->isNew()) {
                    $affectedRows += $this->aPopulationCensus->save($con);
                }
                $this->setPopulationCensus($this->aPopulationCensus);
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

            if ($this->electionConstituenciesScheduledForDeletion !== null) {
                if (!$this->electionConstituenciesScheduledForDeletion->isEmpty()) {
                    \ElectionConstituencyQuery::create()
                        ->filterByPrimaryKeys($this->electionConstituenciesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionConstituenciesScheduledForDeletion = null;
                }
            }

            if ($this->collElectionConstituencies !== null) {
                foreach ($this->collElectionConstituencies as $referrerFK) {
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

        $this->modifiedColumns[ConstituencyCensusTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ConstituencyCensusTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_CONSTITUENCY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'constituency_id';
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_POPULATION_CENSUS_ID)) {
            $modifiedColumns[':p' . $index++]  = 'population_census_id';
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_POPULATION)) {
            $modifiedColumns[':p' . $index++]  = 'population';
        }

        $sql = sprintf(
            'INSERT INTO constituencies_censuses (%s) VALUES (%s)',
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
                    case 'constituency_id':
                        $stmt->bindValue($identifier, $this->constituency_id, PDO::PARAM_INT);
                        break;
                    case 'population_census_id':
                        $stmt->bindValue($identifier, $this->population_census_id, PDO::PARAM_INT);
                        break;
                    case 'population':
                        $stmt->bindValue($identifier, $this->population, PDO::PARAM_INT);
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
        $pos = ConstituencyCensusTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getConstituencyId();
                break;
            case 2:
                return $this->getPopulationCensusId();
                break;
            case 3:
                return $this->getPopulation();
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

        if (isset($alreadyDumpedObjects['ConstituencyCensus'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ConstituencyCensus'][$this->hashCode()] = true;
        $keys = ConstituencyCensusTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getConstituencyId(),
            $keys[2] => $this->getPopulationCensusId(),
            $keys[3] => $this->getPopulation(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aConstituency) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'constituency';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'constituencies';
                        break;
                    default:
                        $key = 'Constituency';
                }

                $result[$key] = $this->aConstituency->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPopulationCensus) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'populationCensus';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'population_censuses';
                        break;
                    default:
                        $key = 'PopulationCensus';
                }

                $result[$key] = $this->aPopulationCensus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collElectionConstituencies) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionConstituencies';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_constituencies_censusess';
                        break;
                    default:
                        $key = 'ElectionConstituencies';
                }

                $result[$key] = $this->collElectionConstituencies->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\ConstituencyCensus
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ConstituencyCensusTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ConstituencyCensus
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setConstituencyId($value);
                break;
            case 2:
                $this->setPopulationCensusId($value);
                break;
            case 3:
                $this->setPopulation($value);
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
        $keys = ConstituencyCensusTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setConstituencyId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPopulationCensusId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPopulation($arr[$keys[3]]);
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
     * @return $this|\ConstituencyCensus The current object, for fluid interface
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
        $criteria = new Criteria(ConstituencyCensusTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_ID)) {
            $criteria->add(ConstituencyCensusTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_CONSTITUENCY_ID)) {
            $criteria->add(ConstituencyCensusTableMap::COL_CONSTITUENCY_ID, $this->constituency_id);
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_POPULATION_CENSUS_ID)) {
            $criteria->add(ConstituencyCensusTableMap::COL_POPULATION_CENSUS_ID, $this->population_census_id);
        }
        if ($this->isColumnModified(ConstituencyCensusTableMap::COL_POPULATION)) {
            $criteria->add(ConstituencyCensusTableMap::COL_POPULATION, $this->population);
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
        $criteria = ChildConstituencyCensusQuery::create();
        $criteria->add(ConstituencyCensusTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \ConstituencyCensus (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setConstituencyId($this->getConstituencyId());
        $copyObj->setPopulationCensusId($this->getPopulationCensusId());
        $copyObj->setPopulation($this->getPopulation());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getElectionConstituencies() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionConstituency($relObj->copy($deepCopy));
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
     * @return \ConstituencyCensus Clone of current object.
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
     * Declares an association between this object and a ChildConstituency object.
     *
     * @param  ChildConstituency $v
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     * @throws PropelException
     */
    public function setConstituency(ChildConstituency $v = null)
    {
        if ($v === null) {
            $this->setConstituencyId(0);
        } else {
            $this->setConstituencyId($v->getId());
        }

        $this->aConstituency = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildConstituency object, it will not be re-added.
        if ($v !== null) {
            $v->addConstituencyCensus($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildConstituency object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildConstituency The associated ChildConstituency object.
     * @throws PropelException
     */
    public function getConstituency(ConnectionInterface $con = null)
    {
        if ($this->aConstituency === null && ($this->constituency_id != 0)) {
            $this->aConstituency = ChildConstituencyQuery::create()->findPk($this->constituency_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aConstituency->addConstituencyCensuses($this);
             */
        }

        return $this->aConstituency;
    }

    /**
     * Declares an association between this object and a ChildPopulationCensus object.
     *
     * @param  ChildPopulationCensus $v
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPopulationCensus(ChildPopulationCensus $v = null)
    {
        if ($v === null) {
            $this->setPopulationCensusId(0);
        } else {
            $this->setPopulationCensusId($v->getId());
        }

        $this->aPopulationCensus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPopulationCensus object, it will not be re-added.
        if ($v !== null) {
            $v->addConstituencyCensus($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPopulationCensus object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPopulationCensus The associated ChildPopulationCensus object.
     * @throws PropelException
     */
    public function getPopulationCensus(ConnectionInterface $con = null)
    {
        if ($this->aPopulationCensus === null && ($this->population_census_id != 0)) {
            $this->aPopulationCensus = ChildPopulationCensusQuery::create()->findPk($this->population_census_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPopulationCensus->addConstituencyCensuses($this);
             */
        }

        return $this->aPopulationCensus;
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
        if ('ElectionConstituency' == $relationName) {
            $this->initElectionConstituencies();
            return;
        }
    }

    /**
     * Clears out the collElectionConstituencies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionConstituencies()
     */
    public function clearElectionConstituencies()
    {
        $this->collElectionConstituencies = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionConstituencies collection loaded partially.
     */
    public function resetPartialElectionConstituencies($v = true)
    {
        $this->collElectionConstituenciesPartial = $v;
    }

    /**
     * Initializes the collElectionConstituencies collection.
     *
     * By default this just sets the collElectionConstituencies collection to an empty array (like clearcollElectionConstituencies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionConstituencies($overrideExisting = true)
    {
        if (null !== $this->collElectionConstituencies && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionConstituencyTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionConstituencies = new $collectionClassName;
        $this->collElectionConstituencies->setModel('\ElectionConstituency');
    }

    /**
     * Gets an array of ChildElectionConstituency objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildConstituencyCensus is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionConstituency[] List of ChildElectionConstituency objects
     * @throws PropelException
     */
    public function getElectionConstituencies(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionConstituenciesPartial && !$this->isNew();
        if (null === $this->collElectionConstituencies || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionConstituencies) {
                // return empty collection
                $this->initElectionConstituencies();
            } else {
                $collElectionConstituencies = ChildElectionConstituencyQuery::create(null, $criteria)
                    ->filterByConstituencyCensus($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionConstituenciesPartial && count($collElectionConstituencies)) {
                        $this->initElectionConstituencies(false);

                        foreach ($collElectionConstituencies as $obj) {
                            if (false == $this->collElectionConstituencies->contains($obj)) {
                                $this->collElectionConstituencies->append($obj);
                            }
                        }

                        $this->collElectionConstituenciesPartial = true;
                    }

                    return $collElectionConstituencies;
                }

                if ($partial && $this->collElectionConstituencies) {
                    foreach ($this->collElectionConstituencies as $obj) {
                        if ($obj->isNew()) {
                            $collElectionConstituencies[] = $obj;
                        }
                    }
                }

                $this->collElectionConstituencies = $collElectionConstituencies;
                $this->collElectionConstituenciesPartial = false;
            }
        }

        return $this->collElectionConstituencies;
    }

    /**
     * Sets a collection of ChildElectionConstituency objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionConstituencies A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildConstituencyCensus The current object (for fluent API support)
     */
    public function setElectionConstituencies(Collection $electionConstituencies, ConnectionInterface $con = null)
    {
        /** @var ChildElectionConstituency[] $electionConstituenciesToDelete */
        $electionConstituenciesToDelete = $this->getElectionConstituencies(new Criteria(), $con)->diff($electionConstituencies);


        $this->electionConstituenciesScheduledForDeletion = $electionConstituenciesToDelete;

        foreach ($electionConstituenciesToDelete as $electionConstituencyRemoved) {
            $electionConstituencyRemoved->setConstituencyCensus(null);
        }

        $this->collElectionConstituencies = null;
        foreach ($electionConstituencies as $electionConstituency) {
            $this->addElectionConstituency($electionConstituency);
        }

        $this->collElectionConstituencies = $electionConstituencies;
        $this->collElectionConstituenciesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionConstituency objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionConstituency objects.
     * @throws PropelException
     */
    public function countElectionConstituencies(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionConstituenciesPartial && !$this->isNew();
        if (null === $this->collElectionConstituencies || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionConstituencies) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionConstituencies());
            }

            $query = ChildElectionConstituencyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByConstituencyCensus($this)
                ->count($con);
        }

        return count($this->collElectionConstituencies);
    }

    /**
     * Method called to associate a ChildElectionConstituency object to this object
     * through the ChildElectionConstituency foreign key attribute.
     *
     * @param  ChildElectionConstituency $l ChildElectionConstituency
     * @return $this|\ConstituencyCensus The current object (for fluent API support)
     */
    public function addElectionConstituency(ChildElectionConstituency $l)
    {
        if ($this->collElectionConstituencies === null) {
            $this->initElectionConstituencies();
            $this->collElectionConstituenciesPartial = true;
        }

        if (!$this->collElectionConstituencies->contains($l)) {
            $this->doAddElectionConstituency($l);

            if ($this->electionConstituenciesScheduledForDeletion and $this->electionConstituenciesScheduledForDeletion->contains($l)) {
                $this->electionConstituenciesScheduledForDeletion->remove($this->electionConstituenciesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionConstituency $electionConstituency The ChildElectionConstituency object to add.
     */
    protected function doAddElectionConstituency(ChildElectionConstituency $electionConstituency)
    {
        $this->collElectionConstituencies[]= $electionConstituency;
        $electionConstituency->setConstituencyCensus($this);
    }

    /**
     * @param  ChildElectionConstituency $electionConstituency The ChildElectionConstituency object to remove.
     * @return $this|ChildConstituencyCensus The current object (for fluent API support)
     */
    public function removeElectionConstituency(ChildElectionConstituency $electionConstituency)
    {
        if ($this->getElectionConstituencies()->contains($electionConstituency)) {
            $pos = $this->collElectionConstituencies->search($electionConstituency);
            $this->collElectionConstituencies->remove($pos);
            if (null === $this->electionConstituenciesScheduledForDeletion) {
                $this->electionConstituenciesScheduledForDeletion = clone $this->collElectionConstituencies;
                $this->electionConstituenciesScheduledForDeletion->clear();
            }
            $this->electionConstituenciesScheduledForDeletion[]= clone $electionConstituency;
            $electionConstituency->setConstituencyCensus(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ConstituencyCensus is new, it will return
     * an empty collection; or if this ConstituencyCensus has previously
     * been saved, it will retrieve related ElectionConstituencies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ConstituencyCensus.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionConstituency[] List of ChildElectionConstituency objects
     */
    public function getElectionConstituenciesJoinElection(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionConstituencyQuery::create(null, $criteria);
        $query->joinWith('Election', $joinBehavior);

        return $this->getElectionConstituencies($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aConstituency) {
            $this->aConstituency->removeConstituencyCensus($this);
        }
        if (null !== $this->aPopulationCensus) {
            $this->aPopulationCensus->removeConstituencyCensus($this);
        }
        $this->id = null;
        $this->constituency_id = null;
        $this->population_census_id = null;
        $this->population = null;
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
            if ($this->collElectionConstituencies) {
                foreach ($this->collElectionConstituencies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collElectionConstituencies = null;
        $this->aConstituency = null;
        $this->aPopulationCensus = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ConstituencyCensusTableMap::DEFAULT_STRING_FORMAT);
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
