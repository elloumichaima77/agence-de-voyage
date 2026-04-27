<?php
/**
 * FileUploader.php — Classe sécurisée pour l'upload d'images de voyage
 */
class FileUploader {
    private string $targetDirectory;
    private array  $allowedExtensions;
    private int    $maxSize;
    private array  $errors = [];

    public function __construct(
        string $targetDirectory    = '../public/uploads/voyages/',
        array  $allowedExtensions  = ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        int    $maxSize            = 3145728   // 3 Mo
    ) {
        $this->targetDirectory   = rtrim($targetDirectory, '/') . '/';
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
        $this->maxSize           = $maxSize;
    }

    public function upload(array $fileData): string|false {
        $this->errors = [];

        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            $this->addErrorByCode($fileData['error']);
            return false;
        }

        $extension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = "Extension non autorisée : .$extension";
            return false;
        }

        if ($fileData['size'] > $this->maxSize) {
            $maxMo = round($this->maxSize / 1024 / 1024, 1);
            $this->errors[] = "Fichier trop lourd. Max autorisé : {$maxMo} Mo.";
            return false;
        }

        // Vérification MIME réelle (anti-spoofing)
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileData['tmp_name']);
        $allowedMimes = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            $this->errors[] = "Type MIME non autorisé : $mimeType";
            return false;
        }

        if (!is_dir($this->targetDirectory)) {
            if (!mkdir($this->targetDirectory, 0755, true)) {
                $this->errors[] = "Impossible de créer le dossier de destination.";
                return false;
            }
        }

        $newFileName = 'voyage_' . uniqid('', true) . '.' . $extension;
        $destination = $this->targetDirectory . $newFileName;

        if (move_uploaded_file($fileData['tmp_name'], $destination)) {
            return $newFileName;
        }

        $this->errors[] = "Erreur lors du déplacement du fichier.";
        return false;
    }

    public function getErrors(): array { return $this->errors; }

    private function addErrorByCode(int $code): void {
        $this->errors[] = match($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille limite.",
            UPLOAD_ERR_PARTIAL  => "Téléchargement partiel, réessayez.",
            UPLOAD_ERR_NO_FILE  => "Aucun fichier sélectionné.",
            default             => "Erreur inconnue lors de l'upload (code $code).",
        };
    }
}