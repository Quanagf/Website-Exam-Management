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
(19,'admin','$2y$10$MEpVsazYfPeXKEn0x6FDAulKyqTz6iBfM8oIocCz0kRyDWIDiWQsq','admin@gmail.com','admin','admin','2025-07-01 19:37:10','active'),
(20, 'Nhom123', '$2y$10$ZkKKN5BRVYYGs7xjxj/9VOD0AWG6KUBZTanXgGab3ruHg3EmhQoju', 'Nhom123@example.com', 'creator', 'Nhom123', '2025-07-05 17:41:53', 'active');


INSERT INTO `tests` (`id`, `test_creator_id`, `title`, `description`, `duration`, `created_at`, `share_code`, `is_open`, `open_time`, `close_time`) VALUES
(1, 20, 'Đề Toán Cơ Bản', 'Kiểm tra kiến thức Toán học.', 60, '2025-07-05 17:44:51', 'JRJCXT', 0, '2025-07-06 12:00:00', '2025-07-07 12:00:00'),
(2, 20, 'Đề Lý Thuyết Tin', 'Kiểm tra kiến thức Tin học.', 60, '2025-07-05 17:44:51', 'ABC123', 0, '2025-07-06 12:00:00', '2025-07-07 12:00:00'),
(3, 20, 'Đề Tiếng Anh', 'Kiểm tra kiến thức tiếng Anh.', 60, '2025-07-05 17:44:51', 'DEF456', 0, '2025-07-06 12:00:00', '2025-07-07 12:00:00'),
(4, 20, 'Đề Vật Lý', 'Kiểm tra kiến thức Vật Lý.', 60, '2025-07-05 17:44:51', 'GHI789', 0, '2025-07-06 12:00:00', '2025-07-07 12:00:00'),
(5, 20, 'Đề Lịch Sử', 'Kiểm tra kiến thức Lịch Sử.', 60, '2025-07-05 17:44:51', 'JKL012', 0, '2025-07-06 12:00:00', '2025-07-07 12:00:00');

INSERT INTO `questions` (`id`, `test_id`, `content`, `option_a`, `option_b`, `option_c`, `option_d`, `correct`, `score`) VALUES
(1001,1,'2 + 2 bằng mấy?','3','4','5','6','B',1),
(1002,1,'5 * 6 bằng?','30','25','20','35','A',1),
(1003,1,'12 / 4 bằng?','2','3','4','6','B',1),
(1004,1,'Căn bậc hai của 81 là?','9','8','7','6','A',1),
(1005,1,'7 + 8 = ?','13','14','15','16','C',1),
(1006,1,'15 - 9 = ?','5','6','7','8','B',1),
(1007,1,'9 * 9 = ?','72','81','90','99','B',1),
(1008,1,'100 / 20 = ?','4','5','6','7','B',1),
(1009,1,'Số nguyên tố nhỏ nhất?','0','1','2','3','C',1),
(1010,1,'10 + 15 = ?','20','25','30','35','B',1),
(1011,1,'3 bình phương = ?','6','9','12','15','B',1),
(1012,1,'4 * 5 = ?','10','15','20','25','C',1),
(1013,1,'14 / 2 = ?','6','7','8','9','B',1),
(1014,1,'6 * 7 = ?','36','42','48','54','B',1),
(1015,1,'25 - 17 = ?','6','7','8','9','C',1),
(1016,1,'Số chia hết cho 3 và 5?','10','15','20','25','B',1),
(1017,1,'2 * 12 = ?','22','24','26','28','B',1),
(1018,1,'50 / 5 = ?','5','10','15','20','C',1),
(1019,1,'Số lẻ trong các số sau: 2,4,6,7','2','4','6','7','D',1),
(1020,1,'Căn bậc hai của 49 = ?','5','6','7','8','C',1),
(2001,2,'HTML là viết tắt của?','Hyper Text Markup Language','High Text Machine Language','Hyperlinks and Text Markup Language','None','A',1),
(2002,2,'RAM là gì?','Bộ nhớ chỉ đọc','Bộ nhớ truy cập ngẫu nhiên','Bộ xử lý trung tâm','Đĩa cứng','B',1),
(2003,2,'CPU viết tắt của?','Central Processing Unit','Central Programming Unit','Control Processing Unit','Central Performance Unit','A',1),
(2004,2,'CSS dùng để?','Tạo nội dung','Tạo cơ sở dữ liệu','Định dạng giao diện','Quản lý server','C',1),
(2005,2,'Công cụ tìm kiếm phổ biến nhất?','Yahoo','Bing','Google','DuckDuckGo','C',1),
(2006,2,'JavaScript là?','Ngôn ngữ lập trình','Hệ điều hành','Trình duyệt','Phần cứng','A',1),
(2007,2,'Hệ điều hành nào sau đây?','Photoshop','Windows','Chrome','Word','B',1),
(2008,2,'Python là?','CSDL','HĐH','Ngôn ngữ lập trình','Thiết bị mạng','C',1),
(2009,2,'WWW viết tắt của?','World Wide Web','Web Wide World','Wide Web World','None','A',1),
(2010,2,'CSDL phổ biến?','Oracle','MS Access','MySQL','Tất cả đúng','D',1),
(2011,2,'PHP chủ yếu dùng để?','Frontend','Backend','Thiết kế đồ họa','Hệ điều hành','B',1),
(2012,2,'Thiết bị mạng nào sau đây?','Switch','Mouse','Printer','Monitor','A',1),
(2013,2,'TCP/IP dùng để?','Nén dữ liệu','Giao thức mạng','Chia sẻ máy in','Mã hóa tệp','B',1),
(2014,2,'Hệ điều hành mã nguồn mở?','Windows','Linux','macOS','iOS','B',1),
(2015,2,'Email là gì?','Thư điện tử','Tin nhắn SMS','Cuộc gọi thoại','Báo cáo','A',1),
(2016,2,'FTP dùng để?','Tạo file','Truyền tệp','Xóa tệp','Sao chép dữ liệu','B',1),
(2017,2,'IP viết tắt của?','Internet Protocol','Internet Provider','Internal Process','Internet Page','A',1),
(2018,2,'Dữ liệu lớn là?','Big Data','Tiny Data','Medium Data','None','A',1),
(2019,2,'CMS là?','Content Management System','Control Machine System','Central Module System','Content Machine System','A',1),
(2020,2,'Đơn vị lưu trữ nhỏ nhất?','KB','Bit','Byte','MB','B',1),
(3001,3,'Hello nghĩa là?','Xin chào','Tạm biệt','Cảm ơn','Xin lỗi','A',1),
(3002,3,'Goodbye nghĩa là?','Xin chào','Tạm biệt','Cảm ơn','Xin lỗi','B',1),
(3003,3,'Thank you nghĩa là?','Xin lỗi','Cảm ơn','Tạm biệt','Xin chào','B',1),
(3004,3,'Sorry nghĩa là?','Xin lỗi','Cảm ơn','Xin chào','Tạm biệt','A',1),
(3005,3,'Dog nghĩa là?','Mèo','Chó','Cá','Chim','B',1),
(3006,3,'Cat nghĩa là?','Chó','Mèo','Chim','Cá','B',1),
(3007,3,'Bird nghĩa là?','Chim','Chó','Mèo','Cá','A',1),
(3008,3,'Fish nghĩa là?','Cá','Mèo','Chó','Chim','A',1),
(3009,3,'Book nghĩa là?','Sách','Bút','Vở','Bàn','A',1),
(3010,3,'Pen nghĩa là?','Sách','Bút','Vở','Bàn','B',1),
(3011,3,'Chair nghĩa là?','Bàn','Ghế','Giường','Tủ','B',1),
(3012,3,'Table nghĩa là?','Bàn','Ghế','Giường','Tủ','A',1),
(3013,3,'Window nghĩa là?','Cửa ra vào','Cửa sổ','Tường','Trần nhà','B',1),
(3014,3,'Door nghĩa là?','Cửa ra vào','Cửa sổ','Tường','Trần nhà','A',1),
(3015,3,'Father nghĩa là?','Mẹ','Cha','Anh','Em','B',1),
(3016,3,'Mother nghĩa là?','Mẹ','Cha','Anh','Em','A',1),
(3017,3,'Brother nghĩa là?','Mẹ','Cha','Anh/em trai','Em gái','C',1),
(3018,3,'Sister nghĩa là?','Mẹ','Cha','Anh','Chị/em gái','D',1),
(3019,3,'School nghĩa là?','Trường học','Bệnh viện','Công viên','Siêu thị','A',1),
(3020,3,'Hospital nghĩa là?','Trường học','Bệnh viện','Công viên','Siêu thị','B',1),
(4001,4,'Đơn vị đo lực?','Joule','Newton','Watt','Met','B',1),
(4002,4,'Đơn vị đo công suất?','Watt','Newton','Joule','Volt','A',1),
(4003,4,'1kWh bằng bao nhiêu Joule?','3.6 triệu','3.6 nghìn','360','3600','A',1),
(4004,4,'Định luật Ohm viết là?','U = R/I','U = IR','I = UR','R = IU','B',1),
(4005,4,'Điện trở ký hiệu là?','R','I','U','P','A',1),
(4006,4,'Vận tốc ký hiệu là?','v','a','s','t','A',1),
(4007,4,'Gia tốc ký hiệu là?','a','v','s','t','A',1),
(4008,4,'Nhiệt độ nước sôi?','0°C','50°C','100°C','150°C','C',1),
(4009,4,'Định luật bảo toàn năng lượng?','Năng lượng không tự sinh ra hoặc mất đi','Năng lượng tự sinh ra','Năng lượng tự mất đi','Năng lượng không thay đổi','A',1),
(4010,4,'Đơn vị đo điện dung?','Henry','Farad','Ohm','Watt','B',1),
(4011,4,'Đơn vị đo điện áp?','Volt','Ampere','Farad','Watt','A',1),
(4012,4,'Đơn vị đo dòng điện?','Volt','Ampere','Farad','Watt','B',1),
(4013,4,'Công thức tính công cơ học?','A = F.s','A = m.g','A = m/v','A = F/t','A',1),
(4014,4,'Đơn vị đo tần số?','Hz','W','V','A','A',1),
(4015,4,'Định luật Archimedes liên quan đến?','Định luật nhiệt','Lực đẩy Ác-si-mét','Định luật điện','Định luật từ','B',1),
(4016,4,'Công suất điện ký hiệu?','P','U','I','R','A',1),
(4017,4,'Công suất điện tính bằng?','P = U/I','P = U*I','P = I/R','P = U+I','B',1),
(4018,4,'Cường độ dòng điện ký hiệu?','U','I','R','P','B',1),
(4019,4,'Tia hồng ngoại là?','Tia nhiệt','Tia X','Tia gamma','Tia tử ngoại','A',1),
(4020,4,'Tia tử ngoại là?','Tia UV','Tia X','Tia gamma','Tia hồng ngoại','A',1),
(5001,5,'Bác Hồ sinh năm?','1890','1895','1900','1905','A',1),
(5002,5,'Chiến thắng Điện Biên Phủ năm?','1945','1954','1975','1968','B',1),
(5003,5,'Nước VNDCCH ra đời năm?','1945','1954','1975','1930','A',1),
(5004,5,'Chiến dịch Hồ Chí Minh năm?','1945','1954','1975','1968','C',1),
(5005,5,'Ngày Quốc khánh?','19/5','2/9','30/4','23/11','B',1),
(5006,5,'Tuyên ngôn độc lập đọc ở?','Hà Nội','Huế','Sài Gòn','Đà Nẵng','A',1),
(5007,5,'Nguyễn Ái Quốc là tên của?','Trần Phú','Phạm Văn Đồng','Hồ Chí Minh','Võ Nguyên Giáp','C',1),
(5008,5,'Đảng CSVN thành lập năm?','1930','1945','1954','1975','A',1),
(5009,5,'Hiệp định Paris ký năm?','1954','1973','1975','1968','B',1),
(5010,5,'Khởi nghĩa Nam Kỳ năm?','1930','1940','1945','1954','B',1),
(5011,5,'Phong trào Đông Du do ai lãnh đạo?','Phan Bội Châu','Phan Chu Trinh','Nguyễn Ái Quốc','Trần Phú','A',1),
(5012,5,'Khởi nghĩa Yên Bái năm?','1930','1935','1940','1945','A',1),
(5013,5,'Cách mạng tháng Tám năm?','1945','1954','1975','1968','A',1),
(5014,5,'Phong trào Cần Vương diễn ra thế kỷ?','17','18','19','20','C',1),
(5015,5,'Trận Bạch Đằng năm 938 do ai lãnh đạo?','Ngô Quyền','Lê Lợi','Trần Hưng Đạo','Quang Trung','A',1),
(5016,5,'Trận Chi Lăng năm 1427 chống quân?','Tống','Mông','Minh','Thanh','C',1),
(5017,5,'Quang Trung đại phá quân Thanh năm?','1789','1790','1791','1792','A',1),
(5018,5,'Chiến dịch Biên giới năm?','1945','1950','1954','1975','B',1),
(5019,5,'Phong trào Duy Tân do ai lãnh đạo?','Phan Chu Trinh','Phan Bội Châu','Nguyễn Thái Học','Trần Hưng Đạo','A',1),
(5020,5,'Hiệp định Genève ký năm?','1945','1950','1954','1975','C',1);




