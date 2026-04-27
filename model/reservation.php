<?php
/**
 * Reservation.php - Entité Réservation
 */
class Reservation {
    public const STATUTS = ['en_attente', 'confirmee', 'annulee'];

    public function __construct(
        private int    $id          = 0,
        private int    $user_id     = 0,
        private int    $voyage_id   = 0,
        private int    $nb_personnes = 1,
        private float  $prix_total  = 0.0,
        private string $statut      = "en_attente",
        private string $date_reservation = "",
        // Données jointes (JOIN)
        private string $voyage_titre = "",
        private string $destination  = "",
        private string $user_nom     = "",
        private string $user_prenom  = "",
        private string $user_email   = ""
    ) {}

    public function __get(string $name) {
        return property_exists($this, $name) ? $this->$name : null;
    }

    public function __set(string $name, $value): void {
        if (property_exists($this, $name)) $this->$name = $value;
    }

    public function getBadgeStatut(): string {
        return match($this->statut) {
            'confirmee'  => '<span class="badge badge-success">Confirmée</span>',
            'annulee'    => '<span class="badge badge-danger">Annulée</span>',
            default      => '<span class="badge badge-warning">En attente</span>',
        };
    }
}