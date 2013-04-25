DROP TABLE IF EXISTS `geo_country_domain_zone`;

-- -----------------------------------------------------------------------------
-- COUNTRY DOMAIN ZONES EXTENSION FOR GEO. GEO should be installed
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_country_domain_zone` (
  `country_code_iso2` varchar(3) NOT NULL,
  `domain_zone` char(3) NOT NULL,
  UNIQUE KEY `country_code_iso2` (`country_code_iso2`,`domain_zone`)
) ENGINE=InnoDB;

ALTER TABLE `geo_country_domain_zone` ADD FOREIGN KEY (`country_code_iso2`) REFERENCES `geo_country`(`country_code_iso2`);