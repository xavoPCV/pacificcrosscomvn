CREATE TABLE IF NOT EXISTS `#__gnosis` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`word` VARCHAR(200)  NOT NULL ,
`pronounciation` VARCHAR(100)  NOT NULL ,
`category` INT NOT NULL ,
`definition` TEXT NOT NULL ,
`examples` TEXT NOT NULL ,
`etymology` TEXT NOT NULL ,
`quiz` TEXT NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`creation_date` DATETIME NOT NULL,
`modified_date` DATETIME NOT NULL,
`tags` TEXT NOT NULL,
`source` VARCHAR(255)  NOT NULL, 
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__gnosis_category` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`category_name` VARCHAR(100)  NOT NULL ,
`description` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;