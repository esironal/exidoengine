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
  `is_system` bool NOT NULL DEFAULT false COMMENT "System components couldn't be removed via WEB-UI"
)  ENGINE = InnoDB;

INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('dashboard', 'Dashboard', NOW(), true);
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('user/list', 'Users', NOW(), true);
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('user/access', 'Access', NOW(), true);
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('page/list', 'Pages', NOW(), true);
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('site/config', 'Site config', NOW(), true);
INSERT INTO `component` (`component_path`, `component_name`, `created_at`, `is_system`) VALUES ('component', '...', NOW(), true);