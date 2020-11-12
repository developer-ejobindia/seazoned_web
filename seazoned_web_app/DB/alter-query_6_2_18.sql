# 6-2-2018

ALTER TABLE service_prices DROP FOREIGN KEY service_prices_ibfk_2;
ALTER TABLE service_prices DROP service_id;

ALTER TABLE service_times DROP FOREIGN KEY service_times_ibfk_2;
ALTER TABLE service_times DROP service_id;

CREATE TABLE `landscaper_details` (
  `id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `feature_image` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `landscaper_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`);

ALTER TABLE `landscaper_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `landscaper_details`
  ADD CONSTRAINT `landscaper_details_ibfk_1` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `landscapers` DROP `name`, DROP `description`, DROP `profile_image`, DROP `location`;