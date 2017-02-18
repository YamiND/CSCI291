<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true && isAdmin($mysqli))
    {
        viewCreateBulkCourseForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewCreateBulkCourseForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Create Courses (CSV)");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createCourse" data-toggle="tab">Create Courses in Bulk</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="createCourse">
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
    generateFormStart("../includes/adminFunctions/createBulkCourse", "post", "multipart/form-data"); 
	echo '<h4>Upload Course CSV</h4>';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Courses");
    generateFormEnd();
}

?>
