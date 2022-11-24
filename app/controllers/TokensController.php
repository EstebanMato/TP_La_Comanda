<?php

require_once './interfaces/ITokensUsable.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './models/Usuario.php';

require_once './controllers/UsuarioController.php';


class TokensController implements ITokensUsable
{
    //solo crea el token
    public function CrearToken($request, $response, $args)
    {      
      $usuario = new Usuario(); 
      $usuario = UsuarioController::Autenticar($request, $response);

      if($usuario != NULL)
      {
        $datos = array('nombre' => $usuario->nombre, 'tipo' => $usuario->tipo);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token,
                                      'tipo' => $usuario->tipo));
      }
      else
      {
        $payload = json_encode(array("Usuario inexistente"));        
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

}

?>