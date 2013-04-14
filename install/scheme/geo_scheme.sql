DROP TABLE IF EXISTS `component`;

-- -----------------------------------------------------------------------------
-- GEO
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_country` (
  `country_code_iso3` varchar(3) NOT NULL,
  `country_code_iso2` varchar(2) NOT NULL,
  `country_num_code` int(3) NOT NULL,
  `country_name` varchar(64) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order",
  UNIQUE KEY `country_code_iso3` (`country_code_iso3`),
  UNIQUE KEY `country_code_iso2` (`country_code_iso2`)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_area` (
  `area_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `area_name` varchar(64) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_area` ADD FOREIGN KEY (`country_code_iso3`) REFERENCES `geo_country`(`country_code_iso3`);

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_region` (
  `region_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `region_name` varchar(64) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  `area_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_region` ADD FOREIGN KEY (`country_code_iso3`) REFERENCES `geo_country`(`country_code_iso3`);
ALTER TABLE `geo_region` ADD FOREIGN KEY (`area_id`) REFERENCES `geo_area`(`area_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_city` (
  `city_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `city_name` varchar(64) NOT NULL,
  `city_timezone` varchar(32) NOT NULL,
  `abbr` char(32) DEFAULT NULL,
  `is_regional_center` bool NOT NULL DEFAULT false,
  `country_code_iso3` varchar(3) NOT NULL,
  `area_id` int(9) NOT NULL,
  `region_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city` ADD FOREIGN KEY (`country_code_iso3`) REFERENCES `geo_country`(`country_code_iso3`);
ALTER TABLE `geo_city` ADD FOREIGN KEY (`area_id`) REFERENCES `geo_area`(`area_id`);
ALTER TABLE `geo_city` ADD FOREIGN KEY (`region_id`) REFERENCES `geo_region`(`region_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_city_district` (
  `district_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `district_name` varchar(64) NOT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_district` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_city_subdistrict` (
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
-- Улицы населенных пунктов, Ленина, Мира и т.п.
CREATE TABLE `geo_city_street` (
  `street_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `street_name` varchar(64) NOT NULL,
  `abbr` varchar(32) DEFAULT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_street` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

-- -----------------------------------------------------------------------------

CREATE TABLE `geo_city_landmark` (
  `landmark_id` int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `landmark_name` varchar(64) NOT NULL,
  `description` text DEFAULT NULL,
  `city_id` int(9) NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `position` int(4) NOT NULL DEFAULT '0' COMMENT "Sorting order"
) ENGINE = InnoDB;

ALTER TABLE `geo_city_landmark` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

-- --------------------------------------------------------

CREATE TABLE `geo_country_domain_zone` (
  `country_code_iso3` varchar(3) NOT NULL,
  `domain_zone` char(3) NOT NULL,
  UNIQUE KEY `country_code_iso3` (`country_code_iso3`,`domain_zone`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_country_phone_code` (
  `country_phone_code` int(4) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  UNIQUE KEY `country_code_iso3` (`country_code_iso3`,`country_phone_code`)
) ENGINE=InnoDB;

ALTER TABLE `geo_country_phone_code` ADD FOREIGN KEY (`country_code_iso3`) REFERENCES `geo_country`(`country_code_iso3`);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `geo_city_phone_code` (
  `city_phone_code` int(4) NOT NULL,
  `country_code_iso3` varchar(3) NOT NULL,
  `city_id` int(9) NOT NULL,
  UNIQUE KEY `country_code_iso3` (`city_id`,`city_phone_code`)
) ENGINE=InnoDB;

ALTER TABLE `geo_city_phone_code` ADD FOREIGN KEY (`city_id`) REFERENCES `geo_city`(`city_id`);

ALTER TABLE `geo_city_phone_code` ADD FOREIGN KEY (`country_code_iso3`) REFERENCES `geo_country`(`country_code_iso3`);




INSERT INTO `geo_country_phone_code` (`country_code_iso3`, `phone_code`) VALUES
('ABW', 297),
('AFG', 93),
('AGO', 244),
('AIA', 264),
('ALB', 355),
('AND', 376),
('ANT', 599),
('ARE', 971),
('ARG', 54),
('ARM', 374),
('ASM', 684),
('ATG', 1268),
('AUS', 61),
('AUT', 43),
('AZE', 994),
('BDI', 257),
('BEL', 32),
('BEN', 229),
('BFA', 226),
('BGD', 880),
('BGR', 359),
('BHR', 973),
('BHS', 1242),
('BIH', 387),
('BLM', 590),
('BLR', 375),
('BLZ', 501),
('BMU', 441),
('BOL', 591),
('BRA', 55),
('BRB', 246),
('BTN', 975),
('BWA', 267),
('CAF', 236),
('CAN', 1),
('CHE', 41),
('CHL', 56),
('CHN', 86),
('CIV', 225),
('CMR', 237),
('COK', 682),
('COL', 57),
('COM', 269),
('CPV', 238),
('CRI', 506),
('CUB', 53),
('CYM', 345),
('CYP', 357),
('CZE', 420),
('DEU', 49),
('DJI', 253),
('DMA', 767),
('DNK', 45),
('DOM', 809),
('DOM', 829),
('DOM', 849),
('DZA', 213),
('ECU', 593),
('EGY', 20),
('ERI', 291),
('ESP', 34),
('EST', 372),
('ETH', 251),
('FIN', 358),
('FJI', 679),
('FLK', 500),
('FRA', 33),
('FRO', 298),
('GAB', 241),
('GBR', 44),
('GEO', 995),
('GHA', 233),
('GIB', 350),
('GIN', 224),
('GLP', 590),
('GMB', 220),
('GNB', 245),
('GNQ', 240),
('GRC', 30),
('GRD', 473),
('GRL', 299),
('GTM', 502),
('GUF', 594),
('GUM', 671),
('GUY', 592),
('HKG', 852),
('HND', 504),
('HRV', 385),
('HTI', 509),
('HUN', 36),
('IDN', 62),
('IND', 91),
('IOT', 246),
('IRQ', 964),
('ISL', 354),
('ISR', 972),
('ITA', 39),
('JAM', 876),
('JOR', 962),
('JPN', 81),
('KAZ', 7),
('KEN', 254),
('KGZ', 996),
('KHM', 855),
('KIR', 686),
('KNA', 1869),
('KWT', 965),
('LBN', 961),
('LBR', 231),
('LCA', 1758),
('LIE', 423),
('LKA', 94),
('LSO', 266),
('LTU', 370),
('LUX', 352),
('LVA', 371),
('MAC', 853),
('MAR', 212),
('MCO', 377),
('MDG', 261),
('MDV', 960),
('MEX', 52),
('MHL', 692),
('MLI', 223),
('MLT', 356),
('MMR', 95),
('MNE', 382),
('MNG', 976),
('MNP', 670),
('MOZ', 258),
('MRT', 222),
('MSR', 664),
('MTQ', 596),
('MUS', 230),
('MWI', 265),
('MYS', 60),
('MYT', 262),
('NAM', 264),
('NCL', 687),
('NER', 227),
('NFK', 6723),
('NGA', 234),
('NIC', 505),
('NIU', 683),
('NLD', 31),
('NOR', 47),
('NPL', 977),
('NRU', 674),
('NZL', 64),
('OMN', 968),
('PAK', 92),
('PAN', 507),
('PER', 51),
('PHL', 63),
('PLW', 680),
('PNG', 675),
('POL', 48),
('PRI', 1787),
('PRI', 1939),
('PRT', 351),
('PRY', 595),
('PYF', 689),
('QAT', 974),
('REU', 262),
('ROU', 40),
('RUS', 7),
('RWA', 250),
('SAU', 966),
('SDN', 249),
('SEN', 221),
('SGP', 65),
('SHN', 290),
('SLB', 677),
('SLE', 232),
('SLV', 503),
('SOM', 252),
('SPM', 508),
('SRB', 381),
('STP', 239),
('SUR', 597),
('SVK', 421),
('SVN', 386),
('SWE', 46),
('SWZ', 268),
('SYC', 248),
('TCA', 1649),
('TCD', 235),
('TGO', 228),
('THA', 66),
('TJK', 992),
('TKL', 690),
('TKM', 993),
('TON', 676),
('TTO', 1868),
('TUN', 216),
('TUR', 90),
('TUV', 688),
('TWN', 886),
('UGA', 256),
('UKR', 380),
('URY', 598),
('USA', 1),
('UZB', 998),
('VCT', 1784),
('VEN', 58),
('VUT', 678),
('WLF', 681),
('WSM', 685),
('YEM', 967),
('ZAF', 27),
('ZMB', 260),
('ZWE', 263);