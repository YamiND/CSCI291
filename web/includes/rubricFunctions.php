<?php

function getRubricDescriptions($rubricID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT desc1, desc2, desc3, desc4, desc5, desc6, desc7, desc8, desc9, desc10 FROM rubricDescriptions WHERE rubricID = ?"))
	{
		$stmt->bind_param('i', $rubricID);

		if ($stmt->execute())
		{
			$stmt->bind_result($desc1, $desc2, $desc3, $desc4, $desc5, $desc6, $desc7, $desc8, $desc9, $desc10);
			$stmt->store_result();

			$stmt->fetch();

			return array("$desc1","$desc2","$desc3","$desc4","$desc5","$desc6","$desc7","$desc8","$desc9","$desc10");
		}
	}
}

function getRubricGradeExists($rubricID, $studentID, $facultyID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT gradeRubricID FROM gradedRubrics WHERE rubricID = ? AND studentID = ? AND facultyID = ? LIMIT 1"))
	{
		$stmt->bind_param('iii', $rubricID, $studentID, $facultyID);
		$stmt->execute();
		$stmt->bind_result($gradeRubricID);
		$stmt->store_result();
	
		return $stmt->num_rows;
	}
}

function getRubricName($rubricID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT rubricName FROM rubrics WHERE rubricID = ?"))
	{
		$stmt->bind_param('i', $rubricID);

		if ($stmt->execute())
		{
			$stmt->bind_result($rubricName);
			$stmt->store_result();

			$stmt->fetch();

			return "$rubricName";
		}
	}
}

function getRubricGrade($gradedRubricID, $studentID, $pieceNumber, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT {$pieceNumber} FROM gradedRubrics WHERE gradeRubricID = ? AND studentID = ?"))
	{
		$stmt->bind_param('ii', $gradedRubricID, $studentID);

		if ($stmt->execute())
		{
			$stmt->bind_result($pieceNumber);
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
				$stmt->fetch();

				return "$pieceNumber";
			}
			else
			{
				return "No rubrics graded for student";
			}
		}
		else
		{
			return "No rubrics graded for student";
		}
	}
	else
	{
		return "No rubrics graded for student";
	}
}

function getRPPByPoint($rubricID, $pointID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT {$pointID} FROM rubricPointsPossible WHERE rubricID = ?"))
	{
		$stmt->bind_param('i', $rubricID);

		if ($stmt->execute())
		{
			$stmt->bind_result($pointsPossible);
			$stmt->store_result();

			$stmt->fetch();

			return "$pointsPossible";
		}
	}
}

?>
