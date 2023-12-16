SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text(255) NOT NULL
  PRIMARY KEY (`id`)
);

CREATE TABLE `opticalDrive` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text(255) NOT NULL,
  `vendor` text(255) NOT NULL,
  `price` float NOT NULL,
  `category_id` int NOT NULL
  PRIMARY KEY (`id`)
); 

CREATE TABLE `opticalDrive_property` (
  `id` int NOT NULL AUTO_INCREMENT,
  `opticalDrive_id` int NOT NULL,
  `property_id` int NOT NULL,
  `value` text(255) NOT NULL
  PRIMARY KEY (`id`)
);

CREATE TABLE `property` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text(255) NOT NULL,
  `units` text(255) NOT NULL
  PRIMARY KEY (`id`)
);

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` text(255) NOT NULL,
  `password` text(255) NOT NULL
  PRIMARY KEY (`id`)
);

INSERT INTO `opticalDrive` (`id`, `name`, `vendor`, `price`, `category_id`) VALUES
(1, 'DVD±R/RW SATA Bulk', 'Asus', 695, 1),
(2, 'DVD±R/RW USB 2.0', 'Asus', 1147, 2);

INSERT INTO `opticalDrive_property` (`id`, `opticalDrive_id`, `property_id`, `value`) VALUES
(1, 1, 1, '650'),
(2, 2, 1, '250'),
(3, 1, 2, '170'),
(4, 2, 2, '140');

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Внутрішні приводи'),
(2, 'Зовнішні приводи');

INSERT INTO `property` (`id`, `name`, `units`) VALUES
(1, 'Вага', 'г'),
(2, 'Розмір', 'мм');

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'admin', '96e79218965eb72c92a549dd5a330112');

ALTER TABLE opticaldrive ADD FOREIGN KEY (category_id) REFERENCES category (id);
ALTER TABLE opticaldrive_property ADD FOREIGN KEY (opticaldrive_id) REFERENCES opticaldrive (id);
ALTER TABLE opticaldrive_property ADD FOREIGN KEY (property_id) REFERENCES property (id);
