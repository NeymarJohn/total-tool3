# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Hôte: localhost (MySQL 5.7.23)
# Base de données: videoplay
# Temps de génération: 2019-02-22 03:15:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table 2d_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_categories`;

CREATE TABLE `2d_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `id_relation` int(11) DEFAULT '0',
  `image` varchar(200) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_comments`;

CREATE TABLE `2d_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_video` int(11) DEFAULT NULL,
  `id_relation` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0',
  `ip` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_episodes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_episodes`;

CREATE TABLE `2d_episodes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_relation` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `season` int(11) DEFAULT '1',
  `episode` int(11) DEFAULT '1',
  `type` tinyint(1) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `embed` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_favorites
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_favorites`;

CREATE TABLE `2d_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_video` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_keywords
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_keywords`;

CREATE TABLE `2d_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(200) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id2d_keywords_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_likes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_likes`;

CREATE TABLE `2d_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_com` int(11) DEFAULT NULL,
  `nb_like` int(11) DEFAULT '0',
  `nb_unlike` int(11) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_menus`;

CREATE TABLE `2d_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `ids_menu` varchar(300) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_newsletter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_newsletter`;

CREATE TABLE `2d_newsletter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) DEFAULT NULL,
  `is_member` tinyint(1) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_notes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_notes`;

CREATE TABLE `2d_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_video` int(11) DEFAULT NULL,
  `note` float DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_notifications`;

CREATE TABLE `2d_notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `new` tinyint(1) DEFAULT NULL,
  `id_relation` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_pages`;

CREATE TABLE `2d_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `content` mediumtext,
  `sub_page` int(11) DEFAULT '0',
  `layout` tinyint(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_payments`;

CREATE TABLE `2d_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `subscription_id` varchar(50) DEFAULT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  `currency` varchar(5) DEFAULT '',
  `status` tinytext,
  `ip` varchar(45) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `date_end` timestamp NULL DEFAULT NULL,
  `trial_start` timestamp NULL DEFAULT NULL,
  `trial_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_playlists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_playlists`;

CREATE TABLE `2d_playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `ids_videos` varchar(200) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_posts`;

CREATE TABLE `2d_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `ids_keywords` varchar(100) DEFAULT NULL,
  `content` mediumtext,
  `image` varchar(200) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_posts_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_posts_categories`;

CREATE TABLE `2d_posts_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `id_relation` int(11) DEFAULT '0',
  `description` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_posts_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_posts_comments`;

CREATE TABLE `2d_posts_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_relation` int(11) DEFAULT '0',
  `score` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_posts_keywords
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_posts_keywords`;

CREATE TABLE `2d_posts_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_posts_likes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_posts_likes`;

CREATE TABLE `2d_posts_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_com` int(11) DEFAULT NULL,
  `nb_like` int(11) DEFAULT '0',
  `nb_unlike` int(11) DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_profiles_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_profiles_comments`;

CREATE TABLE `2d_profiles_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(100) DEFAULT NULL,
  `id_user_page` int(11) DEFAULT NULL,
  `id_user_member` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_sessions`;

CREATE TABLE `2d_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `2d_sessions` WRITE;
/*!40000 ALTER TABLE `2d_sessions` DISABLE KEYS */;

INSERT INTO `2d_sessions` (`id`, `ip_address`, `timestamp`, `data`)
VALUES
	('9ao5acnm0vfjlsb9afq9ou8hm05gn2gm','::1',1545177618,X'5F5F63695F6C6173745F726567656E65726174657C693A313534353137373631383B7468656D657C733A393A226461726B7468656D65223B'),
	('o7rn3ihauu3u92derjbq001f8ek4u271','::1',1545177921,X'5F5F63695F6C6173745F726567656E65726174657C693A313534353137373932313B7468656D657C733A393A226461726B7468656D65223B4642524C485F73746174657C733A33323A226164613739306137373134376562626430336635393238353566636262376662223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('jjuq8qbdd606s6i8h0ob6fr380okpf0n','::1',1545178068,X'5F5F63695F6C6173745F726567656E65726174657C693A313534353137373932313B7468656D657C733A393A226461726B7468656D65223B4642524C485F73746174657C733A33323A226164613739306137373134376562626430336635393238353566636262376662223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('13eah6h142jl8qbgpspj865agab0qmsp','::1',1545338926,X'5F5F63695F6C6173745F726567656E65726174657C693A313534353333383837353B7468656D657C733A393A226461726B7468656D65223B4642524C485F73746174657C733A33323A226465636361323136333665633933303335373431393037323664636433646362223B'),
	('n97kvsa8mfucunfaovomg3mihvrd36ek','::1',1550802121,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830323132313B7468656D657C733A393A226461726B7468656D65223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('le54qgmuq3d2nqavc0idmihpkj5lcbjc','::1',1550802451,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830323435313B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('o6ruakdgjnkr66tm4te7valiquntt4a1','::1',1550802756,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830323735363B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('ujp4t0ldr5iejnrnaudhok3ptaid30q0','::1',1550803850,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830333835303B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('8rpkhi1007k1qimekdbpgid3t2n3nul4','::1',1550803341,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830333334313B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('lc4cjk7frg855b1ut45njh9i6g9q5tbg','::1',1550804221,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830343232313B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B'),
	('3222q40qrddltveh2nqnliiqme7i613t','::1',1550804452,X'5F5F63695F6C6173745F726567656E65726174657C693A313535303830343232313B7468656D657C733A373A2264656661756C74223B4642524C485F73746174657C733A33323A223866353937366361323662383839393864633865623630383538663435333338223B69647C733A313A2231223B757365726E616D657C733A353A2261646D696E223B75726C7C733A353A2261646D696E223B61646D696E7C733A313A2231223B');

/*!40000 ALTER TABLE `2d_sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table 2d_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_stats`;

CREATE TABLE `2d_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribut` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_stats_cron
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_stats_cron`;

CREATE TABLE `2d_stats_cron` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(200) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_stats_location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_stats_location`;

CREATE TABLE `2d_stats_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` varchar(11) DEFAULT NULL,
  `country_name` varchar(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table 2d_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_users`;

CREATE TABLE `2d_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `passkey` varchar(45) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  `subscriber` tinyint(1) DEFAULT NULL,
  `badge` varchar(45) DEFAULT NULL,
  `customer_id` varchar(45) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `facebook` varchar(45) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `google` varchar(45) DEFAULT NULL,
  `linkedin` varchar(45) DEFAULT NULL,
  `location` tinytext,
  `about` tinytext,
  `auth_coms` tinyint(1) DEFAULT '0',
  `playlist_profile` varchar(11) DEFAULT NULL,
  `nb_favs` int(11) DEFAULT '0',
  `nb_notes` int(11) DEFAULT '0',
  `nb_coms` int(11) DEFAULT '0',
  `oauth_provider` varchar(255) DEFAULT '',
  `oauth_uid` varchar(255) DEFAULT '',
  `country_code` varchar(45) DEFAULT NULL,
  `country_name` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `url_UNIQUE` (`url`),
  UNIQUE KEY `mail_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `2d_users` WRITE;
/*!40000 ALTER TABLE `2d_users` DISABLE KEYS */;

INSERT INTO `2d_users` (`id`, `url`, `username`, `email`, `password`, `passkey`, `role`, `subscriber`, `badge`, `customer_id`, `status`, `image`, `profile_image`, `ip`, `facebook`, `twitter`, `google`, `linkedin`, `location`, `about`, `auth_coms`, `playlist_profile`, `nb_favs`, `nb_notes`, `nb_coms`, `oauth_provider`, `oauth_uid`, `country_code`, `country_name`, `city`, `date_created`, `date_modified`)
VALUES
	(1,'admin','admin','admin@coffeetheme.com','password','rffvpa8ikrlku2hewsfy','1',NULL,NULL,NULL,1,NULL,NULL,'167.57.135.128',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,0,0,'twitter','844722722856980481','FR','France','Lille','2017-09-18 02:32:59','2017-09-18 02:32:59');

/*!40000 ALTER TABLE `2d_users` ENABLE KEYS */;
UNLOCK TABLES;


# Affichage de la table 2d_videos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `2d_videos`;

CREATE TABLE `2d_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `ids_keywords` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `note` float DEFAULT '0',
  `played` int(11) DEFAULT '0',
  `nb_favs` int(11) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `trailer` varchar(200) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `file` varchar(200) DEFAULT NULL,
  `embed` varchar(200) DEFAULT NULL,
  `subscription` tinyint(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
