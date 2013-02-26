DROP TABLE IF EXISTS `session`;

-- -----------------------------------------------------------------------------
-- SESSION HANDLER
-- -----------------------------------------------------------------------------

CREATE TABLE `session` (
  `session_id` varchar(40) NOT NULL PRIMARY KEY,
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned DEFAULT 0 NOT NULL,
  `date_modify` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  `data` text NOT NULL
) ENGINE = InnoDB;