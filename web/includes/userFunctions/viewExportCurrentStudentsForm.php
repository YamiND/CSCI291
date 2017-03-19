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
									// Single Class
									break;

									case "3":
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
			generateFormOption("1", "Export data for single current student");
			generateFormOption("2", "Export data for single class");
			generateFormOption("3", "Export data for all current students");
		generateFormEndSelectDiv();
        generateFormButton("selectChoiceButton", "Select Choice");
    generateFormEnd();
}

?>
