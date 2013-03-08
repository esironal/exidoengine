DROP TABLE IF EXISTS `component`;

-- -----------------------------------------------------------------------------
-- COMPONENTS
-- -----------------------------------------------------------------------------

CREATE TABLE `component` (
  `component_path` varchar(64) NOT NULL UNIQUE,
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

INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('dashboard', 'Dashboard', NOW(), true, '4102');
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('user/list', 'Users', NOW(), true, '3500');
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('user/access', 'Access', NOW(), true, '3400');
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('page/list', 'Pages', NOW(), true, '2900');
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('site/config', 'Site config', NOW(), true, '1000');
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`, `position`) VALUES ('component', '...', NOW(), true, '100');