<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './controllers/UsuarioController.php';

class SoloMozoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new response();

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $datos = AutentificadorJWT::ObtenerPayLoad($token);

        if($datos->data->tipo === 'mozo' || $datos->data->tipo === 'socio')
        {
            $payload = json_encode(array("Estado:"=>"Autenticado.", "Tipo: "=>$datos->data->tipo));
            $response = $handler->handle($request);

            $response = $response->withStatus(200);
        }
        else
        {
            $payload = json_encode(array("Estado:"=>"Rechazado", "Tipo del token:"=>$datos->data->tipo, "Detalle:"=>"No es mozo"));

            $response = $response->withStatus(200);
        }

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
    }   
}

?>