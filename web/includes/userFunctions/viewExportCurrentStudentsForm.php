<?php

if (isset($_POST['exportChoice']))
{
	$_SESSION['exportChoice'] = $_POST['exportChoice'];
}

if (isset($_POST['changeChoice']))
{
	unset($_SESSION['exportChoice']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true))
    {
        viewExportCurrentStudentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}


function viewExportCurrentStudentForm($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Export Current Student Data");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#exportData" data-toggle="tab">Export Current Student Data</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="exportData">
                                    <br>
            ';

							if (!isset($_SESSION['exportChoice']))
							{
								getExportChoiceForm();
							}
							else
							{
								switch ($_SESSION['exportChoice'])
								{
									case "1":
										chooseCurrentStudentForm($mysqli);
									// Single Student
									break;

									case "2":
										chooseClassForm();										
									// Single Class
									break;

									case "3":
										chooseClassSingleFileForm();										
									// Single Class
									break;

									case "4":
										chooseAllCurrentForm();
									// All current Students
									break;

									case "5":
										chooseAllCurrentSingleFileForm();
									// All current Students
									break;

									default:
										// Invalid Choice
										unset($_SESSION['exportChoice']);
									break;
								}
							}

					if (isset($_SESSION['exportChoice']))
                    {   
                        echo "<br>";
                        generateFormStart("", "post"); 
                            generateFormButton("changeChoice", "Change Choice");
                        generateFormEnd();
                    }   
        echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
';

}

function chooseAllCurrentForm()
{
	generateFormStart("../includes/userFunctions/exportCurrentStudents", "post");
		generateFormHiddenInput("choiceOption", "4");
    	generateFormButton("selectCourse", "Confirm Export all Current Students");
	generateFormEnd();
}

function chooseAllCurrentSingleFileForm()
{
	generateFormStart("../includes/userFunctions/exportCurrentStudents", "post");
		generateFormHiddenInput("choiceOption", "5");
    	generateFormButton("selectCourse", "Confirm Export all Current Students");
	generateFormEnd();
}

function chooseClassSingleFileForm()
{
	generateFormStart("../includes/userFunctions/exportCurrentStudents", "post");
		generateFormHiddenInput("choiceOption", "3");
        generateFormStartSelectDiv("Select Course", "courseID");
        	generateFormOption("1", "BIOL 398/399");
        	generateFormOption("2", "BIOL 499");
        generateFormEndSelectDiv();
    	generateFormButton("selectCourse", "Select Course");
	generateFormEnd();
}

function chooseClassForm()
{
	generateFormStart("../includes/userFunctions/exportCurrentStudents", "post");
		generateFormHiddenInput("choiceOption", "2");
        generateFormStartSelectDiv("Select Course", "courseID");
        	generateFormOption("1", "BIOL 398/399");
        	generateFormOption("2", "BIOL 499");
        generateFormEndSelectDiv();
    	generateFormButton("selectCourse", "Select Course");
	generateFormEnd();
}

function chooseCurrentStudentForm($mysqli)
{
    echo "<h4>Select Student:</h4>";
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

            generateFormStart("../includes/userFunctions/exportCurrentStudents", "post");
				generateFormHiddenInput("choiceOption", "1");
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

function getExportChoiceForm()
{
	generateFormStart("", "post");
        generateFormStartSelectDiv("Select what to Export", "exportChoice");
			generateFormOption("1", "Export Data for Single Current Student");
			generateFormOption("2", "Export Data for Single Class");
			generateFormOption("3", "Export Data for Single Class to Single File");
			generateFormOption("4", "Export Data for All Current Students");
			generateFormOption("5", "Export Data for All Current Students to Single File");
		generateFormEndSelectDiv();
        generateFormButton("selectChoiceButton", "Select Choice");
    generateFormEnd();
}

?>
