CREATE TABLE IF NOT EXISTS `#__faq_faqs` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`faq_category_id` INT(11)  NOT NULL ,
`faq_question` VARCHAR(255)  NOT NULL ,
`faq_answer` TEXT NOT NULL ,
`faq_upvotes` VARCHAR(255)  NOT NULL ,
`faq_downvotes` VARCHAR(255)  NOT NULL ,
`faq_learningcenter` VARCHAR(255)  NOT NULL ,
`faq_date` DATE NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

