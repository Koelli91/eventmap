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

            // returns all events, filtered by optional parameters
            $this->get('', function (Request $request, Response $response, $args) {
                $response->getBody()->write("Hello World!");
                return $response;
            });

            /*
            // returns a specific event
            $app->get('/events/{id}', function($id) use ($app) {

            });

            // updates an event
            $app->put('/events/{id}', function(Request $request, Response $response) {

            });
            */

            // creates a new event
            $this->post('/new', function (Request $request, Response $response, $args) {


                return $response;
            });
        });

        // Categories group
        $this->group('/category', function () {

            $this->post('/new', function (Request $request, Response $response, $args) {
                $category_name = filter_var($request->getParam('category_name'), FILTER_SANITIZE_STRING);

                if (true == add_category($category_name)) {
                    $response->getBody()->write("Kategorie '" . $category_name . "' erfolgreich hinzugefügt.");
                } else {
                    $response->getBody()->write("Kategorie bereits vorhanden.");
                }

                return $response;
            });

        });

    });

});

// Actually run the app
$app->run();