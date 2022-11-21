<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/TicketController.php';
require_once './controllers/ProductoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


$app->group('/usuario', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \UsuarioController::class . ':AltaUsuario');
  $group->get('/listar', \UsuarioController::class . ':ListarUsuarios');
});


$app->group('/producto', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \ProductoController::class . ':AltaProducto');
  $group->get('/listar', \ProductoController::class . ':ListarProductos');
});


$app->group('/mesa', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \MesaController::class . ':AltaMesa');
  $group->get('/listar', \MesaController::class . ':ListarMesas');
});


$app->group('/pedido', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \PedidoController::class . ':AltaPedido');
  $group->get('/listar', \PedidoController::class . ':ListarPedidos');
});

$app->group('/ticket', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \TicketController::class . ':AltaTicket');
  $group->get('/listar', \TicketController::class . ':ListarTickets');
});


$app->get('[/]', function (Request $request, Response $response) 
{    
    $response->getBody()->write("TP La Comanda");
    return $response;
});

$app->run();
