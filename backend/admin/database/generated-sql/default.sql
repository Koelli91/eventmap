
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
    `location_name` VARCHAR(255),
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
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- event_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_category`;

CREATE TABLE `event_category`
(
    `event_id` INTEGER NOT NULL,
    `category_id` INTEGER NOT NULL,
    PRIMARY KEY (`event_id`,`category_id`),
    INDEX `event_category_fi_904832` (`category_id`),
    CONSTRAINT `event_category_fk_b54508`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`),
    CONSTRAINT `event_category_fk_904832`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- image
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `image` BLOB NOT NULL,
    `type_id` INTEGER NOT NULL,
    `event_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `image_fi_f35f5f` (`type_id`),
    INDEX `image_fi_b54508` (`event_id`),
    CONSTRAINT `image_fk_f35f5f`
        FOREIGN KEY (`type_id`)
        REFERENCES `imagetype` (`id`),
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
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- website
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `website`;

CREATE TABLE `website`
(
    `url` VARCHAR(255) NOT NULL,
    `type_id` INTEGER NOT NULL,
    PRIMARY KEY (`url`),
    INDEX `website_fi_99cc5c` (`type_id`),
    CONSTRAINT `website_fk_99cc5c`
        FOREIGN KEY (`type_id`)
        REFERENCES `websitetype` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- websitetype
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `websitetype`;

CREATE TABLE `websitetype`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
