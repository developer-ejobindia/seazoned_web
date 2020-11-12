ALTER TABLE `payment_accounts` ADD `account_password` VARCHAR(255) NULL 
AFTER `account_details`, ADD `account_signature` VARCHAR(255) NULL AFTER `account_password`;

ALTER TABLE `book_services` ADD `transaction_id` VARCHAR(255) NULL AFTER `status`;

ALTER TABLE `book_services` ADD `landscaper_payment` DECIMAL(10,2) NULL AFTER `transaction_id`, 
ADD `admin_payment` DECIMAL(10,2) NULL AFTER `landscaper_payment`;