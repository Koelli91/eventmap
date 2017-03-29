<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Propel\Runtime\Propel;

// setup the autoloading
require_once '../vendor/autoload.php';

// setup Propel
require_once 'database/generated-conf/config.php';

require_once 'functions.php';

$app = new \Slim\App();
$container = $app->getContainer();

// Add logger for Slim
$container['logger'] = function () {
    $logger = new \Monolog\Logger('slim_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("logs/slim.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// Add logger for Propel
$propel_logger = new \Monolog\Logger('propel_logger');
$propel_logger->pushHandler(new \Monolog\Handler\StreamHandler('logs/propel.log', \Monolog\Logger::WARNING));
$serviceContainer->setLogger('propel_logger', $propel_logger);

// register the json response and error handlers
$jsonHelpers = new \JsonHelpers\JsonHelpers($container);
$jsonHelpers->registerResponseView();
$jsonHelpers->registerErrorHandlers();

// Version group
$app->group('/v1', function () {

    // Display a API usage page
    $this->get('', function (Request $request, Response $response) {
        $response->getBody()->write("<h1>This will be the API Usage instruction page.</h1>");
        return $response;
    });

    // Events group
    $this->group('/events', function () {

        $this->get('', function (Request $request, Response $response) {
            $queryParams = $request->getQueryParams();
            $lon = isset($queryParams['lon']) ? $queryParams['lon'] : '';
            $lat = isset($queryParams['lat']) ? $queryParams['lat'] : '';
            $radius = isset($queryParams['radius']) ? $queryParams['radius'] : '';
            $category = isset($queryParams['category']) ? $queryParams['category'] : '';
            $page = isset($queryParams['page']) ? $queryParams['page'] : -1;

            $data = array();
            $errors = array();
            $events = array();
            $eventCountAll = 0;

            if (empty($lat))
                $errors['latitude'] = 'Breitengrad (latitude) fehlt oder ist leer.';
            if (empty($lon))
                $errors['longitude'] = 'Längengrad (longitude) fehlt oder ist leer.';
            if (empty($radius))
                $errors['radius'] = 'Umkreis fehlt.';
            /*if (empty($category))
                $errors['category'] = 'Kategorie fehlt.';*/

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            } else {
                // Kategorie wandeln
                if (strtolower($category) === "alles" || strtolower($category) === "alle" || strtolower($category) === "all")
                    $category = '';

                // Offset in Zahl umwandeln
                if (!empty($offset))
                    $offset = intval($offset);

                $result = get_events($lon, $lat, $radius, $category, $page);
                $events = $result['events'];
                $eventCountAll = $result['eventCountAll'];
                $eventsPerPage = $result['eventsPerPage'];
            }

            $data['success'] = true;
            $data['message'] = 'Events erfolgreich geladen';
            $data['eventCount'] = $eventCountAll;
            $data['eventlist'] = $events;
            $data['eventsPerPage'] = $eventsPerPage;
            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        // creates new events
        $this->post('/new', function (Request $request, Response $response) {
            // Vor dem Einfügen alle vergangenen Veranstaltungen löschen
            delete_old_events();

            $parsedData = $request->getParsedBody();
            $multiple_events = false;
            if (isset($parsedData['events']) && is_array($parsedData['events'])) {
                $multiple_events = true;
            }

            $event = $multiple_events ? $parsedData['events'] : $parsedData;

            $data = array();
            $errors = array();

            $i = 0;
            if ($multiple_events) {
                foreach ($event as $e) {
                    $errs = add_event($e);
                    if (!empty($errs))
                        $errors["$i"] = $errs;
                    $i++;
                }
            } else {
                $errors = add_event($event);
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            } else {
                $data['success'] = true;
                $data['message'] = "Event(s) erfolgreich hinzugefügt.";
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->delete('/old', function (Request $request, Response $response) {
            $countOldEvents = delete_old_events();

            $data['success'] = true;
            if ($countOldEvents > 0) {
                $data['message'] = 'Vergangene Veranstaltungen gelöscht.';
                $data['count'] = $countOldEvents;
            } else {
                $data['message'] = 'Keine vergangenen Veranstaltungen vorhanden.';
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        });

    });

    // Categories group
    $this->group('/category', function () {
				
        $this->get('', function(Request $request, Response $response) {
            $data = array();
            $errors = array();

            $categories = get_categories();

            if ($categories !== null) {
                $data['success'] = true;
                $data['categories'] = $categories->toArray();
            } else {
                $errors['categories'] = "Keine Kategorien vorhanden.";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->get('/byId/{id}', function (Request $request, Response $response, $args) {
            $category_id = filter_var($args['id'], FILTER_SANITIZE_STRING);

            $data = array();
            $errors = array();

            $category = get_category_by_id($category_id);

            if ($category !== null) {
                $data['success'] = true;
                $data['category'] = $category->toArray();
            } else {
                $errors['category'] = "Keine Kategorie mit der ID '$category_id' gefunden.";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->get('/byName/{name}', function (Request $request, Response $response, $args) {
            $category_name = filter_var($args['name'], FILTER_SANITIZE_STRING);

            $data = array();
            $errors = array();

            $category = get_category_by_name($category_name);

            if ($category !== null) {
                $data['success'] = true;
                $data['category'] = $category->toArray();
            } else {
                $errors['category'] = "Keine Kategorie mit dem Namen '$category_name' gefunden.";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->post('/new', function (Request $request, Response $response) {
            $category_name = filter_var($request->getParam('category_name'), FILTER_SANITIZE_STRING);

            $data = array();
            $errors = array();

            if (true === add_category($category_name)) {
                $data['success'] = true;
                $data['message'] = "Kategorie '$category_name' erfolgreich hinzugefügt.";
                $data['category'] = get_category_by_name($category_name)->toArray();
            } else {
                $errors['category'] = "Kategorie bereits vorhanden.";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->put('/byId/{id}/{newName}', function (Request $request, Response $response, $args) {
            $id = filter_var($args['id'], FILTER_SANITIZE_STRING);
            $newName = filter_var($args['newName'], FILTER_SANITIZE_STRING);

            $data = array();
            $errors = array();

            if (update_category_by_id($id, $newName) == true) {
                $data['success'] = true;
                $data['message'] = "Kategorie erfolgreich in '$newName' umbenannt.";
            } else {
                $errors['category'] = "Kategorie nicht gefunden oder Fehler bei der Umbenennung. Überprüfen Sie Ihre Eingaben";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

        $this->put('/byName/{oldName}/{newName}', function (Request $request, Response $response, $args) {
            $oldName = filter_var($args['oldName'], FILTER_SANITIZE_STRING);
            $newName = filter_var($args['newName'], FILTER_SANITIZE_STRING);

            $data = array();
            $errors = array();

            if (update_category_by_name($oldName, $newName) == true) {
                $data['success'] = true;
                $data['message'] = "Kategorie erfolgreich in '$newName' umbenannt.";
            } else {
                $errors['category'] = "Kategorie nicht gefunden oder Fehler bei der Umbenennung. Überprüfen Sie Ihre Eingaben.";
            }

            if (!empty($errors)) {
                $data['success'] = false;
                $data['errors'] = $errors;
            }

            $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        });

    });

});

// Actually run the app
$app->run();