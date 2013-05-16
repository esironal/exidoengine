INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (1, 'root', 'root', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (100, 'administrators', 'Administrators', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (101, 'exidoengine', 'exidoengine', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (102, 'publishers', 'Publishers', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (103, 'developers', 'Web-developers', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (104, 'visitors', 'Simple users', NOW(), true);
INSERT INTO `group` (`group_id`, `group_name`, `description`, `created_at`, `is_system`) VALUES (105, 'others', 'Others', NOW(), true);

INSERT INTO `user_role` (`role_key`, `description`, `created_at`, `is_system`) VALUES ('root', 'root', NOW(), true);
INSERT INTO `user_role` (`role_key`, `description`, `created_at`, `is_system`) VALUES ('administrator', 'Administrator', NOW(), true);
INSERT INTO `user_role` (`role_key`, `description`, `created_at`, `is_system`) VALUES ('developer', 'Web-developer', NOW(), true);
INSERT INTO `user_role` (`role_key`, `description`, `created_at`, `is_system`) VALUES ('publisher', 'Publisher', NOW(), true);
INSERT INTO `user_role` (`role_key`, `description`, `created_at`, `is_system`) VALUES ('visitor', 'Simple user', NOW(), true);

INSERT INTO `user` (`user_id`,`user_name`,`password`,`user_email`,`owner_id`,`owner_name`,`group_id`,`group_name`,`role_key`,`created_at`,`is_system`) VALUES (100,'root',MD5(RAND()),'root@root',NULL,NULL,1,'root','root',NOW(),true);
INSERT INTO `user` (`user_id`,`user_name`,`password`,`user_email`,`owner_id`,`owner_name`,`group_id`,`group_name`,`role_key`,`created_at`,`is_system`) VALUES (500,'exidoengine',MD5('exidoengine'),'exido@exidoengine.com',100,'root',101,'exidoengine','administrator',NOW(),true);
INSERT INTO `user` (`user_id`,`user_name`,`user_email`,`password`,`unique_session_id`,`owner_id`,`owner_name`,`group_id`,`group_name`, `role_key`,`created_at`,`is_system`) VALUES ('600', 'guest', 'guest@exidoengine.com', MD5('guest'),'5627a272bf2563cee5877539bd906e7cc3eb33afcefe2b570a08906f9a34ae48', '100', 'root', '104', 'visitors', 'visitor', NOW(), true);

INSERT INTO `user_access` (`user_id`, `component`, `instance`, `permissions`) VALUES
(500, 'ALL', 'ADMINISTRATOR', 'rwx'),
(600, 'ALL', 'FRONTEND', 'rwx');