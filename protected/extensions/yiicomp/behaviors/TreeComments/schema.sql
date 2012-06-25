CREATE TABLE `comments` (
  `id` int(11) NOT NULL auto_increment,
  `order` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` enum('thing') NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fk_comments_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;