ALTER TABLE `#__gnosis` ADD `creation_date` DATETIME NOT NULL;
ALTER TABLE `#__gnosis` ADD `modified_date` DATETIME NOT NULL;
ALTER TABLE `#__gnosis` ADD `tags` TEXT NOT NULL;
ALTER TABLE `#__gnosis` ADD `source` VARCHAR(255)  NOT NULL ;