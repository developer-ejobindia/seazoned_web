ALTER TABLE `payment_accounts` ADD `card_no` VARCHAR(255) NOT NULL AFTER `name`, ADD `month` VARCHAR(255) NOT NULL AFTER `card_no`, ADD `year` VARCHAR(255) NOT NULL AFTER `month`, ADD `cvv_no` INT(11) NOT NULL AFTER `year`;

ALTER TABLE `book_services` CHANGE `status` `status` INT(1) NOT NULL DEFAULT '0' COMMENT '0 for service request, 1 for accept the request, 2 for end the job, 3 for complete the payment'; 


--19-03-2018

ALTER TABLE `payment_accounts` ADD `card_brand` VARCHAR(255) NULL DEFAULT NULL AFTER `card_no`;

