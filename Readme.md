Projet Driv'n Cook – Mission 1

Lien du site hébergé : https://inkwei.com/

GitHub du projet : https://github.com/ilha92/drivncook

Présentation générale

Pour cette Mission 1, j’ai travaillé sur la gestion des services franchisés du projet annuel ESGI 2024-2025.
L’objectif était de créer une application web permettant à la société Driv’n Cook de gérer :

Les franchisés

Les camions

Les pannes et l’entretien

L’approvisionnement

Les ventes

Technologies utilisées

Back-end : PHP (sans framework)

Front-end : HTML, CSS, JavaScript (basique)

Base de données : MySQL, gérée via MySQL Workbench

Serveur local : WAMP

J’ai choisi des technologies simples pour me concentrer sur la logique métier et l’organisation du code.

Architecture du projet

Le projet est organisé par responsabilités, ce qui rend le code plus clair et facile à maintenir :

drivncook/
│
├── access/ # Connexion, inscription, déconnexion
│ ├── login.php
│ ├── register.php
│ └── logout.php
│
├── public/ # Pages accessibles côté utilisateur
│ ├── index.php
│ ├── admin/ # Back-office admin
│ └── franchise/ # Espace franchisé
│
├── pdf/ # Génération de PDF
│
├── config/ # Configuration BDD
│
├── src/ # Code source
│ ├── models/ # Gestion des données

├── assets/ # Styles et scripts front
└── README.md

Fonctionnalités principales

Gestion des franchisés : création, modification, suppression, consultation

Gestion des camions et entrepôts avec suivi des entretiens et pannes

Gestion des produits et approvisionnements

Suivi des ventes avec historique accessible aux admins et franchisés

Génération de PDF pour les rapports et listes

Ces fonctionnalités couvrent la gestion opérationnelle du réseau de franchises, côté administration et franchisé.

Accès et rôles

Il y a 2 types d’utilisateurs :

1. Administrateur

Créé directement dans la base de données

Accès complet au back-office

Ne peut pas créer de compte via formulaire

2. Franchisé

Peut s’inscrire via register.php

Accès à un espace personnel pour suivre camions, commandes, achats et ventes

La séparation des rôles permet de sécuriser l’accès et de limiter les actions selon le profil.

Projet réalisé dans le cadre du projet annuel ESGI 2024-2025 – Mission 1.
