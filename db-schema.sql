
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255)  NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT 'registered users',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_UNIQUE` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_member` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `user_member`
  ADD CONSTRAINT `FK_member_user`
FOREIGN KEY (`uid`)
REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_member_group`
FOREIGN KEY (`gid`)
REFERENCES `user_group` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

