SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `empty`;
CREATE TABLE `empty` (
  `empty_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  PRIMARY KEY (`empty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(256) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `group` (`id`, `name`, `description`) VALUES
(1,	'Group 1', 'value 1'),
(2,	'Group 2', 'value 1'),
(3,	'Group 3', 'value 2');

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(256) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1,	'Group 1', 'value 1'),
(2,	'Group 2', 'value 1'),
(3,	'Group 3', 'value 2');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` int(10) unsigned DEFAULT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `surname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `amount` double NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `timestamp` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `action` (`action`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`user_id`, `action`, `group_id`, `name`, `surname`, `email`, `last_login`, `amount`, `avatar`, `order`, `timestamp`) VALUES
(1,	1,	1,	'John',	'Doe',	'john.doe@test.xx',	NULL,	0,	'/avatar/01.png',	100,	1418255325),
(2,	1,	2,	'Peter',	'Larson',	'peter.larson@test.xx',	'2014-09-09 13:37:32',	15220.654,	'/avatar/02.png',	160,	1418255330),
(3,	1,	2,	'Claude',	'Graves',	'claude.graves@test.xx',	'2014-09-02 14:17:32',	9876.465498,	'/avatar/03.png',	180,	1418255311),
(4,	0,	3,	'Stuart',	'Norman',	'stuart.norman@test.xx',	'2014-09-09 18:39:18',	98766.2131,	'/avatar/04.png',	120,	1418255328),
(5,	1,	1,	'Kathy',	'Arnold',	'kathy.arnold@test.xx',	'2014-09-07 10:24:07',	456.987,	'/avatar/05.png',	140,	1418155313),
(6,	0,	3,	'Jan',	'Wilson',	'jan.wilson@test.xx',	'2014-09-03 13:15:22',	123,	'/avatar/06.png',	150,	1418255318),
(7,	0,	1,	'Alberta',	'Erickson',	'alberta.erickson@test.xx',	'2014-08-06 13:37:17',	98753.654,	'/avatar/07.png',	110,	1418255327),
(8,	1,	3,	'Ada',	'Wells',	'ada.wells@test.xx',	'2014-08-12 11:25:16',	852.3654,	'/avatar/08.png',	70,	1418255332),
(9,	0,	2,	'Ethel',	'Figueroa',	'ethel.figueroa@test.xx',	'2014-09-05 10:23:26',	45695.986,	'/avatar/09.png',	20,	1417255305),
(10,	1,	3,	'Ian',	'Goodwin',	'ian.goodwin@test.xx',	'2014-09-04 12:26:19',	1236.9852,	'/avatar/10.png',	130,	1418255331),
(11,	1,	2,	'Francis',	'Hayes',	'francis.hayes@test.xx',	'2014-09-03 10:16:17',	5498.345,	'/avatar/11.png',	0,	1417255293),
(12,	0,	1,	'Erma',	'Burns',	'erma.burns@test.xx',	'2014-07-02 15:42:15',	63287.9852,	'/avatar/12.png',	60,	1418255316),
(13,	1,	3,	'Kristina',	'Jenkins',	'kristina.jenkins@test.xx',	'2014-08-20 14:39:43',	74523.96549,	'/avatar/13.png',	40,	1418255334),
(14,	0,	3,	'Virgil',	'Hunt',	'virgil.hunt@test.xx',	'2014-08-12 16:09:38',	65654.6549,	'/avatar/14.png',	30,	1418255276),
(15,	1,	1,	'Max',	'Martin',	'max.martin@test.xx',	'2014-09-01 12:14:20',	541236.5495,	'/avatar/15.png',	170,	1418255317),
(16,	0,	2,	'Melody',	'Manning',	'melody.manning@test.xx',	'2014-09-02 12:26:20',	9871.216,	'/avatar/16.png',	50,	1418255281),
(17,	0,	3,	'Catherine',	'Todd',	'catherine.todd@test.xx',	'2014-06-11 15:14:39',	100.2,	'/avatar/17.png',	10,	1416255313),
(18,	0,	1,	'Douglas',	'Stanley',	'douglas.stanley@test.xx',	'2014-04-16 15:22:18',	900,	'/avatar/18.png',	90,	1416255332),
(19,	1,	2,	'Patti',	'Diaz',	'patti.diaz@test.xx',	'2014-09-11 12:17:16',	1500,	'/avatar/19.png',	80,	1418255275),
(20,	0,	1,	'John',	'Petterson',	'john.petterson@test.xx',	'2014-10-10 10:10:10',	2500,	'/avatar/20.png',	190,	1418255275);