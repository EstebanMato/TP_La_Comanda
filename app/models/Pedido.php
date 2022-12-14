<?php

class Pedido
{
    public $id;
    public $id_ticket;
    public $cliente;
    public $id_mozo;
    public $id_producto;
    public $id_mesa;
    public $tiempo_restante;
    public $estado;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id, id_ticket, cliente, id_mozo, id_producto, id_mesa, tiempo_restante, estado) VALUES (:id, :id_ticket, :cliente, :id_mozo, :id_producto, :id_mesa, :tiempo_restante, :estado)");
        $consulta->bindValue(':id', $this->id);
        $consulta->bindValue(':id_ticket', $this->id_ticket, PDO::PARAM_STR);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.id, id_ticket, cliente, id_mozo, id_producto, PRO.nombre, id_mesa, tiempo_restante, estado 
                                                        FROM pedidos P, productos PRO
                                                        WHERE P.id_producto = PRO.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.id, id_ticket, cliente, id_mozo, id_producto, PRO.nombre, id_mesa, tiempo_restante, estado 
                                                        FROM pedidos P, productos PRO
                                                        WHERE P.id_producto = PRO.id && P.estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPorTipo($tipo, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.id, id_ticket, cliente, id_mozo, id_producto, PRO.nombre, id_mesa, tiempo_restante, estado 
                                                        FROM pedidos P, productos PRO
                                                        WHERE P.id_producto = PRO.id && PRO.preparador = :tipo && P.estado = :estado");

        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.id, PRO.nombre, id_ticket, cliente, id_mozo, id_producto, id_mesa, tiempo_restante, estado 
                                                        FROM pedidos P, productos PRO
                                                        WHERE P.id = :id && id_producto = PRO.id");

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPorIdTicket($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT P.id, PRO.nombre, id_ticket, cliente, id_mozo, id_producto, id_mesa, tiempo_restante, estado 
                                                        FROM pedidos P, productos PRO
                                                        WHERE id_ticket = :id && id_producto = PRO.id");

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function actualizarPedido($id, $estado, $tiempo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET tiempo_restante = :tiempo , estado = :estado WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $tiempo, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function consultarTiempoRestante($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(tiempo_restante) AS total FROM pedidos WHERE id_ticket = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll();;
    }

    public static function obtenerPrecio($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(precio) AS total FROM pedidos, productos WHERE id_ticket = :codigo && id_producto = productos.id");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll();;
    }
}
?>