ALTER TABLE `book_services` ADD `notification_status_user` INT(11) NOT NULL DEFAULT '1' AFTER `payment_date`, ADD `notification_status_landscaper` INT(11) NOT NULL DEFAULT '1' AFTER `notification_status_user`;
UPDATE `book_services` SET `notification_status_user`=0,`notification_status_landscaper`=0