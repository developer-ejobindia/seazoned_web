ALTER TABLE `user_messages` ADD `time` TIMESTAMP NOT NULL AFTER `description`, ADD `status` INT(11) NOT NULL DEFAULT '0' AFTER `time`;