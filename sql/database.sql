-- =====================================
-- Tạo Database
-- =====================================
CREATE DATABASE IF NOT EXISTS `exam_management`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `exam_management`;

-- =====================================
-- Tạo Bảng `users`
-- =====================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','creator','taker') DEFAULT 'taker',
  `fullname` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','locked') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- Tạo Bảng `tests`
-- =====================================
DROP TABLE IF EXISTS `tests`;
CREATE TABLE `tests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_creator_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `duration` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `share_code` varchar(10) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  `open_time` datetime DEFAULT NULL,
  `close_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `share_code` (`share_code`),
  KEY `test_creator_id` (`test_creator_id`),
  CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`test_creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- Tạo Bảng `questions`
-- =====================================
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_id` int NOT NULL,
  `content` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct` varchar(1) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- Tạo Bảng `question_responses`
-- =====================================
DROP TABLE IF EXISTS `question_responses`;
CREATE TABLE `question_responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `test_id` int NOT NULL,
  `question_id` int NOT NULL,
  `selected_option` varchar(1) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `question_responses_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `question_responses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- Tạo Bảng `test_responses`
-- =====================================
DROP TABLE IF EXISTS `test_responses`;
CREATE TABLE `test_responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_id` int DEFAULT NULL,
  `test_taker_id` int DEFAULT NULL,
  `score` float DEFAULT NULL,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','completed') NOT NULL DEFAULT 'completed',
  `force_reset_time` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `test_taker_id` (`test_taker_id`),
  KEY `test_responses_ibfk_1` (`test_id`),
  CONSTRAINT `test_responses_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `test_responses_ibfk_2` FOREIGN KEY (`test_taker_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- Dữ liệu bảng `users`
-- =====================================
INSERT INTO `users` VALUES
(8,'quantran1109','$2y$10$zixw5a2sYwAVDgw9KyR0KO/8tBLKpO0GUN2OKwgOU5PznDVBNNXRi','quantranhoang24@gmail.com','creator','Trần Hoàng Quâncs','2025-06-25 01:56:52','active'),
(9,'quan11092005','$2y$10$dME2Vjdalo/Fv.GKCfYZXuStC.lRTodBb64jarKOl8jDMgZcNrxau','quantranhoang24@gmail.com','creator',NULL,'2025-06-25 02:40:26','active'),
(13,'adafdwf','$2y$10$fbZqNFxBFmTli7HXaCN2J.XPg4.51W.EH0gPVi5EPuQuwkRABdJRW','quantranhoang243@gmail.com','creator',NULL,'2025-06-25 02:47:27','active'),
(14,'fqafa','$2y$10$KFyn1GSyQDdVVd8jQNFhP.J/JR3i62EzSucWrkXGHTV5M/f3ISPDC','ad@hadw.com','creator',NULL,'2025-06-25 03:20:47','active'),
(16,'quan1192005','$2y$10$M5s0h5vrrs0y7Sq1q7q8quZgTidivbOqUMaEtxb63PNJkxTJwq3qm','quantranhoang24@gmail.com','taker','Trần Hoàng Quân','2025-06-25 03:45:05','active'),
(17,'quan12','$2y$10$.V20/o1D6Kv7MxtDTmsWvuclpa6.0Il/FbFAoE2widHYYf6Iltgr.','quantran@gmail.com','creator',NULL,'2025-06-29 09:49:54','active'),
(19,'admin','$2y$10$MEpVsazYfPeXKEn0x6FDAulKyqTz6iBfM8oIocCz0kRyDWIDiWQsq','admin@gmail.com','admin','admin','2025-07-01 19:37:10','active');


-- =====================================
-- Dữ liệu bảng `tests`
-- =====================================
INSERT INTO `tests` VALUES

;
-- =====================================
-- Dữ liệu bảng `questions`
-- =====================================
INSERT INTO `questions` VALUES

;
-- =====================================
-- Dữ liệu bảng `question_responses`
-- =====================================
INSERT INTO `question_responses` VALUES
;

-- =====================================
-- Dữ liệu bảng `test_responses`
-- =====================================
INSERT INTO `test_responses` VALUES

;


