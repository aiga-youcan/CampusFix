# CampusFix 🎓🔧

## 📖 À Propos du Projet (Projet Fil Rouge)

**CampusFix** est une application web full-stack développée dans le cadre d'un **Projet de Fin d'Étude (Projet Fil Rouge)**. Son objectif principal est de digitaliser et d'optimiser la gestion des espaces physiques et les interventions de maintenance au sein d'un campus universitaire ou d'une entreprise.

Souvent, le signalement d'un projecteur en panne, d'un problème de plomberie ou d'un défaut électrique repose sur une communication lente, au format papier ou désorganisée. CampusFix résout ce problème en offrant un tableau de bord centralisé où les utilisateurs peuvent signaler des incidents, et où les administrateurs peuvent assigner ces tâches aux équipes de maintenance tout en suivant le cycle de résolution en temps réel.

### 🎯 Objectifs

* **Digitalisation :** Éliminer le format papier pour les demandes de maintenance.
* **Traçabilité :** Suivre le statut exact et l'historique de chaque incident signalé.
* **Efficacité :** Réduire les temps d'arrêt des salles de classe et des installations du campus.

## 👥 Fonctionnalités et Acteurs

L'application est structurée autour de trois types d'utilisateurs principaux, chacun disposant de permissions et de tableaux de bord spécifiques :

### 1. 👨‍🎓 Utilisateurs Standards (Étudiants / Professeurs)
* **Tableau de bord :** Consulter l'état global des incidents qu'ils ont signalés.
* **Signaler un incident (Signalement) :** Soumettre un ticket en précisant la salle, la catégorie de l'incident (matériel, plomberie, etc.) et son degré d'urgence.
* **Notifications :** Recevoir des alertes en temps réel lorsque le statut de leur ticket change (ex: de *En attente* à *En cours* ou *Résolu*).

### 2. 🛠️ Personnel de Maintenance (Techniciens)
* **Liste des Interventions :** Consulter les tâches assignées triées par priorité et par date.
* **Mise à jour du statut :** Modifier l'état du ticket en *Démarré*, *En attente de pièces*, ou *Terminé*.
* **Rapports d'intervention :** Ajouter des notes détaillant la réparation effectuée ou les pièces remplacées lors de l'intervention.

### 3. 👑 Administrateur
* **Gestion des Salles :** Ajouter, modifier ou supprimer des salles et installations du campus.
* **Gestion des Utilisateurs :** Gérer les rôles et les permissions de tous les utilisateurs sur la plateforme.
* **Affectation des Tickets :** Examiner les "Signalements" entrants et assigner les "Interventions" à des techniciens spécifiques.
* **Analytiques et Statistiques :** Visualiser des indicateurs globaux (ex: temps de résolution moyen, types d'incidents les plus fréquents).

## ⚙️ Architecture et UML

Ce projet respecte les principes de la Clean Architecture en utilisant le patron **MVC (Modèle-Vue-Contrôleur)**, enrichi d'une **Couche de Services (Service Layer)** pour garder les contrôleurs légers et testables.

* **Design Pattern :** MVC + Service / Repository Pattern.
* **Conception de la Base de Données :** Base de données relationnelle normalisée pour assurer l'intégrité des données.

## 🛠️ Technologies Utilisées

### Backend
* [Laravel 10](https://laravel.com/) - Le framework PHP pour les artisans du web.
* PHP 8.1+
* MySQL (via Docker)

### Frontend
* Moteur de template Laravel Blade
* Tailwind CSS / Bootstrap *(À modifier selon votre choix)*
* Alpine.js / jQuery *(À modifier si applicable)*

### DevOps & Outils
* Docker & Docker Compose
* Git & GitHub

## 🚀 Guide d'Installation

Suivez ces instructions pour configurer le projet localement pour le développement et les tests.

### Prérequis
Assurez-vous d'avoir installé les éléments suivants :
* [Docker Desktop](https://www.docker.com/products/docker-desktop)
* [Composer](https://getcomposer.org/)
* [Git](https://git-scm.com/)

### Étapes d'installation

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/aiga-youcan/campusfix.git
   cd campusfix
   ```

2. **Installer les dépendances PHP :**
   ```bash
   composer install
   ```

3. **Configurer les Variables d'Environnement :**
   ```bash
   cp .env.example .env
   ```
   *Assurez-vous de vérifier que les identifiants de votre base de données dans le `.env` correspondent à ceux de votre fichier `docker-compose.yml`.*

4. **Démarrer les Conteneurs Docker :**
   ```bash
   docker-compose up -d
   ```

5. **Générer la Clé de l'Application & Migrer la Base de Données :**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```
   *(L'option `--seed` remplira la base de données avec des utilisateurs, des salles et des tickets de test).*

6. **Accéder à l'Application :**
   Ouvrez votre navigateur et allez sur : `(https://campusfix.freehosting.dev/)`

## 📸 Captures d'Écran

| Tableau de Bord Admin | Formulaire de Signalement | 
| ----- | ----- | 
| <img src="https://github.com/user-attachments/assets/c16d41ea-f96c-42bc-a4f4-9502be570744" />
| <img src="https://github.com/user-attachments/assets/1eb398d6-e03c-46d3-9329-a186930a6de0" />
| 

| Vue Technicien | Notifications en Temps Réel | 
| ----- | ----- | 
| <img src="https://github.com/user-attachments/assets/ee7a8063-1113-444c-ac88-5465dbdc718c" />
| <img src="https://github.com/user-attachments/assets/1f3a1842-034e-43bf-a08d-54b77d43adaa" />
| 

## 📂 Structure du Projet

Un aperçu des répertoires principaux reflétant l'architecture personnalisée :

```text
campusfix/
├── app/
│   ├── Http/Controllers/   # Gère les requêtes entrantes
│   ├── Models/             # Modèles Eloquent (Salles, Signalements...)
│   └── Services/           # Logique Métier Encapsulée (Couche personnalisée)
├── database/
│   ├── migrations/         # Définitions des schémas de la BDD
│   └── seeders/            # Peuplement initial des données
├── resources/
│   └── views/              # Templates UI Blade
├── routes/
│   ├── web.php             # Routes de l'interface web
│   └── api.php             # Points de terminaison API (si applicable)
└── docker-compose.yml      # Configuration de l'environnement Docker
```

## 🔮 Perspectives d'Évolution (Roadmap)

* [ ] **Application Mobile :** Développer une application mobile cross-platform avec Flutter ou React Native communiquant avec une API Laravel.
* [ ] **Intégration QR Code :** Permettre aux utilisateurs de scanner un QR code situé dans une salle pour ouvrir instantanément un formulaire de signalement pré-rempli.
* [ ] **Maintenance Préventive :** Ajouter une planification pour des vérifications de routine avant que les problèmes ne surviennent.

## 👨‍💻 Auteur

**SABRAR RIDA**

* LinkedIn: [Votre Profil](https://linkedin.com/in/ridasabrar)
* GitHub: [@aiga-youcan](https://github.com/aiga-youcan)

*Développé avec ❤️ comme Projet de Fin d'Étude.*
