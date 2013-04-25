DROP TABLE IF EXISTS `geo_country`;

-- -----------------------------------------------------------------------------
-- GEO
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_country` (
  `country_code_iso2` varchar(2) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  `country_name` varchar(64) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order",
  UNIQUE KEY `country_code_iso3` (`country_code_iso3`),
  UNIQUE KEY `country_code_iso2` (`country_code_iso2`)
) ENGINE=InnoDB;