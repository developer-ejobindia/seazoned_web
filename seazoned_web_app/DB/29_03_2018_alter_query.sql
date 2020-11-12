CREATE TABLE `privacy_policy` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `privacy_policy`
  ADD PRIMARY KEY (`id`);