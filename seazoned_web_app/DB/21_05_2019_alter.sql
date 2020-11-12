ALTER TABLE `users` ADD `ssn_no` VARCHAR(255) NULL AFTER `forgot_password_status`, 
ADD `drivers_license` VARCHAR(255) NULL AFTER `ssn_no`;