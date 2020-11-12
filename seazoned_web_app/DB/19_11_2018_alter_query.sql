ALTER TABLE `book_services` ADD `card_no` BIGINT NOT NULL AFTER `payment_date`;
ALTER TABLE `book_services` CHANGE `card_no` `card_no` BIGINT(20) NULL;
ALTER TABLE `book_services` ADD `mode_of_payment` VARCHAR(255) NOT NULL AFTER `card_no`;
ALTER TABLE `book_services` CHANGE `mode_of_payment` `mode_of_payment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
