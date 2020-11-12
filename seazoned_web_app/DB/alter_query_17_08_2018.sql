ALTER TABLE `book_services` ADD `booking_time` TIMESTAMP NOT NULL AFTER `notification_status_landscaper`, ADD `accept_time` DATETIME NOT NULL AFTER `booking_time`, ADD `reject_time` DATETIME NOT NULL AFTER `accept_time`, ADD `end_job_time` DATETIME NOT NULL AFTER `reject_time`;
ALTER TABLE `book_services` DROP `end_job_time`;
ALTER TABLE `book_services` CHANGE `booking_time` `booking_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP();