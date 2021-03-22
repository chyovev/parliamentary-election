<?php

namespace Base;

use \AssemblyTypes as ChildAssemblyTypes;
use \AssemblyTypesQuery as ChildAssemblyTypesQuery;
use \Elections as ChildElections;
use \ElectionsIndependentCandidates as ChildElectionsIndependentCandidates;
use \ElectionsIndependentCandidatesQuery as ChildElectionsIndependentCandidatesQuery;
use \ElectionsParties as ChildElectionsParties;
use \ElectionsPartiesQuery as ChildElectionsPartiesQuery;
use \ElectionsQuery as ChildElectionsQuery;
use \PopulationCensuses as ChildPopulationCensuses;
use \PopulationCensusesQuery as ChildPopulationCensusesQuery;
use \Exception;
use \PDO;
use Map\ElectionsIndependentCandidatesTableMap;
use Map\ElectionsPartiesTableMap;
use Map\ElectionsTableMap;
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
 * Base class that represents a row from the 'elections' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Elections implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ElectionsTableMap';


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
     * The value for the slug field.
     *
     * @var        string
     */
    protected $slug;

    /**
     * The value for the assembly_type_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $assembly_type_id;

    /**
     * The value for the population_census_id field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $population_census_id;

    /**
     * The value for the active_suffrage field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $active_suffrage;

    /**
     * The value for the official field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $official;

    /**
     * @var        ChildAssemblyTypes
     */
    protected $aAssemblyTypes;

    /**
     * @var        ChildPopulationCensuses
     */
    protected $aPopulationCensuses;

    /**
     * @var        ObjectCollection|ChildElectionsIndependentCandidates[] Collection to store aggregation of ChildElectionsIndependentCandidates objects.
     */
    protected $collElectionsIndependentCandidatess;
    protected $collElectionsIndependentCandidatessPartial;

    /**
     * @var        ObjectCollection|ChildElectionsParties[] Collection to store aggregation of ChildElectionsParties objects.
     */
    protected $collElectionsPartiess;
    protected $collElectionsPartiessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionsIndependentCandidates[]
     */
    protected $electionsIndependentCandidatessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionsParties[]
     */
    protected $electionsPartiessScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->assembly_type_id = 0;
        $this->population_census_id = 0;
        $this->active_suffrage = 0;
        $this->official = false;
    }

    /**
     * Initializes internal state of Base\Elections object.
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
     * Compares this with another <code>Elections</code> instance.  If
     * <code>obj</code> is an instance of <code>Elections</code>, delegates to
     * <code>equals(Elections)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Elections The current object, for fluid interface
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the [assembly_type_id] column value.
     *
     * @return int
     */
    public function getAssemblyTypeId()
    {
        return $this->assembly_type_id;
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
     * Get the [active_suffrage] column value.
     *
     * @return int
     */
    public function getActiveSuffrage()
    {
        return $this->active_suffrage;
    }

    /**
     * Get the [official] column value.
     *
     * @return boolean
     */
    public function getOfficial()
    {
        return $this->official;
    }

    /**
     * Get the [official] column value.
     *
     * @return boolean
     */
    public function isOfficial()
    {
        return $this->getOfficial();
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_SLUG] = true;
        }

        return $this;
    } // setSlug()

    /**
     * Set the value of [assembly_type_id] column.
     *
     * @param int $v new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setAssemblyTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->assembly_type_id !== $v) {
            $this->assembly_type_id = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_ASSEMBLY_TYPE_ID] = true;
        }

        if ($this->aAssemblyTypes !== null && $this->aAssemblyTypes->getId() !== $v) {
            $this->aAssemblyTypes = null;
        }

        return $this;
    } // setAssemblyTypeId()

    /**
     * Set the value of [population_census_id] column.
     *
     * @param int $v new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setPopulationCensusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->population_census_id !== $v) {
            $this->population_census_id = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_POPULATION_CENSUS_ID] = true;
        }

        if ($this->aPopulationCensuses !== null && $this->aPopulationCensuses->getId() !== $v) {
            $this->aPopulationCensuses = null;
        }

        return $this;
    } // setPopulationCensusId()

    /**
     * Set the value of [active_suffrage] column.
     *
     * @param int $v new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setActiveSuffrage($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active_suffrage !== $v) {
            $this->active_suffrage = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_ACTIVE_SUFFRAGE] = true;
        }

        return $this;
    } // setActiveSuffrage()

    /**
     * Sets the value of the [official] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function setOfficial($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->official !== $v) {
            $this->official = $v;
            $this->modifiedColumns[ElectionsTableMap::COL_OFFICIAL] = true;
        }

        return $this;
    } // setOfficial()

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
            if ($this->assembly_type_id !== 0) {
                return false;
            }

            if ($this->population_census_id !== 0) {
                return false;
            }

            if ($this->active_suffrage !== 0) {
                return false;
            }

            if ($this->official !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ElectionsTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ElectionsTableMap::translateFieldName('Slug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->slug = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ElectionsTableMap::translateFieldName('AssemblyTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->assembly_type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ElectionsTableMap::translateFieldName('PopulationCensusId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->population_census_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ElectionsTableMap::translateFieldName('ActiveSuffrage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active_suffrage = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ElectionsTableMap::translateFieldName('Official', TableMap::TYPE_PHPNAME, $indexType)];
            $this->official = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = ElectionsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Elections'), 0, $e);
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
        if ($this->aAssemblyTypes !== null && $this->assembly_type_id !== $this->aAssemblyTypes->getId()) {
            $this->aAssemblyTypes = null;
        }
        if ($this->aPopulationCensuses !== null && $this->population_census_id !== $this->aPopulationCensuses->getId()) {
            $this->aPopulationCensuses = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(ElectionsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildElectionsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAssemblyTypes = null;
            $this->aPopulationCensuses = null;
            $this->collElectionsIndependentCandidatess = null;

            $this->collElectionsPartiess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Elections::setDeleted()
     * @see Elections::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildElectionsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionsTableMap::DATABASE_NAME);
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
                ElectionsTableMap::addInstanceToPool($this);
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

            if ($this->aAssemblyTypes !== null) {
                if ($this->aAssemblyTypes->isModified() || $this->aAssemblyTypes->isNew()) {
                    $affectedRows += $this->aAssemblyTypes->save($con);
                }
                $this->setAssemblyTypes($this->aAssemblyTypes);
            }

            if ($this->aPopulationCensuses !== null) {
                if ($this->aPopulationCensuses->isModified() || $this->aPopulationCensuses->isNew()) {
                    $affectedRows += $this->aPopulationCensuses->save($con);
                }
                $this->setPopulationCensuses($this->aPopulationCensuses);
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

            if ($this->electionsPartiessScheduledForDeletion !== null) {
                if (!$this->electionsPartiessScheduledForDeletion->isEmpty()) {
                    \ElectionsPartiesQuery::create()
                        ->filterByPrimaryKeys($this->electionsPartiessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionsPartiessScheduledForDeletion = null;
                }
            }

            if ($this->collElectionsPartiess !== null) {
                foreach ($this->collElectionsPartiess as $referrerFK) {
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

        $this->modifiedColumns[ElectionsTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ElectionsTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ElectionsTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_SLUG)) {
            $modifiedColumns[':p' . $index++]  = 'slug';
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'assembly_type_id';
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_POPULATION_CENSUS_ID)) {
            $modifiedColumns[':p' . $index++]  = 'population_census_id';
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_ACTIVE_SUFFRAGE)) {
            $modifiedColumns[':p' . $index++]  = 'active_suffrage';
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_OFFICIAL)) {
            $modifiedColumns[':p' . $index++]  = 'official';
        }

        $sql = sprintf(
            'INSERT INTO elections (%s) VALUES (%s)',
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
                    case 'slug':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case 'assembly_type_id':
                        $stmt->bindValue($identifier, $this->assembly_type_id, PDO::PARAM_INT);
                        break;
                    case 'population_census_id':
                        $stmt->bindValue($identifier, $this->population_census_id, PDO::PARAM_INT);
                        break;
                    case 'active_suffrage':
                        $stmt->bindValue($identifier, $this->active_suffrage, PDO::PARAM_INT);
                        break;
                    case 'official':
                        $stmt->bindValue($identifier, (int) $this->official, PDO::PARAM_INT);
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
        $pos = ElectionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSlug();
                break;
            case 2:
                return $this->getAssemblyTypeId();
                break;
            case 3:
                return $this->getPopulationCensusId();
                break;
            case 4:
                return $this->getActiveSuffrage();
                break;
            case 5:
                return $this->getOfficial();
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

        if (isset($alreadyDumpedObjects['Elections'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Elections'][$this->hashCode()] = true;
        $keys = ElectionsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSlug(),
            $keys[2] => $this->getAssemblyTypeId(),
            $keys[3] => $this->getPopulationCensusId(),
            $keys[4] => $this->getActiveSuffrage(),
            $keys[5] => $this->getOfficial(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAssemblyTypes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assemblyTypes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assembly_types';
                        break;
                    default:
                        $key = 'AssemblyTypes';
                }

                $result[$key] = $this->aAssemblyTypes->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPopulationCensuses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'populationCensuses';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'population_censuses';
                        break;
                    default:
                        $key = 'PopulationCensuses';
                }

                $result[$key] = $this->aPopulationCensuses->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collElectionsPartiess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionsPartiess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_partiess';
                        break;
                    default:
                        $key = 'ElectionsPartiess';
                }

                $result[$key] = $this->collElectionsPartiess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Elections
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ElectionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Elections
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setSlug($value);
                break;
            case 2:
                $this->setAssemblyTypeId($value);
                break;
            case 3:
                $this->setPopulationCensusId($value);
                break;
            case 4:
                $this->setActiveSuffrage($value);
                break;
            case 5:
                $this->setOfficial($value);
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
        $keys = ElectionsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSlug($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAssemblyTypeId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPopulationCensusId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setActiveSuffrage($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setOfficial($arr[$keys[5]]);
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
     * @return $this|\Elections The current object, for fluid interface
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
        $criteria = new Criteria(ElectionsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ElectionsTableMap::COL_ID)) {
            $criteria->add(ElectionsTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_SLUG)) {
            $criteria->add(ElectionsTableMap::COL_SLUG, $this->slug);
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID)) {
            $criteria->add(ElectionsTableMap::COL_ASSEMBLY_TYPE_ID, $this->assembly_type_id);
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_POPULATION_CENSUS_ID)) {
            $criteria->add(ElectionsTableMap::COL_POPULATION_CENSUS_ID, $this->population_census_id);
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_ACTIVE_SUFFRAGE)) {
            $criteria->add(ElectionsTableMap::COL_ACTIVE_SUFFRAGE, $this->active_suffrage);
        }
        if ($this->isColumnModified(ElectionsTableMap::COL_OFFICIAL)) {
            $criteria->add(ElectionsTableMap::COL_OFFICIAL, $this->official);
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
        $criteria = ChildElectionsQuery::create();
        $criteria->add(ElectionsTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Elections (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSlug($this->getSlug());
        $copyObj->setAssemblyTypeId($this->getAssemblyTypeId());
        $copyObj->setPopulationCensusId($this->getPopulationCensusId());
        $copyObj->setActiveSuffrage($this->getActiveSuffrage());
        $copyObj->setOfficial($this->getOfficial());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getElectionsIndependentCandidatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionsIndependentCandidates($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getElectionsPartiess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionsParties($relObj->copy($deepCopy));
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
     * @return \Elections Clone of current object.
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
     * Declares an association between this object and a ChildAssemblyTypes object.
     *
     * @param  ChildAssemblyTypes $v
     * @return $this|\Elections The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAssemblyTypes(ChildAssemblyTypes $v = null)
    {
        if ($v === null) {
            $this->setAssemblyTypeId(0);
        } else {
            $this->setAssemblyTypeId($v->getId());
        }

        $this->aAssemblyTypes = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAssemblyTypes object, it will not be re-added.
        if ($v !== null) {
            $v->addElections($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAssemblyTypes object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAssemblyTypes The associated ChildAssemblyTypes object.
     * @throws PropelException
     */
    public function getAssemblyTypes(ConnectionInterface $con = null)
    {
        if ($this->aAssemblyTypes === null && ($this->assembly_type_id != 0)) {
            $this->aAssemblyTypes = ChildAssemblyTypesQuery::create()->findPk($this->assembly_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAssemblyTypes->addElectionss($this);
             */
        }

        return $this->aAssemblyTypes;
    }

    /**
     * Declares an association between this object and a ChildPopulationCensuses object.
     *
     * @param  ChildPopulationCensuses $v
     * @return $this|\Elections The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPopulationCensuses(ChildPopulationCensuses $v = null)
    {
        if ($v === null) {
            $this->setPopulationCensusId(0);
        } else {
            $this->setPopulationCensusId($v->getId());
        }

        $this->aPopulationCensuses = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPopulationCensuses object, it will not be re-added.
        if ($v !== null) {
            $v->addElections($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPopulationCensuses object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPopulationCensuses The associated ChildPopulationCensuses object.
     * @throws PropelException
     */
    public function getPopulationCensuses(ConnectionInterface $con = null)
    {
        if ($this->aPopulationCensuses === null && ($this->population_census_id != 0)) {
            $this->aPopulationCensuses = ChildPopulationCensusesQuery::create()->findPk($this->population_census_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPopulationCensuses->addElectionss($this);
             */
        }

        return $this->aPopulationCensuses;
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
        if ('ElectionsIndependentCandidates' == $relationName) {
            $this->initElectionsIndependentCandidatess();
            return;
        }
        if ('ElectionsParties' == $relationName) {
            $this->initElectionsPartiess();
            return;
        }
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
     * If this ChildElections is new, it will return
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
                    ->filterByElections($this)
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
     * @return $this|ChildElections The current object (for fluent API support)
     */
    public function setElectionsIndependentCandidatess(Collection $electionsIndependentCandidatess, ConnectionInterface $con = null)
    {
        /** @var ChildElectionsIndependentCandidates[] $electionsIndependentCandidatessToDelete */
        $electionsIndependentCandidatessToDelete = $this->getElectionsIndependentCandidatess(new Criteria(), $con)->diff($electionsIndependentCandidatess);


        $this->electionsIndependentCandidatessScheduledForDeletion = $electionsIndependentCandidatessToDelete;

        foreach ($electionsIndependentCandidatessToDelete as $electionsIndependentCandidatesRemoved) {
            $electionsIndependentCandidatesRemoved->setElections(null);
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
                ->filterByElections($this)
                ->count($con);
        }

        return count($this->collElectionsIndependentCandidatess);
    }

    /**
     * Method called to associate a ChildElectionsIndependentCandidates object to this object
     * through the ChildElectionsIndependentCandidates foreign key attribute.
     *
     * @param  ChildElectionsIndependentCandidates $l ChildElectionsIndependentCandidates
     * @return $this|\Elections The current object (for fluent API support)
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
        $electionsIndependentCandidates->setElections($this);
    }

    /**
     * @param  ChildElectionsIndependentCandidates $electionsIndependentCandidates The ChildElectionsIndependentCandidates object to remove.
     * @return $this|ChildElections The current object (for fluent API support)
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
            $electionsIndependentCandidates->setElections(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Elections is new, it will return
     * an empty collection; or if this Elections has previously
     * been saved, it will retrieve related ElectionsIndependentCandidatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Elections.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionsIndependentCandidates[] List of ChildElectionsIndependentCandidates objects
     */
    public function getElectionsIndependentCandidatessJoinConstituencies(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionsIndependentCandidatesQuery::create(null, $criteria);
        $query->joinWith('Constituencies', $joinBehavior);

        return $this->getElectionsIndependentCandidatess($query, $con);
    }

    /**
     * Clears out the collElectionsPartiess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionsPartiess()
     */
    public function clearElectionsPartiess()
    {
        $this->collElectionsPartiess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionsPartiess collection loaded partially.
     */
    public function resetPartialElectionsPartiess($v = true)
    {
        $this->collElectionsPartiessPartial = $v;
    }

    /**
     * Initializes the collElectionsPartiess collection.
     *
     * By default this just sets the collElectionsPartiess collection to an empty array (like clearcollElectionsPartiess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionsPartiess($overrideExisting = true)
    {
        if (null !== $this->collElectionsPartiess && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionsPartiesTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionsPartiess = new $collectionClassName;
        $this->collElectionsPartiess->setModel('\ElectionsParties');
    }

    /**
     * Gets an array of ChildElectionsParties objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildElections is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionsParties[] List of ChildElectionsParties objects
     * @throws PropelException
     */
    public function getElectionsPartiess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsPartiessPartial && !$this->isNew();
        if (null === $this->collElectionsPartiess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionsPartiess) {
                // return empty collection
                $this->initElectionsPartiess();
            } else {
                $collElectionsPartiess = ChildElectionsPartiesQuery::create(null, $criteria)
                    ->filterByElections($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionsPartiessPartial && count($collElectionsPartiess)) {
                        $this->initElectionsPartiess(false);

                        foreach ($collElectionsPartiess as $obj) {
                            if (false == $this->collElectionsPartiess->contains($obj)) {
                                $this->collElectionsPartiess->append($obj);
                            }
                        }

                        $this->collElectionsPartiessPartial = true;
                    }

                    return $collElectionsPartiess;
                }

                if ($partial && $this->collElectionsPartiess) {
                    foreach ($this->collElectionsPartiess as $obj) {
                        if ($obj->isNew()) {
                            $collElectionsPartiess[] = $obj;
                        }
                    }
                }

                $this->collElectionsPartiess = $collElectionsPartiess;
                $this->collElectionsPartiessPartial = false;
            }
        }

        return $this->collElectionsPartiess;
    }

    /**
     * Sets a collection of ChildElectionsParties objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionsPartiess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildElections The current object (for fluent API support)
     */
    public function setElectionsPartiess(Collection $electionsPartiess, ConnectionInterface $con = null)
    {
        /** @var ChildElectionsParties[] $electionsPartiessToDelete */
        $electionsPartiessToDelete = $this->getElectionsPartiess(new Criteria(), $con)->diff($electionsPartiess);


        $this->electionsPartiessScheduledForDeletion = $electionsPartiessToDelete;

        foreach ($electionsPartiessToDelete as $electionsPartiesRemoved) {
            $electionsPartiesRemoved->setElections(null);
        }

        $this->collElectionsPartiess = null;
        foreach ($electionsPartiess as $electionsParties) {
            $this->addElectionsParties($electionsParties);
        }

        $this->collElectionsPartiess = $electionsPartiess;
        $this->collElectionsPartiessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionsParties objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionsParties objects.
     * @throws PropelException
     */
    public function countElectionsPartiess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionsPartiessPartial && !$this->isNew();
        if (null === $this->collElectionsPartiess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionsPartiess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionsPartiess());
            }

            $query = ChildElectionsPartiesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByElections($this)
                ->count($con);
        }

        return count($this->collElectionsPartiess);
    }

    /**
     * Method called to associate a ChildElectionsParties object to this object
     * through the ChildElectionsParties foreign key attribute.
     *
     * @param  ChildElectionsParties $l ChildElectionsParties
     * @return $this|\Elections The current object (for fluent API support)
     */
    public function addElectionsParties(ChildElectionsParties $l)
    {
        if ($this->collElectionsPartiess === null) {
            $this->initElectionsPartiess();
            $this->collElectionsPartiessPartial = true;
        }

        if (!$this->collElectionsPartiess->contains($l)) {
            $this->doAddElectionsParties($l);

            if ($this->electionsPartiessScheduledForDeletion and $this->electionsPartiessScheduledForDeletion->contains($l)) {
                $this->electionsPartiessScheduledForDeletion->remove($this->electionsPartiessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionsParties $electionsParties The ChildElectionsParties object to add.
     */
    protected function doAddElectionsParties(ChildElectionsParties $electionsParties)
    {
        $this->collElectionsPartiess[]= $electionsParties;
        $electionsParties->setElections($this);
    }

    /**
     * @param  ChildElectionsParties $electionsParties The ChildElectionsParties object to remove.
     * @return $this|ChildElections The current object (for fluent API support)
     */
    public function removeElectionsParties(ChildElectionsParties $electionsParties)
    {
        if ($this->getElectionsPartiess()->contains($electionsParties)) {
            $pos = $this->collElectionsPartiess->search($electionsParties);
            $this->collElectionsPartiess->remove($pos);
            if (null === $this->electionsPartiessScheduledForDeletion) {
                $this->electionsPartiessScheduledForDeletion = clone $this->collElectionsPartiess;
                $this->electionsPartiessScheduledForDeletion->clear();
            }
            $this->electionsPartiessScheduledForDeletion[]= clone $electionsParties;
            $electionsParties->setElections(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Elections is new, it will return
     * an empty collection; or if this Elections has previously
     * been saved, it will retrieve related ElectionsPartiess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Elections.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionsParties[] List of ChildElectionsParties objects
     */
    public function getElectionsPartiessJoinParties(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionsPartiesQuery::create(null, $criteria);
        $query->joinWith('Parties', $joinBehavior);

        return $this->getElectionsPartiess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAssemblyTypes) {
            $this->aAssemblyTypes->removeElections($this);
        }
        if (null !== $this->aPopulationCensuses) {
            $this->aPopulationCensuses->removeElections($this);
        }
        $this->id = null;
        $this->slug = null;
        $this->assembly_type_id = null;
        $this->population_census_id = null;
        $this->active_suffrage = null;
        $this->official = null;
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
            if ($this->collElectionsIndependentCandidatess) {
                foreach ($this->collElectionsIndependentCandidatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collElectionsPartiess) {
                foreach ($this->collElectionsPartiess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collElectionsIndependentCandidatess = null;
        $this->collElectionsPartiess = null;
        $this->aAssemblyTypes = null;
        $this->aPopulationCensuses = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ElectionsTableMap::DEFAULT_STRING_FORMAT);
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
