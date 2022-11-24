<?php

require_once './models/Ticket.php';
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
      $ticket->estado = "Abierto";
      $ticket->foto = "vacio";

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
    $foto = "./Fotos/".$_FILES['foto']['name'];

    var_dump($foto);
    var_dump($ticket);
    Ticket::insertarFoto($ticket, $foto);
    new MoverFotos("./Fotos/", $_FILES, $_FILES['foto']['name']);
    $payload = json_encode(array("Mensaje" => "Foto subida correctamente"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}

?>
