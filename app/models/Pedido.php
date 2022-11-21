<?php

class Pedido
{
    public $id;
    public $codigo;
    public $cliente;
    public $id_mozo;
    public $id_producto;
    public $id_mesa;
    public $tiempo_restante;
    public $estado;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id, codigo, cliente, id_mozo, id_producto, id_mesa, tiempo_restante, estado) VALUES (:id, :codigo, :cliente, :id_mozo, :id_producto, :id_mesa, :tiempo_restante, :estado)");
        $consulta->bindValue(':id', $this->id);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $this->id_mozo, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_restante', $this->tiempo_restante, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, cliente, id_mozo, id_producto, id_mesa, tiempo_restante, estado FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
}
?>