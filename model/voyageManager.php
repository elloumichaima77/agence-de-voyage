<?php
/**
 * VoyageManager.php - DAO pour les voyages (CRUD complet)
 */
class VoyageManager {
    public function __construct(private PDO $db) {}
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM voyages ORDER BY date_depart ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyage');
    }

    public function getById(int $id): ?Voyage {
        $stmt = $this->db->prepare("SELECT * FROM voyages WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyage');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function search(string $destination = '', string $categorie = '', float $prixMax = 0, string $date = ''): array {
        $sql    = "SELECT * FROM voyages WHERE 1=1";
        $params = [];

        if (!empty($destination)) {
            $sql .= " AND (destination LIKE :dest OR pays LIKE :dest OR titre LIKE :dest)";
            $params[':dest'] = "%" . $destination . "%";
        }
        if (!empty($categorie)) {
            $sql .= " AND categorie = :cat";
            $params[':cat'] = $categorie;
        }
        if ($prixMax > 0) {
            $sql .= " AND prix <= :prix";
            $params[':prix'] = $prixMax;
        }
        if (!empty($date)) {
            $sql .= " AND date_depart >= :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY date_depart ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyage');
    }

    public function getByCategorie(string $categorie): array {
        $stmt = $this->db->prepare("SELECT * FROM voyages WHERE categorie = :cat ORDER BY prix ASC");
        $stmt->execute([':cat' => $categorie]);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyage');
    }

    public function getPromos(): array {
        $stmt = $this->db->query("SELECT * FROM voyages WHERE en_promo = 1 ORDER BY prix ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyage');
    }

    public function getAllCategories(): array {
        $stmt = $this->db->query("SELECT DISTINCT categorie FROM voyages ORDER BY categorie");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllPays(): array {
        $stmt = $this->db->query("SELECT DISTINCT pays FROM voyages ORDER BY pays");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── CREATE ────────────────────────────────────────────────────────────────

    public function insert(Voyage $v): bool {
        $sql = "INSERT INTO voyages 
                (titre, destination, pays, prix, duree, places, date_depart, date_retour,
                 categorie, description, image, en_promo, ancien_prix, note)
                VALUES 
                (:titre, :dest, :pays, :prix, :duree, :places, :depart, :retour,
                 :cat, :desc, :img, :promo, :ancien, :note)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'  => $v->titre,
            ':dest'   => $v->destination,
            ':pays'   => $v->pays,
            ':prix'   => $v->prix,
            ':duree'  => $v->duree,
            ':places' => $v->places,
            ':depart' => $v->date_depart,
            ':retour' => $v->date_retour,
            ':cat'    => $v->categorie,
            ':desc'   => $v->description,
            ':img'    => $v->image,
            ':promo'  => $v->en_promo ? 1 : 0,
            ':ancien' => $v->ancien_prix,
            ':note'   => $v->note,
        ]);
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────

    public function update(Voyage $v): bool {
        $sql = "UPDATE voyages SET
                    titre       = :titre,
                    destination = :dest,
                    pays        = :pays,
                    prix        = :prix,
                    duree       = :duree,
                    places      = :places,
                    date_depart = :depart,
                    date_retour = :retour,
                    categorie   = :cat,
                    description = :desc,
                    en_promo    = :promo,
                    ancien_prix = :ancien,
                    note        = :note
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'  => $v->titre,
            ':dest'   => $v->destination,
            ':pays'   => $v->pays,
            ':prix'   => $v->prix,
            ':duree'  => $v->duree,
            ':places' => $v->places,
            ':depart' => $v->date_depart,
            ':retour' => $v->date_retour,
            ':cat'    => $v->categorie,
            ':desc'   => $v->description,
            ':promo'  => $v->en_promo ? 1 : 0,
            ':ancien' => $v->ancien_prix,
            ':note'   => $v->note,
            ':id'     => $v->id,
        ]);
    }

    public function updateImage(int $id, string $image): bool {
        $stmt = $this->db->prepare("UPDATE voyages SET image = :img WHERE id = :id");
        return $stmt->execute([':img' => $image, ':id' => $id]);
    }

    public function decrementerPlaces(int $id, int $nb): bool {
        $stmt = $this->db->prepare(
            "UPDATE voyages SET places = places - :nb WHERE id = :id AND places >= :nb"
        );
        return $stmt->execute([':nb' => $nb, ':id' => $id]);
    }

    // ── DELETE ────────────────────────────────────────────────────────────────

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM voyages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}