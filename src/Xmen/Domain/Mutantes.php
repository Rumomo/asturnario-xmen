<?php

final class Mutante
{
    private int $id;
    private string $nombre;
    /** @var Poder[] */
    private array $poderes = [];
    private string $descripcion;

    // Acepta 1 poder (Poder) o varios ([Poder, Poder, ...])
    public function __construct(int $id, string $nombre, Poder|array $poderes, string $descripcion)
    {
        $this->id = $id;
        $this->nombre = $nombre;

        // Normaliza a array de Poder
        $this->poderes = is_array($poderes) ? $poderes : [$poderes];

        // Opcional: valida tipos
        foreach ($this->poderes as $p) {
            if (!$p instanceof Poder) {
                throw new \InvalidArgumentException('Cada poder debe ser instancia de enum Poder');
            }
        }

        $this->descripcion = $descripcion;
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }

    /** @return Poder[] */
    public function getPoderes(): array { return $this->poderes; }

    /** Compatibilidad hacia atrás: devuelve el primer poder */
    public function getPoder(): Poder { return $this->poderes[0]; }

    public function getDescripcion(): string { return $this->descripcion; }
}
