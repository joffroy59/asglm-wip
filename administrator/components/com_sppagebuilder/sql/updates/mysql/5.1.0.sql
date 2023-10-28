-- version: 5.1.0

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_presets` (
  `id` INT(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `addon_name` varchar(255) NOT NULL DEFAULT '',
  `preset` mediumtext NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0', 
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;