<?php

namespace Base;

use \AssemblyType as ChildAssemblyType;
use \AssemblyTypeQuery as ChildAssemblyTypeQuery;
use \Election as ChildElection;
use \ElectionParty as ChildElectionParty;
use \ElectionPartyQuery as ChildElectionPartyQuery;
use \ElectionQuery as ChildElectionQuery;
use \IndependentCandidate as ChildIndependentCandidate;
use \IndependentCandidateQuery as ChildIndependentCandidateQuery;
use \PopulationCensus as ChildPopulationCensus;
use \PopulationCensusQuery as ChildPopulationCensusQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\ElectionPartyTableMap;
use Map\ElectionTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'elections' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Election implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ElectionTableMap';


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
     * The value for the threshold_percentage field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $threshold_percentage;

    /**
     * The value for the official field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $official;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildAssemblyType
     */
    protected $aAssemblyType;

    /**
     * @var        ChildPopulationCensus
     */
    protected $aPopulationCensus;

    /**
     * @var        ObjectCollection|ChildIndependentCandidate[] Collection to store aggregation of ChildIndependentCandidate objects.
     */
    protected $collIndependentCandidates;
    protected $collIndependentCandidatesPartial;

    /**
     * @var        ObjectCollection|ChildElectionParty[] Collection to store aggregation of ChildElectionParty objects.
     */
    protected $collElectionParties;
    protected $collElectionPartiesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildIndependentCandidate[]
     */
    protected $independentCandidatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildElectionParty[]
     */
    protected $electionPartiesScheduledForDeletion = null;

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
        $this->threshold_percentage = 0;
        $this->official = false;
    }

    /**
     * Initializes internal state of Base\Election object.
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
     * Compares this with another <code>Election</code> instance.  If
     * <code>obj</code> is an instance of <code>Election</code>, delegates to
     * <code>equals(Election)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Election The current object, for fluid interface
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
     * Get the [threshold_percentage] column value.
     *
     * @return int
     */
    public function getThresholdPercentage()
    {
        return $this->threshold_percentage;
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
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ElectionTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[ElectionTableMap::COL_SLUG] = true;
        }

        return $this;
    } // setSlug()

    /**
     * Set the value of [assembly_type_id] column.
     *
     * @param int $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setAssemblyTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->assembly_type_id !== $v) {
            $this->assembly_type_id = $v;
            $this->modifiedColumns[ElectionTableMap::COL_ASSEMBLY_TYPE_ID] = true;
        }

        if ($this->aAssemblyType !== null && $this->aAssemblyType->getId() !== $v) {
            $this->aAssemblyType = null;
        }

        return $this;
    } // setAssemblyTypeId()

    /**
     * Set the value of [population_census_id] column.
     *
     * @param int $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setPopulationCensusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->population_census_id !== $v) {
            $this->population_census_id = $v;
            $this->modifiedColumns[ElectionTableMap::COL_POPULATION_CENSUS_ID] = true;
        }

        if ($this->aPopulationCensus !== null && $this->aPopulationCensus->getId() !== $v) {
            $this->aPopulationCensus = null;
        }

        return $this;
    } // setPopulationCensusId()

    /**
     * Set the value of [active_suffrage] column.
     *
     * @param int $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setActiveSuffrage($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active_suffrage !== $v) {
            $this->active_suffrage = $v;
            $this->modifiedColumns[ElectionTableMap::COL_ACTIVE_SUFFRAGE] = true;
        }

        return $this;
    } // setActiveSuffrage()

    /**
     * Set the value of [threshold_percentage] column.
     *
     * @param int $v new value
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setThresholdPercentage($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->threshold_percentage !== $v) {
            $this->threshold_percentage = $v;
            $this->modifiedColumns[ElectionTableMap::COL_THRESHOLD_PERCENTAGE] = true;
        }

        return $this;
    } // setThresholdPercentage()

    /**
     * Sets the value of the [official] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Election The current object (for fluent API support)
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
            $this->modifiedColumns[ElectionTableMap::COL_OFFICIAL] = true;
        }

        return $this;
    } // setOfficial()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ElectionTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Election The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ElectionTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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

            if ($this->threshold_percentage !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ElectionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ElectionTableMap::translateFieldName('Slug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->slug = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ElectionTableMap::translateFieldName('AssemblyTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->assembly_type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ElectionTableMap::translateFieldName('PopulationCensusId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->population_census_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ElectionTableMap::translateFieldName('ActiveSuffrage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active_suffrage = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ElectionTableMap::translateFieldName('ThresholdPercentage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->threshold_percentage = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ElectionTableMap::translateFieldName('Official', TableMap::TYPE_PHPNAME, $indexType)];
            $this->official = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ElectionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ElectionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = ElectionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Election'), 0, $e);
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
        if ($this->aAssemblyType !== null && $this->assembly_type_id !== $this->aAssemblyType->getId()) {
            $this->aAssemblyType = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(ElectionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildElectionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAssemblyType = null;
            $this->aPopulationCensus = null;
            $this->collIndependentCandidates = null;

            $this->collElectionParties = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Election::setDeleted()
     * @see Election::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildElectionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ElectionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ElectionTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ElectionTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ElectionTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ElectionTableMap::addInstanceToPool($this);
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

            if ($this->aAssemblyType !== null) {
                if ($this->aAssemblyType->isModified() || $this->aAssemblyType->isNew()) {
                    $affectedRows += $this->aAssemblyType->save($con);
                }
                $this->setAssemblyType($this->aAssemblyType);
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

            if ($this->electionPartiesScheduledForDeletion !== null) {
                if (!$this->electionPartiesScheduledForDeletion->isEmpty()) {
                    \ElectionPartyQuery::create()
                        ->filterByPrimaryKeys($this->electionPartiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->electionPartiesScheduledForDeletion = null;
                }
            }

            if ($this->collElectionParties !== null) {
                foreach ($this->collElectionParties as $referrerFK) {
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

        $this->modifiedColumns[ElectionTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ElectionTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ElectionTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_SLUG)) {
            $modifiedColumns[':p' . $index++]  = 'slug';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_ASSEMBLY_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'assembly_type_id';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_POPULATION_CENSUS_ID)) {
            $modifiedColumns[':p' . $index++]  = 'population_census_id';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_ACTIVE_SUFFRAGE)) {
            $modifiedColumns[':p' . $index++]  = 'active_suffrage';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_THRESHOLD_PERCENTAGE)) {
            $modifiedColumns[':p' . $index++]  = 'threshold_percentage';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_OFFICIAL)) {
            $modifiedColumns[':p' . $index++]  = 'official';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(ElectionTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
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
                    case 'threshold_percentage':
                        $stmt->bindValue($identifier, $this->threshold_percentage, PDO::PARAM_INT);
                        break;
                    case 'official':
                        $stmt->bindValue($identifier, (int) $this->official, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = ElectionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getThresholdPercentage();
                break;
            case 6:
                return $this->getOfficial();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
                return $this->getUpdatedAt();
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

        if (isset($alreadyDumpedObjects['Election'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Election'][$this->hashCode()] = true;
        $keys = ElectionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSlug(),
            $keys[2] => $this->getAssemblyTypeId(),
            $keys[3] => $this->getPopulationCensusId(),
            $keys[4] => $this->getActiveSuffrage(),
            $keys[5] => $this->getThresholdPercentage(),
            $keys[6] => $this->getOfficial(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAssemblyType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assemblyType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assembly_types';
                        break;
                    default:
                        $key = 'AssemblyType';
                }

                $result[$key] = $this->aAssemblyType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collElectionParties) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'electionParties';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'elections_partiess';
                        break;
                    default:
                        $key = 'ElectionParties';
                }

                $result[$key] = $this->collElectionParties->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Election
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ElectionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Election
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
                $this->setThresholdPercentage($value);
                break;
            case 6:
                $this->setOfficial($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
                $this->setUpdatedAt($value);
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
        $keys = ElectionTableMap::getFieldNames($keyType);

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
            $this->setThresholdPercentage($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setOfficial($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCreatedAt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUpdatedAt($arr[$keys[8]]);
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
     * @return $this|\Election The current object, for fluid interface
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
        $criteria = new Criteria(ElectionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ElectionTableMap::COL_ID)) {
            $criteria->add(ElectionTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_SLUG)) {
            $criteria->add(ElectionTableMap::COL_SLUG, $this->slug);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_ASSEMBLY_TYPE_ID)) {
            $criteria->add(ElectionTableMap::COL_ASSEMBLY_TYPE_ID, $this->assembly_type_id);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_POPULATION_CENSUS_ID)) {
            $criteria->add(ElectionTableMap::COL_POPULATION_CENSUS_ID, $this->population_census_id);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_ACTIVE_SUFFRAGE)) {
            $criteria->add(ElectionTableMap::COL_ACTIVE_SUFFRAGE, $this->active_suffrage);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_THRESHOLD_PERCENTAGE)) {
            $criteria->add(ElectionTableMap::COL_THRESHOLD_PERCENTAGE, $this->threshold_percentage);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_OFFICIAL)) {
            $criteria->add(ElectionTableMap::COL_OFFICIAL, $this->official);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_CREATED_AT)) {
            $criteria->add(ElectionTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(ElectionTableMap::COL_UPDATED_AT)) {
            $criteria->add(ElectionTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildElectionQuery::create();
        $criteria->add(ElectionTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Election (or compatible) type.
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
        $copyObj->setThresholdPercentage($this->getThresholdPercentage());
        $copyObj->setOfficial($this->getOfficial());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getIndependentCandidates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIndependentCandidate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getElectionParties() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addElectionParty($relObj->copy($deepCopy));
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
     * @return \Election Clone of current object.
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
     * Declares an association between this object and a ChildAssemblyType object.
     *
     * @param  ChildAssemblyType $v
     * @return $this|\Election The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAssemblyType(ChildAssemblyType $v = null)
    {
        if ($v === null) {
            $this->setAssemblyTypeId(0);
        } else {
            $this->setAssemblyTypeId($v->getId());
        }

        $this->aAssemblyType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAssemblyType object, it will not be re-added.
        if ($v !== null) {
            $v->addElection($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAssemblyType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAssemblyType The associated ChildAssemblyType object.
     * @throws PropelException
     */
    public function getAssemblyType(ConnectionInterface $con = null)
    {
        if ($this->aAssemblyType === null && ($this->assembly_type_id != 0)) {
            $this->aAssemblyType = ChildAssemblyTypeQuery::create()->findPk($this->assembly_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAssemblyType->addElections($this);
             */
        }

        return $this->aAssemblyType;
    }

    /**
     * Declares an association between this object and a ChildPopulationCensus object.
     *
     * @param  ChildPopulationCensus $v
     * @return $this|\Election The current object (for fluent API support)
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
            $v->addElection($this);
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
                $this->aPopulationCensus->addElections($this);
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
        if ('IndependentCandidate' == $relationName) {
            $this->initIndependentCandidates();
            return;
        }
        if ('ElectionParty' == $relationName) {
            $this->initElectionParties();
            return;
        }
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
     * If this ChildElection is new, it will return
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
                    ->filterByElection($this)
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
     * @return $this|ChildElection The current object (for fluent API support)
     */
    public function setIndependentCandidates(Collection $independentCandidates, ConnectionInterface $con = null)
    {
        /** @var ChildIndependentCandidate[] $independentCandidatesToDelete */
        $independentCandidatesToDelete = $this->getIndependentCandidates(new Criteria(), $con)->diff($independentCandidates);


        $this->independentCandidatesScheduledForDeletion = $independentCandidatesToDelete;

        foreach ($independentCandidatesToDelete as $independentCandidateRemoved) {
            $independentCandidateRemoved->setElection(null);
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
                ->filterByElection($this)
                ->count($con);
        }

        return count($this->collIndependentCandidates);
    }

    /**
     * Method called to associate a ChildIndependentCandidate object to this object
     * through the ChildIndependentCandidate foreign key attribute.
     *
     * @param  ChildIndependentCandidate $l ChildIndependentCandidate
     * @return $this|\Election The current object (for fluent API support)
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
        $independentCandidate->setElection($this);
    }

    /**
     * @param  ChildIndependentCandidate $independentCandidate The ChildIndependentCandidate object to remove.
     * @return $this|ChildElection The current object (for fluent API support)
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
            $independentCandidate->setElection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Election is new, it will return
     * an empty collection; or if this Election has previously
     * been saved, it will retrieve related IndependentCandidates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Election.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildIndependentCandidate[] List of ChildIndependentCandidate objects
     */
    public function getIndependentCandidatesJoinConstituency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildIndependentCandidateQuery::create(null, $criteria);
        $query->joinWith('Constituency', $joinBehavior);

        return $this->getIndependentCandidates($query, $con);
    }

    /**
     * Clears out the collElectionParties collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addElectionParties()
     */
    public function clearElectionParties()
    {
        $this->collElectionParties = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collElectionParties collection loaded partially.
     */
    public function resetPartialElectionParties($v = true)
    {
        $this->collElectionPartiesPartial = $v;
    }

    /**
     * Initializes the collElectionParties collection.
     *
     * By default this just sets the collElectionParties collection to an empty array (like clearcollElectionParties());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initElectionParties($overrideExisting = true)
    {
        if (null !== $this->collElectionParties && !$overrideExisting) {
            return;
        }

        $collectionClassName = ElectionPartyTableMap::getTableMap()->getCollectionClassName();

        $this->collElectionParties = new $collectionClassName;
        $this->collElectionParties->setModel('\ElectionParty');
    }

    /**
     * Gets an array of ChildElectionParty objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildElection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildElectionParty[] List of ChildElectionParty objects
     * @throws PropelException
     */
    public function getElectionParties(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionPartiesPartial && !$this->isNew();
        if (null === $this->collElectionParties || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collElectionParties) {
                // return empty collection
                $this->initElectionParties();
            } else {
                $collElectionParties = ChildElectionPartyQuery::create(null, $criteria)
                    ->filterByElection($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collElectionPartiesPartial && count($collElectionParties)) {
                        $this->initElectionParties(false);

                        foreach ($collElectionParties as $obj) {
                            if (false == $this->collElectionParties->contains($obj)) {
                                $this->collElectionParties->append($obj);
                            }
                        }

                        $this->collElectionPartiesPartial = true;
                    }

                    return $collElectionParties;
                }

                if ($partial && $this->collElectionParties) {
                    foreach ($this->collElectionParties as $obj) {
                        if ($obj->isNew()) {
                            $collElectionParties[] = $obj;
                        }
                    }
                }

                $this->collElectionParties = $collElectionParties;
                $this->collElectionPartiesPartial = false;
            }
        }

        return $this->collElectionParties;
    }

    /**
     * Sets a collection of ChildElectionParty objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $electionParties A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildElection The current object (for fluent API support)
     */
    public function setElectionParties(Collection $electionParties, ConnectionInterface $con = null)
    {
        /** @var ChildElectionParty[] $electionPartiesToDelete */
        $electionPartiesToDelete = $this->getElectionParties(new Criteria(), $con)->diff($electionParties);


        $this->electionPartiesScheduledForDeletion = $electionPartiesToDelete;

        foreach ($electionPartiesToDelete as $electionPartyRemoved) {
            $electionPartyRemoved->setElection(null);
        }

        $this->collElectionParties = null;
        foreach ($electionParties as $electionParty) {
            $this->addElectionParty($electionParty);
        }

        $this->collElectionParties = $electionParties;
        $this->collElectionPartiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ElectionParty objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ElectionParty objects.
     * @throws PropelException
     */
    public function countElectionParties(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collElectionPartiesPartial && !$this->isNew();
        if (null === $this->collElectionParties || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collElectionParties) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getElectionParties());
            }

            $query = ChildElectionPartyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByElection($this)
                ->count($con);
        }

        return count($this->collElectionParties);
    }

    /**
     * Method called to associate a ChildElectionParty object to this object
     * through the ChildElectionParty foreign key attribute.
     *
     * @param  ChildElectionParty $l ChildElectionParty
     * @return $this|\Election The current object (for fluent API support)
     */
    public function addElectionParty(ChildElectionParty $l)
    {
        if ($this->collElectionParties === null) {
            $this->initElectionParties();
            $this->collElectionPartiesPartial = true;
        }

        if (!$this->collElectionParties->contains($l)) {
            $this->doAddElectionParty($l);

            if ($this->electionPartiesScheduledForDeletion and $this->electionPartiesScheduledForDeletion->contains($l)) {
                $this->electionPartiesScheduledForDeletion->remove($this->electionPartiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildElectionParty $electionParty The ChildElectionParty object to add.
     */
    protected function doAddElectionParty(ChildElectionParty $electionParty)
    {
        $this->collElectionParties[]= $electionParty;
        $electionParty->setElection($this);
    }

    /**
     * @param  ChildElectionParty $electionParty The ChildElectionParty object to remove.
     * @return $this|ChildElection The current object (for fluent API support)
     */
    public function removeElectionParty(ChildElectionParty $electionParty)
    {
        if ($this->getElectionParties()->contains($electionParty)) {
            $pos = $this->collElectionParties->search($electionParty);
            $this->collElectionParties->remove($pos);
            if (null === $this->electionPartiesScheduledForDeletion) {
                $this->electionPartiesScheduledForDeletion = clone $this->collElectionParties;
                $this->electionPartiesScheduledForDeletion->clear();
            }
            $this->electionPartiesScheduledForDeletion[]= clone $electionParty;
            $electionParty->setElection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Election is new, it will return
     * an empty collection; or if this Election has previously
     * been saved, it will retrieve related ElectionParties from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Election.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildElectionParty[] List of ChildElectionParty objects
     */
    public function getElectionPartiesJoinParty(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildElectionPartyQuery::create(null, $criteria);
        $query->joinWith('Party', $joinBehavior);

        return $this->getElectionParties($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAssemblyType) {
            $this->aAssemblyType->removeElection($this);
        }
        if (null !== $this->aPopulationCensus) {
            $this->aPopulationCensus->removeElection($this);
        }
        $this->id = null;
        $this->slug = null;
        $this->assembly_type_id = null;
        $this->population_census_id = null;
        $this->active_suffrage = null;
        $this->threshold_percentage = null;
        $this->official = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collIndependentCandidates) {
                foreach ($this->collIndependentCandidates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collElectionParties) {
                foreach ($this->collElectionParties as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collIndependentCandidates = null;
        $this->collElectionParties = null;
        $this->aAssemblyType = null;
        $this->aPopulationCensus = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ElectionTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildElection The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ElectionTableMap::COL_UPDATED_AT] = true;

        return $this;
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
