<?php
/**
 * UserManager.php - DAO pour les utilisateurs
 */
class UserManager {
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool)$stmt->fetchColumn();
    }

    public function insert(User $u): bool {
        $sql  = "INSERT INTO users (nom, prenom, email, password, role, telephone) 
                 VALUES (:nom, :prenom, :email, :pwd, :role, :tel)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'    => $u->nom,
            ':prenom' => $u->prenom,
            ':email'  => $u->email,
            ':pwd'    => $u->password,
            ':role'   => $u->role,
            ':tel'    => $u->telephone,
        ]);
    }

    public function getLastId(): int {
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}