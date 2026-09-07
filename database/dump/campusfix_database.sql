-- ====================================================================
-- CampusFix Database Export (MySQL 8.0+)
-- Projet Fil Rouge Full-Stack Laravel (Laratrust RBAC + AI Triage)
-- Date d'export : Septembre 2026
-- ====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Structure de la table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données initiales pour `users` (Mot de passe commun : 'password')
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Directeur Administratif', 'admin@campusfix.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(2, 'Karim Alami (Technicien)', 'technicien@campusfix.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
(3, 'Youssef Bennani (Étudiant)', 'etudiant@campusfix.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- --------------------------------------------------------
-- Structure de la table `roles` (Laratrust)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'admin', 'Administrateur', 'Gestion globale et supervision'),
(2, 'technicien', 'Technicien de Maintenance', 'Interventions techniques et clôture des pannes'),
(3, 'demandeur', 'Demandeur', 'Création et consultation de signalements');

-- --------------------------------------------------------
-- Structure de la table `role_user`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'App\\Models\\User',
  PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(1, 1, 'App\\Models\\User'),
(2, 2, 'App\\Models\\User'),
(3, 3, 'App\\Models\\User');

-- --------------------------------------------------------
-- Structure de la table `signalements`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `signalements`;
CREATE TABLE `signalements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('plomberie','electricite','mobilier','autre') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'autre',
  `severity` enum('faible','moyen','critique') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'faible',
  `status` enum('signale','pris_en_charge','resolu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'signale',
  `ai_score` int(11) NOT NULL DEFAULT 10,
  `ai_diagnostic` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ai_recommended_action` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ai_estimated_hours` decimal(4,1) NOT NULL DEFAULT 2.0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `signalements_user_id_foreign` (`user_id`),
  CONSTRAINT `signalements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `signalements` (`id`, `user_id`, `title`, `description`, `location`, `category`, `severity`, `status`, `ai_score`, `ai_diagnostic`, `ai_recommended_action`, `ai_estimated_hours`, `created_at`, `updated_at`) VALUES
(1, 3, 'Court-circuit et étincelles au tableau électrique', 'Des étincelles et une forte odeur de brûlé proviennent du tableau dans le laboratoire de chimie.', 'Laboratoire de Chimie - Bâtiment C', 'electricite', 'critique', 'pris_en_charge', 95, 'Intervention d\'urgence prioritaire requise. Risque élevé sur la sécurité des usagers ou la continuité de service.', 'Couper l\'alimentation générale du laboratoire et déployer immédiatement un électricien agréé.', 1.5, NOW(), NOW()),
(2, 3, 'Fuite d\'eau sous l\'évier des sanitaires', 'Un écoulement d\'eau constant sous le robinet provoque une flaque au sol près des toilettes.', 'Sanitaires 1er étage - Bâtiment A', 'plomberie', 'moyen', 'signale', 55, 'Incident modéré nécessitant une prise en charge dans la journée pour éviter une aggravation.', 'Planifier l\'intervention d\'un plombier lors de la prochaine tournée.', 3.0, NOW(), NOW()),
(3, 3, 'Pied de table métallique desserré', 'Une table bancale dans la rangée 4 de l\'amphithéâtre.', 'Amphithéâtre 1', 'mobilier', 'faible', 'resolu', 38, 'Dysfonctionnement mineur sans risque sécuritaire direct.', 'Resserrage et stabilisation du piétement.', 0.5, NOW(), NOW());

-- --------------------------------------------------------
-- Structure de la table `interventions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `interventions`;
CREATE TABLE `interventions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `signalement_id` bigint(20) UNSIGNED NOT NULL,
  `technicien_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 30,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `interventions_signalement_id_foreign` (`signalement_id`),
  KEY `interventions_technicien_id_foreign` (`technicien_id`),
  CONSTRAINT `interventions_signalement_id_foreign` FOREIGN KEY (`signalement_id`) REFERENCES `signalements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `interventions_technicien_id_foreign` FOREIGN KEY (`technicien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interventions` (`id`, `signalement_id`, `technicien_id`, `notes`, `duration_minutes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Disjoncteur différentiel coupé à titre préventif. Remplacement des fusibles haute tension en cours.', 45, NOW(), NOW()),
(2, 3, 2, 'Vis de serrage resserrées et embout plastique remis en place. Table de nouveau opérationnelle.', 15, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
