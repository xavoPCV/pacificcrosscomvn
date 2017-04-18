CREATE TABLE IF NOT EXISTS `#__fleximport_fields` (
`id` int(11) unsigned NOT NULL auto_increment,
`type_id` int(11) NOT NULL,
`field_type` varchar(50) NOT NULL,
`flexi_field_id` int(11) NOT NULL,
`name` varchar(255) NOT NULL,
`label` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
`iscore` tinyint(1) NOT NULL default '0',
`isrequired` tinyint(1) NOT NULL default '0',
`params` text NOT NULL,
`published` tinyint(1) NOT NULL default '0',
`checked_out` int(11) unsigned NOT NULL default '0',
`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
`ordering` int(11) NOT NULL default '0', PRIMARY KEY  (`id`),
KEY `type_id` (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__fleximport_types` (
`id` int(11) NOT NULL auto_increment,
`flexi_type_id` int(11) NOT NULL,
`import_type` varchar(50) NOT NULL,
`name` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
`params` text NOT NULL,
`published` tinyint(1) NOT NULL default '0',
`checked_out` int(11) unsigned NOT NULL default '0',
`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__fleximport_export` (
`id` int(11) NOT NULL auto_increment,
`type_id` int(11) NOT NULL,
`item_id` int(11) NOT NULL,
`date_export` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;