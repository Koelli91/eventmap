<?php

namespace Base;

use \Category as ChildCategory;
use \CategoryQuery as ChildCategoryQuery;
use \Event as ChildEvent;
use \EventCategory as ChildEventCategory;
use \EventCategoryQuery as ChildEventCategoryQuery;
use \EventQuery as ChildEventQuery;
use \Image as ChildImage;
use \ImageQuery as ChildImageQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\EventCategoryTableMap;
use Map\EventTableMap;
use Map\ImageTableMap;
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
 * Base class that represents a row from the 'event' table.
 *
 * 
 *
 * @package    propel.generator..Base
 */
abstract class Event implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\EventTableMap';


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
     * The value for the name field.
     * 
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     * 
     * @var        string
     */
    protected $description;

    /**
     * The value for the longitude field.
     * 
     * @var        double
     */
    protected $longitude;

    /**
     * The value for the latitude field.
     * 
     * @var        double
     */
    protected $latitude;

    /**
     * The value for the location_name field.
     * 
     * @var        string
     */
    protected $location_name;

    /**
     * The value for the street_no field.
     * 
     * @var        string
     */
    protected $street_no;

    /**
     * The value for the zip_code field.
     * 
     * @var        string
     */
    protected $zip_code;

    /**
     * The value for the city field.
     * 
     * @var        string
     */
    protected $city;

    /**
     * The value for the country field.
     * 
     * @var        string
     */
    protected $country;

    /**
     * The value for the begin field.
     * 
     * @var        DateTime
     */
    protected $begin;

    /**
     * The value for the end field.
     * 
     * @var        DateTime
     */
    protected $end;

    /**
     * @var        ObjectCollection|ChildEventCategory[] Collection to store aggregation of ChildEventCategory objects.
     */
    protected $collEventCategories;
    protected $collEventCategoriesPartial;

    /**
     * @var        ObjectCollection|ChildImage[] Collection to store aggregation of ChildImage objects.
     */
    protected $collImages;
    protected $collImagesPartial;

    /**
     * @var        ObjectCollection|ChildCategory[] Cross Collection to store aggregation of ChildCategory objects.
     */
    protected $collCategories;

    /**
     * @var bool
     */
    protected $collCategoriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCategory[]
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventCategory[]
     */
    protected $eventCategoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildImage[]
     */
    protected $imagesScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Event object.
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
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Event The current object, for fluid interface
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
     * Get the [name] column value.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [description] column value.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [longitude] column value.
     * 
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get the [latitude] column value.
     * 
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the [location_name] column value.
     * 
     * @return string
     */
    public function getLocationName()
    {
        return $this->location_name;
    }

    /**
     * Get the [street_no] column value.
     * 
     * @return string
     */
    public function getStreetNo()
    {
        return $this->street_no;
    }

    /**
     * Get the [zip_code] column value.
     * 
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Get the [city] column value.
     * 
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [country] column value.
     * 
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get the [optionally formatted] temporal [begin] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBegin($format = NULL)
    {
        if ($format === null) {
            return $this->begin;
        } else {
            return $this->begin instanceof \DateTimeInterface ? $this->begin->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [end] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEnd($format = NULL)
    {
        if ($format === null) {
            return $this->end;
        } else {
            return $this->end instanceof \DateTimeInterface ? $this->end->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EventTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[EventTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [longitude] column.
     * 
     * @param double $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setLongitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->longitude !== $v) {
            $this->longitude = $v;
            $this->modifiedColumns[EventTableMap::COL_LONGITUDE] = true;
        }

        return $this;
    } // setLongitude()

    /**
     * Set the value of [latitude] column.
     * 
     * @param double $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setLatitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->latitude !== $v) {
            $this->latitude = $v;
            $this->modifiedColumns[EventTableMap::COL_LATITUDE] = true;
        }

        return $this;
    } // setLatitude()

    /**
     * Set the value of [location_name] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setLocationName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_name !== $v) {
            $this->location_name = $v;
            $this->modifiedColumns[EventTableMap::COL_LOCATION_NAME] = true;
        }

        return $this;
    } // setLocationName()

    /**
     * Set the value of [street_no] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setStreetNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->street_no !== $v) {
            $this->street_no = $v;
            $this->modifiedColumns[EventTableMap::COL_STREET_NO] = true;
        }

        return $this;
    } // setStreetNo()

    /**
     * Set the value of [zip_code] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setZipCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip_code !== $v) {
            $this->zip_code = $v;
            $this->modifiedColumns[EventTableMap::COL_ZIP_CODE] = true;
        }

        return $this;
    } // setZipCode()

    /**
     * Set the value of [city] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[EventTableMap::COL_CITY] = true;
        }

        return $this;
    } // setCity()

    /**
     * Set the value of [country] column.
     * 
     * @param string $v new value
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[EventTableMap::COL_COUNTRY] = true;
        }

        return $this;
    } // setCountry()

    /**
     * Sets the value of [begin] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setBegin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->begin !== null || $dt !== null) {
            if ($this->begin === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->begin->format("Y-m-d H:i:s.u")) {
                $this->begin = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_BEGIN] = true;
            }
        } // if either are not null

        return $this;
    } // setBegin()

    /**
     * Sets the value of [end] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Event The current object (for fluent API support)
     */
    public function setEnd($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->end !== null || $dt !== null) {
            if ($this->end === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->end->format("Y-m-d H:i:s.u")) {
                $this->end = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_END] = true;
            }
        } // if either are not null

        return $this;
    } // setEnd()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('Longitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->longitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTableMap::translateFieldName('Latitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->latitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTableMap::translateFieldName('LocationName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTableMap::translateFieldName('StreetNo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->street_no = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventTableMap::translateFieldName('ZipCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->zip_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EventTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EventTableMap::translateFieldName('Begin', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->begin = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EventTableMap::translateFieldName('End', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->end = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = EventTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Event'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collEventCategories = null;

            $this->collImages = null;

            $this->collCategories = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
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

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
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
                EventTableMap::addInstanceToPool($this);
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

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->categoriesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \EventCategoryQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->categoriesScheduledForDeletion = null;
                }

            }

            if ($this->collCategories) {
                foreach ($this->collCategories as $category) {
                    if (!$category->isDeleted() && ($category->isNew() || $category->isModified())) {
                        $category->save($con);
                    }
                }
            }


            if ($this->eventCategoriesScheduledForDeletion !== null) {
                if (!$this->eventCategoriesScheduledForDeletion->isEmpty()) {
                    \EventCategoryQuery::create()
                        ->filterByPrimaryKeys($this->eventCategoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventCategoriesScheduledForDeletion = null;
                }
            }

            if ($this->collEventCategories !== null) {
                foreach ($this->collEventCategories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->imagesScheduledForDeletion !== null) {
                if (!$this->imagesScheduledForDeletion->isEmpty()) {
                    \ImageQuery::create()
                        ->filterByPrimaryKeys($this->imagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->imagesScheduledForDeletion = null;
                }
            }

            if ($this->collImages !== null) {
                foreach ($this->collImages as $referrerFK) {
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

        $this->modifiedColumns[EventTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(EventTableMap::COL_LONGITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'longitude';
        }
        if ($this->isColumnModified(EventTableMap::COL_LATITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'latitude';
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'location_name';
        }
        if ($this->isColumnModified(EventTableMap::COL_STREET_NO)) {
            $modifiedColumns[':p' . $index++]  = 'street_no';
        }
        if ($this->isColumnModified(EventTableMap::COL_ZIP_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'zip_code';
        }
        if ($this->isColumnModified(EventTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'city';
        }
        if ($this->isColumnModified(EventTableMap::COL_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'country';
        }
        if ($this->isColumnModified(EventTableMap::COL_BEGIN)) {
            $modifiedColumns[':p' . $index++]  = 'begin';
        }
        if ($this->isColumnModified(EventTableMap::COL_END)) {
            $modifiedColumns[':p' . $index++]  = 'end';
        }

        $sql = sprintf(
            'INSERT INTO event (%s) VALUES (%s)',
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
                    case 'name':                        
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'description':                        
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'longitude':                        
                        $stmt->bindValue($identifier, $this->longitude, PDO::PARAM_STR);
                        break;
                    case 'latitude':                        
                        $stmt->bindValue($identifier, $this->latitude, PDO::PARAM_STR);
                        break;
                    case 'location_name':                        
                        $stmt->bindValue($identifier, $this->location_name, PDO::PARAM_STR);
                        break;
                    case 'street_no':                        
                        $stmt->bindValue($identifier, $this->street_no, PDO::PARAM_STR);
                        break;
                    case 'zip_code':                        
                        $stmt->bindValue($identifier, $this->zip_code, PDO::PARAM_STR);
                        break;
                    case 'city':                        
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case 'country':                        
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_STR);
                        break;
                    case 'begin':                        
                        $stmt->bindValue($identifier, $this->begin ? $this->begin->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'end':                        
                        $stmt->bindValue($identifier, $this->end ? $this->end->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getLongitude();
                break;
            case 4:
                return $this->getLatitude();
                break;
            case 5:
                return $this->getLocationName();
                break;
            case 6:
                return $this->getStreetNo();
                break;
            case 7:
                return $this->getZipCode();
                break;
            case 8:
                return $this->getCity();
                break;
            case 9:
                return $this->getCountry();
                break;
            case 10:
                return $this->getBegin();
                break;
            case 11:
                return $this->getEnd();
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

        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getLongitude(),
            $keys[4] => $this->getLatitude(),
            $keys[5] => $this->getLocationName(),
            $keys[6] => $this->getStreetNo(),
            $keys[7] => $this->getZipCode(),
            $keys[8] => $this->getCity(),
            $keys[9] => $this->getCountry(),
            $keys[10] => $this->getBegin(),
            $keys[11] => $this->getEnd(),
        );
        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }
        
        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collEventCategories) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventCategories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_categories';
                        break;
                    default:
                        $key = 'EventCategories';
                }
        
                $result[$key] = $this->collEventCategories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collImages) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'images';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'images';
                        break;
                    default:
                        $key = 'Images';
                }
        
                $result[$key] = $this->collImages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Event
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Event
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setLongitude($value);
                break;
            case 4:
                $this->setLatitude($value);
                break;
            case 5:
                $this->setLocationName($value);
                break;
            case 6:
                $this->setStreetNo($value);
                break;
            case 7:
                $this->setZipCode($value);
                break;
            case 8:
                $this->setCity($value);
                break;
            case 9:
                $this->setCountry($value);
                break;
            case 10:
                $this->setBegin($value);
                break;
            case 11:
                $this->setEnd($value);
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
        $keys = EventTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLongitude($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLatitude($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLocationName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setStreetNo($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setZipCode($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCity($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCountry($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setBegin($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setEnd($arr[$keys[11]]);
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
     * @return $this|\Event The current object, for fluid interface
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
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $criteria->add(EventTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $criteria->add(EventTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventTableMap::COL_DESCRIPTION)) {
            $criteria->add(EventTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(EventTableMap::COL_LONGITUDE)) {
            $criteria->add(EventTableMap::COL_LONGITUDE, $this->longitude);
        }
        if ($this->isColumnModified(EventTableMap::COL_LATITUDE)) {
            $criteria->add(EventTableMap::COL_LATITUDE, $this->latitude);
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION_NAME)) {
            $criteria->add(EventTableMap::COL_LOCATION_NAME, $this->location_name);
        }
        if ($this->isColumnModified(EventTableMap::COL_STREET_NO)) {
            $criteria->add(EventTableMap::COL_STREET_NO, $this->street_no);
        }
        if ($this->isColumnModified(EventTableMap::COL_ZIP_CODE)) {
            $criteria->add(EventTableMap::COL_ZIP_CODE, $this->zip_code);
        }
        if ($this->isColumnModified(EventTableMap::COL_CITY)) {
            $criteria->add(EventTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(EventTableMap::COL_COUNTRY)) {
            $criteria->add(EventTableMap::COL_COUNTRY, $this->country);
        }
        if ($this->isColumnModified(EventTableMap::COL_BEGIN)) {
            $criteria->add(EventTableMap::COL_BEGIN, $this->begin);
        }
        if ($this->isColumnModified(EventTableMap::COL_END)) {
            $criteria->add(EventTableMap::COL_END, $this->end);
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
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Event (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setLongitude($this->getLongitude());
        $copyObj->setLatitude($this->getLatitude());
        $copyObj->setLocationName($this->getLocationName());
        $copyObj->setStreetNo($this->getStreetNo());
        $copyObj->setZipCode($this->getZipCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setBegin($this->getBegin());
        $copyObj->setEnd($this->getEnd());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEventCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImage($relObj->copy($deepCopy));
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
     * @return \Event Clone of current object.
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
        if ('EventCategory' == $relationName) {
            return $this->initEventCategories();
        }
        if ('Image' == $relationName) {
            return $this->initImages();
        }
    }

    /**
     * Clears out the collEventCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventCategories()
     */
    public function clearEventCategories()
    {
        $this->collEventCategories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventCategories collection loaded partially.
     */
    public function resetPartialEventCategories($v = true)
    {
        $this->collEventCategoriesPartial = $v;
    }

    /**
     * Initializes the collEventCategories collection.
     *
     * By default this just sets the collEventCategories collection to an empty array (like clearcollEventCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventCategories($overrideExisting = true)
    {
        if (null !== $this->collEventCategories && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventCategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collEventCategories = new $collectionClassName;
        $this->collEventCategories->setModel('\EventCategory');
    }

    /**
     * Gets an array of ChildEventCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventCategory[] List of ChildEventCategory objects
     * @throws PropelException
     */
    public function getEventCategories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventCategoriesPartial && !$this->isNew();
        if (null === $this->collEventCategories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventCategories) {
                // return empty collection
                $this->initEventCategories();
            } else {
                $collEventCategories = ChildEventCategoryQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventCategoriesPartial && count($collEventCategories)) {
                        $this->initEventCategories(false);

                        foreach ($collEventCategories as $obj) {
                            if (false == $this->collEventCategories->contains($obj)) {
                                $this->collEventCategories->append($obj);
                            }
                        }

                        $this->collEventCategoriesPartial = true;
                    }

                    return $collEventCategories;
                }

                if ($partial && $this->collEventCategories) {
                    foreach ($this->collEventCategories as $obj) {
                        if ($obj->isNew()) {
                            $collEventCategories[] = $obj;
                        }
                    }
                }

                $this->collEventCategories = $collEventCategories;
                $this->collEventCategoriesPartial = false;
            }
        }

        return $this->collEventCategories;
    }

    /**
     * Sets a collection of ChildEventCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventCategories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventCategories(Collection $eventCategories, ConnectionInterface $con = null)
    {
        /** @var ChildEventCategory[] $eventCategoriesToDelete */
        $eventCategoriesToDelete = $this->getEventCategories(new Criteria(), $con)->diff($eventCategories);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventCategoriesScheduledForDeletion = clone $eventCategoriesToDelete;

        foreach ($eventCategoriesToDelete as $eventCategoryRemoved) {
            $eventCategoryRemoved->setEvent(null);
        }

        $this->collEventCategories = null;
        foreach ($eventCategories as $eventCategory) {
            $this->addEventCategory($eventCategory);
        }

        $this->collEventCategories = $eventCategories;
        $this->collEventCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventCategory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventCategory objects.
     * @throws PropelException
     */
    public function countEventCategories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventCategoriesPartial && !$this->isNew();
        if (null === $this->collEventCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventCategories());
            }

            $query = ChildEventCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventCategories);
    }

    /**
     * Method called to associate a ChildEventCategory object to this object
     * through the ChildEventCategory foreign key attribute.
     *
     * @param  ChildEventCategory $l ChildEventCategory
     * @return $this|\Event The current object (for fluent API support)
     */
    public function addEventCategory(ChildEventCategory $l)
    {
        if ($this->collEventCategories === null) {
            $this->initEventCategories();
            $this->collEventCategoriesPartial = true;
        }

        if (!$this->collEventCategories->contains($l)) {
            $this->doAddEventCategory($l);

            if ($this->eventCategoriesScheduledForDeletion and $this->eventCategoriesScheduledForDeletion->contains($l)) {
                $this->eventCategoriesScheduledForDeletion->remove($this->eventCategoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventCategory $eventCategory The ChildEventCategory object to add.
     */
    protected function doAddEventCategory(ChildEventCategory $eventCategory)
    {
        $this->collEventCategories[]= $eventCategory;
        $eventCategory->setEvent($this);
    }

    /**
     * @param  ChildEventCategory $eventCategory The ChildEventCategory object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventCategory(ChildEventCategory $eventCategory)
    {
        if ($this->getEventCategories()->contains($eventCategory)) {
            $pos = $this->collEventCategories->search($eventCategory);
            $this->collEventCategories->remove($pos);
            if (null === $this->eventCategoriesScheduledForDeletion) {
                $this->eventCategoriesScheduledForDeletion = clone $this->collEventCategories;
                $this->eventCategoriesScheduledForDeletion->clear();
            }
            $this->eventCategoriesScheduledForDeletion[]= clone $eventCategory;
            $eventCategory->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventCategories from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventCategory[] List of ChildEventCategory objects
     */
    public function getEventCategoriesJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventCategoryQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getEventCategories($query, $con);
    }

    /**
     * Clears out the collImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addImages()
     */
    public function clearImages()
    {
        $this->collImages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collImages collection loaded partially.
     */
    public function resetPartialImages($v = true)
    {
        $this->collImagesPartial = $v;
    }

    /**
     * Initializes the collImages collection.
     *
     * By default this just sets the collImages collection to an empty array (like clearcollImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImages($overrideExisting = true)
    {
        if (null !== $this->collImages && !$overrideExisting) {
            return;
        }

        $collectionClassName = ImageTableMap::getTableMap()->getCollectionClassName();

        $this->collImages = new $collectionClassName;
        $this->collImages->setModel('\Image');
    }

    /**
     * Gets an array of ChildImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @throws PropelException
     */
    public function getImages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                // return empty collection
                $this->initImages();
            } else {
                $collImages = ChildImageQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collImagesPartial && count($collImages)) {
                        $this->initImages(false);

                        foreach ($collImages as $obj) {
                            if (false == $this->collImages->contains($obj)) {
                                $this->collImages->append($obj);
                            }
                        }

                        $this->collImagesPartial = true;
                    }

                    return $collImages;
                }

                if ($partial && $this->collImages) {
                    foreach ($this->collImages as $obj) {
                        if ($obj->isNew()) {
                            $collImages[] = $obj;
                        }
                    }
                }

                $this->collImages = $collImages;
                $this->collImagesPartial = false;
            }
        }

        return $this->collImages;
    }

    /**
     * Sets a collection of ChildImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $images A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setImages(Collection $images, ConnectionInterface $con = null)
    {
        /** @var ChildImage[] $imagesToDelete */
        $imagesToDelete = $this->getImages(new Criteria(), $con)->diff($images);

        
        $this->imagesScheduledForDeletion = $imagesToDelete;

        foreach ($imagesToDelete as $imageRemoved) {
            $imageRemoved->setEvent(null);
        }

        $this->collImages = null;
        foreach ($images as $image) {
            $this->addImage($image);
        }

        $this->collImages = $images;
        $this->collImagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Image objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Image objects.
     * @throws PropelException
     */
    public function countImages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImages());
            }

            $query = ChildImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collImages);
    }

    /**
     * Method called to associate a ChildImage object to this object
     * through the ChildImage foreign key attribute.
     *
     * @param  ChildImage $l ChildImage
     * @return $this|\Event The current object (for fluent API support)
     */
    public function addImage(ChildImage $l)
    {
        if ($this->collImages === null) {
            $this->initImages();
            $this->collImagesPartial = true;
        }

        if (!$this->collImages->contains($l)) {
            $this->doAddImage($l);

            if ($this->imagesScheduledForDeletion and $this->imagesScheduledForDeletion->contains($l)) {
                $this->imagesScheduledForDeletion->remove($this->imagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildImage $image The ChildImage object to add.
     */
    protected function doAddImage(ChildImage $image)
    {
        $this->collImages[]= $image;
        $image->setEvent($this);
    }

    /**
     * @param  ChildImage $image The ChildImage object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeImage(ChildImage $image)
    {
        if ($this->getImages()->contains($image)) {
            $pos = $this->collImages->search($image);
            $this->collImages->remove($pos);
            if (null === $this->imagesScheduledForDeletion) {
                $this->imagesScheduledForDeletion = clone $this->collImages;
                $this->imagesScheduledForDeletion->clear();
            }
            $this->imagesScheduledForDeletion[]= clone $image;
            $image->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     */
    public function getImagesJoinImagetype(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Imagetype', $joinBehavior);

        return $this->getImages($query, $con);
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collCategories crossRef collection.
     *
     * By default this just sets the collCategories collection to an empty collection (like clearCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCategories()
    {
        $collectionClassName = EventCategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collCategories = new $collectionClassName;
        $this->collCategoriesPartial = true;
        $this->collCategories->setModel('\Category');
    }

    /**
     * Checks if the collCategories collection is loaded.
     *
     * @return bool
     */
    public function isCategoriesLoaded()
    {
        return null !== $this->collCategories;
    }

    /**
     * Gets a collection of ChildCategory objects related by a many-to-many relationship
     * to the current object by way of the event_category cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildCategory[] List of ChildCategory objects
     */
    public function getCategories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCategories) {
                    $this->initCategories();
                }
            } else {

                $query = ChildCategoryQuery::create(null, $criteria)
                    ->filterByEvent($this);
                $collCategories = $query->find($con);
                if (null !== $criteria) {
                    return $collCategories;
                }

                if ($partial && $this->collCategories) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collCategories as $obj) {
                        if (!$collCategories->contains($obj)) {
                            $collCategories[] = $obj;
                        }
                    }
                }

                $this->collCategories = $collCategories;
                $this->collCategoriesPartial = false;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of Category objects related by a many-to-many relationship
     * to the current object by way of the event_category cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $categories A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setCategories(Collection $categories, ConnectionInterface $con = null)
    {
        $this->clearCategories();
        $currentCategories = $this->getCategories();

        $categoriesScheduledForDeletion = $currentCategories->diff($categories);

        foreach ($categoriesScheduledForDeletion as $toDelete) {
            $this->removeCategory($toDelete);
        }

        foreach ($categories as $category) {
            if (!$currentCategories->contains($category)) {
                $this->doAddCategory($category);
            }
        }

        $this->collCategoriesPartial = false;
        $this->collCategories = $categories;

        return $this;
    }

    /**
     * Gets the number of Category objects related by a many-to-many relationship
     * to the current object by way of the event_category cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Category objects
     */
    public function countCategories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getCategories());
                }

                $query = ChildCategoryQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collCategories);
        }
    }

    /**
     * Associate a ChildCategory to this object
     * through the event_category cross reference table.
     * 
     * @param ChildCategory $category
     * @return ChildEvent The current object (for fluent API support)
     */
    public function addCategory(ChildCategory $category)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
        }

        if (!$this->getCategories()->contains($category)) {
            // only add it if the **same** object is not already associated
            $this->collCategories->push($category);
            $this->doAddCategory($category);
        }

        return $this;
    }

    /**
     * 
     * @param ChildCategory $category
     */
    protected function doAddCategory(ChildCategory $category)
    {
        $eventCategory = new ChildEventCategory();

        $eventCategory->setCategory($category);

        $eventCategory->setEvent($this);

        $this->addEventCategory($eventCategory);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$category->isEventsLoaded()) {
            $category->initEvents();
            $category->getEvents()->push($this);
        } elseif (!$category->getEvents()->contains($this)) {
            $category->getEvents()->push($this);
        }

    }

    /**
     * Remove category of this object
     * through the event_category cross reference table.
     * 
     * @param ChildCategory $category
     * @return ChildEvent The current object (for fluent API support)
     */
    public function removeCategory(ChildCategory $category)
    {
        if ($this->getCategories()->contains($category)) { $eventCategory = new ChildEventCategory();

            $eventCategory->setCategory($category);
            if ($category->isEventsLoaded()) {
                //remove the back reference if available
                $category->getEvents()->removeObject($this);
            }

            $eventCategory->setEvent($this);
            $this->removeEventCategory(clone $eventCategory);
            $eventCategory->clear();

            $this->collCategories->remove($this->collCategories->search($category));
            
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }

            $this->categoriesScheduledForDeletion->push($category);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->longitude = null;
        $this->latitude = null;
        $this->location_name = null;
        $this->street_no = null;
        $this->zip_code = null;
        $this->city = null;
        $this->country = null;
        $this->begin = null;
        $this->end = null;
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
            if ($this->collEventCategories) {
                foreach ($this->collEventCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collImages) {
                foreach ($this->collImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEventCategories = null;
        $this->collImages = null;
        $this->collCategories = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
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
