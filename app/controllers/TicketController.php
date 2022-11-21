<?php

require_once './models/Ticket.php';
require_once './interfaces/IApiUsable.php';

class TicketController extends Ticket implements IApiUsable
{
  public static function AltaTicket($request, $response)
  {
    $parametros = $request->getParsedBody();

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $codigo = substr(str_shuffle($permitted_chars), 0, 5);
    

    $ticket = new Ticket();
    $ticket->codigo = $codigo;
    $ticket->id_mesa = $parametros['id_mesa'];
    $ticket->id_mozo = $parametros['id_mozo'];
    $ticket->estado = "Abierto";

    if(isset($_POST['id_mozo']) && !empty($_POST['id_mozo'])){
        $ticket->crearTicket();
        $payload = json_encode(array("Numero ticket" => $codigo));
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
}

?>
