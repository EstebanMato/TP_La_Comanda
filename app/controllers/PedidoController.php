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
    
    if(isset($_POST['cliente']) && !empty($_POST['cliente']) &&
    isset($_POST['producto']) && !empty($_POST['producto']) && 
    isset($_POST['cantidad']) && !empty($_POST['cantidad']) && 
    isset($_POST['codigoTicket']) && !empty($_POST['codigoTicket'])){
      
      $ticket = new Ticket();
      $ticket = Ticket::obtenerTicket($parametros['codigoTicket']);

      $producto = new Producto();
      $producto = Producto::obtenerProductoPorNombre($parametros['producto']);

      if($ticket && $producto){

        $pedido = new pedido();
        $pedido->id_ticket = $parametros['codigoTicket'];
        $pedido->cliente = $parametros['cliente'];
        $pedido->id_mozo = $ticket[0]->id_mozo; 
        $pedido->id_producto = $producto[0]->id;
        $pedido->id_mesa = $ticket[0]->id_mesa;
        $pedido->tiempo_restante = "0";
        $pedido->estado = "abierto";
        
        $cantidad = $parametros['cantidad'];
        for($i=0; $i<$cantidad; $i++){
          $pedido->crearPedido();
        }
          $payload = json_encode(array("mensaje" => "Pedido agregado al ticket" , "ticket" => $ticket[0]->codigo));
      }else{
        $payload = json_encode(array("ERROR" => "Producto o ticket inexistente"));

      }
    }else{

      $payload = json_encode(array("ERROR" => "Faltan datos para el alta"));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarPedidos($request, $response)
  {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    $data = AutentificadorJWT::ObtenerData($token);

    if(!strcmp($data->tipo , "socio") ){

      $lista = Pedido::obtenerTodos();
      $payload = json_encode(array("listaPedidos" => $lista));
    }else{
      $lista = Pedido::obtenerPorTipo($data->tipo);
      $payload = json_encode(array("listaPedidos" => $lista));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ModificarPedido($request, $response)
  {

    $parametros = $request->getParsedBody();

    Pedido::actualizarPedido( $parametros['idPedido'], $parametros['estado'] , $parametros['tiempoRestante']);

    $payload = json_encode(array("Mensaje" => "Pedido modificado con exito"));
    $response->getBody()->write($payload);
    

    return $response->withHeader('Content-Type', 'application/json');
  }
}

?>
