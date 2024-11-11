<?php
require_once './controller/ControllerCuenta.php';
require_once './controller/ControllerAccionBancaria.php';
require_once './controller/ControllerMovimientos.php';
require_once './controller/ControllerAjuste.php';
require_once './controller/LoginController.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/LogMiddleware.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/segundo_parcial/app");

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();


// Ruta principal
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Bienvenido al banco.");
    return $response;
});


$app->group('/cuenta', function (RouteCollectorProxy $group) {
    $group->get('/', \ControllerCuenta::class . ':TraerTodos')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->get('/{nroCuenta}', \ControllerCuenta::class . ':TraerSaldo')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

        // ALTA
    $group->post('/', \ControllerCuenta::class . ':CargarUno');

    $group->put('/', \ControllerCuenta::class . ':ModificarUno')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->delete('/', \ControllerCuenta::class . ':BorrarUno')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->post('/deposito', \ControllerAccionBancaria::class . ':HacerDeposito')
        ->add(\AuthMiddleware::class . ':VerificarRolCajero')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->post('/retiro', \ControllerAccionBancaria::class . ':HacerRetiro')
        ->add(\AuthMiddleware::class . ':VerificarRolCajero')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->post('/ajuste', \ControllerAjuste::class . ':RealizarAjuste')
        ->add(\AuthMiddleware::class . ':VerificarRolSupervisor')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');
});


$app->group('/movimientos', function (RouteCollectorProxy $group) {
    $group->get('/depositos', \ControllerMovimientos::class . ':ObtenerDepositos')
        ->add(\AuthMiddleware::class . ':VerificarRolOperador')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->get('/retiros', \ControllerMovimientos::class . ':ObtenerRetiros')
        ->add(\AuthMiddleware::class . ':VerificarRolOperador')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');

    $group->get('/todos', \ControllerMovimientos::class . ':ObtenerTodos')
        ->add(\AuthMiddleware::class . ':VerificarRolOperador')
        ->add(new LogMiddleware())
        ->add(\AuthMiddleware::class . ':VerificarToken');
});


$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->post('/login', \LoginController::class . ':Login');
    $group->post('/login/cuenta', \LoginController::class . ':LoginCuenta');
});


// Run app
$app->run();
?>