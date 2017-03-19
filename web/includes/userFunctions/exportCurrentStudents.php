<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../studentFunctions.php';
include_once '../rubricFunctions.php';
include_once '../facultyFunctions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

// Set our default time which we'll use for file output
date_default_timezone_set('America/New_York');

if ((login_check($mysqli) == true))
{
	// If our login succeeds and we're an admin, process the csv
	parseChoice($mysqli);
}
else
{
   $_SESSION['fail'] = 'Export data Failed, invalid permissions';
   header('Location: ../../pages/viewExportCurrentStudents');
}

function parseChoice($mysqli)
{
	if (isset($_POST['choiceOption']) && !empty($_POST['choiceOption']))
	{
		$choiceOption = $_POST['choiceOption'];

		switch ($choiceOption)
		{
			case "1":
				outputStudentData($mysqli);
			break;

			case "2":
			break;

			case "3":
			break;

			default:
			   	$_SESSION['fail'] = 'Export data Failed, invalid choice';
			   header('Location: ../../pages/viewExportCurrentStudents');
			break;
		}
	}
}

function outputStudentData($mysqli)
{
	// Output CSV Format would look like this
	// File Name: Student's Name-Current Date.csv
	// File Contents:
	// Course Name,Rubric Name,Graded By Name,Descriptive Piece,Grade for Piece,Total Percentage Name, Total Percentage,Faculty Feedback

	if (isset($_POST['studentID']) && !empty($_POST['studentID']))
	{

		// Get our data that we need for our queries
		$studentID = $_POST['studentID'];
		$courseID = getCourseID($studentID, $mysqli);

		// Get the student's name (we will use this for the file name)
		$studentName = getStudentName($studentID, $mysqli);

		// Get today's date
		$currDate = date('Y-m-d');

		// Output to CSV + Header Information
		$filename = "$studentName-$currDate.csv";
		$fp = fopen('php://output', 'w');
		$_SESSION['success'] = 'Student Data Exported';
		header('Content-type: application/csv');
		header("Content-Disposition: attachment; filename=\"$filename\"");

		// Get the course name
		$courseName = getCourseName($courseID, $mysqli);

		// Get the faculty name
		$facultyName = getFacultyName($_SESSION['userID'], $mysqli);

		if ($stmt = $mysqli->prepare("SELECT rubricID FROM rubrics WHERE courseID = ?"))
		{
			$stmt->bind_param('i', $courseID);

			if ($stmt->execute())
			{
				$stmt->bind_result($rubricID);
				$stmt->store_result();

				while ($stmt->fetch())
				{
					// Go through each Rubric 
					$rubricName = getRubricName($rubricID, $mysqli);
				
					if ($stmt2 = $mysqli->prepare("SELECT gradeRubricID, facultyID, facultyFeedback FROM gradedRubrics WHERE rubricID = ? AND studentID = ?"))
					{	
						$stmt2->bind_param('ii', $rubricID, $studentID);

						if ($stmt2->execute())
						{
							$stmt2->bind_result($gradeRubricID, $facultyID, $facultyFeedback);
							$stmt2->store_result();

							if ($stmt2->num_rows > 0)
							{
								while ($stmt2->fetch())
								{
									// Initiliaze Empty Array that we will use to output to CSV
									$outputArray = [];
							

									$totalGrade = 0;
									$totalPointsPossible = 0;
									$facultyName = getFacultyName($facultyID, $mysqli);
									$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

									// Our Initial Array values	
									$outputArray = array($courseName, $rubricName, $facultyName);

									for ($i = 0; $i < count($rubricDescArray); $i++)
									{
										if ($rubricDescArray[$i] != NULL)
										{
											$pieceNumber = "piece" . ($i + 1);
											$pointID = "point" . ($i + 1);

											$totalGrade += getRubricGrade($gradeRubricID, $studentID, $pieceNumber, $mysqli);
											$totalPointsPossible += getRPPByPoint($rubricID, $pointID, $mysqli);
											
											// The subcategory name
											$rubricSubCategory = $rubricDescArray[$i];

											$rubricCategoryScore = getRubricGrade($gradeRubricID, $studentID, $pieceNumber, $mysqli) . " / " . getRPPByPoint($rubricID, $pointID, $mysqli);

											array_push($outputArray, $rubricSubCategory, $rubricCategoryScore);
										}
									}
									$rubricPercentage =  number_format(($totalGrade / $totalPointsPossible) * 100, 2, '.', '') . "%";
									array_push($outputArray, "Total Percentage:", $rubricPercentage, $facultyFeedback);

									// Add our faculty members data to the CSV
									fputcsv($fp, $outputArray);
								}
							}
							else
							{
								echo "No grades for student";
							}
						}
					}
					else
					{
						echo "No grades for student";
					}
				}
			}
		}
	}
	else
	{
	   $_SESSION['fail'] = 'Export data Failed, data not sent';
	   header('Location: ../../pages/viewExportCurrentStudents');
	}
}

function getCourseName($courseID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT courseName FROM courses WHERE courseID = ?"))
    {   
        $stmt->bind_param('i', $courseID);

        if ($stmt->execute())
        {   
            $stmt->bind_result($courseName);
            $stmt->store_result();

            $stmt->fetch();

            return $courseName;
        }   
    }   
}

function getCourseID($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentCourseID FROM students WHERE studentID = ?"))
    {   
        $stmt->bind_param('i', $studentID);

        if ($stmt->execute())
        {   
            $stmt->bind_result($courseID);

            $stmt->store_result();

            $stmt->fetch();

            return $courseID;
        }   
    }   
}

?>
