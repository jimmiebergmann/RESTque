-- --------------------------------------------------------
-- Värd:                         127.0.0.1
-- Server version:               10.1.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for restque
CREATE DATABASE IF NOT EXISTS `restque` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `restque`;

-- Dumping structure for tabell restque.log
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('push_message','pull_message','finish_message','user_login') NOT NULL,
  `info` text NOT NULL,
  `ip` tinytext NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for tabell restque.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tube` int(10) unsigned NOT NULL,
  `state` enum('new','taken','finished') NOT NULL DEFAULT 'new',
  `message` blob NOT NULL,
  `message_size` smallint(6) NOT NULL,
  `receiver` enum('consumer','producer') NOT NULL,
  `time_to_live` int(10) unsigned NOT NULL,
  `time_to_run` int(10) unsigned NOT NULL,
  `delay` int(10) unsigned NOT NULL,
  `creation_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE KEY` (`id`,`tube`),
  KEY `state` (`state`),
  KEY `FK_messages_tubes` (`tube`),
  CONSTRAINT `FK_messages_tubes` FOREIGN KEY (`tube`) REFERENCES `tubes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for tabell restque.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `user_id` int(10) unsigned NOT NULL,
  `login` bit(1) NOT NULL DEFAULT b'1' COMMENT 'Can User login?',
  `pull` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Can user pull messages?',
  `push` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Can user push messages?',
  `tube` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Can user listen to new tubes?',
  `read_log` bit(1) NOT NULL DEFAULT b'0',
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `FK_key` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for tabell restque.tubes
CREATE TABLE IF NOT EXISTS `tubes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `total_messages` int(10) unsigned NOT NULL DEFAULT '0',
  `creation_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for tabell restque.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` tinytext NOT NULL,
  `password` varchar(50) NOT NULL,
  `token` varchar(32) NOT NULL,
  `total_pull` int(10) unsigned NOT NULL DEFAULT '0',
  `total_push` int(10) unsigned NOT NULL DEFAULT '0',
  `total_finish` int(10) unsigned NOT NULL DEFAULT '0',
  `creation_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COMMENT='List of users';

-- Data exporting was unselected.
-- Dumping structure for tabell restque.user_tubes
CREATE TABLE IF NOT EXISTS `user_tubes` (
  `user_id` int(10) unsigned NOT NULL,
  `tube` int(10) unsigned NOT NULL,
  UNIQUE KEY `UNIQUE KEY` (`user_id`,`tube`),
  KEY `FK_user_tubes_tubes` (`tube`),
  CONSTRAINT `FK_user_tubes_tubes` FOREIGN KEY (`tube`) REFERENCES `tubes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_tubes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
