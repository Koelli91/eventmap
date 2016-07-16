<?php

namespace Map;

use \Event;
use \EventQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'event' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Event';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'event.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'event.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'event.description';

    /**
     * the column name for the longitude field
     */
    const COL_LONGITUDE = 'event.longitude';

    /**
     * the column name for the latitude field
     */
    const COL_LATITUDE = 'event.latitude';

    /**
     * the column name for the location_name field
     */
    const COL_LOCATION_NAME = 'event.location_name';

    /**
     * the column name for the street_no field
     */
    const COL_STREET_NO = 'event.street_no';

    /**
     * the column name for the zip_code field
     */
    const COL_ZIP_CODE = 'event.zip_code';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'event.city';

    /**
     * the column name for the country field
     */
    const COL_COUNTRY = 'event.country';

    /**
     * the column name for the begin field
     */
    const COL_BEGIN = 'event.begin';

    /**
     * the column name for the end field
     */
    const COL_END = 'event.end';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'Longitude', 'Latitude', 'LocationName', 'StreetNo', 'ZipCode', 'City', 'Country', 'Begin', 'End', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'longitude', 'latitude', 'locationName', 'streetNo', 'zipCode', 'city', 'country', 'begin', 'end', ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID, EventTableMap::COL_NAME, EventTableMap::COL_DESCRIPTION, EventTableMap::COL_LONGITUDE, EventTableMap::COL_LATITUDE, EventTableMap::COL_LOCATION_NAME, EventTableMap::COL_STREET_NO, EventTableMap::COL_ZIP_CODE, EventTableMap::COL_CITY, EventTableMap::COL_COUNTRY, EventTableMap::COL_BEGIN, EventTableMap::COL_END, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'longitude', 'latitude', 'location_name', 'street_no', 'zip_code', 'city', 'country', 'begin', 'end', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'Longitude' => 3, 'Latitude' => 4, 'LocationName' => 5, 'StreetNo' => 6, 'ZipCode' => 7, 'City' => 8, 'Country' => 9, 'Begin' => 10, 'End' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'longitude' => 3, 'latitude' => 4, 'locationName' => 5, 'streetNo' => 6, 'zipCode' => 7, 'city' => 8, 'country' => 9, 'begin' => 10, 'end' => 11, ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID => 0, EventTableMap::COL_NAME => 1, EventTableMap::COL_DESCRIPTION => 2, EventTableMap::COL_LONGITUDE => 3, EventTableMap::COL_LATITUDE => 4, EventTableMap::COL_LOCATION_NAME => 5, EventTableMap::COL_STREET_NO => 6, EventTableMap::COL_ZIP_CODE => 7, EventTableMap::COL_CITY => 8, EventTableMap::COL_COUNTRY => 9, EventTableMap::COL_BEGIN => 10, EventTableMap::COL_END => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'longitude' => 3, 'latitude' => 4, 'location_name' => 5, 'street_no' => 6, 'zip_code' => 7, 'city' => 8, 'country' => 9, 'begin' => 10, 'end' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('event');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Event');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', true, 1000, null);
        $this->addColumn('longitude', 'Longitude', 'FLOAT', true, null, null);
        $this->addColumn('latitude', 'Latitude', 'FLOAT', true, null, null);
        $this->addColumn('location_name', 'LocationName', 'VARCHAR', false, 255, null);
        $this->addColumn('street_no', 'StreetNo', 'VARCHAR', false, 255, null);
        $this->addColumn('zip_code', 'ZipCode', 'VARCHAR', false, 5, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 255, null);
        $this->addColumn('country', 'Country', 'VARCHAR', false, 255, null);
        $this->addColumn('begin', 'Begin', 'TIMESTAMP', true, null, null);
        $this->addColumn('end', 'End', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EventCategory', '\\EventCategory', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':event_id',
    1 => ':id',
  ),
), null, null, 'EventCategories', false);
        $this->addRelation('Image', '\\Image', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':event_id',
    1 => ':id',
  ),
), null, null, 'Images', false);
        $this->addRelation('Category', '\\Category', RelationMap::MANY_TO_MANY, array(), null, null, 'Categories');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }
    
    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Event object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventTableMap::OM_CLASS;
            /** @var Event $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();
    
        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = EventTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Event $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(EventTableMap::COL_ID);
            $criteria->addSelectColumn(EventTableMap::COL_NAME);
            $criteria->addSelectColumn(EventTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(EventTableMap::COL_LONGITUDE);
            $criteria->addSelectColumn(EventTableMap::COL_LATITUDE);
            $criteria->addSelectColumn(EventTableMap::COL_LOCATION_NAME);
            $criteria->addSelectColumn(EventTableMap::COL_STREET_NO);
            $criteria->addSelectColumn(EventTableMap::COL_ZIP_CODE);
            $criteria->addSelectColumn(EventTableMap::COL_CITY);
            $criteria->addSelectColumn(EventTableMap::COL_COUNTRY);
            $criteria->addSelectColumn(EventTableMap::COL_BEGIN);
            $criteria->addSelectColumn(EventTableMap::COL_END);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.longitude');
            $criteria->addSelectColumn($alias . '.latitude');
            $criteria->addSelectColumn($alias . '.location_name');
            $criteria->addSelectColumn($alias . '.street_no');
            $criteria->addSelectColumn($alias . '.zip_code');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.begin');
            $criteria->addSelectColumn($alias . '.end');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Event object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Event) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventTableMap::DATABASE_NAME);
            $criteria->add(EventTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EventQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed               $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }

        if ($criteria->containsKey(EventTableMap::COL_ID) && $criteria->keyContainsValue(EventTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventTableMap::buildTableMap();
