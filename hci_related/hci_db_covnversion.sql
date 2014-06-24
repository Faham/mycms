
-- new tables:
-- `mycms_settings`
-- `mycms_doc`
-- `mycms_image`
-- `mycms_video`
-- `mycms_people`
-- `mycms_publication`
-- `mycms_research`
-- `mycms_video_publication`
-- `mycms_video_research`
-- `mycms_image_people`
-- `mycms_doc_publication`
-- `mycms_image_publication`
-- `mycms_people_publication`
-- `mycms_image_research`
-- `mycms_people_research`
-- `mycms_publication_research`

-- old talbes:
-- `group`
-- `person`
-- `publication`
-- `project`
-- `personimage`
-- `publicationimage`
-- `projectimage`
-- `profileproject`
-- `projectperson`
-- `profilepublication`
-- `publicationperson`
-- `projectpublication`

-- --------------------------------------------------------

DROP VIEW IF EXISTS
  `mycms_publication_video`,
  `mycms_research_video`,
  `mycms_people_image`,
  `mycms_publication_doc`,
  `mycms_publication_image`,
  `mycms_publication_people`,
  `mycms_research_image`,
  `mycms_research_people`,
  `mycms_research_publication`;

DROP TABLE IF EXISTS
  `mycms_video_publication`,
  `mycms_video_research`,
  `mycms_image_people`,
  `mycms_doc_publication`,
  `mycms_image_publication`,
  `mycms_people_publication`,
  `mycms_image_research`,
  `mycms_people_research`,
  `mycms_publication_research`,
  `mycms_settings`,
  `mycms_doc`,
  `mycms_image`,
  `mycms_video`,
  `mycms_people`,
  `mycms_publication`,
  `mycms_research`;

-- --------------------------------------------------------

--
-- Table structure for table `mycms_doc`
--

CREATE TABLE IF NOT EXISTS `mycms_doc` (
  `doc_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`doc_id`),
  UNIQUE KEY `doc_id` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mycms_video`
--

CREATE TABLE IF NOT EXISTS `mycms_video` (
  `video_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `video_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`video_id`),
  UNIQUE KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- -------------------------------------------------------------

-- converting old `person` to new `mycms_people`.


CREATE TABLE `mycms_people` LIKE `person`;
ALTER TABLE `mycms_people` ENGINE=InnoDB;
INSERT `mycms_people` SELECT * FROM `person`;

-- old groups                 new groups
-- 1 Faculty                  1 faculty
-- 2 Adjunct Faculty          2 adjunct_faculty
-- 3 Researchers              3 researcher
-- 4 Graduate Students        4 graduate_student
-- 5 Staff                    5 staff
-- 6 Alumni                   6 alumni
-- 7 Recent visitors          7 recent_visitor
-- 8 Undergraduate Students   8 undergraduate_student
-- NULL or 0                  9 other

UPDATE `mycms_people` SET `GroupId` = 9  WHERE `GroupId` = 0 OR `GroupId` IS NULL;

-- 1 IsAsmin                  1 administrator
-- NULL or 0                  2 authenticated

UPDATE `mycms_people` SET `IsAdmin` = 2  WHERE `IsAdmin` = 0 OR `IsAdmin` = NULL;

ALTER TABLE `mycms_people`
CHANGE `Id`              `people_id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
CHANGE `FirstName`       `people_firstname`   varchar(50) COLLATE utf8_unicode_ci NOT NULL       AFTER `people_id`,
CHANGE `MiddleInitial`   `people_middlename`  varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL   AFTER `people_firstname`,
CHANGE `LastName`        `people_lastname`    varchar(50) COLLATE utf8_unicode_ci NOT NULL       AFTER `people_middlename`,
CHANGE `Affiliation`     `people_affiliation` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL  AFTER `people_lastname`,
CHANGE `Email`           `people_email`       varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL   AFTER `people_affiliation`,
CHANGE `Bio`             `people_bio`         varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `people_email`,
CHANGE `GroupId`         `people_group`       enum('faculty','adjunct_faculty','researcher','graduate_student','staff','alumni','recent_visitor','undergraduate_student','other') COLLATE utf8_unicode_ci NOT NULL AFTER `people_bio`,
CHANGE `IsAdmin`         `people_role`        enum('administrator','authenticated') COLLATE utf8_unicode_ci NOT NULL AFTER `people_group`,
CHANGE `NSID`            `people_nsid`        varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL    AFTER `people_role`,
ADD                      `people_start`       date DEFAULT NULL                                  AFTER `people_nsid`,
ADD                      `people_end`         date DEFAULT NULL                                  AFTER `people_start`,
DROP   `ProjectPref`,
DROP   `PublicationPref`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`people_id`);

-- -------------------------------------------------------------

-- converting old `publication` to new `mycms_publication`.


CREATE TABLE `mycms_publication` LIKE `publication`;
ALTER TABLE `mycms_publication` ENGINE=InnoDB;
INSERT `mycms_publication` SELECT * FROM `publication`;

-- old types                 new types
-- 1 Conference              1  article
-- 2 Journal                 2  book
-- 3 Book                    3  booklet
-- 4 Book Chapter            4  conference
-- 5 Tech Report             5  inbook
-- 6 M.Sc. Thesis            6  incollection
-- 7 Ph.D. Thesis            7  inproceedings
--                           8  manual
--                           9  mastersthesis
--                           10 misc
--                           11 phdthesis
--                           12 proceedings
--                           13 techreport
--                           14 unpublished

UPDATE `mycms_publication` SET `Type` = 13 WHERE `Type` = 5;
UPDATE `mycms_publication` SET `Type` = 5 WHERE `Type` = 4;
UPDATE `mycms_publication` SET `Type` = 4 WHERE `Type` = 1;
UPDATE `mycms_publication` SET `Type` = 1 WHERE `Type` = 2;
UPDATE `mycms_publication` SET `Type` = 2 WHERE `Type` = 3;
UPDATE `mycms_publication` SET `Type` = 9 WHERE `Type` = 6;
UPDATE `mycms_publication` SET `Type` = 11 WHERE `Type` = 7;

ALTER TABLE `mycms_publication`
CHANGE  `Id`                    `publication_id`        int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
CHANGE  `Type`                  `publication_type` enum('article','book','booklet','conference','inbook','incollection','inproceedings','manual','mastersthesis','misc','phdthesis','proceedings','techreport','unpublished') COLLATE utf8_unicode_ci NOT NULL AFTER `publication_id`,
CHANGE  `Title`                 `publication_title`              varchar(300) COLLATE utf8_unicode_ci NOT NULL AFTER `publication_type`,
ADD                             `publication_booktitle`          varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_title`,
CHANGE  `Abstract`              `publication_abstract`           varchar(10000) COLLATE utf8_unicode_ci NOT NULL AFTER `publication_booktitle`,
CHANGE  `Year`                  `publication_year`               year(4) NOT NULL AFTER `publication_abstract`,
ADD                             `publication_month` enum('january','february','march','april','may','june','july','august','september','october','november','december') COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_year`,
CHANGE  `ToAppear`              `publication_toappear`           tinyint(1) NOT NULL AFTER `publication_month`,
CHANGE  `VolumeNum`             `publication_volume`             varchar(20)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_toappear`,
CHANGE  `IssueNum`              `publication_number`             varchar(20)  COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'issuenumber' AFTER `publication_volume`,
CHANGE  `Series`                `publication_series`             varchar(100)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_number`,
CHANGE  `Location`              `publication_address`            varchar(100)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_series`,
CHANGE  `Pages`                 `publication_pages`              varchar(50)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_address`,
CHANGE  `DOI`                   `publication_doi_number`         varchar(100)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_pages`,
CHANGE  `AdditionalInfo`        `publication_note`               varchar(200)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_doi_number`,
CHANGE  `Journal`               `publication_journal`            varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_note`,
CHANGE  `ISBN`                  `publication_isbn`               varchar(40)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_journal`,
CHANGE  `Edition`               `publication_edition`            varchar(20)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_isbn`,
CHANGE  `Chapter`               `publication_chapter`            varchar(20)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_edition`,
CHANGE  `TechNumber`            `publication_technumber`         varchar(20)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_chapter`,
CHANGE  `School`                `publication_school`             varchar(100)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_technumber`,
ADD                             `publication_howpublished`       varchar(50)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_school`,
ADD                             `publication_institution`        varchar(50)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_howpublished`,
ADD                             `publication_organization`       varchar(50)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_institution`,
ADD                             `publication_publisher`          varchar(50)  COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_organization`,
ADD                             `publication_url`                varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_publisher`,
CHANGE  `PDFLink`               `publication_temp_pdflink`       varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_url`,
CHANGE  `SecondaryLink`         `publication_temp_secondarylink` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `publication_temp_pdflink`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`publication_id`);

-- --------------------------------------------------------

--
-- Table structure for table `mycms_doc_publication`
--

CREATE TABLE IF NOT EXISTS `mycms_doc_publication` (
  `doc_id` int(11) UNSIGNED NOT NULL,
  `publication_id` int(11) UNSIGNED NOT NULL,
  `doc_order` int(11) DEFAULT NULL,
  `publication_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`doc_id`,`publication_id`),
  KEY `publication_id` (`publication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT `mycms_doc` (`doc_filename`)
SELECT reverse(substring_index(reverse(`publication_temp_pdflink`), '/', 1))
FROM `mycms_publication`
WHERE `publication_temp_pdflink` IS NOT NULL and `publication_temp_pdflink` != '' and `publication_id` not in (78, 95, 112, 129);

-- for pub_id 138 download the pdflink and add it to files

INSERT `mycms_doc_publication` (`doc_id`, `publication_id`)
SELECT a.`doc_id`, b.`publication_id`
FROM `mycms_doc` as a, `mycms_publication` as b
WHERE a.`doc_filename` = reverse(substring_index(reverse(b.`publication_temp_pdflink`), '/', 1));

ALTER TABLE `mycms_publication` DROP `publication_temp_pdflink`;

--
-- Constraints for table `mycms_doc_publication`
--
ALTER TABLE `mycms_doc_publication`
  ADD CONSTRAINT `mycms_doc_publication_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `mycms_doc` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_doc_publication_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `mycms_publication` (`publication_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_publication_doc`
--
DROP TABLE IF EXISTS `mycms_publication_doc`;
CREATE VIEW `mycms_publication_doc` AS (select
  `mycms_doc_publication`.`doc_id` AS `doc_id`,
  `mycms_doc_publication`.`publication_id` AS `publication_id`,
  `mycms_doc_publication`.`doc_order` AS `doc_order`,
  `mycms_doc_publication`.`publication_order` AS `publication_order`
  from `mycms_doc_publication`);

-- --------------------------------------------------------

--
-- Table structure for table `mycms_video_publication`
--

CREATE TABLE IF NOT EXISTS `mycms_video_publication` (
  `video_id` int(11) UNSIGNED NOT NULL,
  `publication_id` int(11) UNSIGNED NOT NULL,
  `video_order` int(11) DEFAULT NULL,
  `publication_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`video_id`,`publication_id`),
  KEY `publication_id` (`publication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT `mycms_video` (`video_filename`)
SELECT reverse(substring_index(reverse(`publication_temp_secondarylink`), '/', 1))
FROM `mycms_publication`
WHERE `publication_temp_secondarylink` IS NOT NULL and `publication_temp_secondarylink` != '';

INSERT `mycms_video_publication` (`video_id`, `publication_id`)
SELECT a.`video_id`, b.`publication_id`
FROM `mycms_video` as a, `mycms_publication` as b
WHERE a.`video_filename` = reverse(substring_index(reverse(b.`publication_temp_secondarylink`), '/', 1));

ALTER TABLE `mycms_publication` DROP `publication_temp_secondarylink`;

--
-- Constraints for table `mycms_video_publication`
--
ALTER TABLE `mycms_video_publication`
  ADD CONSTRAINT `mycms_video_publication_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `mycms_video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_video_publication_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `mycms_publication` (`publication_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_publication_video`
--
DROP TABLE IF EXISTS `mycms_publication_video`;
CREATE VIEW `mycms_publication_video` AS (select
  `mycms_video_publication`.`video_id` AS `video_id`,
  `mycms_video_publication`.`publication_id` AS `publication_id`,
  `mycms_video_publication`.`video_order` AS `video_order`,
  `mycms_video_publication`.`publication_order` AS `publication_order`
  from `mycms_video_publication`);

-- -------------------------------------------------------------

-- converting old `project` to new `mycms_research`.


CREATE TABLE `mycms_research` LIKE `project`;
ALTER TABLE `mycms_research` ENGINE=InnoDB;
INSERT `mycms_research` SELECT * FROM `project`;

UPDATE `mycms_research` SET `Past` = 4 WHERE `Past` = 1;
UPDATE `mycms_research` SET `Past` = 1 WHERE `Past` = 0;

ALTER TABLE `mycms_research`
CHANGE  `Id`       `research_id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT           FIRST,
CHANGE  `Name`     `research_title`       varchar(150) COLLATE utf8_unicode_ci NOT NULL      AFTER `research_id`,
CHANGE  `TagLine`  `research_summary`     varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL  AFTER `research_title`,
CHANGE  `Summary`  `research_description` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `research_summary`,
CHANGE  `Past`     `research_status`      enum('active','future','onhold','past','unknown') COLLATE utf8_unicode_ci NOT NULL AFTER `research_description`,
CHANGE  `Order`    `research_priority`    int(10) unsigned DEFAULT NULL                      AFTER `research_status`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`research_id`);

-- --------------------------------------------------------

-- move people, publication and research images from
-- uploads/ to files/ as the following:
-- uploads/person-*.jpg      to files/people/people-*.jpg
-- uploads/project-*.jpg     to files/research/research-*.jpg
-- uploads/publication-*.jpg to files/publication/publication-*.jpg

--
-- Table structure for table `mycms_image`
--


CREATE TABLE IF NOT EXISTS `mycms_image` (
  `image_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_filename` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `image_id` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- -------------------------------------------------------------

-- converting old `personimage` to new `mycms_image_people`.

CREATE TABLE `mycms_image_people` LIKE `personimage`;
ALTER TABLE `mycms_image_people` ENGINE=InnoDB;
INSERT `mycms_image_people` SELECT * FROM `personimage`;

ALTER TABLE `mycms_image_people`
  ADD               `image_id`     int(11) UNSIGNED NOT NULL  FIRST,
  CHANGE `PersonId` `people_id`    int(11) UNSIGNED NOT NULL  AFTER `image_id`,
  ADD               `image_order`  int(11) DEFAULT NULL       AFTER `people_id`,
  ADD               `people_order` int(11) DEFAULT NULL       AFTER `image_order`;

INSERT `mycms_image` (`image_filename`) SELECT CONCAT('people-', `Id`, '.jpg') FROM `mycms_image_people`;
UPDATE `mycms_image_people` as a
	JOIN `mycms_image` as b
	ON b.`image_filename` = CONCAT('people-', a.`Id`, '.jpg')
	SET a.`image_id` = b.`image_id`;

ALTER TABLE `mycms_image_people`
  DROP `Id`;

-- select * from `mycms_image_people` where `people_id` not in (select `people_id` from `mycms_people`);
-- image_id people_id image_order people_order
-- 10       0         NULL        NULL

delete from `mycms_image_people` where `image_id` = 10 and `people_id` = 0 and `image_order` IS NULL and `people_order` IS NULL;

--
-- Constraints for table `mycms_image_people`
--
ALTER TABLE `mycms_image_people`
  ADD CONSTRAINT `mycms_image_people_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `mycms_image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_image_people_ibfk_2` FOREIGN KEY (`people_id`) REFERENCES `mycms_people` (`people_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_people_image`
--

CREATE VIEW `mycms_people_image` AS (select
  `mycms_image_people`.`image_id` AS `image_id`,
  `mycms_image_people`.`people_id` AS `people_id`,
  `mycms_image_people`.`image_order` AS `image_order`,
  `mycms_image_people`.`people_order` AS `people_order`
  from `mycms_image_people`);

-- -------------------------------------------------------------

-- converting old `projectimage` to new `mycms_image_research`.


CREATE TABLE `mycms_image_research` LIKE `projectimage`;
ALTER TABLE `mycms_image_research` ENGINE=InnoDB;
INSERT `mycms_image_research` SELECT * FROM `projectimage`;

ALTER TABLE `mycms_image_research`
  ADD                `image_id`       int(11) UNSIGNED NOT NULL FIRST,
  CHANGE `ProjectId` `research_id`    int(11) UNSIGNED NOT NULL AFTER `image_id`,
  ADD                `image_order`    int(11) DEFAULT NULL      AFTER `research_id`,
  ADD                `research_order` int(11) DEFAULT NULL      AFTER `image_order`;

INSERT `mycms_image` (`image_filename`) SELECT CONCAT('research-', `Id`, '.jpg') FROM `mycms_image_research`;
UPDATE `mycms_image_research` as a
	JOIN `mycms_image` as b
	ON b.`image_filename` = CONCAT('research-', a.`Id`, '.jpg')
	SET a.`image_id` = b.`image_id`;

ALTER TABLE `mycms_image_research`
  DROP `Id`;

--
-- Constraints for table `mycms_image_research`
--
ALTER TABLE `mycms_image_research`
  ADD CONSTRAINT `mycms_image_research_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `mycms_image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_image_research_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `mycms_research` (`research_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_research_image`
--

CREATE VIEW `mycms_research_image` AS (select
  `mycms_image_research`.`image_id` AS `image_id`,
  `mycms_image_research`.`research_id` AS `research_id`,
  `mycms_image_research`.`image_order` AS `image_order`,
  `mycms_image_research`.`research_order` AS `research_order`
  from `mycms_image_research`);

-- -------------------------------------------------------------

-- converting old `publicationimage` to new `mycms_image_publication`.


CREATE TABLE `mycms_image_publication` LIKE `publicationimage`;
ALTER TABLE `mycms_image_publication` ENGINE=InnoDB;
INSERT `mycms_image_publication` SELECT * FROM `publicationimage`;

ALTER TABLE `mycms_image_publication`
  ADD                    `image_id` int(11) UNSIGNED NOT NULL       FIRST,
  CHANGE `PublicationId` `publication_id` int(11) UNSIGNED NOT NULL AFTER `image_id`,
  ADD                    `image_order` int(11) DEFAULT NULL         AFTER `publication_id`,
  ADD                    `publication_order` int(11) DEFAULT NULL   AFTER `image_order`;

INSERT `mycms_image` (`image_filename`) SELECT CONCAT('publication-', `Id`, '.jpg') FROM `mycms_image_publication`;
UPDATE `mycms_image_publication` as a
	JOIN `mycms_image` as b
	ON b.`image_filename` = CONCAT('publication-', a.`Id`, '.jpg')
	SET a.`image_id` = b.`image_id`;

ALTER TABLE `mycms_image_publication`
  DROP `Id`;

--
-- Constraints for table `mycms_image_publication`
--
ALTER TABLE `mycms_image_publication`
  ADD CONSTRAINT `mycms_image_publication_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `mycms_image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_image_publication_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `mycms_publication` (`publication_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_publication_image`
--

CREATE VIEW `mycms_publication_image` AS (select
  `mycms_image_publication`.`image_id` AS `image_id`,
  `mycms_image_publication`.`publication_id` AS `publication_id`,
  `mycms_image_publication`.`image_order` AS `image_order`,
  `mycms_image_publication`.`publication_order` AS `publication_order`
  from `mycms_image_publication`);

-- -------------------------------------------------------------

-- converting old `publicationperson` and `profilepublication` to new `mycms_people_publication`.

CREATE TABLE `mycms_people_publication` AS
	SELECT
		a.`PersonId`      as `people_id`,
		a.`PublicationId` as `publication_id`,
		a.`Order`         as `people_order`,
		b.`Order`         as `publication_order`
	FROM `publicationperson` AS a
	LEFT JOIN  `profilepublication` AS b
	ON a.`PublicationId` = b.`PublicationId`
	AND a.`PersonId`     = b.`PersonId`
	;
ALTER TABLE `mycms_people_publication` ENGINE=InnoDB;

ALTER TABLE `mycms_people_publication`
  CHANGE `people_id`         `people_id`         int(11) UNSIGNED NOT NULL,
  CHANGE `publication_id`    `publication_id`    int(11) UNSIGNED NOT NULL,
  CHANGE `people_order`      `people_order`      int(11) DEFAULT NULL,
  CHANGE `publication_order` `publication_order` int(11) DEFAULT NULL;

-- looks like the following contain an invalid foreign key!
-- profilepublication:
-- PersonId PublicationId Order
-- 182 274 1
delete from `mycms_people_publication` where `people_id` = 182 and `publication_id` = 274 and `publication_order` = 1;

--
-- Constraints for table `mycms_people_publication`
--
ALTER TABLE `mycms_people_publication`
  ADD CONSTRAINT `mycms_people_publication_ibfk_1` FOREIGN KEY (`people_id`) REFERENCES `mycms_people` (`people_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_people_publication_ibfk_2` FOREIGN KEY (`publication_id`) REFERENCES `mycms_publication` (`publication_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_publication_people`
--

CREATE VIEW `mycms_publication_people` AS (select
  `mycms_people_publication`.`people_id` AS `people_id`,
  `mycms_people_publication`.`publication_id` AS `publication_id`,
  `mycms_people_publication`.`people_order` AS `people_order`,
  `mycms_people_publication`.`publication_order` AS `publication_order`
  from `mycms_people_publication`);

-- -------------------------------------------------------------

-- converting old `projectperson` and `profileproject` to new `mycms_people_research`.


CREATE TABLE `mycms_people_research` AS
  SELECT
    a.`PersonId`      as `people_id`,
    a.`ProjectId` as `research_id`,
    a.`Order`         as `people_order`,
    b.`Order`         as `research_order`
  FROM `projectperson` AS a
  LEFT JOIN  `profileproject` AS b
  ON a.`ProjectId` = b.`ProjectId`
  AND a.`PersonId`     = b.`PersonId`
  ;
ALTER TABLE `mycms_people_research` ENGINE=InnoDB;

ALTER TABLE `mycms_people_research`
  CHANGE `people_id`      `people_id`      int(11) UNSIGNED NOT NULL,
  CHANGE `research_id`    `research_id`    int(11) UNSIGNED NOT NULL,
  CHANGE `people_order`   `people_order`   int(11) DEFAULT NULL,
  CHANGE `research_order` `research_order` int(11) DEFAULT NULL;

--
-- Constraints for table `mycms_people_research`
--
ALTER TABLE `mycms_people_research`
  ADD CONSTRAINT `mycms_people_research_ibfk_1` FOREIGN KEY (`people_id`) REFERENCES `mycms_people` (`people_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_people_research_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `mycms_research` (`research_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_research_people`
--

CREATE VIEW `mycms_research_people` AS (select
  `mycms_people_research`.`people_id` AS `people_id`,
  `mycms_people_research`.`research_id` AS `research_id`,
  `mycms_people_research`.`people_order` AS `people_order`,
  `mycms_people_research`.`research_order` AS `research_order`
  from `mycms_people_research`);

-- -------------------------------------------------------------

-- converting old `projectpublication` to new `mycms_publication_research`.

CREATE TABLE `mycms_publication_research` LIKE `projectpublication`;
ALTER TABLE `mycms_publication_research` ENGINE=InnoDB;
INSERT `mycms_publication_research` SELECT * FROM `projectpublication`;

ALTER TABLE `mycms_publication_research`
CHANGE `PublicationId`  `publication_id`    int(11) UNSIGNED NOT NULL  FIRST,
CHANGE `ProjectId`      `research_id`       int(11) UNSIGNED NOT NULL  AFTER `publication_id`,
CHANGE `Order`          `publication_order` int(11) DEFAULT NULL       AFTER `research_id`,
ADD                     `research_order`    int(11) DEFAULT NULL       AFTER `publication_order`;

-- select * from `mycms_publication_research` where `publication_id` not in (select `publication_id` from `mycms_publication`);
-- invalid foreight key values.
-- publication_id research_id publication_order research_order
-- 83             35          1                 NULL
-- 27             35          2                 NULL
-- 118            35          3                 NULL
delete from `mycms_publication_research` where `publication_id` = 83 and `research_id` = 35 and `publication_order` = 1;
delete from `mycms_publication_research` where `publication_id` = 27 and `research_id` = 35 and `publication_order` = 2;
delete from `mycms_publication_research` where `publication_id` = 118 and `research_id` = 35 and `publication_order` = 3;

--
-- Constraints for table `mycms_publication_research`
--
ALTER TABLE `mycms_publication_research`
  ADD CONSTRAINT `mycms_publication_research_ibfk_1` FOREIGN KEY (`publication_id`) REFERENCES `mycms_publication` (`publication_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mycms_publication_research_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `mycms_research` (`research_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Structure for view `mycms_research_publication`
--

CREATE VIEW `mycms_research_publication` AS (select
  `mycms_publication_research`.`publication_id` AS `publication_id`,
  `mycms_publication_research`.`research_id` AS `research_id`,
  `mycms_publication_research`.`publication_order` AS `publication_order`,
  `mycms_publication_research`.`research_order` AS `research_order`
  from `mycms_publication_research`);

-- -------------------------------------------------------------
