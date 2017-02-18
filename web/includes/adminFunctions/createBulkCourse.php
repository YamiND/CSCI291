<?php

include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	// If our login succeeds and we're an admin, process the csv
	parseCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creation Failed, invalid permissions';
   	header('Location: ../../pages/createBulkUser');
}

function parseCSV($mysqli)
{
	$filename = "coursesCreated.csv";
	$fp = fopen('php://output', 'w');

	$_SESSION['success'] = 'Courses Created';
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	if($_FILES['csvFile']['error'] == 0)
	{
    	$name = $_FILES['csvFile']['name'];
    	$ext = strtolower(end(explode('.', $_FILES['csvFile']['name'])));
	    $type = $_FILES['csvFile']['type'];
	    $tmpName = $_FILES['csvFile']['tmp_name'];

    	// check the file is a csv
    	if($ext === 'csv')
		{
			$userCSV = array_map('str_getcsv', file($tmpName));
			foreach($userCSV as $i => $data)
			{

				// The CSV Format should be: Course Name, Course Semester, Course Year

				// Example:
				// BIOL398,Spring,2017
				// BIOL499,Spring,2016
				// BIOL398,Spring,2018
			
				$courseName = $userCSV[$i][0];
				$courseSemester  = $userCSV[$i][1];
				$courseYear = $userCSV[$i][2];

				$value = createCourses($courseName, $courseSemester, $courseYear, $mysqli);

				$row = explode(',', $value);
				fputcsv($fp, $row);
			}
    	}
		else
		{
   			$_SESSION['fail'] = 'Course Creation Failed, file uploaded does not end in .csv';
   			header('Location: ../../pages/createBulkCourse');
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Course Creation Failed, file uploaded does not end in .csv';
   		header('Location: ../../pages/createBulkCourse');
	}
}

function createCourses($courseName, $courseSemester, $courseYear, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT courseID FROM courses where courseName = ? AND courseSemester = ? AND courseYear = ?"))
	{
		$stmt->bind_param('ssi', $courseName, $courseSemester, $courseYear);

		if($stmt->execute())
		{
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
   				$_SESSION['fail'] = 'Course Creation Failed, file uploaded does not end in .csv';
		   		header('Location: ../../pages/createBulkCourse');
			}
			else
			{
				if ($stmt = $mysqli->prepare("INSERT INTO courses (courseName, courseSemester, courseYear) VALUES (?, ?, ?)"))
				{
    				$stmt->bind_param('ssi', $courseName, $courseSemester, $courseYear); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						return "$courseName, $courseSemester, $courseYear";
					}
					else
					{
						$_SESSION['fail'] = $stmt->error;
		   				header('Location: ../../pages/createBulkCourse');
					}
				}
				else
				{
					$_SESSION['fail'] = $stmt->error;
		   			header('Location: ../../pages/createBulkCourse');
				}
			}
		}
		else
		{
			$_SESSION['fail'] = $stmt->error;
		   	header('Location: ../../pages/createBulkCourse');
		}
	}
	else
	{
		$_SESSION['fail'] = $stmt->error;
	   	header('Location: ../../pages/createBulkCourse');
	}
}

?>
