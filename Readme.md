# Projet Driv'n Cook – Mission 1

##  Présentation générale

Ce projet correspond à la **Mission 1 : Gestion des services franchisés** du projet annuel ESGI 2024-2025.

L’objectif est de développer une **application web** permettant à la société **Driv'n Cook** de gérer :

* les franchisés
* le parc de camions
* les pannes et le carnet d’entretien
* l’approvisionnement
* les ventes et leur historique

L’application est développée avec des technologies **simples et accessibles** afin de garantir une bonne compréhension du code.

---

##  Technologies utilisées

* **Back-end** : PHP (sans framework)
* **Front-end** : HTML, CSS, JavaScript (basique)
* **Base de données** : MySQL
* **Outil BDD** : MySQL Workbench
* **Serveur local** : XAMPP / WAMP / MAMP

---

## Architecture générale du projet

Le projet est organisé de manière claire afin de séparer les responsabilités et faciliter la maintenance.

```text
drivncook/
│
├── config/
│   └── database.php
│
├── public/
│   ├── index.php
│   ├── login.php
│   └── logout.php
│   └── register.php
│
├── src/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── FranchiseController.php
│   │   └── CamionController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Franchise.php
│   │   └── Camion.php
│   │
│   └── views/
│       ├── admin/
│       └── franchise/
│
├── assets/
│   ├── css/
│   └── js/
│
└── README.md
```

---

##  Détail des dossiers et fichiers

###  `config/`

Contient les fichiers de configuration du projet.

* `database.php` :

  * gère la connexion à la base de données MySQL
  * crée l’objet `$pdo` utilisé dans tout le projet

---

###  `public/`

Dossier accessible depuis le navigateur.

* `index.php` : page d’accueil
* `login.php` : formulaire de connexion
* `register.php` : formulaire d’inscription permettant la création d’un compte franchisé
* `logout.php` : déconnexion de l’utilisateur

 Ce dossier est le **point d’entrée du site web**.

---

###  `src/`

Contient toute la logique de l’application.

#### 📂 `controllers/`

Les contrôleurs gèrent les actions de l’utilisateur.

* `AuthController.php` :

  * connexion
  * gestion des sessions
  * redirections selon le rôle (admin ou franchisé)

* `FranchiseController.php` :

  * création d’un franchisé
  * modification
  * suppression
  * affichage

* `CamionController.php` :

  * gestion du parc de camions
  * attribution à un franchisé

---

#### 📂 `models/`

Les modèles communiquent avec la base de données.

* `User.php` :

  * requêtes SQL liées aux utilisateurs

* `Franchise.php` :

  * requêtes SQL liées aux franchisés

* `Camion.php` :

  * requêtes SQL liées aux camions

👉 Chaque modèle correspond à une table de la base de données.

---

#### 📂 `views/`

Contient les pages HTML affichées à l’utilisateur.

* `admin/` :

  * pages accessibles uniquement à l’administrateur

* `franchise/` :

  * espace personnel du franchisé

---

### 🔹 `assets/`

Contient les fichiers statiques.

* `css/` : styles CSS
* `js/` : scripts JavaScript simples

---

## 🔐 Gestion des rôles et inscription

Deux types d’utilisateurs existent :

* **Administrateur**

  * créé directement dans la base de données
  * accès au back-office
  * aucun utilisateur ne peut s’inscrire en tant qu’administrateur

* **Franchisé**

  * peut créer son compte via la page d’inscription (register)
  * le rôle `franchise` est attribué automatiquement côté serveur
  * accès à un espace personnel

⚠️ Le rôle **administrateur n’est jamais disponible** dans les formulaires.
⚠️ Le rôle `franchise` est défini automatiquement côté PHP lors de l’inscription.

---

##  Objectif pédagogique

Cette architecture a été choisie pour :

* rester **simple et compréhensible**
* respecter une logique professionnelle
* faciliter l’évolution du projet
* répondre aux exigences du sujet

---

##  Évolutions prévues

* génération de PDF (ventes, historiques)
* amélioration de la sécurité
* ajout de statistiques

---

**Projet réalisé dans le cadre du projet annuel ESGI 2024-2025 – Mission 1**

---

## Phase 1 — Back-end (maintenant)

Ce référentiel contient une première implémentation back-end pour la gestion des franchisés et des camions.

Ce qui est implémenté dans la Phase 1 :

- Schéma de base : `db/schema.sql` (création des tables utilisateurs, franchises, camions, entretiens, ventes, approvisionnements).
- Seed minimal : `db/seed.sql` (exemples et instructions pour générer des mots de passe hashés).
- Modèles : `src/models/User.php`, `src/models/Franchise.php`, `src/models/Camion.php` (CRUD de base).
- Contrôleurs : `src/controllers/AuthController.php`, `src/controllers/FranchiseController.php`, `src/controllers/CamionController.php`.
- Pages de test HTML (formulaires) :
  - `/public/franchises.php` (liste), `/public/franchise_edit.php` (create/edit)
  - `/public/camions.php` (liste), `/public/camion_edit.php` (create/edit)

Tests manuels (quick start) :

1. Importer `db/schema.sql` puis (optionnel) `db/seed.sql`.
2. Générer des hashes de mots de passe pour `db/seed.sql` si vous souhaitez utiliser les exemples (`php -r "echo password_hash('votre_mdp', PASSWORD_DEFAULT)."`).
3. Démarrer Apache + MySQL (XAMPP/WAMP) et ouvrir :

  - `http://localhost/drivncook/public/franchises.php` pour tester les franchises
  - `http://localhost/drivncook/public/camions.php` pour tester les camions

4. Les formulaires permettent de créer, modifier et supprimer des enregistrements (intenté pour usage local et tests).

Prochaines étapes recommandées :

- Renforcer la validation et gestion des erreurs.
- Ajouter authentification et accès restreint aux pages d'administration (déjà commencé).
- Ajouter tests unitaires pour les modèles.

