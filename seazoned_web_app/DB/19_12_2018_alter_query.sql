ALTER TABLE `payment_accounts` ADD `is_primary` INT(1) NOT NULL DEFAULT '0' AFTER `cvv_no`;
ALTER TABLE `payment_accounts` CHANGE `cvv_no` `cvv_no` VARCHAR(255) NULL DEFAULT NULL;