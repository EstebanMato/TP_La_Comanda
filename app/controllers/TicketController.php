<?php

require_once './models/Ticket.php';
require_once './models/Pedido.php';
require_once './controllers/MoverFotosController.php';
require_once './interfaces/IApiUsable.php';

class TicketController extends Ticket implements IApiUsable
{
  public static function AltaTicket($request, $response)
  {
    $parametros = $request->getParsedBody();

    if(isset($_POST['id_mozo']) && !empty($_POST['id_mozo']) && (isset($_POST['id_mesa']) && !empty($_POST['id_mesa']))){
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $codigo = substr(str_shuffle($permitted_chars), 0, 5);
      

      $ticket = new Ticket();
      $ticket->codigo = $codigo;

      $ticket->id_mesa = $parametros['id_mesa'];
      $ticket->id_mozo = $parametros['id_mozo'];
      $ticket->estado = "abierto";
      $ticket->foto = "vacio";
      $ticket->precionFinal = 0;

      $mozo = Usuario::obtenerUsuarioPorId($ticket->id_mozo);
 
      if(Mesa::obtenerMesaPorId($ticket->id_mesa) && $mozo && !strcmp($mozo->tipo,"mozo") ){
        Mesa::cambiarEstado("Cliente esperando pedido", $ticket->id_mesa);
        $ticket->crearTicket();
        $payload = json_encode(array("Numero ticket" => $codigo));
      }else{
        $payload = json_encode(array("ERROR" => "Mesa o mozo inexsistente"));
      }
    }else{

      $payload = json_encode(array("ERROR" => "Faltan datos para el alta"));
    }

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ConsultarTicket($request, $response, $args)
  {

    $ticket = Pedido::consultarTiempoRestante($args['codigo']);
    $pedidos = Pedido::obtenerPorIdTicket($args['codigo']);

    $payload = json_encode(array("TiempoRestantePromedio" =>$ticket[0], "pedidos" => $pedidos));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarTickets($request, $response)
  {
    $lista = Ticket::obtenerTodos();
    $payload = json_encode(array("listaTickets" => $lista));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function SubirFoto($request, $response)
  {
    $parametros = $request->getParsedBody();
    $ticket = $parametros['ticket'];
    $rutaFoto = "./Fotos/".$ticket .$_FILES['foto']['name'];

    Ticket::insertarFoto($ticket, $rutaFoto);
    new MoverFotos("./Fotos/", $_FILES, $ticket . $_FILES['foto']['name'] );
    $payload = json_encode(array("Mensaje" => "Foto subida correctamente"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function FacturarTicket($request, $response)
  {
    $parametros = $request->getParsedBody();

    $ticket = Ticket::obtenerTicket($parametros['codigoTicket']);
    
    if($ticket){
      $ticket[0]->precioFinal = (Pedido::obtenerPrecio($ticket[0]->codigo))[0][0];
      
      Ticket::cobrarTicket($parametros['codigoTicket'] , $ticket[0]->precioFinal);
      Mesa::cambiarEstado("cliente pagando",$ticket[0]->id_mesa);

      $payload = json_encode(array("Precio Final" => $ticket[0]->precioFinal));
    }else{
      
      $payload = json_encode(array("ERROR" => "Ticket invalido"));
    }
  

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function CerrarTicket($request, $response)
  {
    $parametros = $request->getParsedBody();

    $ticket = Ticket::obtenerTicket($parametros['codigoTicket']);
    
    if($ticket){
      
      Mesa::cambiarEstado("cerrada",$ticket[0]->id_mesa);

      $payload = json_encode(array("Mensaje" =>"Mesa cerrada"));
    }else{
      
      $payload = json_encode(array("ERROR" => "Ticket invalido"));
    }
  
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}

?>
