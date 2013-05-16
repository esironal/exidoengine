DROP TABLE IF EXISTS `component`;

-- -----------------------------------------------------------------------------
-- COMPONENTS
-- -----------------------------------------------------------------------------

CREATE TABLE `component` (
  `component_key` varchar(64) NOT NULL UNIQUE,
  `component_name` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `has_backend` bool NOT NULL DEFAULT true COMMENT "If TRUE the component has an administration UI",
  `has_frontend` bool NOT NULL DEFAULT true COMMENT "If TRUE the component has a frontend UI",
  `is_visible_in_backend_menu` bool NOT NULL DEFAULT true COMMENT "If TRUE the component is visible in admin menu",
  `is_enabled` bool NOT NULL DEFAULT true,
  `is_installed` bool NOT NULL DEFAULT false,
  `is_system` bool NOT NULL DEFAULT false COMMENT "System components couldn't be removed via WEB-UI",
  `position` INT( 4 ) NOT NULL DEFAULT '0' COMMENT "Sorting order"
)  ENGINE = InnoDB;