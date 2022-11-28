<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './controllers/UsuarioController.php';

class ValidarModificacionPedidoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new response();

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $datos = AutentificadorJWT::ObtenerPayLoad($token);
        $parametros = $request->getParsedBody();
        $pedido = Pedido::obtenerPorId($parametros['idPedido']);

        $producto = Producto::obtenerProductoPorId($pedido[0]->id_producto);


        if(!strcmp($producto[0]->preparador,$datos->data->tipo) || !strcmp("socio",$datos->data->tipo) || !strcmp("mozo",$datos->data->tipo) ){
            $response = $handler->handle($request);
        }else{
            $payload = json_encode(array("ERROR:"=>"No autorizado", "Mensaje:"=>"Perfil distinto al requerido para modificar esta solicitud"));
            $response->getBody()->write($payload);
        }


    return $response->withHeader('Content-Type', 'application/json');
    }   
}

?>