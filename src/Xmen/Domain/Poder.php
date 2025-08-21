<?php

enum Poder: string {
    /* Básicos que ya usabas */
    case Volar                = 'volar';
    case Superfuerza         = 'superfuerza';
    case Teletransportacion  = 'teletransportacion';
    case Telepatia           = 'telepatia';
    case Telequinesis        = 'telequinesis';
    case Invisibilidad       = 'invisibilidad';

    /* Clásicos X‑Men vistos en tu index/slides */
    case RayosOpticos        = 'rayos_opticos';         // Cíclope
    case Hielo               = 'hielo';                 // Hombre de Hielo
    case ControlClima        = 'control_del_clima';     // Tormenta
    case Magnetismo          = 'magnetismo';            // Polaris / Magneto
    case Curacion            = 'curacion';              // Lobezno/X‑23
    case GritoSonico         = 'grito_sonico';          // Banshee
    case Luz                 = 'luz';                   // Dazzler / Júbilo (energía luminosa)
    case Intangibilidad      = 'intangibilidad';        // Kitty Pryde
    case Probabilidad        = 'probabilidad';          // Domino
    case Hechiceria          = 'hechiceria';            // Magik
    case ArmaduraPsionica    = 'armadura_psionica';     // Armor
    case Arena               = 'arena';                 // Dust
    case Electricidad        = 'electricidad';          // Surge
    case AbsorcionEnergia    = 'absorcion_de_energia';  // Bishop / Gambito (variante)
    case AbsorcionPoderes    = 'absorcion_de_poderes';  // Rogue
    case FormaDiamante       = 'forma_de_diamante';     // Emma Frost
    case PielMetalica        = 'piel_metalica';         // Colossus
    case PoderCosmico        = 'poder_cosmico';         // Fénix

    /* --- Helpers de presentación --- */
    public function label(): string {
        return match ($this) {
            self::Volar               => 'volar',
            self::Superfuerza         => 'superfuerza',
            self::Teletransportacion  => 'teletransportación',
            self::Telepatia           => 'telepatía',
            self::Telequinesis        => 'telequinesis',
            self::Invisibilidad       => 'invisibilidad',

            self::RayosOpticos        => 'rayos ópticos',
            self::Hielo               => 'hielo',
            self::ControlClima        => 'control del clima',
            self::Magnetismo          => 'magnetismo',
            self::Curacion            => 'curación acelerada',
            self::GritoSonico         => 'grito sónico',
            self::Luz                 => 'luz/energía luminosa',
            self::Intangibilidad      => 'intangibilidad',
            self::Probabilidad        => 'manipulación de probabilidad',
            self::Hechiceria          => 'hechicería',
            self::ArmaduraPsionica    => 'armadura psiónica',
            self::Arena               => 'cuerpo de arena',
            self::Electricidad        => 'electricidad',
            self::AbsorcionEnergia    => 'absorción de energía',
            self::AbsorcionPoderes    => 'absorción de poderes',
            self::FormaDiamante       => 'forma de diamante',
            self::PielMetalica        => 'piel metálica',
            self::PoderCosmico        => 'poder cósmico',
        };
    }

    /** Clase de color Bootstrap para la badge */
    public function badgeClass(): string {
        return match ($this) {
            self::Volar               => 'info',
            self::Superfuerza         => 'danger',
            self::Teletransportacion  => 'warning',
            self::Telepatia           => 'primary',
            self::Telequinesis        => 'primary',
            self::Invisibilidad       => 'secondary',

            self::RayosOpticos        => 'danger',
            self::Hielo               => 'info',
            self::ControlClima        => 'info',
            self::Magnetismo          => 'dark',
            self::Curacion            => 'success',
            self::GritoSonico         => 'warning',
            self::Luz                 => 'light',
            self::Intangibilidad      => 'secondary',
            self::Probabilidad        => 'success',
            self::Hechiceria          => 'dark',
            self::ArmaduraPsionica    => 'secondary',
            self::Arena               => 'warning',
            self::Electricidad        => 'info',
            self::AbsorcionEnergia    => 'warning',
            self::AbsorcionPoderes    => 'warning',
            self::FormaDiamante       => 'light',
            self::PielMetalica        => 'secondary',
            self::PoderCosmico        => 'danger',
        };
    }
}
