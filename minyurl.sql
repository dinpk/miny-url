
CREATE TABLE `urls` (
  `key_url` int(10) UNSIGNED NOT NULL,
  `hash` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `urls`
  ADD PRIMARY KEY (`key_url`),
  ADD UNIQUE KEY `url` (`url`);

ALTER TABLE `urls`
  MODIFY `key_url` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

