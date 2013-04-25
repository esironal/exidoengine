DROP TABLE IF EXISTS `geo_city`;
DROP TABLE IF EXISTS `geo_region`;
DROP TABLE IF EXISTS `geo_area`;

-- -----------------------------------------------------------------------------
-- STATES/REGIONS/CITIES EXTENSION FOR GEO. GEO should be installed
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_area` (
  `area_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `area_name` varchar(64) NOT NULL,
  `country_code_iso2` varchar(3) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_area` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_region` (
  `region_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `region_name` varchar(64) NOT NULL,
  `region_abbr` varchar(8) DEFAULT NULL COMMENT "Regions/States abbreviation for separate countries",
  `country_code_iso2` varchar(3) NOT NULL,
  `area_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_region` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);
ALTER TABLE `geo_region` ADD FOREIGN KEY (`area_id`) REFERENCES `geo_area`(`area_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city` (
  `city_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `city_name` varchar(64) NOT NULL,
  `city_timezone` varchar(32) NOT NULL,
  `city_abbr` char(32) DEFAULT NULL,
  `is_regional_center` bool NOT NULL DEFAULT false,
  `country_code_iso2` varchar(3) NOT NULL,
  `area_id` int(9) NOT NULL,
  `region_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);
ALTER TABLE `geo_city` ADD FOREIGN KEY (`area_id`) REFERENCES `geo_area`(`area_id`);
ALTER TABLE `geo_city` ADD FOREIGN KEY (`region_id`) REFERENCES `geo_region`(`region_id`);