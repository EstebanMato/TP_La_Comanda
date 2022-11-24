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
require_once './controllers/TokensController.php';

require_once './middlewares/VerificarTokenMiddleware.php';
require_once './middlewares/SoloAdminMiddleware.php';
require_once './middlewares/SoloMozoMiddleware.php';
require_once './middlewares/ValidarModificacionPedidoMiddleware.php';


// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

//Login que retorna el token
$app->group('/login', function (RouteCollectorProxy $group)
{
  $group->post('/crearToken', \TokensController::class . ':CrearToken');
});

$app->group('/usuario', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \UsuarioController::class . ':AltaUsuario');
  $group->get('/listar', \UsuarioController::class . ':ListarUsuarios');
})->add (new SoloAdminMiddleware);


$app->group('/producto', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \ProductoController::class . ':AltaProducto')->add (new SoloAdminMiddleware);
  $group->get('/listar', \ProductoController::class . ':ListarProductos');
});


$app->group('/mesa', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \MesaController::class . ':AltaMesa')->add (new SoloAdminMiddleware);
  $group->get('/listar', \MesaController::class . ':ListarMesas');
});


$app->group('/pedido', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \PedidoController::class . ':AltaPedido')->add (new SoloMozoMiddleware);
  $group->get('/listar', \PedidoController::class . ':ListarPedidos');
  $group->post('/modificar', \PedidoController::class . ':ModificarPedido')->add (new ValidarModificacionPedidoMiddleware);
})->add (new VerificarTokenMiddleware);

$app->group('/ticket', function (RouteCollectorProxy $group)
{
  $group->post('/crear', \TicketController::class . ':AltaTicket')->add (new SoloMozoMiddleware);
  $group->get('/listar', \TicketController::class . ':ListarTickets');
});

$app->group('/foto', function (RouteCollectorProxy $group)
{
  $group->post('/subir', \TicketController::class . ':SubirFoto')->add (new SoloMozoMiddleware);
});


$app->get('[/]', function (Request $request, Response $response) 
{    
    $response->getBody()->write("TP La Comanda");
    return $response;
});

$app->run();
