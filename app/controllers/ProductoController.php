<?php
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public static function AltaProducto($request, $response)
  {
    $parametros = $request->getParsedBody();

    $producto = new producto();
    $producto->nombre = $parametros['nombre'];
    $producto->preparador = $parametros['preparador'];

    if(isset($_POST['nombre']) && !empty($_POST['nombre']) && isset($_POST['preparador']) && !empty($_POST['preparador'])){
  
      if(UsuarioController::ValidarTipo($producto->preparador)){
        $producto->crearProducto();
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

  public static function ListarProductos($request, $response)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProductos" => $lista));

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
}

?>
