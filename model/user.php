<?php
/**
 * User.php - Entité Utilisateur
 */
class User {
    public function __construct(
        private int    $id       = 0,
        private string $nom      = "",
        private string $prenom   = "",
        private string $email    = "",
        private string $password = "",   // stocké haché
        private string $role     = "client",  // client | admin
        private string $telephone = "",
        private string $created_at = ""
    ) {}

    public function __get(string $name) {
        return property_exists($this, $name) ? $this->$name : null;
    }

    public function __set(string $name, $value): void {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function getNomComplet(): string {
        return $this->prenom . ' ' . $this->nom;
    }
}