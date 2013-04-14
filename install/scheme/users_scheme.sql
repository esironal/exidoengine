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