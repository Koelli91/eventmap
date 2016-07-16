
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- event
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(1000) NOT NULL,
    `longitude` FLOAT NOT NULL,
    `latitude` FLOAT NOT NULL,
    `street_no` VARCHAR(255),
    `zip_code` VARCHAR(5),
    `city` VARCHAR(255),
    `country` VARCHAR(255),
    `begin` DATETIME NOT NULL,
    `end` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- event_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_category`;

CREATE TABLE `event_category`
(
    `event_id` INTEGER NOT NULL,
    `category_name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`event_id`,`category_name`),
    INDEX `event_category_fi_a0d3c0` (`category_name`),
    CONSTRAINT `event_category_fk_b54508`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`),
    CONSTRAINT `event_category_fk_a0d3c0`
        FOREIGN KEY (`category_name`)
        REFERENCES `category` (`name`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- image
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `image` BLOB NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `event_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `image_fi_fc9d49` (`type`),
    INDEX `image_fi_b54508` (`event_id`),
    CONSTRAINT `image_fk_fc9d49`
        FOREIGN KEY (`type`)
        REFERENCES `imagetype` (`type`),
    CONSTRAINT `image_fk_b54508`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- imagetype
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `imagetype`;

CREATE TABLE `imagetype`
(
    `type` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`type`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- website
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `website`;

CREATE TABLE `website`
(
    `url` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`url`),
    INDEX `website_fi_642db7` (`type`),
    CONSTRAINT `website_fk_642db7`
        FOREIGN KEY (`type`)
        REFERENCES `websitetype` (`type`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- websitetype
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `websitetype`;

CREATE TABLE `websitetype`
(
    `type` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`type`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
