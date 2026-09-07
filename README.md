# CampusFix — Plateforme de Maintenance & Moteur d'IA Triage

Projet fil rouge développé sous **Laravel 10+ (PHP 8.2+)**, conteneurisé sous **Docker** et exploitant **MySQL 8.0**, le système de rôles/permissions **Laratrust (RBAC)** et un agent d'intelligence artificielle de triage technique (**CampusAiAgent**).

---

## 🎯 1. Fonctionnalités Principales

- **Contrôle d'Accès basé sur les Rôles (RBAC)** :
  - **Demandeur** (Étudiant / Personnel) : déclaration de pannes, suivi en temps réel de ses signalements.
  - **Technicien** : consultation des pannes assignées, prise en charge immédiate, saisie des rapports d'intervention et durée.
  - **Administrateur** : supervision globale, métriques en temps réel, indicateurs clés de performance (KPIs).
- **Agent IA Triage (`CampusAiAgent`)** :
  - Calcul dynamique d'un **Score d'Urgence (0 à 100)** basé sur la sémantique de l'incident (mots-clés de criticité, danger, fuite, feu, court-circuit).
  - Facteur de pondération selon la sensibilité du lieu (Laboratoire, Datacenter, Amphithéâtre vs couloir).
  - Détermination de la sévérité (`faible`, `moyen`, `critique`), estimation du temps d'intervention et recommandations d'action.
  - Bouton de réévaluation en temps réel.
- **Conception Merise & Intégrité** :
  - Modèle Conceptuel (MCD) et Logique (MLD) normalisés.
  - Clés étrangères avec contraintes `ON DELETE CASCADE` pour assurer la cohérence des données.

---

## 🚀 2. Démarrage Rapide avec Docker

### Prérequis :
- Docker et Docker Compose installés.

### Lancement en 1 commande :
```bash
docker-compose up -d --build
```

L'application démarre alors automatiquement :
- **Application Web** : [http://localhost:8000](http://localhost:8000)
- **phpMyAdmin** : [http://localhost:8080](http://localhost:8080) (Serveur: `mysql`, Utilisateur: `campus_user`, Mot de passe: `secret_password`)
- **Base de données MySQL** : Port `3306` (initialisée automatiquement avec le dump `database/dump/campusfix_database.sql`).

---

## 🛠️ 3. Démarrage Local (Sans Docker)

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Exécuter les migrations et le Seeder (avec données de test)
php artisan migrate:fresh --seed

# 4. Lancer le serveur local
php artisan serve
```

---

## 🔑 4. Comptes de Démonstration (Soutenance)

Tous les comptes partagent le mot de passe : `password`

| Rôle | Adresse Email | Mot de passe | Permissions |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@campusfix.test` | `password` | Supervision globale, KPIs, tous les signalements |
| **Technicien** | `technicien@campusfix.test` | `password` | Prise en charge des pannes, saisie des interventions |
| **Demandeur** | `etudiant@campusfix.test` | `password` | Création de signalement, consultation de ses tickets |

---

## 🧪 5. Exécution des Tests Automatisés

```bash
php artisan test
```

---

## 📂 6. Structure de la Base de Données Exportée

Le fichier SQL complet est disponible à l'emplacement suivant :
`database/dump/campusfix_database.sql`

Tables principales :
- `users` : comptes utilisateurs et identifiants hachés.
- `roles` & `role_user` : gestion des rôles multi-niveaux Laratrust.
- `signalements` : tickets de panne avec colonnes IA (`ai_score`, `ai_diagnostic`, `ai_recommended_action`, `ai_estimated_hours`).
- `interventions` : compte-rendus techniques d'intervention.
