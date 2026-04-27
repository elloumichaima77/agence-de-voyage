<?php
/**
 * addVoyageController.php — Ajout d'un voyage (admin uniquement)
 */
session_start();
require "../config/db_connect.php";
require "../model/voyage.php";
require "../model/voyageManager.php";
require "../model/fileUploder.php";

// Sécurité : admin uniquement
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit;
}

$errors  = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des champs
    $titre      = trim($_POST['titre']      ?? '');
    $dest       = trim($_POST['destination']?? '');
    $pays       = trim($_POST['pays']       ?? '');
    $prix       = (float)($_POST['prix']    ?? 0);
    $duree      = (int)($_POST['duree']     ?? 1);
    $places     = (int)($_POST['places']    ?? 0);
    $depart     = trim($_POST['date_depart']?? '');
    $retour     = trim($_POST['date_retour']?? '');
    $cat        = trim($_POST['categorie']  ?? '');
    $desc       = trim($_POST['description']?? '');
    $en_promo   = isset($_POST['en_promo']) ? true : false;
    $ancien_prix = $en_promo ? (float)($_POST['ancien_prix'] ?? 0) : null;

    if (empty($titre) || empty($dest) || empty($pays) || $prix <= 0 || empty($depart)) {
        $errors[] = "Merci de remplir tous les champs obligatoires.";
    }

    // Gestion image
    $imageName = 'images.jpg';
    if (!empty($_FILES['image_file']['name'])) {
        $uploader = new FileUploader('../public/uploads/voyages/');
        $result   = $uploader->upload($_FILES['image_file']);
        if ($result) {
            $imageName = $result;
        } else {
            $errors = array_merge($errors, $uploader->getErrors());
        }
    }

    if (empty($errors)) {
        $voyage = new Voyage(0, $titre, $dest, $pays, $prix, $duree, $places,
                             $depart, $retour, $cat, $desc, $imageName, $en_promo, $ancien_prix);
        $manager = new VoyageManager($db);
        if ($manager->insert($voyage)) {
            $message = "Le voyage \"$titre\" a été ajouté avec succès !";
        } else {
            $errors[] = "Erreur lors de l'enregistrement en base de données.";
        }
    }
}

require "../view/addVoyage.php";