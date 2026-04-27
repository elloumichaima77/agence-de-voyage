-- ══════════════════════════════════════════════
--  SkyVoyage — Dump SQL de la base de données
--  Base : bd_agence_voyage
-- ══════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS bd_agence_voyage
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bd_agence_voyage;

-- ── TABLE USERS ───────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100)  NOT NULL,
    prenom     VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('client','admin') DEFAULT 'client',
    telephone  VARCHAR(20)   DEFAULT '',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── TABLE VOYAGES ─────────────────────────────
CREATE TABLE IF NOT EXISTS voyages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(200)  NOT NULL,
    destination VARCHAR(150)  NOT NULL,
    pays        VARCHAR(100)  NOT NULL,
    prix        DECIMAL(10,2) NOT NULL DEFAULT 0,
    duree       INT           NOT NULL DEFAULT 1,
    places      INT           NOT NULL DEFAULT 0,
    date_depart DATE          NOT NULL,
    date_retour DATE,
    categorie   VARCHAR(80)   DEFAULT 'Découverte',
    description TEXT,
    image       VARCHAR(255)  DEFAULT 'voyage_default.jpg',
    en_promo    TINYINT(1)    DEFAULT 0,
    ancien_prix DECIMAL(10,2),
    note        DECIMAL(3,1)  DEFAULT 0.0,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── TABLE RÉSERVATIONS ────────────────────────
CREATE TABLE IF NOT EXISTS reservations (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    voyage_id        INT NOT NULL,
    nb_personnes     INT           DEFAULT 1,
    prix_total       DECIMAL(10,2) DEFAULT 0,
    statut           ENUM('en_attente','confirmee','annulee') DEFAULT 'en_attente',
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (voyage_id) REFERENCES voyages(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ══════════════════════════════════════════════
--  DONNÉES DE TEST
-- ══════════════════════════════════════════════

-- Utilisateurs (mdp admin : admin123 | client : client123)
INSERT INTO users (nom, prenom, email, password, role, telephone) VALUES
('Admin',    'Super',   'admin@skyvoyage.tn',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',  '+216 71 000 000'),
('Ben Salah','Amira',   'amira@mail.tn',       '$2y$10$TKh8H1.PkuiI3cBXM/3Wru5HcfYiOu4o5xm.GwsF.jI2jO1/B5a2', 'client', '+216 99 123 456'),
('Chaabane', 'Yassine', 'yassine@mail.tn',     '$2y$10$TKh8H1.PkuiI3cBXM/3Wru5HcfYiOu4o5xm.GwsF.jI2jO1/B5a2', 'client', '+216 55 654 321');

-- Voyages
INSERT INTO voyages (titre, destination, pays, prix, duree, places, date_depart, date_retour, categorie, description, image, en_promo, ancien_prix, note) VALUES
('Escapade Romantique à Paris',  'Paris',     'France',     2800.00, 7,  15, '2026-06-10', '2026-06-17', 'Romantique', 'Découvrez la Ville Lumière : Eiffel, Louvre, croisières sur la Seine.', 'voyage_default.jpg', 0, NULL,    4.8),
('Safari en Tanzanie',           'Serengeti', 'Tanzanie',   5500.00, 10, 8,  '2026-07-05', '2026-07-15', 'Aventure',   'Partez à la rencontre des Big Five dans la savane africaine.',         'voyage_default.jpg', 1, 6500.00, 4.9),
('Plages de Bali',               'Bali',      'Indonésie',  3200.00, 12, 20, '2026-08-01', '2026-08-13', 'Plage',      'Plages paradisiaques, temples anciens et rizières en terrasse.',       'voyage_default.jpg', 0, NULL,    4.7),
('Rome et la Toscane',           'Rome',      'Italie',     2100.00, 8,  25, '2026-05-20', '2026-05-28', 'Culture',    'Art, gastronomie et dolce vita au cœur de l\'Italie.',                 'voyage_default.jpg', 1, 2500.00, 4.6),
('Maldives Luxe',                'Malé',      'Maldives',   8900.00, 7,  6,  '2026-09-15', '2026-09-22', 'Luxe',       'Villas sur pilotis, lagon turquoise et plongée en snorkeling.',        'voyage_default.jpg', 0, NULL,    5.0),
('Découverte du Japon',          'Tokyo',     'Japon',      4200.00, 14, 12, '2026-10-01', '2026-10-15', 'Découverte', 'De Tokyo à Kyoto : temples, geishas et cuisine nippone.',              'voyage_default.jpg', 0, NULL,    4.8),
('Aventure en Patagonie',        'Torres del Paine','Chili',6800.00, 15, 10, '2026-11-10', '2026-11-25', 'Aventure',   'Trekking et paysages sauvages au bout du monde.',                      'voyage_default.jpg', 0, NULL,    4.9),
('Famille à Disneyland Paris',   'Paris',     'France',     1800.00, 4,  30, '2026-07-15', '2026-07-19', 'Famille',    'Magie et émerveillement pour petits et grands à Disneyland.',          'voyage_default.jpg', 1, 2200.00, 4.5);

-- Réservations de test
INSERT INTO reservations (user_id, voyage_id, nb_personnes, prix_total, statut) VALUES
(2, 1, 2, 5600.00, 'confirmee'),
(2, 3, 1, 3200.00, 'en_attente'),
(3, 4, 3, 6300.00, 'confirmee'),
(3, 5, 2, 17800.00,'en_attente');
