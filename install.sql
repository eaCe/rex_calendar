CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%event_calendar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `startdate` date NOT NULL default '0000-00-00',
  `enddate` date NOT NULL default '0000-00-00',
  `day` int(10) unsigned NOT NULL,
  `month` int(10) unsigned NOT NULL,
  `year` int(10) unsigned NOT NULL,
  `title` text NOT NULL,
  `starttime` text  NOT NULL,
  `endtime` text  NOT NULL,
  `description` mediumtext NOT NULL,
  `image` text NOT NULL,
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;