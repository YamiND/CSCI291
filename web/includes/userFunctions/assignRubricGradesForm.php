<?php 

if (isset($_POST['studentID']) && !empty($_POST['studentID']))
{
	$_SESSION['studentID'] = $_POST['studentID'];
}

if (isset($_POST['rubricID']) && !empty($_POST['rubricID']))
{
	$_SESSION['rubricID'] = $_POST['rubricID'];
}

if (isset($_POST['changeStudent']))
{
	unset($_SESSION['studentID']);
	unset($_SESSION['courseID']);
	unset($_SESSION['rubricID']);
}

if (isset($_POST['changeRubric']))
{
	unset($_SESSION['rubricID']);
}

date_default_timezone_set('UTC');

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isFaculty($mysqli) || (isAdmin($mysqli))))
    {
		selectCurrentStudent($mysqli);			
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function selectCurrentStudent($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Rubric Grades");
    echo '
                        </div>
                        <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#viewGrades" data-toggle="tab">View Rubric Grades</a>
                            </li>
                        </ul>
                        <!-- /.panel-heading -->
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="addAssignment">
                                
        ';

					if (!isset($_SESSION['studentID']))
					{
                        chooseCurrentStudentForm($mysqli);
					}
					else if (isset($_SESSION['studentID']) && !isset($_SESSION['rubricID']))
					{
						$_SESSION['courseID'] = getCourseID($_SESSION['studentID'], $mysqli);
						echo "<h3>Student is in class: " . getCourseName($_SESSION['courseID'], $mysqli) . "</h3>";
						echo "<br>";
						echo "<h4>Select Rubric for Class</h4>";
						chooseRubricForm($_SESSION['courseID'], $mysqli);
					}
					else
					{
						assignStudentGrades($_SESSION['studentID'], $_SESSION['rubricID'], $mysqli);
					}

                    if (isset($_SESSION['rubricID']))
                    {
                        echo "<br>";
                        generateFormStart("", "post"); 
                            generateFormButton("changeRubric", "Change Rubric");
                        generateFormEnd();
//                        echo "<br>";
                    }

                    if (isset($_SESSION['studentID']))
                    {
						echo "<br>";
                        generateFormStart("", "post"); 
                            generateFormButton("changeStudent", "Change Student");
                        generateFormEnd();
                        echo "<br>";
                    }
    echo '              
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.panel -->
            </div>
        </div>
    ';
}

function assignStudentGrades($studentID, $rubricID, $mysqli)
{
	$rubricName = getRubricName($rubricID, $mysqli);

	echo "<h3>Rubric Grades for: " . getStudentName($studentID, $mysqli) . "</h3>";
	echo "<h3>Rubric Name: $rubricName</h3>";
	echo "<h3>Graded By: " . getFacultyName($_SESSION['userID'], $mysqli) . "</h3>";
	echo "<br>";

	generateFormStart("../includes/userFunctions/assignRubricGrades", "post");
		
		generateFormHiddenInput("studentID", $studentID);		
		generateFormHiddenInput("rubricID", $rubricID);		
		generateFormHiddenInput("facultyID", $_SESSION['userID']);		

		$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT gradeRubricID, facultyFeedback FROM gradedRubrics WHERE studentID = ? AND facultyID = ? AND rubricID = ?"))
		{
			$stmt->bind_param('iii', $studentID, $_SESSION['userID'], $rubricID);

			if ($stmt->execute())
			{
				$stmt->bind_result($gradeRubricID, $facultyFeedback);
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
					while ($stmt->fetch())
					{
						for ($i = 0; $i < count($rubricDescArray); $i++)
						{
							if ($rubricDescArray[$i] != NULL)
							{
								$pieceNumber = "piece" . ($i + 1);
								$pointID = "point" . ($i + 1);

								generateFormInputDiv($rubricDescArray[$i], "number", "$pieceNumber", getRubricGrade($gradeRubricID, $studentID, $pieceNumber, $mysqli), NULL, "0", getRPPByPoint($rubricID, $pointID, $mysqli), NULL);
								echo "<h5>$rubricDescArray[$i] Max Points Possible: " . getRPPByPoint($rubricID, $pointID, $mysqli) . "</h5>";

								echo "<br>";
							}
						}
						generateFormTextAreaDiv("Faculty Feedback","facultyFeedback","10",$facultyFeedback);
					}
				}
				else
				{
						for ($i = 0; $i < count($rubricDescArray); $i++)
						{
							if ($rubricDescArray[$i] != NULL)
							{
								$pieceNumber = "piece" . ($i + 1);
								$pointID = "point" . ($i + 1);

								generateFormInputDiv($rubricDescArray[$i], "number", "$pieceNumber", NULL, NULL, "0", getRPPByPoint($rubricID, $pointID, $mysqli), NULL);
								echo "<h5>$rubricDescArray[$i] Max Points Possible: " . getRPPByPoint($rubricID, $pointID, $mysqli) . "</h5>";

								echo "<br>";
							}
						}
						generateFormTextAreaDiv("Faculty Feedback","facultyFeedback","10");

				}
			}
		}
		
		generateFormButton("assignGrades", "Assign Scores");
	generateFormEnd();
}

function chooseRubricForm($courseID, $mysqli)
{
	generateFormStart("", "post");
		generateFormStartSelectDiv(NULL, "rubricID");
		
		if ($stmt = $mysqli->prepare("SELECT rubricID, rubricName FROM rubrics WHERE courseID = ?"))
		{
			$stmt->bind_param('i', $courseID);

			if ($stmt->execute())
			{
				$stmt->bind_result($rubricID, $rubricName);

				$stmt->store_result();

				while ($stmt->fetch())
				{
					generateFormOption($rubricID, "$rubricName");
				}
			}
		}

		generateFormEndSelectDiv();
		generateFormButton("selectRubric", "Select Rubric");
	generateFormEnd();
}

/*function getRubricGrades($studentID, $courseID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT rubricID FROM rubrics WHERE courseID = ?"))
	{
		$stmt->bind_param('i', $courseID);

		if ($stmt->execute())
		{
			$stmt->bind_result($rubricID);
			$stmt->store_result();

			echo "<h3>Rubric Grades for: " . getStudentName($studentID, $mysqli) . "</h3>";
			echo "<br>";

			while ($stmt->fetch())
			{
				$rubricName = getRubricName($rubricID, $mysqli);
				echo "<h4>Rubric Name: $rubricName </h4>";
				echo "<br>";
		

				if ($stmt2 = $mysqli->prepare("SELECT gradeRubricID FROM gradedRubrics WHERE rubricID = ? AND studentID = ? AND facultyID = ?"))
                {   
                    $stmt2->bind_param('iii', $rubricID, $studentID, $_SESSION['userID']);

                    if ($stmt2->execute())
                    {   
                        $stmt2->bind_result($gradeRubricID);
                        $stmt2->store_result();

                        if ($stmt2->num_rows > 0)
                        {   
                            while ($stmt2->fetch())
                            {   
								$totalGrade = 0;
								$totalPointsPossible = 0;

//								echo "<h4>Graded By: " . getFacultyName($_SESSION['userID'], $mysqli) . "</h4>";

								$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

								echo "<ul><dl>";

								for ($i = 0; $i < count($rubricDescArray); $i++)
								{
									if ($rubricDescArray[$i] != NULL)
									{
										$pieceNumber = "piece" . ($i + 1);
										$pointID = "point" . ($i + 1);

										$totalGrade += getRubricGrade($gradeRuubricID, $studentID, $pieceNumber, $mysqli);
										$totalPointsPossible += getRPPByPoint($rubricID, $pointID, $mysqli);
										echo "<dt>$rubricDescArray[$i]</dt>";
										
										echo "<dd>&nbsp&nbsp&nbsp&nbsp" . getRubricGrade($gradeRubricID, $studentID, $pieceNumber, $mysqli) . " / " . getRPPByPoint($rubricID, $pointID, $mysqli) . "</dd>";
									}
								}
								echo "</dl></ul>";
								echo "<h5>&nbsp&nbsp&nbsp&nbsp Total Score for Rubric: $totalGrade / $totalPointsPossible </h5>";
								echo "<h5>&nbsp&nbsp&nbsp&nbsp Total Percentage for Rubric: " . number_format(($totalGrade / $totalPointsPossible) * 100, 2, '.', '') . "% </h5>";
								echo "<br>";
							}
						}
					}
				}
			}
		}
	}
}*/

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

function chooseCurrentStudentForm($mysqli)
{
	echo "<br>";
						echo "<h4>Select Student</h4>";
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

	if ($stmt = $mysqli->prepare("SELECT studentID, studentFirstName, studentLastName FROM students WHERE studentSemester = ?"))
	{
		$stmt->bind_param('s', $semester);

		if($stmt->execute())
		{
			$stmt->bind_result($studentID, $studentFirstName, $studentLastName);
			$stmt->store_result();

			generateFormStart("", "post");
	        	generateFormStartSelectDiv(NULL, "studentID");
				while($stmt->fetch())
				{
					generateFormOption($studentID, "$studentLastName, $studentFirstName");
				}			
				generateFormEndSelectDiv();
			 generateFormButton("selectStudent", "Select Student");
	        generateFormEnd();
		}
		else
		{
			echo "Error occured <br>";
		}
	}
	else
	{
		return;
	}
}

?>
