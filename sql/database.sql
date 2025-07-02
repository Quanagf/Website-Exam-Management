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
(19,'admin','1230','admin@gmail.com','admin','admin','2025-07-01 19:37:10','active');
(21,'vu123','123123','min@gmail.com','taker','vu123','2025-07-01 19:37:10','active');

-- =====================================
-- Dữ liệu bảng `tests`
-- =====================================
INSERT INTO `tests` VALUES
(82,8,'adwad','ădada',15,'2025-07-01 20:41:10','R9Q41S',0,'2025-07-01 20:41:00','2025-08-01 20:45:00'),
(83,8,'ưdadawjkd','ădjladjnawdâd',15,'2025-07-01 20:46:03','3OCTV7',0,'2025-07-01 20:51:00','2025-07-10 20:45:00'),
(85,8,'á','zsa',32,'2025-07-02 00:08:26','6ZIZHI',0,'2025-07-02 00:08:00','2025-07-02 00:10:00'),
(86,8,'ăda','ădad',10,'2025-07-02 00:12:11','B8T4T5',0,'2025-07-02 00:15:00','2025-07-02 00:34:00');

-- =====================================
-- Dữ liệu bảng `questions`
-- =====================================
INSERT INTO `questions` VALUES
(66,83,'adawdhkadhjh','ahdjwhda','haldwjdahdu','ăhdljdhaj','ăljdadaww','C',3.33),
(67,83,'ădkajhdj','alwjkdjakdj','uahwudhaidh','uahuwhdu','uhdsuhad','D',3.33),
(68,83,'ădhaikdhkj','ahkwdhkajhd','kjahkjdhwjh','khkajhdj','khakjhw','C',3.33),
(70,86,'ădad','adwwad','adwad','adwad','adwa','C',1.25),
(71,86,'ădadad','adwdad','adwad','ăd','ad','B',1.25),
(72,86,'dfg','áda','ằ','adw','ădad','C',1.25),
(73,86,'sfsg','sgeghf','dfd','sdc','sdfsf','A',1.25),
(74,86,'adwasa','fasfa','câc','sfawf','à','B',1.25),
(75,86,'adwdwad','adsa','đâ','sdawd','ădf','C',1.25),
(76,86,'adwad','ưdad','uawd','ădada','ưdad','D',1.25),
(77,86,'adwa','adad','adwa','dưa','đâ','D',1.25);

-- =====================================
-- Dữ liệu bảng `question_responses`
-- =====================================
INSERT INTO `question_responses` VALUES
(135,16,83,66,'C','2025-07-01 23:06:17'),
(136,16,83,67,'D','2025-07-01 23:06:17'),
(137,16,83,68,'B','2025-07-01 23:06:17'),
(155,16,86,70,'C','2025-07-02 00:23:35'),
(156,16,86,71,'B','2025-07-02 00:23:35'),
(157,16,86,72,'C','2025-07-02 00:23:35'),
(158,16,86,73,'A','2025-07-02 00:23:35'),
(159,16,86,74,'B','2025-07-02 00:23:35'),
(160,16,86,75,'C','2025-07-02 00:23:35'),
(161,16,86,76,'D','2025-07-02 00:23:35'),
(162,16,86,77,'D','2025-07-02 00:23:35');

-- =====================================
-- Dữ liệu bảng `test_responses`
-- =====================================
INSERT INTO `test_responses` VALUES
(20,83,16,6.67,'2025-07-01 23:06:17','completed'),
(21,86,16,10,'2025-07-02 00:23:35','completed');

UPDATE `users`
SET `role` = 'admin'
WHERE `id` = 21;

