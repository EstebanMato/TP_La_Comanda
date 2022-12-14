<?php
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/Ticket.php';
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public static function AltaMesa($request, $response)
  {
    $parametros = $request->getParsedBody();

    $mesa = new Mesa();
    $mesa->codigo = $parametros['codigo'];
    $mesa->estado = "Cliente esperando pedido";

    if(isset($_POST['codigo']) && !empty($_POST['codigo'])){
        $mesa->crearMesa();
        $payload = json_encode(array("mensaje" => "Alta exitosa"));
    }else{

      $payload = json_encode(array("ERROR" => "Faltan datos para el alta"));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarMesas($request, $response)
  {
    $lista = Mesa::obtenerTodas();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarMasUsada($request, $response)
  {
    $masUsada = Ticket::mesaMasUsada();
  
    $payload = json_encode(array("Mesa mas usada" => $masUsada[0]->codigo));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarMesa($request, $response)
  {
    $parametros = $request->getParsedBody();
    $estado = $parametros['estado'];
    $mesa= $parametros['mesa'];
    Mesa::cambiarEstadoMesa($estado, $mesa);

    $payload = json_encode(array("Mensaje" => "Se cambio correctamente"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}

?>
