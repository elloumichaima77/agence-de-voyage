<?php
/**
 * reservationController.php — Gestion des réservations
 */
session_start();
require "../config/db_connect.php";
require "../model/voyage.php";
require "../model/voyageManager.php";
require "../model/reservation.php";
require "../model/reservationmanager.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit;
}

$action   = $_GET['action'] ?? $_POST['action'] ?? '';
$rManager = new ReservationManager($db);
$vManager = new VoyageManager($db);
$errors   = [];
$message  = '';

// ── CRÉER UNE RÉSERVATION ─────────────────────────────────────────────────────
if ($action === 'reserver' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $voyageId    = (int)($_POST['voyage_id']    ?? 0);
    $nbPersonnes = (int)($_POST['nb_personnes'] ?? 1);

    $voyage = $vManager->getById($voyageId);

    if (!$voyage) {
        $errors[] = "Voyage introuvable.";
    } elseif ($nbPersonnes < 1) {
        $errors[] = "Nombre de personnes invalide.";
    } elseif ($voyage->places < $nbPersonnes) {
        $errors[] = "Pas assez de places disponibles (reste : {$voyage->places}).";
    } else {
        $prixTotal = $voyage->prix * $nbPersonnes;
        $res = new Reservation(0, $_SESSION['user_id'], $voyageId, $nbPersonnes, $prixTotal);

        if ($rManager->insert($res)) {
            $vManager->decrementerPlaces($voyageId, $nbPersonnes);
            $message = "Réservation confirmée ! Total : " . number_format($prixTotal, 2) . " TND";
        } else {
            $errors[] = "Erreur lors de la réservation.";
        }
    }
    $_SESSION['res_message'] = $message;
    $_SESSION['res_errors']  = $errors;
    header("Location: ../view/mesReservations.php");
    exit;
}

// ── ANNULER UNE RÉSERVATION ───────────────────────────────────────────────────
if ($action === 'annuler' && isset($_GET['id'])) {
    $id  = (int)$_GET['id'];
    $res = $rManager->getById($id);

    if ($res && $res->user_id === $_SESSION['user_id']) {
        $rManager->updateStatut($id, 'annulee');
    }
    header("Location: ../view/mesReservations.php");
    exit;
}

// ── ADMIN : CHANGER STATUT ────────────────────────────────────────────────────
if ($action === 'statut' && $_SESSION['user_role'] === 'admin') {
    $id     = (int)($_POST['id']     ?? 0);
    $statut = trim($_POST['statut']  ?? '');
    if (in_array($statut, Reservation::STATUTS)) {
        $rManager->updateStatut($id, $statut);
    }
    header("Location: ../view/adminReservations.php");
    exit;
}

header("Location: ../view/catalogue.php");
exit;