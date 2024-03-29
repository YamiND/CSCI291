<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../rubricFunctions.php';

sec_session_start();

if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isFaculty($mysqli)))
{
	assignRubricGrades($mysqli);
}
else
{
	$_SESSION['fail'] = 'Rubric Grade could not be added/modified, invalid permissions';
   	header('Location: ../../pages/assignRubricGrades');
}

function assignRubricGrades($mysqli)
{
	if (isset($_POST['studentID'], $_POST['rubricID'], $_POST['facultyID']) && !empty($_POST['studentID']) && !empty($_POST['rubricID']) && !empty($_POST['facultyID']))
	{
		$rubricID = $_POST['rubricID'];
		$studentID = $_POST['studentID'];
		$facultyID = $_POST['facultyID'];

		if (isset($_POST['facultyFeedback']))
		{
			$facultyFeedback = $_POST['facultyFeedback'];
		}
		else
		{
			$facultyFeedback = NULL;
		}

		$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

		$pieceString = "";

		$pieceArray[1] = '';
		$pieceArray[2] = '';
		$pieceArray[3] = '';
		$pieceArray[4] = '';
		$pieceArray[5] = '';
		$pieceArray[6] = '';
		$pieceArray[7] = '';
		$pieceArray[8] = '';
		$pieceArray[9] = '';
		$pieceArray[10] = '';

		for ($i = 0; $i < count($rubricDescArray); $i++)
		{
			$pieceNumber = "piece" . ($i + 1);

			if ($rubricDescArray[$i] != NULL)
			{
				if (isset($_POST["$pieceNumber"]))
				{
					$pieceArray[$i + 1] = $_POST["$pieceNumber"];
				}
				else
				{
					$_SESSION['fail'] = 'Rubric Grade could not be added/modified, data not sent';
				   	header('Location: ../../pages/assignRubricGrades');
				}
			}
		}

		// I know this is bad :(
		$piece1 = $pieceArray[1];
		$piece2 = $pieceArray[2];
		$piece3 = $pieceArray[3];
		$piece4 = $pieceArray[4];
		$piece5 = $pieceArray[5];
		$piece6 = $pieceArray[6];
		$piece7 = $pieceArray[7];
		$piece8 = $pieceArray[8];
		$piece9 = $pieceArray[9];
		$piece10 = $pieceArray[10];

		if (getRubricGradeExists($rubricID, $studentID, $facultyID, $mysqli) == 1)
		{
			updateGrade($piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $studentID, $rubricID, $facultyID, $facultyFeedback, $mysqli);
		}
		else
		{
			insertGrade($piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $studentID, $rubricID, $facultyID, $facultyFeedback, $mysqli);
		}
	}
	else
	{
		$_SESSION['fail'] = 'Rubric Grade could not be added/modified, data not sent';
   		header('Location: ../../pages/assignRubricGrades');
	}
}

function insertGrade($piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $studentID, $rubricID, $facultyID, $facultyFeedback, $mysqli)
{
	if ($stmt = $mysqli->prepare("INSERT INTO gradedRubrics(rubricID, studentID, facultyID, piece1, piece2, piece3, piece4, piece5, piece6, piece7, piece8, piece9, piece10, facultyFeedback) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
	{
		$stmt->bind_param('iiidddddddddds', $rubricID, $studentID, $facultyID, $piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $facultyFeedback);

		if($stmt->execute())
		{
			$_SESSION['success'] = "Rubric Grade Added";
   			header('Location: ../../pages/assignRubricGrades');
		}
		else
		{
			$_SESSION['fail'] = "Rubric Grade could not Updated, database insert execute failure";
   			header('Location: ../../pages/assignRubricGrades');
		}
	}
	else
	{
		$_SESSION['fail'] = "Rubric Grade could not Updated, database insert failure";
   		header('Location: ../../pages/assignRubricGrades');
	}
}

function updateGrade($piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $studentID, $rubricID, $facultyID, $facultyFeedback, $mysqli)
{
	if ($stmt = $mysqli->prepare("UPDATE gradedRubrics SET piece1 = ?, piece2 = ?, piece3 = ?, piece4 = ?, piece5 = ?, piece6 = ?, piece7 = ?, piece8 = ?, piece9 = ?, piece10 = ?, facultyFeedback = ? WHERE studentID = ? AND rubricID = ? AND facultyID = ?"))
	{
		$stmt->bind_param('ddddddddddsiii', $piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $facultyFeedback, $studentID, $rubricID, $facultyID);

		if ($stmt->execute())
		{
			$_SESSION['success'] = "Rubric Grade Updated";
   			header('Location: ../../pages/assignRubricGrades');
		}
		else
		{
			$_SESSION['fail'] = "Rubric Grade could not Updated, database update failure";
  			header('Location: ../../pages/assignRubricGrades');
		}
	}
	else
	{
		$_SESSION['fail'] = "Rubric Grade could not Updated, database update failure";
		header('Location: ../../pages/assignRubricGrades');
	}
}

?>
