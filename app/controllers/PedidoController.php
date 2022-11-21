<?php
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/Usuario.php';
require_once './models/Producto.php';
require_once './models/Ticket.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
  public static function AltaPedido($request, $response)
  {
    $parametros = $request->getParsedBody();
    
    if(isset($_POST['codigoTicket']) && !empty($_POST['codigoTicket'])){
      
      $ticket = new Ticket();
      $ticket = Ticket::obtenerTicket($parametros['codigoTicket']);

      $producto = new Producto();
      $producto = Producto::obtenerProductoPorNombre($parametros['producto']);

      $pedido = new pedido();
      $pedido->codigo = $parametros['codigoTicket'];
      $pedido->cliente = $parametros['cliente'];
      $pedido->id_mozo = $ticket[0]->id_mozo; 
      $pedido->id_producto = $producto[0]->id;
      $pedido->id_mesa = $ticket[0]->id_mesa;
      $pedido->tiempo_restante = "0";
      $pedido->estado = "abierto";
      
      $cantidad = $parametros['cantidad'];

        $pedido->crearPedido();
        $payload = json_encode(array("mensaje" => "Alta exitosa"));
    }else{

      $payload = json_encode(array("ERROR" => "Faltan datos para el alta"));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarPedidos($request, $response)
  {
    $lista = Pedido::obtenerTodos();
    $payload = json_encode(array("listaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}

?>
