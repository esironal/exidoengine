DROP TABLE IF EXISTS `geo_country_phone_code`;

-- -----------------------------------------------------------------------------
-- COUNTRY PHONES EXTENSION FOR GEO. GEO should be installed
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_country_phone_code` (
  `country_code_iso2` varchar(3) NOT NULL,
  `phone_code` int(4) NOT NULL,
  UNIQUE KEY `country_code_iso2` (`country_code_iso2`,`phone_code`)
) ENGINE=InnoDB;

ALTER TABLE `geo_country_phone_code` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);