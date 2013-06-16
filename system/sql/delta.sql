ALTER TABLE `api_cache`
ADD `message` char(255) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `api_name`,
COMMENT='';

ALTER TABLE `api_cache`
CHANGE `api_name` `api_name` char(12) COLLATE 'utf8_general_ci' NOT NULL FIRST,
CHANGE `message` `message` char(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `api_name`,
CHANGE `hash` `hash` char(64) COLLATE 'utf8_general_ci' NOT NULL AFTER `message`,
RENAME TO `social_current`,
COMMENT='' ENGINE='InnoDB';

ALTER TABLE `api_updates`
CHANGE `api_name` `api_name` varchar(32) COLLATE 'utf8_general_ci' NOT NULL AFTER `update_id`,
CHANGE `hash` `hash` char(64) COLLATE 'utf8_general_ci' NOT NULL AFTER `message`,
RENAME TO `social_statuses`,
COMMENT='' COLLATE 'utf8_general_ci';

DELIMITER ;;
CREATE TRIGGER `social_message_au` AFTER UPDATE ON `social_current` FOR EACH ROW
INSERT INTO `social_statuses` (api_name, message, hash) VALUES(NEW.api_name, NEW.message, NEW.hash);;
DELIMITER ;

ALTER TABLE `social_current`
COMMENT='' COLLATE 'utf8_general_ci';

ALTER TABLE `social_statuses`
COMMENT='' COLLATE 'utf8_general_ci';

ALTER TABLE `snapshots`
COMMENT='' COLLATE 'utf8_general_ci';

ALTER TABLE `visitors`
COMMENT='' COLLATE 'utf8_general_ci';

INSERT INTO `social_current` (`api_name`, `message`, `hash`, `datetime`)
VALUES ('instagram', '', '', now());