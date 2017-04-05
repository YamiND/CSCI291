<?php

include_once '../dbConnect.php';
include_once '../functions.php';

ini_set("auto_detect_line_endings", true);

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

				$studentFirstName  = $userCSV[$i][0];
				$studentLastName = $userCSV[$i][1];
				$studentEmail = $userCSV[$i][2];
				$courseNumber = $userCSV[$i][3];

				createStudent($studentFirstName, $studentLastName, $studentEmail, $courseNumber, $mysqli);
			}
    	}
		else
		{
   			$_SESSION['fail'] = 'Student Creation Failed, file uploaded does not end in .csv';
   			header('Location: ../../pages/createBulkStudent');
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Student Creation Failed, file upload error';
   		header('Location: ../../pages/createBulkStudent');
	}
}

function createStudent($studentFirstName, $studentLastName, $studentEmail, $courseNumber, $mysqli)
{
	date_default_timezone_set('UTC');

    // Get current semester
    $currYear = date('Y');
    $currDate = date('Y-m-d');

    if (($currDate > "$currYear-01-01") && ($currDate < "$currYear-06-01"))
    {   
        $semester = "SP$currYear";
    }   
    else if (($currDate > "$currYear-06-01") && ($currDate < "$currYear-08-01"))
    {   
        $semester = "SU$currYear";
    }   
    else
    {   
        $semester = "FA$currYear";
    }   

	if ($stmt = $mysqli->prepare("SELECT studentEmail FROM students where studentEmail = ? AND studentSemester = ?"))
	{
		$stmt->bind_param('ss', $studentEmail, $semester);

		if($stmt->execute())
		{
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
    			$_SESSION['fail'] = 'Student Creation Failed, Student already exists';
   				header('Location: ../../pages/createBulkStudent');
			}
			else
			{
				if ($stmt = $mysqli->prepare("INSERT INTO students (studentFirstName, studentLastName, studentEmail, studentSemester, studentCourseID) VALUES (?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssi', $studentFirstName, $studentLastName, $studentEmail, $semester, $courseNumber); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
   						$_SESSION['success'] = 'Student Account Creation Successful';
			   			header('Location: ../../pages/createBulkStudent');
					}
				}
			}
		}
		else
		{
   			$_SESSION['fail'] = 'Account Creation Failed, select failure';
   			header('Location: ../../pages/createBulkStudent');
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Account Creation Failed, database select error';
   		header('Location: ../../pages/createBulkStudent');
	}
}

function randomString($length = 8) 
{
	// This function is used to generate a random password
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) 
	{
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

?>
