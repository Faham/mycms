
CREATE TABLE IF NOT EXISTS `mycms_settings` (
  `var` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `val` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_research` (
  `research_id` int(11) NOT NULL AUTO_INCREMENT,
  `research_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `research_summary` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `research_description` varchar(1500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `research_status` enum('active','future','onhold','past','unknown') COLLATE utf8_unicode_ci NOT NULL,
  `research_priority` enum('1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`research_id`),
  UNIQUE KEY `research_id` (`research_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_publication` (
  `publication_id` int(11) NOT NULL AUTO_INCREMENT,
  `publication_type` enum(
    'article',
    'book',
    'booklet',
    'conference',
    'inbook ',
    'incollection',
    'inproceedings',
    'manual',
    'mastersthesis',
    'misc',
    'phdthesis',
    'proceedings',
    'techreport',
    'unpublished') COLLATE utf8_unicode_ci NOT NULL,
  `publication_title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `publication_booktitle` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_abstract` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `publication_year` year(4) NOT NULL,
  `publication_month` enum(
    'january',
    'february',
    'march',
    'april',
    'may',
    'june',
    'july',
    'august',
    'september',
    'october',
    'november',
    'december') COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_toappear` tinyint(1) NOT NULL,
  `publication_volume` int(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_issuenum` int(11) DEFAULT NULL,
  `publication_series` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_pages` int(11) DEFAULT NULL,
  `publication_doi_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_note` int(200) DEFAULT NULL,
  `publication_journal` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_isbn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_edition` int(11) DEFAULT NULL,
  `publication_chapter` int(11) DEFAULT NULL,
  `publication_technumber` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_school` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_howpublished` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_institution` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_organization` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_publisher` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publication_url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`publication_id`),
  UNIQUE KEY `publication_id` (`publication_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_people` (
  `people_id` int(11) NOT NULL AUTO_INCREMENT,
  `people_firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `people_middlename` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `people_lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `people_affiliation` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `people_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `people_bio` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `people_group` enum(
    'faculty',
    'adjunct_faculty',
    'researcher',
    'graduate_student',
    'undergraduate_student',
    'alumni',
    'recent_visitor') COLLATE utf8_unicode_ci NOT NULL,
  `people_start` date NOT NULL,
  `people_end` date NOT NULL,
  PRIMARY KEY (`people_id`),
  UNIQUE KEY `people_id` (`people_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_people_publication` (
  `people_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_publication_people` AS (SELECT * FROM `mycms_people_publication`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_people_research` (
  `people_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_research_people` AS (SELECT * FROM `mycms_people_research`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_publication_research` (
  `publication_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_research_publication` AS (SELECT * FROM `mycms_publication_research`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `image_id` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_video` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`video_id`),
  UNIQUE KEY `video_id` (`video_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_doc` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`doc_id`),
  UNIQUE KEY `doc_id` (`doc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_image_people` (
  `image_id` int(11) NOT NULL,
  `people_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_people_image` AS (SELECT * FROM `mycms_image_people`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_image_publication` (
  `image_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_publication_image` AS (SELECT * FROM `mycms_image_publication`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_image_research` (
  `image_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_research_image` AS (SELECT * FROM `mycms_image_research`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_video_publication` (
  `video_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_publication_video` AS (SELECT * FROM `mycms_video_publication`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_video_research` (
  `video_id` int(11) NOT NULL,
  `research_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_research_video` AS (SELECT * FROM `mycms_video_research`);

-- ----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `mycms_doc_publication` (
  `doc_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE OR REPLACE VIEW `mycms_publication_doc` AS (SELECT * FROM `mycms_doc_publication`);

-- ----------------------------------------------------------------------------

