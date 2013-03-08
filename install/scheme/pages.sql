DROP TABLE IF EXISTS `page_attribute_set_list`;
DROP TABLE IF EXISTS `page_attribute_set`;
DROP TABLE IF EXISTS `page_attribute_value_decimal`;
DROP TABLE IF EXISTS `page_attribute_value_bool`;
DROP TABLE IF EXISTS `page_attribute_value_datetime`;
DROP TABLE IF EXISTS `page_attribute_value_varchar`;
DROP TABLE IF EXISTS `page_attribute_value_int`;
DROP TABLE IF EXISTS `page_attribute_value_text`;
DROP TABLE IF EXISTS `page_attribute`;
DROP TABLE IF EXISTS `page_entity`;
DROP TABLE IF EXISTS `data_type`;


-- -----------------------------------------------------------------------------
-- EAV DATA TYPES
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `data_type` (
  `data_type_key` varchar(32) NOT NULL UNIQUE,
  `data_type_table` varchar(32) NOT NULL UNIQUE,
  `description` varchar(128) DEFAULT NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------

CREATE TABLE `page_entity` (
  `entity_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `entity_key` varchar(32) NOT NULL UNIQUE,
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `is_private` bool NOT NULL DEFAULT false,
  `description` text DEFAULT NULL,
  `parent_id` int(9) DEFAULT NULL
) ENGINE = InnoDB AUTO_INCREMENT = 100;

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute` (
  `attribute_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_type_key` varchar(32) NOT NULL,
  `attribute_key` varchar(32) NOT NULL UNIQUE,
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `is_system` bool NOT NULL DEFAULT false COMMENT 'Cannot be removed',
  `is_user_defined` bool NOT NULL DEFAULT false COMMENT 'Can be changed via UI',
  `is_unique` bool NOT NULL DEFAULT false COMMENT 'Defines is unique',
  `is_required` bool NOT NULL DEFAULT false COMMENT 'Defines is required',
  `default_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `backend_object` varchar(32) DEFAULT NULL,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute` ADD FOREIGN KEY (`data_type_key`) REFERENCES `data_type`(`data_type_key`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_set` (
  `attribute_set_key` varchar(32) NOT NULL UNIQUE,
  `created_at` datetime NOT NULL,
  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `is_private` bool NOT NULL DEFAULT false,
  `description` text DEFAULT NULL
)  ENGINE = InnoDB;

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_set_list` (
  `attribute_set_key` varchar(32) NOT NULL,
  `attribute_id` int(9) NOT NULL
)  ENGINE = InnoDB;

ALTER TABLE `page_attribute_set_list` ADD FOREIGN KEY (`attribute_set_key`) REFERENCES `page_attribute_set`(`attribute_set_key`);
ALTER TABLE `page_attribute_set_list` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_text` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` text DEFAULT NULL
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_text` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_text` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_datetime` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` datetime DEFAULT NULL
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_datetime` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_datetime` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_int` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` int(9) DEFAULT NULL
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_int` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_int` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_bool` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` bool DEFAULT 0
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_bool` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_bool` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_varchar` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` varchar(255) DEFAULT NULL
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_varchar` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_varchar` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `page_attribute_value_decimal` (
  `value_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `attribute_id` int(9) NOT NULL,
  `entity_id` int(9) NOT NULL,
  `value` decimal(12,4) DEFAULT NULL
)  ENGINE = InnoDB AUTO_INCREMENT = 100;

ALTER TABLE `page_attribute_value_decimal` ADD FOREIGN KEY (`attribute_id`) REFERENCES `page_attribute`(`attribute_id`);
ALTER TABLE `page_attribute_value_decimal` ADD FOREIGN KEY (`entity_id`) REFERENCES `page_entity`(`entity_id`);

-- -----------------------------------------------------------------------------

INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('int','int');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('decimal','decimal');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('bool','bool');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('text','text');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('varchar','varchar');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('datetime','datetime');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `backend_object`, `description`) VALUES (100, 'title','text', NOW(),'eav/eavFormInput', 'Page title');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `backend_object`, `description`) VALUES (101, 'keywords','text', NOW(),'eav/eavFormTextarea', 'Page keywords');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `backend_object`, `description`) VALUES (102, 'description','text', NOW(),'eav/eavFormTextarea', 'Page description');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`) VALUES (103, 'created_at', 'datetime', NOW());
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`) VALUES (104, 'updated_at', 'datetime', NOW());
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `backend_object`, `description`) VALUES (105, 'is_enabled', 'bool', NOW(),'eav/eavFormCheckbox', 'Enable?');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`) VALUES (106, 'is_draft', 'bool', NOW());
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (107, 'group_id', 'int', NOW(), '1', '@SU.GROUP_ID');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (108, 'group_name', 'varchar', NOW(), '1', '@SU.GROUP_NAME');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (109, 'owner_id', 'int', NOW(), '1', '@SU.USER_ID');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (110, 'owner_name', 'varchar', NOW(), '1', '@SU.USER_NAME');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (111, 'permissions_owner', 'varchar', NOW(), '1', 'rwx');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (112, 'permissions_group', 'varchar', NOW(), '1', 'r--');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `is_system`, `default_value`) VALUES (113, 'permissions_other', 'varchar', NOW(), '1', 'r--');
INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`, `backend_object`, `description`) VALUES (114, 'content','text', NOW(),'eav/eavFormTextarea', 'Page content');

INSERT INTO `page_attribute_set` (`attribute_set_key`,`created_at`,`is_private`,`description`) VALUES ('default', NOW(),true,'default');
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 100);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 101);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 102);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 103);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 104);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 105);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 106);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 107);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 108);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 109);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 110);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 111);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 112);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 113);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 114);