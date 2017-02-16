SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `bioRubricDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `bioRubricDB` ;

-- -----------------------------------------------------
-- Table `bioRubricDB`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`users` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`users` (
  `userID` INT NOT NULL ,
  `userEmail` VARCHAR(45) NOT NULL ,
  `userPassword` VARCHAR(256) NOT NULL ,
  `userSalt` VARCHAR(256) NOT NULL ,
  `userFirstName` VARCHAR(45) NOT NULL ,
  `userLastName` VARCHAR(45) NOT NULL ,
  `isAdmin` TINYINT(1) NOT NULL ,
  `isFaculty` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`userID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`courses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`courses` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`courses` (
  `courseID` INT NOT NULL ,
  `courseName` VARCHAR(7) NOT NULL ,
  `courseSemester` VARCHAR(6) NOT NULL ,
  PRIMARY KEY (`courseID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`students`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`students` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`students` (
  `studentID` INT NOT NULL ,
  `studentFirstName` VARCHAR(45) NOT NULL ,
  `studentLastName` VARCHAR(45) NOT NULL ,
  `studentEmail` VARCHAR(256) NOT NULL ,
  PRIMARY KEY (`studentID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`rubrics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`rubrics` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`rubrics` (
  `rubricID` INT NOT NULL AUTO_INCREMENT ,
  `rubricName` VARCHAR(45) NOT NULL ,
  `courseID` INT NOT NULL ,
  PRIMARY KEY (`rubricID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`gradedRubrics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`gradedRubrics` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`gradedRubrics` (
  `gradeRubricID` INT NOT NULL ,
  `rubricID` INT NOT NULL ,
  `studentID` INT NOT NULL ,
  `piece1` INT NULL ,
  `piece2` INT NULL ,
  `piece3` INT NULL ,
  `piece4` INT NULL ,
  `piece5` INT NULL ,
  `piece6` INT NULL ,
  `piece7` INT NULL ,
  `piece8` INT NULL ,
  `piece9` INT NULL ,
  `piece10` INT NULL ,
  PRIMARY KEY (`gradeRubricID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`studentClassList`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`studentClassList` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`studentClassList` (
  `courseID` INT NOT NULL ,
  `studentID` INT NOT NULL )
ENGINE = InnoDB;

USE `bioRubricDB` ;

SET SQL_MODE = '';
GRANT USAGE ON *.* TO bioGrade;
 DROP USER bioGrade;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'bioGrade' IDENTIFIED BY 'bioGrade';

GRANT ALL ON `bioRubricDB`.* TO 'bioGrade';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`users` (`userID`, `userEmail`, `userPassword`, `userSalt`, `userFirstName`, `userLastName`, `isAdmin`, `isFaculty`) VALUES (1, 'tpostma@lssu.edu', '506beb3bb6a2c033392158f6451e85d5862b9997c999a3438cd3c4e93d65e7bf5b205f5cd132724f1db7ef9bd94088a72f9b9417c829cf081fffc3c2c599496f', '46d5eb8476b910c3501188f91f4fedfd593d8a7b13c27e25c34fd683297f43fd79f4f79cd87c9eaccdee8ed636adef49a461f1591013c7face1081191f5deb38', 'Tyler', 'Postma', true, true);
INSERT INTO `bioRubricDB`.`users` (`userID`, `userEmail`, `userPassword`, `userSalt`, `userFirstName`, `userLastName`, `isAdmin`, `isFaculty`) VALUES (2, 'faculty@lssu.edu', '506beb3bb6a2c033392158f6451e85d5862b9997c999a3438cd3c4e93d65e7bf5b205f5cd132724f1db7ef9bd94088a72f9b9417c829cf081fffc3c2c599496f', '46d5eb8476b910c3501188f91f4fedfd593d8a7b13c27e25c34fd683297f43fd79f4f79cd87c9eaccdee8ed636adef49a461f1591013c7face1081191f5deb38', 'Faculty', 'LSSU', false, true);

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`courses`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`courses` (`courseID`, `courseName`, `courseSemester`) VALUES (1, 'BIOL398', 'FALL17');
INSERT INTO `bioRubricDB`.`courses` (`courseID`, `courseName`, `courseSemester`) VALUES (2, 'BIOL499', 'SPRING16');
INSERT INTO `bioRubricDB`.`courses` (`courseID`, `courseName`, `courseSemester`) VALUES (3, 'BIOL399', 'SUMMER17');

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`students`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`) VALUES (1, 'Tyler', 'Postma', 'tpostma@lssu.edu');
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`) VALUES (2, 'Test', 'Student', 'tstudent@lssu.edu');
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`) VALUES (3, 'Another', 'Student', 'astudent@lssu.edu');

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`rubrics`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (1, 'Proposal Presentation Rubric ', 1);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (2, 'Paper Rubic - Experiential Learning ', 2);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (3, 'Paper Rubric - Research Thesis', 2);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (4, 'Presentation Rubric - Experiential Learning', 2);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (5, 'Presentation Rubric - Research Thesis ', 2);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (6, 'Poster Rubric - Experiential Learning', 2);
INSERT INTO `bioRubricDB`.`rubrics` (`rubricID`, `rubricName`, `courseID`) VALUES (7, 'Poster Grading Rubric - Research Projects', 2);

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`gradedRubrics`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`gradedRubrics` (`gradeRubricID`, `rubricID`, `studentID`, `piece1`, `piece2`, `piece3`, `piece4`, `piece5`, `piece6`, `piece7`, `piece8`, `piece9`, `piece10`) VALUES (1, 1, 1, 15, 15, 10, 10, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`studentClassList`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`studentClassList` (`courseID`, `studentID`) VALUES (1, 1);
INSERT INTO `bioRubricDB`.`studentClassList` (`courseID`, `studentID`) VALUES (2, 2);
INSERT INTO `bioRubricDB`.`studentClassList` (`courseID`, `studentID`) VALUES (2, 3);

COMMIT;
