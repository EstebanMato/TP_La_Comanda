<?php

class MoverFotos
{
    public $direccion;
    public $extension;
    public $nombre;
    public $pathDireccion;

    public function __construct($direccion, $foto, $nombre)
    {
        MoverFotos::CrearDirectorio($direccion);
        $this->direccion = $direccion;
        $this->Guardar($direccion,$foto, $nombre);
    }

    private static function CrearDirectorio($direccion)
    {
        if (!file_exists($direccion)) 
        {
            mkdir($direccion);
        }
    }

    public function Guardar($direccion,$foto,$nombre)
    {
        move_uploaded_file($foto['foto']['tmp_name'], $direccion.$nombre);
    }

    public static function Mover($dirVieja ,$dirNueva ,$nombreArch)
    {
        self::CrearDirectorio($dirNueva);

        rename($dirVieja.$nombreArch, $dirNueva.$nombreArch);
    }
}


?>