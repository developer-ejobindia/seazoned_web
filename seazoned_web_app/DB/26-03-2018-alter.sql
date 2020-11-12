
CREATE TABLE `firebases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_token` text NOT NULL,
  `device_type` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `firebases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `firebases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
  
 --27-03-2018--
 
 ALTER TABLE `book_services` ADD `payment_date` DATETIME NULL DEFAULT NULL AFTER `admin_payment`;