<?php
/**
 * Voyage.php - Entité Voyage
 */
class Voyage {
    public const DEVISE = "TND";

    public function __construct(
        private int    $id         = 0,
        private string $titre      = "",
        private string $destination = "",
        private string $pays       = "",
        private float  $prix       = 0.0,
        private int    $duree      = 1,         // en jours
        private int    $places     = 0,
        private string $date_depart = "",
        private string $date_retour = "",
        private string $categorie  = "Découverte",
        private string $description = "",
        private string $image      = "voyage_default.jpg",
        private bool   $en_promo   = false,
        private ?float $ancien_prix = null,
        private float  $note       = 0.0
    ) {}

    public function __toString(): string {
        return "Voyage [{$this->id}] {$this->titre} → {$this->destination} | "
             . number_format($this->prix, 2) . " " . self::DEVISE
             . " | Places: {$this->places}";
    }

    public function __get(string $name) {
        return property_exists($this, $name) ? $this->$name : null;
    }

    public function __set(string $name, $value): void {
        if (!property_exists($this, $name)) return;

        if ($name === 'prix' && $value < 0) {
            echo "Erreur : Le prix ne peut pas être négatif.<br>"; return;
        }
        if ($name === 'places' && $value < 0) {
            echo "Erreur : Les places ne peuvent pas être négatives.<br>"; return;
        }
        if ($name === 'note' && ($value < 0 || $value > 5)) {
            echo "Erreur : La note doit être entre 0 et 5.<br>"; return;
        }
        $this->$name = $value;
    }

    public function isDisponible(): bool {
        return $this->places > 0;
    }

    public function getEtoiles(): string {
        $plein  = (int)$this->note;
        $demi   = ($this->note - $plein >= 0.5) ? 1 : 0;
        $vide   = 5 - $plein - $demi;
        return str_repeat('★', $plein) . str_repeat('½', $demi) . str_repeat('☆', $vide);
    }
}