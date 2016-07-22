
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
    `longitude` DOUBLE NOT NULL,
    `latitude` DOUBLE NOT NULL,
    `koordX` DOUBLE NOT NULL,
    `koordY` DOUBLE NOT NULL,
    `koordZ` DOUBLE NOT NULL,
    `location_name` VARCHAR(255),
    `street_no` VARCHAR(255),
    `zip_code` VARCHAR(5),
    `city` VARCHAR(255),
    `country` VARCHAR(255),
    `begin` DATETIME NOT NULL,
    `end` DATETIME,
    `image` VARCHAR(255),
    `website` VARCHAR(255),
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
    PRIMARY KEY (`id`),
    UNIQUE INDEX `category_u_d94269` (`name`)
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
        REFERENCES `event` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `event_category_fk_904832`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
