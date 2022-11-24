<?php

class Ticket
{
    public $id;
    public $codigo;
    public $id_mesa;
    public $id_mozo;
    public $estado;
    public $foto;

    public function crearTicket()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tickets (id, codigo, id_mesa, id_mozo, estado, foto) VALUES (:id, :codigo, :id_mesa, :id_mozo, :estado, :foto)");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $this->id_mozo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, id_mesa, id_mozo, estado , foto FROM tickets");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ticket');
    }

    public static function obtenerTicket($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, id_mesa, id_mozo, estado, foto FROM tickets WHERE codigo = :codigo" );
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ticket');
    }

    
    public static function insertarFoto($codigo, $ruta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE tickets SET foto = :ruta WHERE codigo = :codigo" );
        $consulta->bindValue(':ruta', $ruta, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ticket');
    }
}
?>