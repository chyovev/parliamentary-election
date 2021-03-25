
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- assembly_types
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `assembly_types`;

CREATE TABLE `assembly_types`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(50) NOT NULL,
    `minimum_constituency_mandates` int(11) unsigned DEFAULT 0 NOT NULL,
    `total_mandates` int(11) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- constituencies
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `constituencies`;

CREATE TABLE `constituencies`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(191),
    `coordinates` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `title` (`title`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- constituencies_censuses
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `constituencies_censuses`;

CREATE TABLE `constituencies_censuses`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `constituency_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `population_census_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `population` int(11) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FK_constituencies_censuses_constituencies` (`constituency_id`),
    INDEX `FK_constituencies_censuses_population_censuses` (`population_census_id`),
    CONSTRAINT `FK_constituencies_censuses_constituencies`
        FOREIGN KEY (`constituency_id`)
        REFERENCES `constituencies` (`id`)
        ON UPDATE CASCADE,
    CONSTRAINT `FK_constituencies_censuses_population_censuses`
        FOREIGN KEY (`population_census_id`)
        REFERENCES `population_censuses` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- elections
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `elections`;

CREATE TABLE `elections`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `slug` VARCHAR(255) NOT NULL,
    `assembly_type_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `population_census_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `active_suffrage` int(11) unsigned DEFAULT 0 NOT NULL,
    `threshold_percentage` int(11) unsigned DEFAULT 0 NOT NULL,
    `total_valid_votes` int(11) unsigned DEFAULT 0 NOT NULL,
    `total_invalid_votes` int(11) unsigned DEFAULT 0 NOT NULL,
    `official` tinyint(3) unsigned DEFAULT 0 NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `slug` (`slug`),
    INDEX `FK_elections_assembly_types` (`assembly_type_id`),
    INDEX `FK_elections_population_censuses` (`population_census_id`),
    CONSTRAINT `FK_elections_assembly_types`
        FOREIGN KEY (`assembly_type_id`)
        REFERENCES `assembly_types` (`id`)
        ON UPDATE CASCADE,
    CONSTRAINT `FK_elections_population_censuses`
        FOREIGN KEY (`population_census_id`)
        REFERENCES `population_censuses` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- elections_independent_candidates
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `elections_independent_candidates`;

CREATE TABLE `elections_independent_candidates`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `election_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `constituency_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `votes` int(11) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `constituency` (`constituency_id`),
    INDEX `FK_elections_independent_candidates_elections` (`election_id`),
    CONSTRAINT `FK_elections_independent_candidates_constituencies`
        FOREIGN KEY (`constituency_id`)
        REFERENCES `constituencies` (`id`)
        ON UPDATE CASCADE,
    CONSTRAINT `FK_elections_independent_candidates_elections`
        FOREIGN KEY (`election_id`)
        REFERENCES `elections` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- elections_parties
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `elections_parties`;

CREATE TABLE `elections_parties`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `election_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `list_number` int(11) unsigned DEFAULT 0 NOT NULL,
    `party_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `party_color` CHAR(7),
    `total_votes` int(11) unsigned DEFAULT 0 NOT NULL,
    `ord` int(11) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FK_elections_parties_elections` (`election_id`),
    INDEX `FK_elections_parties_parties` (`party_id`),
    CONSTRAINT `FK_elections_parties_elections`
        FOREIGN KEY (`election_id`)
        REFERENCES `elections` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `FK_elections_parties_parties`
        FOREIGN KEY (`party_id`)
        REFERENCES `parties` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- elections_parties_votes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `elections_parties_votes`;

CREATE TABLE `elections_parties_votes`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `election_party_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `constituency_id` int(11) unsigned DEFAULT 0 NOT NULL,
    `votes` int(11) unsigned DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FK_elections_parties_votes_elections_parties` (`election_party_id`),
    INDEX `FK_elections_parties_votes_constituencies` (`constituency_id`),
    CONSTRAINT `FK_elections_parties_votes_constituencies`
        FOREIGN KEY (`constituency_id`)
        REFERENCES `constituencies` (`id`)
        ON UPDATE CASCADE,
    CONSTRAINT `FK_elections_parties_votes_elections_parties`
        FOREIGN KEY (`election_party_id`)
        REFERENCES `elections_parties` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- parties
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `parties`;

CREATE TABLE `parties`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255),
    `abbreviation` VARCHAR(50),
    `created_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- population_censuses
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `population_censuses`;

CREATE TABLE `population_censuses`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `year` INTEGER(4) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `year` (`year`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
