DROP TABLE IF EXISTS `site`;

-- -----------------------------------------------------------------------------
-- SITES
-- -----------------------------------------------------------------------------

CREATE TABLE `site` (
  `domain` varchar(64) NOT NULL UNIQUE,
  `administrator_path` varchar(64) NOT NULL UNIQUE,
  `site_title` varchar(250) NOT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_enabled` bool NOT NULL DEFAULT true,
  `is_system` bool NOT NULL DEFAULT false COMMENT "Couldn't be removed via WEB-UI"
)  ENGINE = InnoDB;