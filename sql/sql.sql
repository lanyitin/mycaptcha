CREATE TABLE IF NOT EXISTS `inform` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_ip` varchar(15) collate utf8_unicode_ci NOT NULL,
  `challenge` varchar(10) collate utf8_unicode_ci NOT NULL,
  `session_id` char(32) collate utf8_unicode_ci NOT NULL,
  `server_ip` varchar(40) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;
