<?php

function getStudentName($studentID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM students WHERE studentID = ?"))
	{
		$stmt->bind_param('i', $studentID);

		if ($stmt->execute())
		{
			$stmt->bind_result($studentFirstName, $studentLastName);
			$stmt->store_result();

			$stmt->fetch();

			return "$studentLastName, $studentFirstName";
		}
	}
}

function getNumCurrentStudents($mysqli)
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

    if ($stmt = $mysqli->prepare("SELECT studentID FROM students WHERE studentSemester = ?"))
    {
		$stmt->bind_param('s', $semester);
		if($stmt->execute())
        {
            $stmt->bind_result($studentID);
            $stmt->store_result();
			
			return $stmt->num_rows;
		}
		else
		{
			return "-1";
		}
	}
	else
	{
		return "-1";
	}
}

?>
