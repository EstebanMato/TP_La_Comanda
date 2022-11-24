<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public static function AltaUsuario($request, $response)
  {
    $parametros = $request->getParsedBody();

    $usuario = new Usuario();
    $usuario->nombre = $parametros['nombre'];
    $usuario->clave = $parametros['clave'];
    $usuario->tipo = $parametros['tipo'];

    if(isset($_POST['nombre']) && !empty($_POST['nombre']) && isset($_POST['clave']) && !empty($_POST['clave']) && isset($_POST['tipo']) && !empty($_POST['tipo'])){
      if(self::ValidarTipo($usuario->tipo)){
        $usuario->crearUsuario();
        $payload = json_encode(array("mensaje" => "Alta exitosa"));
      }else{
        $payload = json_encode(array("ERROR" => "Perfil empleado invalido"));
      }
    }else{

      $payload = json_encode(array("ERROR" => "Faltan datos para el alta"));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarUsuarios($request, $response)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuarios" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ValidarTipo($tipo)
  {
    if(!strcmp($tipo,"bartender") || !strcmp($tipo,"mozo") || !strcmp($tipo,"cervecero") || !strcmp($tipo,"cocinero") || !strcmp($tipo,"socio")){
      return true;
    }else{
      return false;
    }
  }

  public static function Autenticar($request, $response)
    {
      $parametros = $request->getParsedBody();

      $usr = new Usuario();
      $usr->nombre = $parametros['nombre'];
      $usr->tipo = $parametros['tipo'];
      $usr->clave = $parametros['clave'];
      
      $usuario = Usuario::obtenerUsuario($usr->nombre);

      
      if($usuario){
        if($usr->nombre == $usuario->nombre && password_verify($usr->clave, $usuario->clave) && $usuario->tipo == $usr->tipo)
        {
          return $usuario;
        }
        else
        {
          return NULL;
        }
      }else
      {
        return NULL;
      }
    }
}

?>
