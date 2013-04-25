DROP TABLE IF EXISTS `geo_city_phone_code`;

-- -----------------------------------------------------------------------------
-- CITY PHONES EXTENSION FOR GEO. GEO and GEO REGIONAL should be installed
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_phone_code` (
  `country_code_iso2` varchar(3) NOT NULL,
  `city_phone_code` int(4) NOT NULL,
  `city_id` int(9) NOT NULL,
  UNIQUE KEY `country_code_iso2` (`city_id`,`city_phone_code`)
) ENGINE=InnoDB;

ALTER TABLE `geo_city_phone_code` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);
ALTER TABLE `geo_city_phone_code` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);