<?php

include_once '../dbConnect.php';
include_once '../functions.php';

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
	if (isset($_POST['studentID'], $_POST['rubricID']) && !empty($_POST['studentID']) && !empty($_POST['rubricID']))
	{
		$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

		$pieceString = "";

		$piece1 = NULL;
		$piece2 = NULL;
		$piece3 = NULL;
		$piece4 = NULL;
		$piece5 = NULL;
		$piece6 = NULL;
		$piece7 = NULL;
		$piece8 = NULL;
		$piece9 = NULL;
		$piece10 = NULL;

		for ($i = 0; $i < count($rubricDescArray); $i++)
		{
			$pieceNumber = "piece" . ($i + 1);

			if (isset($_POST["$pieceNumber"]) && !empty($_POST["$pieceNumber"]))
			{
				${'piece' . ($i + 1)} = $_POST["$pieceNumber"];
			}
			else
			{
				$_SESSION['fail'] = 'Rubric Grade could not be added/modified, data not sent';
			   	header('Location: ../../pages/assignRubricGrades');
			}
		}

		if ($stmt = $mysqli->prepare("SELECT gradeRubricID FROM gradedRubrics WHERE rubricID = ? AND studentID = ?"))
		{
			$stmt->bind_param('ii', $rubricID, $studentID);

			if ($stmt->execute())
			{
				if ($stmt->num_rows > 0)
				{
					if ($stmt2 = $mysqli->prepare("UPDATE gradedRubrics SET piece1 = ?, piece2 = ?, piece3 = ?, piece4 = ?, piece5 = ?, piece6 = ?, piece7 = ?, piece8 = ?, piece9 = ?, piece10 = ? WHERE studentID = ? AND rubricID = ?"))
					{
						$stmt2->bind_param('ddddddddddii', $piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10, $studentID, $rubricID);

						$stmt2->execute();

						$_SESSION['success'] = "Rubric Grade Updated";
			   			header('Location: ../../pages/assignRubricGrades');
					}
				}
				else
				{
					if ($stmt2 = $mysqli->prepare("INSERT INTO gradedRubrics (rubricID, studentID, piece1, piece2, piece3, piece4, piece5, piece6, piece7, piece8, piece9, piece10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
					{
						$stmt2->bind_param('iidddddddddd', $rubricID, $studentID, $piece1, $piece2, $piece3, $piece4, $piece5, $piece6, $piece7, $piece8, $piece9, $piece10);

						$stmt2->execute();

						$_SESSION['success'] = "Rubric Grade Added";
			   			header('Location: ../../pages/assignRubricGrades');
					}
				}	
			}
		}
	}
	else
	{
		$_SESSION['fail'] = 'Rubric Grade could not be added/modified, data not sent';
   		header('Location: ../../pages/assignRubricGrades');
	}
}

?>
