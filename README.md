# CampusFix — Plateforme de Gestion de Maintenance

Projet développé sous **Laravel 10+ (PHP 8.2+)**, conteneurisé avec **Docker (MySQL 8.0, Nginx, PHP-FPM)**, intégrant le système d'authentification **Laravel Breeze**, la gestion des autorisations via **Gates, Policies & Middleware**, une architecture en couches (**Controller -> Service -> Model**), et des tests automatisés avec **Factories & Seeders**.

---

## 🎯 Architecture & Fonctionnalités Clés

1. **Architecture en Couches (Controller -> Service -> Model)** :
   - **Services métier dédiés** (`SignalementService`, `InterventionService`, `SalleService`, `DashboardService`) pour isoler la logique métier des contrôleurs.
   - Contrôleurs allégés et focalisés sur la gestion des requêtes HTTP et des réponses.

2. **Authentification & Sécurité (Laravel Breeze)** :
   - Flux d'authentification complet Breeze : Connexion, Inscription, Déconnexion et Réinitialisation de mot de passe.
   - Protection CSRF, hachage bcrypt/Argon2id des mots de passe, et throttling contre le brute-force (`RateLimiter`).

3. **Contrôle d'Accès & Autorisations (Gates, Policies, Middleware)** :
   - **Policies** : `SignalementPolicy`, `InterventionPolicy`, `SallePolicy` définissant finement les droits CRUD par entité.
   - **Middleware** : `RoleMiddleware` filtrant les routes selon les rôles (`admin`, `technicien`, `demandeur`).
   - **Gates** : Vérifications directes (`admin-only`, `tech-access`, `intervene`).

4. **Base de Données Relationnelle MySQL & Migrations** :
   - **Zéro fichier SQLite**, **zéro dump SQL statique** : initialisation 100% via les **Migrations Laravel**.
   - Clés étrangères avec contraintes d'intégrité référentielle (`ON DELETE CASCADE`, `ON DELETE SET NULL`).

5. **Données de Démonstration via Factories & DatabaseSeeder** :
   - `UserFactory`, `SalleFactory`, `SignalementFactory`, `InterventionFactory` utilisant **Faker**.
   - Commande unique d'initialisation : `php artisan migrate:fresh --seed`.

6. **Conteneurisation Docker & Structure `docker/`** :
   - L'ensemble des fichiers Docker et dépendances frontend (`package.json`, `Dockerfile`, `docker-compose.yml`, configs Nginx/PHP) sont organisés dans le dossier `docker/`.

---

## 🚀 Démarrage Rapide

### Option A : Via Docker (Recommandé)

```bash
# 1. Lancer les conteneurs (App, Nginx, MySQL, PhpMyAdmin)
docker compose up -d --build

# 2. Exécuter les migrations et le seeder (qui appelle les factories)
docker compose exec app php artisan migrate:fresh --seed
```

- Application Web : **http://localhost:8000**
- PhpMyAdmin : **http://localhost:8080** (User: `campus_user`, Pass: `secret_password`)

---

### Option B : En Local (PHP + Composer)

```bash
# 1. Installer les dépendances PHP et Node
composer install
npm install && npm run build

# 2. Configurer l'environnement (.env déjà prêt pour MySQL)
php artisan key:generate

# 3. Lancer les migrations et le seeder
php artisan migrate:fresh --seed

# 4. Démarrer le serveur de développement
php artisan serve
```

---

## 👥 Comptes de Test Pré-configurés

Tous les comptes utilisent le mot de passe : `password`

| Rôle | Email | Droits & Accès |
| :--- | :--- | :--- |
| **Direction (Admin)** | `admin@estfbs.usms.ac.ma` | Supervision globale, gestion complète des signalements, interventions et salles |
| **Technicien** | `technicien@estfbs.usms.ac.ma` | Prise en charge des pannes, saisie des interventions et durées |
| **Étudiant (Demandeur)** | `etudiant@usms.ma` | Déclaration d'incidents, suivi de l'état de ses signalements |

---

## 🏛️ Structure des Couches du Projet

```
campusfix/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/ (Breeze Controllers)
│   │   │   ├── DashboardController.php
│   │   │   ├── SignalementController.php
│   │   │   └── InterventionController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/Auth/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Salle.php
│   │   ├── Signalement.php
│   │   └── Intervention.php
│   ├── Policies/
│   │   ├── SignalementPolicy.php
│   │   ├── InterventionPolicy.php
│   │   └── SallePolicy.php
│   └── Services/
│       ├── SignalementService.php
│       ├── InterventionService.php
│       ├── SalleService.php
│       └── DashboardService.php
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── SalleFactory.php
│   │   ├── SignalementFactory.php
│   │   └── InterventionFactory.php
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── docker/
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── package.json
│   ├── nginx/default.conf
│   └── php/local.ini
├── resources/views/
│   ├── auth/ (Breeze Views)
│   ├── dashboard/
│   ├── signalements/
│   └── layouts/
└── routes/
    ├── web.php
    └── auth.php
```
