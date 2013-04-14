INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('int','int');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('decimal','decimal');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('bool','bool');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('text','text');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('varchar','varchar');
INSERT INTO `data_type` (`data_type_key`, `data_type_table`) VALUES ('datetime','datetime');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (100,'title','varchar',NOW(),'eav/eavFormInput','Page title','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (101,'keywords','text', NOW(),'eav/eavFormTextarea', 'Page keywords','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (102,'description','text', NOW(),'eav/eavFormTextarea', 'Page description','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`)
VALUES (103,'created_at','datetime',NOW(),'1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`)
VALUES (104,'updated_at','datetime',NOW(),'1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (105,'is_enabled', 'bool',NOW(),'eav/eavFormCheckbox','Enable?','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`)
VALUES (106,'is_draft','bool',NOW(),'1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (107,'group_id','int',NOW(),'1','@SU.GROUP_ID','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (108,'group_name','varchar',NOW(),'1','@SU.GROUP_NAME','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (109,'owner_id','int',NOW(),'1','@SU.USER_ID','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (110,'owner_name', 'varchar',NOW(),'1','@SU.USER_NAME','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (111,'permissions_owner','varchar',NOW(),'1','rwx','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (112,'permissions_group','varchar',NOW(),'1','r--','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`is_system`,`default_value`)
VALUES (113,'permissions_other','varchar',NOW(),'1','r--','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (114,'content','text',NOW(),'eav/eavFormTextarea','Page content','1');

INSERT INTO `page_attribute` (`attribute_id`,`attribute_key`,`data_type_key`,`created_at`,`backend_object`,`description`,`is_system`)
VALUES (115,'urlpath','varchar',NOW(),'eav/eavFormInput','URL key','1');

INSERT INTO `page_attribute_set` (`attribute_set_key`,`created_at`,`is_private`,`description`) VALUES ('default',NOW(),true,'default');
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 100);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 101);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 102);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 103);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 104);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 105);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 106);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 107);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 108);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 109);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 110);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 111);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 112);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 113);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 114);
INSERT INTO `page_attribute_set_list` (`attribute_set_key`,`attribute_id`) VALUES ('default', 115);