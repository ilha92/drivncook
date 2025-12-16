# Projet Driv'n Cook – Mission 1

## Présentation générale

Ce projet correspond à la **Mission 1 : Gestion des services franchisés** du projet annuel ESGI 2024-2025.

L’objectif est de développer une **application web** permettant à la société **Driv'n Cook** de gérer :

- les franchisés
- le parc de camions
- les pannes et le carnet d’entretien
- l’approvisionnement
- les ventes et leur historique

L’application est développée avec des technologies **simples et accessibles** afin de garantir une bonne compréhension du code.

---

## Technologies utilisées

- **Back-end** : PHP (sans framework)
- **Front-end** : HTML, CSS, JavaScript (basique)
- **Base de données** : MySQL
- **Outil BDD** : MySQL Workbench
- **Serveur local** : WAMP

---

## Architecture générale du projet

Le projet est organisé de manière claire afin de séparer les responsabilités et faciliter la maintenance.

```text
drivncook/
│
├── public/
  ├── franchise/
│   ├── dashboard.php
│   ├── profil.php
│   ├── edit_profil.php
│   ├── achats.php
│   └── nouvel_achat.php

│   ├── index.php
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── config/
│   └── database.php
│
├── src/
│   ├── models/
│   │   ├── Adminr.php
│   │   ├── Franchise.php
│   │   └── Camion.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── FranchiseController.php
│   │   └── CamionController.php
│   │
│   └── views/
│       ├── admin/
│       └── franchise/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   └── js/
│       └── script.js
│
└── README.md

```

---

## Détail des dossiers et fichiers

### `config/`

Contient les fichiers de configuration du projet.

- `database.php` :

  - gère la connexion à la base de données MySQL
  - crée l’objet `$pdo` utilisé dans tout le projet

---

### `public/`

Dossier accessible depuis le navigateur.

- `index.php` : page d’accueil
- `login.php` : formulaire de connexion
- `register.php` : formulaire d’inscription permettant la création d’un compte franchisé
- `logout.php` : déconnexion de l’utilisateur

Ce dossier est le **point d’entrée du site web**.

---

### `src/`

Contient toute la logique de l’application.

### `controllers/`

Les contrôleurs gèrent les actions de l’utilisateur.

- `AuthController.php` :

  - connexion
  - gestion des sessions
  - redirections selon le rôle (admin ou franchisé)

- `FranchiseController.php` :

  - création d’un franchisé
  - modification
  - suppression
  - affichage

- `CamionController.php` :

  - gestion du parc de camions
  - attribution à un franchisé

---

#### 📂 `models/`

Les modèles communiquent avec la base de données.

- `Admin.php` :

  - requêtes SQL liées aux Administrateurs

- `Franchise.php` :

  - requêtes SQL liées aux franchisés

- `Camion.php` :

  - requêtes SQL liées aux camions

👉 Chaque modèle correspond à une table de la base de données.

---

#### 📂 `views/`

Contient les pages HTML affichées à l’utilisateur.

- `admin/` :

  - pages accessibles uniquement à l’administrateur

- `franchise/` :

  - espace personnel du franchisé

---

### 🔹 `assets/`

Contient les fichiers statiques.

- `css/` : styles CSS
- `js/` : scripts JavaScript simples

---

## 🔐 Gestion des rôles et inscription

Deux types d’utilisateurs existent :

- **Administrateur**

  - créé directement dans la base de données
  - accès au back-office
  - aucun utilisateur ne peut s’inscrire en tant qu’administrateur

- **Franchisé**

  - peut créer son compte via la page d’inscription (register)
  - le rôle `franchise` est attribué automatiquement côté serveur
  - accès à un espace personnel

⚠️ Le rôle **administrateur n’est jamais disponible** dans les formulaires.
⚠️ Le rôle `franchise` est défini automatiquement côté PHP lors de l’inscription.

---

## Évolutions prévues

- génération de PDF (ventes, historiques)
- amélioration de la sécurité
- ajout de statistiques

---

**Projet réalisé dans le cadre du projet annuel ESGI 2024-2025 – Mission 1**
