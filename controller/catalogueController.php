<?php
/**
 * catalogueController.php — Récupère les voyages pour la vue catalogue
 */
session_start();
require "../config/db_connect.php";
require "../model/voyage.php";
require "../model/voyageManager.php";

$manager    = new VoyageManager($db);
$categories = $manager->getAllCategories();
$pays       = $manager->getAllPays();

// Recherche multicritères via $_GET
$destination = trim($_GET['destination'] ?? '');
$categorie   = trim($_GET['categorie']   ?? '');
$prixMax     = (float)($_GET['prix_max'] ?? 0);
$date        = trim($_GET['date']        ?? '');

$voyages = $manager->search($destination, $categorie, $prixMax, $date);