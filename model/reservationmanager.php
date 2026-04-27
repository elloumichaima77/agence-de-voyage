<?php
class ReservationManager {
    public function __construct(private PDO $db) {}

    public function getAll(): array {
        $sql = "SELECT r.*, v.titre AS voyage_titre, v.destination, v.image, v.pays,
                       u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
                FROM reservations r
                JOIN voyages v ON r.voyage_id = v.id
                JOIN users u   ON r.user_id   = u.id
                ORDER BY r.date_reservation DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reservation');
    }

    public function getByUser(int $userId): array {
        $sql = "SELECT r.*, 
                       v.titre      AS voyage_titre, 
                       v.destination, 
                       v.date_depart, 
                       v.image, 
                       v.pays
                FROM reservations r
                JOIN voyages v ON r.voyage_id = v.id
                WHERE r.user_id = :uid
                ORDER BY r.date_reservation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // FETCH_ASSOC pour éviter les conflits
    }

    public function getById(int $id): ?Reservation {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reservation');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(Reservation $r): bool {
        $sql  = "INSERT INTO reservations (user_id, voyage_id, nb_personnes, prix_total, statut)
                 VALUES (:uid, :vid, :nb, :prix, :statut)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':uid'    => $r->user_id,
            ':vid'    => $r->voyage_id,
            ':nb'     => $r->nb_personnes,
            ':prix'   => $r->prix_total,
            ':statut' => $r->statut,
        ]);
    }

    public function updateStatut(int $id, string $statut): bool {
        $stmt = $this->db->prepare("UPDATE reservations SET statut = :statut WHERE id = :id");
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}