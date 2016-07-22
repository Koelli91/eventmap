<?php

use Propel\Runtime\Propel;

function add_category($name)
{
    $category = CategoryQuery::create()->findOneByName($name);
    if ($category === null) {
        $category = new Category();
        $category->setName($name);
        $affected_rows = $category->save();

        if ($affected_rows == 1)
            return true;
    }

    return false;
}

function update_category_by_id($id, $newName)
{
    $category = CategoryQuery::create()->findPk($id);

    if ($category !== null) {
        $category->setName($newName);
        $affected_rows = $category->save();
        if ($affected_rows > 0)
            return true;
    }

    return false;
}

function update_category_by_name($oldName, $newName) {
    $category = CategoryQuery::create()->findOneByName($oldName);

    if ($category !== null) {
        $category->setName($newName);
        $affected_rows = $category->save();
        if ($affected_rows > 0)
            return true;
    }

    return false;
}

function get_category_by_name($name)
{
    return CategoryQuery::create()->findOneByName($name);
}

function get_category_by_id($id)
{
    return CategoryQuery::create()->findPk($id);
}

function get_or_add_category($name) {
    $category = get_category_by_name($name);
    if ($category !== null)
        return $category;

    add_category($name);
    return get_category_by_name($name);
}

function add_event($eventarr) {
    $errors = array();

    $latitude = isset($eventarr['location']['lat']) ? $eventarr['location']['lat'] : '';
    $longitude = isset($eventarr['location']['lon']) ? $eventarr['location']['lon'] : '';
    $street = isset($eventarr['location']['street']) ? $eventarr['location']['street'] : '';
    $zip = isset($eventarr['location']['zip']) ? $eventarr['location']['zip'] : '';
    $city = isset($eventarr['location']['city']) ? $eventarr['location']['city'] : '';
    $country = isset($eventarr['location']['country']) ? $eventarr['location']['country'] : '';
    $loc_name = isset($eventarr['location']['name']) ? $eventarr['location']['name'] : '';
    $event_name = isset($eventarr['name']) ? $eventarr['name'] : '';
    $description = isset($eventarr['description']) ? $eventarr['description'] : '';
    $category = isset($eventarr['category']) ? $eventarr['category'] : '';
    $begin = isset($eventarr['time_start']) ? $eventarr['time_start'] : '';
    $end = isset($eventarr['time_end']) ? $eventarr['time_end'] : '';
    $photo = isset($eventarr['photo']) ? $eventarr['photo'] : '';
    $website = isset($eventarr['website']) ? $eventarr['website'] : '';

    // check if all required fields are given
    if ( empty($latitude) )
        $errors['latitude'] = 'Breitengrad (latitude) fehlt.';
    if ( empty($longitude) )
        $errors['longitude'] = 'Längengrad (longitude) fehlt.';
    if ( empty($event_name) )
        $errors['event_name'] = 'Eventname fehlt.';
    /*if ( empty($description) )
        $errors['description'] = 'Eventbeschreibung fehlt.';*/
    if ( empty($begin) )
        $errors['time_start'] = 'Starttermin fehlt.';

    // Check if event already exists
    if (get_event_by_name($event_name) !== null)
        $errors['event'] = 'Event existiert bereits.';

    if (!empty($errors))
        return $errors;

    $lamda = $longitude * pi() / 180;
    $phi = $latitude * pi() / 180;
    $erdradius = 6371;
    $x = $erdradius * cos($phi) * cos($lamda);
    $y = $erdradius * cos($phi) * sin($lamda);
    $z = $erdradius * sin($phi);

    $event = new Event();
    $event->setLatitude($longitude);
    $event->setLongitude($latitude);
    $event->setKoordx($x);
    $event->setKoordy($y);
    $event->setKoordz($z);
    $event->setName($event_name);
    $event->setDescription($description);
    $event->setBegin($begin);
    if (!empty($end))
        $event->setEnd($end);
    if (!empty($street))
        $event->setStreetNo($street);
    if (!empty($zip))
        $event->setZipCode($zip);
    if (!empty($city))
        $event->setCity($city);
    if (!empty($country))
        $event->setCountry($country);
    if (!empty($loc_name))
        $event->setLocationName($loc_name);
    if (!empty($photo))
        $event->setImage($photo);
    if (!empty($website))
        $event->setWebsite($website);
    if (!empty($category)) {
        $cat = new Category();
        $cat->setName($category);
        $cat->save();
        $event->addCategory($cat);
    }

    $affected_rows = $event->save();

    if ($affected_rows <= 0)
        $errors['event'] = 'Event konnte nicht gespeichert werden.';

    return $errors;
}

function delete_old_events() {
    $today = (new DateTime("now"))->format('Y-m-d');
    $tomorrow = (new DateTime("now + 1 day"))->format('Y-m-d');
    // DELETE FROM event WHERE begin < {heute} AND (end IS NULL OR end < {morgen})
    // Eigentlich würde die Prüfung auf 'end' genügen, da dieses Feld allerdings NULL sein kann
    // zusätzliche Prüfung ob der 'begin' vor Heute liegt
    $to_delete = EventQuery::create()
        ->where('Event.begin < "' . $today . '" AND ( Event.end IS NULL OR Event.end < "' . $tomorrow . '")')
        ->find()
        ->getPrimaryKeys();

    foreach ($to_delete as $d)
        EventCategoryQuery::create()
            ->findByEventId($d)
            ->delete();

    EventQuery::create()
        ->filterByPrimaryKeys($to_delete)
        ->delete();

    return count($to_delete);
}

function get_event_by_name($name) {
    return EventQuery::create()->findOneByName($name);
}

function get_events($lon, $lat, $radius, $category, $page) {
    // Max. Anzahl an Ergebnissen pro Seite (mobile Ansicht)
    $max_results = 20;

    // Gradmaß in Bogenmaß umwandeln
    $lambda = $lon * pi() / 180;
    $phi = $lat * pi() / 180;
    // Erdradius in km
    $erdradius = 6371;
    // Ermittlung des Ursprungsorts
    $ursprungX = $erdradius * cos($phi) * cos($lambda);
    $ursprungY = $erdradius * cos($phi) * sin($lambda);
    $ursprungZ = $erdradius * sin($phi);
    // Aktuelles und morgiges Datum, damit nur aktuelle Events angezeigt werden
    $today = (new DateTime("now"))->format('Y-m-d');
    $tomorrow = (new DateTime("now + 1 day"))->format('Y-m-d');

    // Events innerhalb des Bereichs aus DB abfragen
    $con = Propel::getWriteConnection(\Map\EventTableMap::DATABASE_NAME);

    // 1. Alle Einträge gemäß der Parameter Lat,Lon,Radius,Category abrufen
    $sql = "SELECT event.id,event.name,event.description,event.longitude,event.latitude,
                              event.koordX, event.koordY, event.koordZ,
                              event.location_name,event.street_no,event.zip_code,event.city,event.country,
                              event.begin,event.end,event.image,event.website,category.name AS category,
                              POWER($ursprungX - event.koordX, 2)
                              + POWER($ursprungY - event.koordY, 2)
                              + POWER($ursprungZ - event.koordZ, 2) AS tmp_calc
                            FROM event
                            INNER JOIN event_category ON event.id = event_category.event_id
                            INNER JOIN category ON category.id = event_category.category_id
                            WHERE
                                POWER($ursprungX - event.koordX, 2)
                              + POWER($ursprungY - event.koordY, 2)
                              + POWER($ursprungZ - event.koordZ, 2)
                            <= " . pow(2 * $erdradius * sin($radius / (2 * $erdradius)), 2) .
        (!empty($category) ? " AND category.name = \"$category\"" : " ") .
        " AND ( begin >= \"$today\" OR (end IS NOT NULL AND end >= \"$tomorrow\") )" .
        " ORDER BY event.begin ASC, tmp_calc ASC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $events = $stmt->fetchAll(2);
    $eventCount = count($events);
    $stmt->closeCursor();

    // Get Results for Page 1, 2, ..., n
    if ($page > 0) {
        $offset = ($page - 1) * $max_results;
        $sql .= " LIMIT $offset,$max_results";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $events = $stmt->fetchAll(2);
    }

    return array(
        'events' => $events,
        'eventCountAll' => $eventCount
    );
}