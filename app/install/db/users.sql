DROP TABLE IF EXISTS `user_access`
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `user_role`;
DROP TABLE IF EXISTS `group`;

-- -----------------------------------------------------------------------------
-- USER GROUP TABLE
-- -----------------------------------------------------------------------------

CREATE TABLE `group` (
  `group_id` int(9) NOT NULL AUTO_INCREMENT,
  `group_pid` int(9) DEFAULT NULL,
  `group_name` varchar(32) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order",
  `is_enabled` bool NOT NULL DEFAULT true,
  `is_system` bool NOT NULL DEFAULT false COMMENT "The system group couldn't be deleted via WEB-UI",
  PRIMARY KEY (`group_id`)
) ENGINE = InnoDB;

ALTER TABLE `group` ADD FOREIGN KEY (`group_pid`) REFERENCES `group`(`group_id`);

INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (1, 'root', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (100, 'administrators', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (101, 'exidoengine', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (102, 'publishers', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (103, 'developers', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (104, 'visitors', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `created_at`, `is_system`) VALUES (105, 'others', NOW(), true);

-- -----------------------------------------------------------------------------
-- USER ROLE TABLE
-- -----------------------------------------------------------------------------

CREATE TABLE `user_role` (
  `role_name` varchar(32) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order",
  `is_system` bool NOT NULL DEFAULT false COMMENT "The system role couldn't be deleted via WEB-UI"
) ENGINE = InnoDB;

INSERT INTO `user_role` (`role_name`, `created_at`, `is_system`) VALUES ('root', NOW(), true);
INSERT INTO `user_role` (`role_name`, `created_at`, `is_system`) VALUES ('administrator', NOW(), true);
INSERT INTO `user_role` (`role_name`, `created_at`, `is_system`) VALUES ('developer', NOW(), true);
INSERT INTO `user_role` (`role_name`, `created_at`, `is_system`) VALUES ('publisher', NOW(), true);
INSERT INTO `user_role` (`role_name`, `created_at`, `is_system`) VALUES ('visitor', NOW(), true);

-- -----------------------------------------------------------------------------
-- USER TABLE
-- -----------------------------------------------------------------------------

CREATE TABLE `user` (
  `user_id` int(9) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL UNIQUE,
  `user_email` varchar(64) NOT NULL UNIQUE,
  `password` varchar(32) DEFAULT NULL,
  `unique_session_id` varchar(64) DEFAULT NULL,
  `owner_id` int(9) DEFAULT NULL,
  `owner_name` varchar(32) DEFAULT NULL,
  `group_id` int(9) NOT NULL,
  `group_name` varchar(32) NOT NULL,
  `role_name` varchar(32) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions_owner` enum('rwx', 'rw-', 'r--', '---', 'r-x', '-wx', '--x', '-w-') NOT NULL DEFAULT 'rwx',
  `permissions_group` enum('rwx', 'rw-', 'r--', '---', 'r-x', '-wx', '--x', '-w-') NOT NULL DEFAULT 'r--',
  `permissions_other` enum('rwx', 'rw-', 'r--', '---', 'r-x', '-wx', '--x', '-w-') NOT NULL DEFAULT 'r--',
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `is_dropped` bool NOT NULL DEFAULT false COMMENT "Dropped (removed) users couldn't be restored via WEB-UI",
  `is_system` bool NOT NULL DEFAULT false COMMENT "System users couldn't be removed via WEB-UI",
  PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;

ALTER TABLE `user` ADD FOREIGN KEY (`owner_id`) REFERENCES `user`(`user_id`);
ALTER TABLE `user` ADD FOREIGN KEY (`owner_name`) REFERENCES `user`(`user_name`);
ALTER TABLE `user` ADD FOREIGN KEY (`group_id`) REFERENCES `group`(`group_id`);
ALTER TABLE `user` ADD FOREIGN KEY (`group_name`) REFERENCES `group`(`group_name`);
ALTER TABLE `user` ADD FOREIGN KEY (`role_name`) REFERENCES `user_role`(`role_name`);

INSERT INTO `user` (`user_id`,`user_name`,`password`,`user_email`,`owner_id`,`owner_name`,`group_id`,`group_name`,`role_name`,`created_at`,`is_system`) VALUES (100,'root',MD5(RAND()),'root@root',NULL,NULL,1,'root','root',NOW(),true);
INSERT INTO `user` (`user_id`,`user_name`,`password`,`user_email`,`owner_id`,`owner_name`,`group_id`,`group_name`,`role_name`,`created_at`,`is_system`) VALUES (500,'exidoengine',MD5('exidoengine'),'exido@exidoengine.com',100,'root',101,'exidoengine','administrator',NOW(),true);
INSERT INTO `user` (`user_id`,`user_name`,`user_email`,`password`,`unique_session_id`,`owner_id`,`owner_name`,`group_id`,`group_name`, `role_name`,`created_at`,`is_system`) VALUES ('600', 'guest', 'guest@exidoengine.com', MD5('guest'),'5627a272bf2563cee5877539bd906e7cc3eb33afcefe2b570a08906f9a34ae48', '100', 'root', '104', 'visitors', 'visitor', NOW(), true);
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- USER ACCESS RULES
-- -----------------------------------------------------------------------------

CREATE TABLE `user_access` (
  `user_id` int(9) NOT NULL,
  `component` varchar(64) NOT NULL COMMENT "System component key",
  `instance` enum('DEVELOPER', 'ADMINISTRATOR', 'FRONTEND', 'PUBLISHER') NOT NULL DEFAULT 'FRONTEND' COMMENT "System instance name",
  `permissions` enum('rwx', 'rw-', 'r--', '---', 'r-x', '-wx', '--x', '-w-') NOT NULL DEFAULT 'rwx' COMMENT "User permissions for component within instance",
  UNIQUE KEY `user_access` (`user_id`,`component`,`instance`)
) ENGINE = InnoDB;

ALTER TABLE `user_access` ADD FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`);

INSERT INTO `user_access` (`user_id`, `component`, `instance`, `permissions`) VALUES
(500, 'ALL', 'ADMINISTRATOR', 'rwx'),
(600, 'ALL', 'FRONTEND', 'rwx');