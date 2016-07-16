<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// setup the autoloading
require_once '../../vendor/autoload.php';

// setup Propel
require_once 'database/generated-conf/config.php';

require_once 'functions.php';

$app = new \Slim\App();
$container = $app->getContainer();

// Add logger for Slim
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('slim_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/slim.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// Add logger for Propel
$propel_logger = new \Monolog\Logger('propel_logger');
$propel_logger->pushHandler(new \Monolog\Handler\StreamHandler('../logs/propel.log', \Monolog\Logger::WARNING));
$serviceContainer->setLogger('propel_logger', $propel_logger);

// register the json response and error handlers
$jsonHelpers = new \JsonHelpers\JsonHelpers($container);
$jsonHelpers->registerResponseView();
$jsonHelpers->registerErrorHandlers();

// API group
$app->group('/api', function () {

    // Version group
    $this->group('/v1', function () {

        // Display a API usage page
        $this->get('', function (Request $request, Response $response) {
            $response->getBody()->write("<h1>This will be the API Usage instruction page.</h1>");
            return $response;
        });

        // Events group
        $this->group('/events', function () {

            // creates a new event
            $this->post('/new', function (Request $request, Response $response, $args) {
                $data = $request->getParam('event');

                /*$category_name = filter_var($request->getParam('category_name'), FILTER_SANITIZE_STRING);

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
                }*/

                $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
                return $response;
            });

        });

        // Categories group
        $this->group('/category', function () {

            $this->get('/byId/{id}', function(Request $request, Response $response, $args) {
                $category_id = filter_var($args['id'], FILTER_SANITIZE_STRING);

                $data = array();
                $errors = array();

                $category = get_category_by_id($category_id);

                if ($category !== null) {
                    $data['success'] = true;
                    $data['category'] = $category->toArray();
                }
                else {
                    $errors['category'] = "Keine Kategorie mit der ID '$category_id' gefunden.";
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['errors'] = $errors;
                }

                $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
                return $response;
            });

            $this->get('/byName/{name}', function(Request $request, Response $response, $args) {
                $category_name = filter_var($args['name'], FILTER_SANITIZE_STRING);

                $data = array();
                $errors = array();

                $category = get_category_by_name($category_name);

                if ($category !== null) {
                    $data['success'] = true;
                    $data['category'] = $category->toArray();
                }
                else {
                    $errors['category'] = "Keine Kategorie mit dem Namen '$category_name' gefunden.";
                }

                if (!empty($errors)) {
                    $data['success'] = false;
                    $data['errors'] = $errors;
                }

                $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
                return $response;
            });

            $this->post('/new', function (Request $request, Response $response, $args) {
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

            $this->put('/byId/{id}/{newName}', function(Request $request, Response $response, $args) {
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

            $this->put('/byName/{oldName}/{newName}', function(Request $request, Response $response, $args) {
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

});

// Actually run the app
$app->run();