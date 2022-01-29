CREATE TABLE `question` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `question` TEXT NOT NULL,
    `user_id` int(11) NOT NULL,
    `video` char(19) not null,
    `created` datetime not null,
    `edited` datetime,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `video` (`video`),
    CONSTRAINT `question_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);

create table `reply` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `answer` TEXT NOT NULL,
    `user_id` int(11) NOT NULL,
    `question_id` bigint(20) not null,
    `created` datetime not null,
    `edited` datetime,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `question_id` (`question_id`),
    CONSTRAINT `answer_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    CONSTRAINT `answer_question_id` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`)
);

create table `question_vote` (
    `id` bigint(20) not null AUTO_INCREMENT,
    `question_id` bigint(20) not null,
    `user_id` int(11) not null,
    `vote` tinyint not null,
    PRIMARY KEY (`id`),
    KEY `question_id` (`question_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `question_vote_question_id` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
    CONSTRAINT `question_vote_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);

create table `reply_vote` (
    `id` bigint(20) not null AUTO_INCREMENT,
    `reply_id` bigint(20) not null,
    `user_id` int(11) not null,
    `vote` tinyint not null,
    PRIMARY KEY (`id`),
    KEY `reply_id` (`reply_id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `reply_vote_reply_id` FOREIGN KEY (`reply_id`) REFERENCES `reply` (`id`),
    CONSTRAINT `reply_vote_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);

ALTER TABLE `question_vote` ADD UNIQUE `unique_question_user` (`question_id`, `user_id`);
ALTER TABLE `reply_vote` ADD UNIQUE `unique_reply_user` (`reply_id`, `user_id`);

ALTER TABLE `question` CHANGE `question` `text` TEXT NOT NULL;
ALTER TABLE `reply` CHANGE `answer` `text` TEXT NOT NULL;

alter table view change `start` `start` timestamp not null default current_timestamp;
UPDATE view set type = 0 where type = 'vid';
UPDATE view set type = 1 where type = 'pdf';
alter table view change `type` `pdf` tinyint;

ALTER TABLE view ADD too_long TINYINT(1) default 0 AFTER stop; 
UPDATE view as v set v.too_long = 1 where v.stop - v.start > 1800;
UPDATE view as v set v.too_long = 0 where v.too_long IS NULL;

ALTER TABLE view ADD speed FLOAT default 1.0 after stop;

ALTER TABLE user ADD studentID INT UNSIGNED AFTER email;
ALTER TABLE user ADD knownAs VARCHAR(45) AFTER lastname;
ALTER TABLE user ADD teamsName VARCHAR(45) AFTER studentID;
ALTER TABLE user ADD hasPicture TINYINT(1) NOT NULL DEFAULT 0;
CREATE INDEX studentID ON user(studentID);
CREATE INDEX teamsName ON user(teamsName);

-- -----------------------------------------------------
-- Table `cs472`.`meeting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cs472`.`meeting` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `day_id` INT NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  `date` DATE NOT NULL,
  `start` TIME NOT NULL,
  `stop` TIME NOT NULL,
  `sessionWeight` FLOAT NOT NULL DEFAULT 0.5,
  PRIMARY KEY (`id`),
  INDEX `fk_meeting_day1_idx` (`day_id` ASC) VISIBLE,
  CONSTRAINT `fk_meeting_day1`
    FOREIGN KEY (`day_id`)
    REFERENCES `cs472`.`day` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cs472`.`attendance_data`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cs472`.`attendance_data` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `meeting_id` BIGINT UNSIGNED NOT NULL,
  `teamsName` VARCHAR(45) NOT NULL,
  `start` TIME NOT NULL,
  `stop` TIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_attendance_meeting1_idx` (`meeting_id` ASC) VISIBLE,
  CONSTRAINT `fk_attendance_meeting1`
    FOREIGN KEY (`meeting_id`)
    REFERENCES `cs472`.`meeting` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cs472`.`attendance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cs472`.`attendance` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `meeting_id` BIGINT UNSIGNED NOT NULL,
  `teamsName` VARCHAR(45) NOT NULL,
  `notEnrolled` TINYINT(1) NOT NULL DEFAULT 0,
  `absent` TINYINT(1) NOT NULL DEFAULT 0,
  `arriveLate` TINYINT(1) NOT NULL DEFAULT 0,
  `leaveEarly` TINYINT(1) NOT NULL DEFAULT 0,
  `middleMissing` TINYINT(1) NOT NULL DEFAULT 0,
  `inClass` TINYINT(1) NOT NULL DEFAULT 0,
  INDEX `fk_attendance_report_meeting1_idx` (`meeting_id` ASC) VISIBLE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_attendance_report_meeting1`
    FOREIGN KEY (`meeting_id`)
    REFERENCES `cs472`.`meeting` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

update user set teamsName = CONCAT(TRIM(firstname), " ", TRIM(lastname));

ALTER TABLE attendance ADD COLUMN excused tinyint(1) NOT NULL DEFAULT 0;