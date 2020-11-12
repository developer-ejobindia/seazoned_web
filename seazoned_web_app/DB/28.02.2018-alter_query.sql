CREATE TABLE `blog_like` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `blog_like` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `blog_like`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blog_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
