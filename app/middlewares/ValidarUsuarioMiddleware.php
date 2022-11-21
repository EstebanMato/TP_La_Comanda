<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarUsuarioMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $response = new response();
        $todoOk=false;
        switch($_SERVER["REQUEST_METHOD"])
        {
            case "GET":
                if(isset($_GET['mail']) && !empty($_GET['mail']))
                {
                    $mail = $_GET['mail'];
                    $todoOk=true;
                }
            break;

            case "POST":
                if(isset($_POST['mail']) && !empty($_POST['mail']))
                {
                    $mail = $_POST['mail'];
                    $todoOk=true;
                }
            break;
        }

        if($todoOk)
        {
            if(UsuarioController::UsuarioExistente($request, $response, $mail))
            {
                $payload = json_encode(array('Usuario:'=>"Encontrado"));
                
                $response = $handler->handle($request);

                $response->getBody()->write($payload);
            }else
            {
                $payload = json_encode(array('Usuario:'=>"No registrado"));
    
                $response->getBody()->write($payload);
            }
        }else
        {
            $payload = json_encode(array('Error:'=>"Faltan datos"));

            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>