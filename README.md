# 🌍 Agence de Voyage

Une application web de gestion d'agence de voyage développée en PHP avec une architecture MVC.

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Apache** (XAMPP / WAMP / LAMP)
- Un navigateur web moderne

---

## ⚙️ Installation et Configuration

### 1. Cloner le Repository

```bash
git clone https://github.com/elloumichaima77/agence-de-voyage.git
cd agence-de-voyage
```

### 2. Configurer le Serveur Web

Placez le dossier du projet dans le répertoire racine de votre serveur web :

- **XAMPP** : `C:/xampp/htdocs/agence-de-voyage`
- **WAMP** : `C:/wamp64/www/agence-de-voyage`
- **Linux** : `/var/www/html/agence-de-voyage`

### 3. Importer la Base de Données

1. Démarrez **MySQL** via XAMPP/WAMP
2. Ouvrez **phpMyAdmin** : `http://localhost/phpmyadmin`
3. Créez une nouvelle base de données nommée `bd_agence_voyage`
4. Cliquez sur **Importer** → sélectionnez le fichier `bd_agence_voyage.sql`
5. Cliquez sur **Exécuter**

Ou via la ligne de commande :

```bash
mysql -u root -p bd_agence_voyage < bd_agence_voyage.sql
```

### 4. Configurer la Connexion à la Base de Données

Ouvrez le fichier `config/` et modifiez les paramètres de connexion :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bd_agence_voyage');
define('DB_USER', 'root');       // Votre utilisateur MySQL
define('DB_PASS', '');           // Votre mot de passe MySQL
```

### 5. Lancer l'Application

Démarrez Apache et MySQL depuis XAMPP/WAMP, puis accédez à :

```
http://localhost/agence-de-voyage/
```

---

## 📁 Structure du Projet

```
agence-de-voyage/
├── assets/                 # Fichiers CSS, JS, images
├── config/                 # Configuration base de données
├── controller/             # Contrôleurs (logique métier)
├── model/                  # Modèles (accès aux données)
├── view/                   # Vues (interface utilisateur)
├── public/uploads/voyages/ # Images des voyages uploadées
├── bd_agence_voyage.sql    # Dump de la base de données
├── index.php               # Point d'entrée de l'application
└── README.md               # Documentation du projet
```

---

## 🗄️ Base de Données

Le fichier `bd_agence_voyage.sql` contient :

- La structure complète des tables
- Les données de test initiales

**Tables principales :**
- `voyages` — Liste des voyages disponibles
- `reservations` — Réservations des clients
- `clients` — Informations des clients
- `utilisateurs` — Comptes administrateurs

---

## 🚀 Fonctionnalités

- 📌 Affichage des voyages disponibles
- 🔍 Recherche et filtrage des destinations
- 📝 Gestion des réservations
- 🖼️ Upload d'images pour les voyages
- 🔐 Espace d'administration
