# 09.01.2018

ALTER TABLE `user_details`
  CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT,
  CHANGE `phone_number` `phone_number` VARCHAR(20) CHARACTER SET utf8
COLLATE utf8_general_ci NULL,
  CHANGE `date_of_birth` `date_of_birth` DATE NULL,
  CHANGE `address` `address` TEXT CHARACTER SET utf8
COLLATE utf8_general_ci NULL,
  CHANGE `city` `city` VARCHAR(100) CHARACTER SET utf8
COLLATE utf8_general_ci NULL DEFAULT NULL,
  CHANGE `state` `state` VARCHAR(100) CHARACTER SET utf8
COLLATE utf8_general_ci NULL,
  CHANGE `country` `country` INT(11) NULL,
  CHANGE `profile_image` `profile_image` LONGTEXT CHARACTER SET utf8
COLLATE utf8_general_ci NULL DEFAULT NULL;

# 10.01.2018

INSERT INTO `user_profiles` (`id`, `profile`) VALUES
  (1, 'Administrator'),
  (2, 'Users'),
  (3, 'Landscaper');

# 11.01.2018

ALTER TABLE user_details
  DROP FOREIGN KEY user_details_ibfk_2;
ALTER TABLE `user_details`
  CHANGE `country` `country` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `user_details`
  DROP `country`;
ALTER TABLE `user_details`
  ADD `country` VARCHAR(255) NOT NULL
  AFTER `state`;

# 12.01.2018

ALTER TABLE `services`
  ADD `logo_name` VARCHAR(255) NOT NULL
  AFTER `description`;

INSERT INTO `services` (`id`, `service_name`, `description`, `logo_name`) VALUES
  (1, 'Mowing And Edging', 'Mowing and Edging', 'mowing.svg'),
  (2, 'Leaf Removal', 'Leaf Removal', 'leaf-removal.svg'),
  (3, 'Lawn Treatment', 'Lawn Treatment', 'lawn-treatment.svg'),
  (4, 'Aeration', 'Aeration', 'aeration.png'),
  (5, 'Sprinkler Winterizing', 'Sprinkler Winterizing', 'sprinkler.svg'),
  (6, 'Pool Cleaning & Upkeep', 'Pool Cleaning & Upkeep', 'swimming-pool-ladder.svg'),
  (7, 'Snow Removal', 'Snow Removal', 'snow-removal.svg');

-- --------------------------------------------------------

--
-- Table structure for table `service_times`
--

CREATE TABLE `service_times` (
  `id`            INT(11)      NOT NULL,
  `landscaper_id` INT(11)      NOT NULL,
  `service_id`    INT(11)      NOT NULL,
  `service_day`   VARCHAR(255) NOT NULL,
  `start_time`    TIME         NOT NULL,
  `end_time`      TIME         NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

--
-- Indexes for table `service_times`
--
ALTER TABLE `service_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `service_id` (`service_id`);

--
-- AUTO_INCREMENT for table `service_times`
--
ALTER TABLE `service_times`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `service_times`
--
ALTER TABLE `service_times`
  ADD CONSTRAINT `service_times_ibfk_1` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_times_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);


# 16-1-2018

ALTER TABLE `service_prices` CHANGE `service_price` `discount_price` DECIMAL(10,2) NOT NULL;
ALTER TABLE `service_prices` ADD `service_id` INT NOT NULL AFTER `landscaper_id`;
ALTER TABLE `service_prices` ADD FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
