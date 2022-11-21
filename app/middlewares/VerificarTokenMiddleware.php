<?php



use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarTokenMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $todoOk = false;
        $response = new Response();

        try 
        {
            AutentificadorJWT::verificarToken($token);
            $todoOk = true;
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array('Error:' => $e->getMessage()));
            $response = $response->withStatus(401);
        }
      
        if ($todoOk) 
        {
            $response = $handler->handle($request);
        }
        
        $payload = json_encode(array('JWT:'=>$todoOk));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}


?>