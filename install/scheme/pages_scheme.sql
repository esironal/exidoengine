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