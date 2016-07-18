<?php

namespace Base;

use \Event as ChildEvent;
use \EventQuery as ChildEventQuery;
use \Exception;
use \PDO;
use Map\EventTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event' table.
 *
 * 
 *
 * @method     ChildEventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildEventQuery orderByLongitude($order = Criteria::ASC) Order by the longitude column
 * @method     ChildEventQuery orderByLatitude($order = Criteria::ASC) Order by the latitude column
 * @method     ChildEventQuery orderByKoordx($order = Criteria::ASC) Order by the koordX column
 * @method     ChildEventQuery orderByKoordy($order = Criteria::ASC) Order by the koordY column
 * @method     ChildEventQuery orderByKoordz($order = Criteria::ASC) Order by the koordZ column
 * @method     ChildEventQuery orderByLocationName($order = Criteria::ASC) Order by the location_name column
 * @method     ChildEventQuery orderByStreetNo($order = Criteria::ASC) Order by the street_no column
 * @method     ChildEventQuery orderByZipCode($order = Criteria::ASC) Order by the zip_code column
 * @method     ChildEventQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildEventQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method     ChildEventQuery orderByBegin($order = Criteria::ASC) Order by the begin column
 * @method     ChildEventQuery orderByEnd($order = Criteria::ASC) Order by the end column
 * @method     ChildEventQuery orderByImage($order = Criteria::ASC) Order by the image column
 * @method     ChildEventQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 *
 * @method     ChildEventQuery groupById() Group by the id column
 * @method     ChildEventQuery groupByName() Group by the name column
 * @method     ChildEventQuery groupByDescription() Group by the description column
 * @method     ChildEventQuery groupByLongitude() Group by the longitude column
 * @method     ChildEventQuery groupByLatitude() Group by the latitude column
 * @method     ChildEventQuery groupByKoordx() Group by the koordX column
 * @method     ChildEventQuery groupByKoordy() Group by the koordY column
 * @method     ChildEventQuery groupByKoordz() Group by the koordZ column
 * @method     ChildEventQuery groupByLocationName() Group by the location_name column
 * @method     ChildEventQuery groupByStreetNo() Group by the street_no column
 * @method     ChildEventQuery groupByZipCode() Group by the zip_code column
 * @method     ChildEventQuery groupByCity() Group by the city column
 * @method     ChildEventQuery groupByCountry() Group by the country column
 * @method     ChildEventQuery groupByBegin() Group by the begin column
 * @method     ChildEventQuery groupByEnd() Group by the end column
 * @method     ChildEventQuery groupByImage() Group by the image column
 * @method     ChildEventQuery groupByWebsite() Group by the website column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventQuery leftJoinEventCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventCategory relation
 * @method     ChildEventQuery rightJoinEventCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventCategory relation
 * @method     ChildEventQuery innerJoinEventCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the EventCategory relation
 *
 * @method     ChildEventQuery joinWithEventCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventCategory relation
 *
 * @method     ChildEventQuery leftJoinWithEventCategory() Adds a LEFT JOIN clause and with to the query using the EventCategory relation
 * @method     ChildEventQuery rightJoinWithEventCategory() Adds a RIGHT JOIN clause and with to the query using the EventCategory relation
 * @method     ChildEventQuery innerJoinWithEventCategory() Adds a INNER JOIN clause and with to the query using the EventCategory relation
 *
 * @method     \EventCategoryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvent findOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent findOneById(int $id) Return the first ChildEvent filtered by the id column
 * @method     ChildEvent findOneByName(string $name) Return the first ChildEvent filtered by the name column
 * @method     ChildEvent findOneByDescription(string $description) Return the first ChildEvent filtered by the description column
 * @method     ChildEvent findOneByLongitude(double $longitude) Return the first ChildEvent filtered by the longitude column
 * @method     ChildEvent findOneByLatitude(double $latitude) Return the first ChildEvent filtered by the latitude column
 * @method     ChildEvent findOneByKoordx(double $koordX) Return the first ChildEvent filtered by the koordX column
 * @method     ChildEvent findOneByKoordy(double $koordY) Return the first ChildEvent filtered by the koordY column
 * @method     ChildEvent findOneByKoordz(double $koordZ) Return the first ChildEvent filtered by the koordZ column
 * @method     ChildEvent findOneByLocationName(string $location_name) Return the first ChildEvent filtered by the location_name column
 * @method     ChildEvent findOneByStreetNo(string $street_no) Return the first ChildEvent filtered by the street_no column
 * @method     ChildEvent findOneByZipCode(string $zip_code) Return the first ChildEvent filtered by the zip_code column
 * @method     ChildEvent findOneByCity(string $city) Return the first ChildEvent filtered by the city column
 * @method     ChildEvent findOneByCountry(string $country) Return the first ChildEvent filtered by the country column
 * @method     ChildEvent findOneByBegin(string $begin) Return the first ChildEvent filtered by the begin column
 * @method     ChildEvent findOneByEnd(string $end) Return the first ChildEvent filtered by the end column
 * @method     ChildEvent findOneByImage(string $image) Return the first ChildEvent filtered by the image column
 * @method     ChildEvent findOneByWebsite(string $website) Return the first ChildEvent filtered by the website column *

 * @method     ChildEvent requirePk($key, ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneById(int $id) Return the first ChildEvent filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByName(string $name) Return the first ChildEvent filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDescription(string $description) Return the first ChildEvent filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLongitude(double $longitude) Return the first ChildEvent filtered by the longitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLatitude(double $latitude) Return the first ChildEvent filtered by the latitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByKoordx(double $koordX) Return the first ChildEvent filtered by the koordX column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByKoordy(double $koordY) Return the first ChildEvent filtered by the koordY column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByKoordz(double $koordZ) Return the first ChildEvent filtered by the koordZ column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLocationName(string $location_name) Return the first ChildEvent filtered by the location_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByStreetNo(string $street_no) Return the first ChildEvent filtered by the street_no column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByZipCode(string $zip_code) Return the first ChildEvent filtered by the zip_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCity(string $city) Return the first ChildEvent filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCountry(string $country) Return the first ChildEvent filtered by the country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByBegin(string $begin) Return the first ChildEvent filtered by the begin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByEnd(string $end) Return the first ChildEvent filtered by the end column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByImage(string $image) Return the first ChildEvent filtered by the image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByWebsite(string $website) Return the first ChildEvent filtered by the website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @method     ChildEvent[]|ObjectCollection findById(int $id) Return ChildEvent objects filtered by the id column
 * @method     ChildEvent[]|ObjectCollection findByName(string $name) Return ChildEvent objects filtered by the name column
 * @method     ChildEvent[]|ObjectCollection findByDescription(string $description) Return ChildEvent objects filtered by the description column
 * @method     ChildEvent[]|ObjectCollection findByLongitude(double $longitude) Return ChildEvent objects filtered by the longitude column
 * @method     ChildEvent[]|ObjectCollection findByLatitude(double $latitude) Return ChildEvent objects filtered by the latitude column
 * @method     ChildEvent[]|ObjectCollection findByKoordx(double $koordX) Return ChildEvent objects filtered by the koordX column
 * @method     ChildEvent[]|ObjectCollection findByKoordy(double $koordY) Return ChildEvent objects filtered by the koordY column
 * @method     ChildEvent[]|ObjectCollection findByKoordz(double $koordZ) Return ChildEvent objects filtered by the koordZ column
 * @method     ChildEvent[]|ObjectCollection findByLocationName(string $location_name) Return ChildEvent objects filtered by the location_name column
 * @method     ChildEvent[]|ObjectCollection findByStreetNo(string $street_no) Return ChildEvent objects filtered by the street_no column
 * @method     ChildEvent[]|ObjectCollection findByZipCode(string $zip_code) Return ChildEvent objects filtered by the zip_code column
 * @method     ChildEvent[]|ObjectCollection findByCity(string $city) Return ChildEvent objects filtered by the city column
 * @method     ChildEvent[]|ObjectCollection findByCountry(string $country) Return ChildEvent objects filtered by the country column
 * @method     ChildEvent[]|ObjectCollection findByBegin(string $begin) Return ChildEvent objects filtered by the begin column
 * @method     ChildEvent[]|ObjectCollection findByEnd(string $end) Return ChildEvent objects filtered by the end column
 * @method     ChildEvent[]|ObjectCollection findByImage(string $image) Return ChildEvent objects filtered by the image column
 * @method     ChildEvent[]|ObjectCollection findByWebsite(string $website) Return ChildEvent objects filtered by the website column
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\EventQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, description, longitude, latitude, koordX, koordY, koordZ, location_name, street_no, zip_code, city, country, begin, end, image, website FROM event WHERE id = :p0';
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
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the longitude column
     *
     * Example usage:
     * <code>
     * $query->filterByLongitude(1234); // WHERE longitude = 1234
     * $query->filterByLongitude(array(12, 34)); // WHERE longitude IN (12, 34)
     * $query->filterByLongitude(array('min' => 12)); // WHERE longitude > 12
     * </code>
     *
     * @param     mixed $longitude The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByLongitude($longitude = null, $comparison = null)
    {
        if (is_array($longitude)) {
            $useMinMax = false;
            if (isset($longitude['min'])) {
                $this->addUsingAlias(EventTableMap::COL_LONGITUDE, $longitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($longitude['max'])) {
                $this->addUsingAlias(EventTableMap::COL_LONGITUDE, $longitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_LONGITUDE, $longitude, $comparison);
    }

    /**
     * Filter the query on the latitude column
     *
     * Example usage:
     * <code>
     * $query->filterByLatitude(1234); // WHERE latitude = 1234
     * $query->filterByLatitude(array(12, 34)); // WHERE latitude IN (12, 34)
     * $query->filterByLatitude(array('min' => 12)); // WHERE latitude > 12
     * </code>
     *
     * @param     mixed $latitude The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByLatitude($latitude = null, $comparison = null)
    {
        if (is_array($latitude)) {
            $useMinMax = false;
            if (isset($latitude['min'])) {
                $this->addUsingAlias(EventTableMap::COL_LATITUDE, $latitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($latitude['max'])) {
                $this->addUsingAlias(EventTableMap::COL_LATITUDE, $latitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_LATITUDE, $latitude, $comparison);
    }

    /**
     * Filter the query on the koordX column
     *
     * Example usage:
     * <code>
     * $query->filterByKoordx(1234); // WHERE koordX = 1234
     * $query->filterByKoordx(array(12, 34)); // WHERE koordX IN (12, 34)
     * $query->filterByKoordx(array('min' => 12)); // WHERE koordX > 12
     * </code>
     *
     * @param     mixed $koordx The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByKoordx($koordx = null, $comparison = null)
    {
        if (is_array($koordx)) {
            $useMinMax = false;
            if (isset($koordx['min'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDX, $koordx['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($koordx['max'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDX, $koordx['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_KOORDX, $koordx, $comparison);
    }

    /**
     * Filter the query on the koordY column
     *
     * Example usage:
     * <code>
     * $query->filterByKoordy(1234); // WHERE koordY = 1234
     * $query->filterByKoordy(array(12, 34)); // WHERE koordY IN (12, 34)
     * $query->filterByKoordy(array('min' => 12)); // WHERE koordY > 12
     * </code>
     *
     * @param     mixed $koordy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByKoordy($koordy = null, $comparison = null)
    {
        if (is_array($koordy)) {
            $useMinMax = false;
            if (isset($koordy['min'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDY, $koordy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($koordy['max'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDY, $koordy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_KOORDY, $koordy, $comparison);
    }

    /**
     * Filter the query on the koordZ column
     *
     * Example usage:
     * <code>
     * $query->filterByKoordz(1234); // WHERE koordZ = 1234
     * $query->filterByKoordz(array(12, 34)); // WHERE koordZ IN (12, 34)
     * $query->filterByKoordz(array('min' => 12)); // WHERE koordZ > 12
     * </code>
     *
     * @param     mixed $koordz The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByKoordz($koordz = null, $comparison = null)
    {
        if (is_array($koordz)) {
            $useMinMax = false;
            if (isset($koordz['min'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDZ, $koordz['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($koordz['max'])) {
                $this->addUsingAlias(EventTableMap::COL_KOORDZ, $koordz['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_KOORDZ, $koordz, $comparison);
    }

    /**
     * Filter the query on the location_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationName('fooValue');   // WHERE location_name = 'fooValue'
     * $query->filterByLocationName('%fooValue%'); // WHERE location_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByLocationName($locationName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_LOCATION_NAME, $locationName, $comparison);
    }

    /**
     * Filter the query on the street_no column
     *
     * Example usage:
     * <code>
     * $query->filterByStreetNo('fooValue');   // WHERE street_no = 'fooValue'
     * $query->filterByStreetNo('%fooValue%'); // WHERE street_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $streetNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByStreetNo($streetNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($streetNo)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_STREET_NO, $streetNo, $comparison);
    }

    /**
     * Filter the query on the zip_code column
     *
     * Example usage:
     * <code>
     * $query->filterByZipCode('fooValue');   // WHERE zip_code = 'fooValue'
     * $query->filterByZipCode('%fooValue%'); // WHERE zip_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zipCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByZipCode($zipCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zipCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_ZIP_CODE, $zipCode, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%'); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the begin column
     *
     * Example usage:
     * <code>
     * $query->filterByBegin('2011-03-14'); // WHERE begin = '2011-03-14'
     * $query->filterByBegin('now'); // WHERE begin = '2011-03-14'
     * $query->filterByBegin(array('max' => 'yesterday')); // WHERE begin > '2011-03-13'
     * </code>
     *
     * @param     mixed $begin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByBegin($begin = null, $comparison = null)
    {
        if (is_array($begin)) {
            $useMinMax = false;
            if (isset($begin['min'])) {
                $this->addUsingAlias(EventTableMap::COL_BEGIN, $begin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($begin['max'])) {
                $this->addUsingAlias(EventTableMap::COL_BEGIN, $begin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_BEGIN, $begin, $comparison);
    }

    /**
     * Filter the query on the end column
     *
     * Example usage:
     * <code>
     * $query->filterByEnd('2011-03-14'); // WHERE end = '2011-03-14'
     * $query->filterByEnd('now'); // WHERE end = '2011-03-14'
     * $query->filterByEnd(array('max' => 'yesterday')); // WHERE end > '2011-03-13'
     * </code>
     *
     * @param     mixed $end The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByEnd($end = null, $comparison = null)
    {
        if (is_array($end)) {
            $useMinMax = false;
            if (isset($end['min'])) {
                $this->addUsingAlias(EventTableMap::COL_END, $end['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($end['max'])) {
                $this->addUsingAlias(EventTableMap::COL_END, $end['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_END, $end, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%'); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%'); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query by a related \EventCategory object
     *
     * @param \EventCategory|ObjectCollection $eventCategory the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventCategory($eventCategory, $comparison = null)
    {
        if ($eventCategory instanceof \EventCategory) {
            return $this
                ->addUsingAlias(EventTableMap::COL_ID, $eventCategory->getEventId(), $comparison);
        } elseif ($eventCategory instanceof ObjectCollection) {
            return $this
                ->useEventCategoryQuery()
                ->filterByPrimaryKeys($eventCategory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventCategory() only accepts arguments of type \EventCategory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventCategory');

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
            $this->addJoinObject($join, 'EventCategory');
        }

        return $this;
    }

    /**
     * Use the EventCategory relation EventCategory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \EventCategoryQuery A secondary query class using the current class as primary query
     */
    public function useEventCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventCategory', '\EventCategoryQuery');
    }

    /**
     * Filter the query by a related Category object
     * using the event_category table as cross reference
     *
     * @param Category $category the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useEventCategoryQuery()
            ->filterByCategory($category, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEvent $event Object to remove from the list of results
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            EventTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventQuery
