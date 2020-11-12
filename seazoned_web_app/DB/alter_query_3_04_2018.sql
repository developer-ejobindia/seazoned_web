CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `questions` varchar(255) NOT NULL,
  `answers` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;