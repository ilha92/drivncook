Projet Driv'n Cook – Mission 1

j'ai heberger mon site voici l'url : https://inkwei.com/

Présentation générale
Ce projet correspond à la Mission 1 : Gestion des services franchisés du projet annuel ESGI 2024-2025.
​
L’objectif est de développer une application web permettant à la société Driv'n Cook de gérer les franchisés, les camions, les pannes, l’entretien, l’approvisionnement et les ventes.

Technologies utilisées
Back-end : PHP (sans framework)

Front-end : HTML, CSS, JavaScript (basique)

Base de données : MySQL

Outil BDD : MySQL Workbench

Serveur local : WAMP

Ces choix restent simples pour se concentrer sur la logique métier et l’organisation du code.
​

Architecture du projet
L’architecture est découpée par responsabilités : accès, pages publiques, génération de PDF, configuration, logique applicative et ressources front.
​

text
drivncook/
│
├── access/
│ ├── login.php
│ ├── register.php
│ └── logout.php
│
├── public/
│ ├── index.php
│ ├── admin/
│ │ ├── dashboard.php
│ │ ├── achats.php
│ │ ├── approvisionnements.php
│ │ ├── camions.php
│ │ ├── entrepots.php
│ │ ├── franchises.php
│ │ ├── produits.php
│ │ └── ventes.php
│ └── franchise/
│ ├── dashboard.php
│ ├── profil.php
│ ├── achat.php
│ ├── achats.php
│ ├── camions.php
│ ├── commandes.php
│ ├── entretien.php
│ └── ventes.php
│
├── pdf/
│ ├── franchises_pdf.php
│ └── ventes_pdf.php
│
├── config/
│ └── database.php
│
├── src/
│ ├── models/
│ │ ├── Admin.php
│ │ ├── Franchise.php
│ │ ├── Camion.php
│ │ ├── Produit.php
│ │ ├── Vente.php
│ │ └── Entretien.php
│ └── controllers/
│ ├── AuthController.php
│ ├── FranchiseController.php
│ ├── CamionController.php
│ ├── ProduitController.php
│ ├── VenteController.php
│ └── EntretienController.php
│
├── assets/
│ ├── css/
│ │ └── style.css
│ └── js/
│ └── script.js
│
└── README.md

Fonctionnalités principales
Gestion des franchisés : création, modification, suppression, consultation.

Gestion du parc de camions et des entrepôts, avec suivi des entretiens et des pannes.

Gestion des produits, des approvisionnements et des achats des franchisés.

Gestion des ventes avec historique accessible pour l’admin et les franchisés.

Génération de PDF pour les listes de franchisés et les rapports de ventes.

Ces fonctionnalités couvrent la gestion opérationnelle d’un réseau de franchises (administration centrale et côté franchisé).
​

Accès et rôles
Deux types d’utilisateurs existent dans l’application.

Administrateur

Créé directement dans la base de données.

Accès au back-office (gestion des franchisés, camions, produits, ventes, etc.).

Le rôle admin n’apparaît jamais dans les formulaires.

Franchisé

Peut créer un compte via register.php.

Le rôle est défini automatiquement côté PHP lors de l’inscription.

Accès à un espace personnel pour suivre ses camions, ses commandes, ses achats et ses ventes.

La séparation des rôles permet de sécuriser les droits et de limiter les actions possibles selon le profil.
​

Projet réalisé dans le cadre du projet annuel ESGI 2024-2025 – Mission 1.
