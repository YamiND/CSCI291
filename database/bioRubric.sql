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
  `userID` INT NOT NULL AUTO_INCREMENT ,
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
  `courseID` INT NOT NULL AUTO_INCREMENT ,
  `courseName` VARCHAR(12) NOT NULL ,
  PRIMARY KEY (`courseID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`students`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`students` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`students` (
  `studentID` INT NOT NULL AUTO_INCREMENT ,
  `studentFirstName` VARCHAR(45) NOT NULL ,
  `studentLastName` VARCHAR(45) NOT NULL ,
  `studentEmail` VARCHAR(256) NOT NULL ,
  `studentSemester` VARCHAR(6) NOT NULL ,
  `studentCourseID` INT NOT NULL ,
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
  `gradeRubricID` INT NOT NULL AUTO_INCREMENT ,
  `rubricID` INT NOT NULL ,
  `studentID` INT NOT NULL ,
  `facultyID` INT NOT NULL ,
  `piece1` DOUBLE NULL ,
  `piece2` DOUBLE NULL ,
  `piece3` DOUBLE NULL ,
  `piece4` DOUBLE NULL ,
  `piece5` DOUBLE NULL ,
  `piece6` DOUBLE NULL ,
  `piece7` DOUBLE NULL ,
  `piece8` DOUBLE NULL ,
  `piece9` DOUBLE NULL ,
  `piece10` DOUBLE NULL ,
  `facultyFeedback` VARCHAR(4096) NULL ,
  PRIMARY KEY (`gradeRubricID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`rubricDescriptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`rubricDescriptions` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`rubricDescriptions` (
  `rubricID` INT NOT NULL ,
  `desc1` VARCHAR(100) NULL ,
  `desc2` VARCHAR(100) NULL ,
  `desc3` VARCHAR(100) NULL ,
  `desc4` VARCHAR(100) NULL ,
  `desc5` VARCHAR(100) NULL ,
  `desc6` VARCHAR(100) NULL ,
  `desc7` VARCHAR(100) NULL ,
  `desc8` VARCHAR(100) NULL ,
  `desc9` VARCHAR(100) NULL ,
  `desc10` VARCHAR(100) NULL ,
  PRIMARY KEY (`rubricID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bioRubricDB`.`rubricPointsPossible`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bioRubricDB`.`rubricPointsPossible` ;

CREATE  TABLE IF NOT EXISTS `bioRubricDB`.`rubricPointsPossible` (
  `rubricID` INT NOT NULL AUTO_INCREMENT ,
  `point1` DOUBLE NULL ,
  `point2` DOUBLE NULL ,
  `point3` DOUBLE NULL ,
  `point4` DOUBLE NULL ,
  `point5` DOUBLE NULL ,
  `point6` DOUBLE NULL ,
  `point7` DOUBLE NULL ,
  `point8` DOUBLE NULL ,
  `point9` DOUBLE NULL ,
  `point10` DOUBLE NULL ,
  PRIMARY KEY (`rubricID`) )
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
INSERT INTO `bioRubricDB`.`users` (`userID`, `userEmail`, `userPassword`, `userSalt`, `userFirstName`, `userLastName`, `isAdmin`, `isFaculty`) VALUES (3, 'jgarvon@lssu.edu', '506beb3bb6a2c033392158f6451e85d5862b9997c999a3438cd3c4e93d65e7bf5b205f5cd132724f1db7ef9bd94088a72f9b9417c829cf081fffc3c2c599496f', '46d5eb8476b910c3501188f91f4fedfd593d8a7b13c27e25c34fd683297f43fd79f4f79cd87c9eaccdee8ed636adef49a461f1591013c7face1081191f5deb38', 'Jason', 'Garvon', true, true);

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`courses`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`courses` (`courseID`, `courseName`) VALUES (1, 'BIOL398/399');
INSERT INTO `bioRubricDB`.`courses` (`courseID`, `courseName`) VALUES (2, 'BIOL499');

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`students`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`, `studentSemester`, `studentCourseID`) VALUES (1, 'Tyler', 'Postma', 'tpostma@lssu.edu', 'SP2017', 1);
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`, `studentSemester`, `studentCourseID`) VALUES (2, 'Test', 'Student', 'tstudent@lssu.edu', 'SP2017', 2);
INSERT INTO `bioRubricDB`.`students` (`studentID`, `studentFirstName`, `studentLastName`, `studentEmail`, `studentSemester`, `studentCourseID`) VALUES (3, 'Another', 'Student', 'astudent@lssu.edu', 'SP2017', 1);

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
INSERT INTO `bioRubricDB`.`gradedRubrics` (`gradeRubricID`, `rubricID`, `studentID`, `facultyID`, `piece1`, `piece2`, `piece3`, `piece4`, `piece5`, `piece6`, `piece7`, `piece8`, `piece9`, `piece10`, `facultyFeedback`) VALUES (1, 1, 1, 1, 15, 15, 10, 10, NULL, NULL, NULL, NULL, NULL, NULL, 'Example Feedback');
INSERT INTO `bioRubricDB`.`gradedRubrics` (`gradeRubricID`, `rubricID`, `studentID`, `facultyID`, `piece1`, `piece2`, `piece3`, `piece4`, `piece5`, `piece6`, `piece7`, `piece8`, `piece9`, `piece10`, `facultyFeedback`) VALUES (2, 1, 1, 2, 10, 10, 5, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'More feedback yayy');

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`rubricDescriptions`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (1, 'Rationale and Objectives', 'Methods or Approach', 'Presentation Format', 'Mechanics of Delivery', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (2, 'Executive Summary', 'Problem Statement and Background', 'Approach', 'Outcomes and Lessons Learned', 'Style', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (3, 'Abstract', 'Introduction', 'Methods', 'Results Including Statistics', 'Discussion/Conclusion', 'Style', NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (4, 'Communicate Rationale and Scope of Project', 'Communicate Outcomes and Lessons Learned', 'Presentation', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (5, 'Communicate Rationale and Scope of Project ', 'Communicate Results', 'Communicate Using Figures and Tables', 'Presentation', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (6, 'Organization', 'Executive Summary', 'Problem Statement/Background', 'Project Goals/Objectives', 'Approach', 'Outputs', 'Outcomes/Lessons Learned', 'Presentation', 'Style', 'Professionalism');
INSERT INTO `bioRubricDB`.`rubricDescriptions` (`rubricID`, `desc1`, `desc2`, `desc3`, `desc4`, `desc5`, `desc6`, `desc7`, `desc8`, `desc9`, `desc10`) VALUES (7, 'Organization', 'Abstract', 'Introduction', 'Objectives/Hypothesis', 'Methods', 'Results', 'Discussion', 'Presentation', 'Style', 'Professionalism');

COMMIT;

-- -----------------------------------------------------
-- Data for table `bioRubricDB`.`rubricPointsPossible`
-- -----------------------------------------------------
START TRANSACTION;
USE `bioRubricDB`;
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (1, 15, 15, 10, 10, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (2, 10, 30, 20, 30, 10, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (3, 10, 20, 15, 20, 25, 10, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (4, 15, 15, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (5, 10, 10, 10, 10, NULL, NULL, NULL, NULL, 10, 10);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (6, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10);
INSERT INTO `bioRubricDB`.`rubricPointsPossible` (`rubricID`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `point7`, `point8`, `point9`, `point10`) VALUES (7, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10);

COMMIT;
