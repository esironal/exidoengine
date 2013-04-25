DROP TABLE IF EXISTS `geo_city_landmark`;
DROP TABLE IF EXISTS `geo_city_street`;
DROP TABLE IF EXISTS `geo_city_subdistrict`;
DROP TABLE IF EXISTS `geo_city_district`;

-- -----------------------------------------------------------------------------
-- ADVANCED CITY EXTENSION FOR GEO. GEO and GEO REGIONAL should be installed
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_district` (
  `district_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `district_name` varchar(64) NOT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_district` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_subdistrict` (
  `subdistrict_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `subdistrict_name` varchar(64) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  `city_id` int(9) NOT NULL,
  `district_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_subdistrict` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);
ALTER TABLE `geo_city_subdistrict` ADD FOREIGN KEY (`district_id`) REFERENCES `geo_city_district`(`district_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_street` (
  `street_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `street_name` varchar(64) NOT NULL,
  `abbr` varchar(32) DEFAULT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_street` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_landmark` (
  `landmark_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `landmark_name` varchar(64) NOT NULL,
  `description` text DEFAULT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_landmark` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);