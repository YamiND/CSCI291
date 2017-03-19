<?php 

if (isset($_POST['schoolSemester']) && !empty($_POST['schoolSemester']))
{
	$_SESSION['schoolSemester'] = $_POST['schoolSemester'];
}

if (isset($_POST['studentID']) && !empty($_POST['studentID']))
{
	$_SESSION['studentID'] = $_POST['studentID'];
}

if (isset($_POST['changeStudent']))
{
	unset($_SESSION['studentID']);
	unset($_SESSION['courseID']);
}

if (isset($_POST['changeSemester']))
{
	unset($_SESSION['studentID']);
	unset($_SESSION['courseID']);
	unset($_SESSION['schoolSemester']);

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

					if (!isset($_SESSION['schoolSemester']))
					{ 
						if (isset($_SESSION['studentID']))
						{
							unset($_SESSION['studentID']);
						}

						echo "<h4>Select Semester</h4>";
						chooseSchoolSemesterForm($mysqli);
					}
					else if (!isset($_SESSION['studentID']))
					{
						echo "<br>";
						echo "<h4>Select Student</h4>";
                        chooseCurrentStudentForm($_SESSION['schoolSemester'], $mysqli);
					}
					else
					{
						$_SESSION['courseID'] = getCourseID($_SESSION['studentID'], $mysqli);
						getRubricGrades($_SESSION['studentID'], $_SESSION['courseID'], $mysqli);
					}

                    if (isset($_SESSION['studentID']))
                    {
                        echo "<br>";
                        generateFormStart("", "post"); 
                            generateFormButton("changeStudent", "Change Student");
                        generateFormEnd();
                        echo "<br>";
                    }

                    if (isset($_SESSION['schoolSemester']))
                    {
                        echo "<br>";
                        generateFormStart("", "post"); 
                            generateFormButton("changeSemester", "Change Semester");
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

function chooseSchoolSemesterForm($mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT DISTINCT(studentSemester) FROM students"))
	{
		if ($stmt->execute())
		{
			$stmt->bind_result($studentSemester);
			$stmt->store_result();

			generateFormStart("", "post");
	      		generateFormStartSelectDiv(NULL, "schoolSemester");
					while($stmt->fetch())
					{
						generateFormOption($studentSemester, "$studentSemester");
					}			
					generateFormEndSelectDiv();
				 generateFormButton("selectSchoolSemester", "Select Semester");
	       	 generateFormEnd();
		}

	}
}

function getRubricGrades($studentID, $courseID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT rubricID FROM rubrics WHERE courseID = ?"))
	{
		$stmt->bind_param('i', $courseID);

		if ($stmt->execute())
		{
			$stmt->bind_result($rubricID);
			$stmt->store_result();
			
			echo "<h3>Student was in Class: " . getCourseName($courseID, $mysqli) . "</h3>";
			echo "<h3>Rubric Grades for: " . getStudentName($studentID, $mysqli) . "</h3>";
			echo "<br>";

			while ($stmt->fetch())
			{
				$rubricName = getRubricName($rubricID, $mysqli);
				echo "<h4>Rubric Name: $rubricName </h4>";
				echo "<br>";	

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
								$totalGrade = 0;
								$totalPointsPossible = 0;
								
                                echo "<h4>Graded By: " . getFacultyName($facultyID, $mysqli) . "</h4>";
	
								$rubricDescArray = getRubricDescriptions($rubricID, $mysqli);

								echo "<ul><dl>";

								for ($i = 0; $i < count($rubricDescArray); $i++)
								{
									if ($rubricDescArray[$i] != NULL)
									{
										$pieceNumber = "piece" . ($i + 1);
										$pointID = "point" . ($i + 1);

										$totalGrade += getRubricGrade($rubricID, $studentID, $pieceNumber, $mysqli);
										$totalPointsPossible += getRPPByPoint($rubricID, $pointID, $mysqli);
										echo "<dt>$rubricDescArray[$i]</dt>";
										
										echo "<dd>&nbsp&nbsp&nbsp&nbsp" . getRubricGrade($rubricID, $studentID, $pieceNumber, $mysqli) . " / " . getRPPByPoint($rubricID, $pointID, $mysqli) . "</dd>";
									}
								}
								echo "</dl></ul>";
								generateFormTextAreaDiv("Faculty Feedback",NULL,"10",$facultyFeedback,"disabled");
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

function chooseCurrentStudentForm($schoolSemester, $mysqli)
{
	echo "<br>";

	if ($stmt = $mysqli->prepare("SELECT studentID, studentFirstName, studentLastName FROM students WHERE studentSemester = ?"))
	{
		$stmt->bind_param('s', $schoolSemester);

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

?>
