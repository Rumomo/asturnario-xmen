<?php

use Cassandra\Collection;
final readonly class Mutantes
{

    private int $id;
    private string $nombre;
    private Poder $poderes ;

    private string $descripcion;


    public function __construct(
        int $id,
        string $nombre,
        Poder $poderes,
        string $descripcion
    )
    {
    }

    public function __mostrar(): void{
        echo "Nombre: {$this->nombre}" .PHP_EOL;
        echo "Poderes: {$this->poderes}" .PHP_EOL;
        echo "Descripcion: {$this->descripcion}" .PHP_EOL;

    }

}
