<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true && (isAdmin($mysqli) || isFaculty($mysqli)))
    {
        viewCreateBulkStudentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewCreateBulkStudentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
			displayPanelHeading("Create Student (CSV)");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createStudents" data-toggle="tab">Create Students in Bulk</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="createStudents">
        ';
	    						getUploadForm();      
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

function getUploadForm()
{
    generateFormStart("../includes/userFunctions/createBulkStudents", "post", "multipart/form-data"); 
	echo '<h4>Upload Student CSV</h4>';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Students");
    generateFormEnd();


		echo "<br>";
	echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
	<h5>The format for the Student's CSV should be this: </h5>

	<p>&nbsp;&nbsp;&nbsp;&nbsp;Student First Name,Student Last Name,Student Email,Course Number</p>
	<h5>Course Number should be one of two numbers</h5>

	<p>&nbsp;&nbsp;&nbsp;&nbsp;1: BIOL 398/399</p>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;2: BIOL 499</p>
	<p>So if the student is in BIOL 499, please put a 2 at the end of the row</p>
	<p>If the student is in BIOL 398 or 399, please put a 1 at the end of the row</p>

	<p>A sample CSV is listed below: </p>
	<h5>&nbsp;&nbsp;&nbsp;&nbsp;Linus,Torvalds,ltorvalds@lssu.edu,1</h5> 
	<h5>&nbsp;&nbsp;&nbsp;&nbsp;Steve,Ballmer,sballmer@lssu.edu,2</h5> 
";
}

?>
