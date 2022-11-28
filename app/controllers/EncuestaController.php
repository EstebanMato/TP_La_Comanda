<?php

require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable
{
  public static function AltaEncuesta($request, $response)
  {
    $parametros = $request->getParsedBody();
    
    $encuesta = new Encuesta();
    $encuesta->codigoTicket = $parametros["codigoTicket"];
    $encuesta->mesa = $parametros["califMesa"];
    $encuesta->restaurante = $parametros["califRestaurante"];
    $encuesta->mozo = $parametros["califMozo"];
    $encuesta->cocinero = $parametros["califCocinero"];
    $totalCalif = $encuesta->mesa + $encuesta->restaurante + $encuesta->mozo + $encuesta->cocinero;
    $encuesta->total = $totalCalif;
    $encuesta->comentarios = $parametros["comentarios"];

    $encuesta->crearEncuesta();

    $payload = json_encode(array("Mensaje" => "Encuesta enviada"));
    

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ListarEncuestas($request, $response)
  {
    $lista = Encuesta::obtenerEncuestas();
    $payload = json_encode(array("listaEncuestas" => $lista));
   

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
  
  public static function ExportarEncuestaCSV($request, $response)
  {
    $lista = Encuesta::obtenerEncuestas();
    for($i=0; $i<count($lista); $i++){

      Encuesta::addFileCSV("./Archivos/encuestas.csv" , $lista[$i]);
    }
    $payload = json_encode(array("mensaje" => "carga OK"));
    
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function ImportarEncuestaCSV($request, $response)
  {
    $encuestas = $_FILES['files'];
    Encuesta::readFileCsv($_FILES['files']['tmp_name']);
    $payload = json_encode(array("mensaje" => "carga OK"));
    
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
  
}
?>
