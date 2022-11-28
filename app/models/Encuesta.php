<?php

class Encuesta
{
    public $id;
    public $codigo_ticket;
    public $mesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $total;
    public $comentarios;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuentas (codigo_ticket, mesa, restaurante, mozo, cocinero, total, comentarios) 
                                                    VALUES (:codigo_ticket, :mesa, :restaurante, :mozo, :cocinero, :total, :comentarios)");
        $consulta->bindValue(':codigo_ticket', $this->codigo_ticket, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':total', $this->total, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerEncuestas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo_ticket, mesa, restaurante, mozo, cocinero, total, comentarios FROM encuentas ORDER BY total DESC");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    
    public static function mostrarEncuesta(Encuesta $encuesta)
    {
        return $encuesta->id . ","  . $encuesta->codigo_ticket . "," . $encuesta->mesa. "," . $encuesta->restaurante. "," . $encuesta->mozo. "," . $encuesta->cocinero. "," . $encuesta->total. "," . $encuesta->comentarios;
    }

    public static function addFileCsv($path, $encuesta)
    {
        if($path != NULL && $encuesta != NULL)
        {
            //Chequear si el archivo existe, sino lo agregamos sin el php_eol
            
            if(!file_exists($path)){
                $archivo = fopen ($path, "w+");
                fwrite($archivo, Encuesta::mostrarEncuesta($encuesta));
            }else{
                $archivo = fopen ($path, "a+");
                fwrite($archivo, PHP_EOL.Encuesta::mostrarEncuesta($encuesta));              
            }

            fclose($archivo);
           return true;
        }
        return false;
    }
     
    //A partir de aca manejamos la lectura del archivo
    //A partir del .csv recibido por parámetro retornamos un listado de usuarios
    public static function readFileCsv($path)
    {
        $listEncuestas = array();
        if ($path != NULL) {
            $archivo = fopen($path, "r");

            while (!feof($archivo)) {
                //Llamamos al metodo que "sabe" como dar de alta el usuario 
                $encuesta = Encuesta::stringToArray(fgets($archivo));
                $encuesta->crearEncuesta();
             //   var_dump($encuesta);
            }

            fclose($archivo);
            return $listEncuestas;
        }
        return false;
    }

     //Leemos el archivo .csv desde readFileCsv y cada clase tendría un metodo para asignar lo leído 
     public static function stringToArray($encuesta)
     {
        $aux = explode(",", $encuesta);
        $encuestaAux = new Encuesta();
        $encuestaAux->id = $aux[0];
        $encuestaAux->codigo_ticket = $aux[1];
        $encuestaAux->mesa= $aux[2];
        $encuestaAux->restaurante= $aux[3];
        $encuestaAux->mozo= $aux[4];
        $encuestaAux->cocinero= $aux[5];
        $encuestaAux->total= $aux[6];
        $encuestaAux->comentarios= $aux[7];
         
         return $encuestaAux;
     }
}
?>