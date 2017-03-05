<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true && isAdmin($mysqli))
    {
        viewCreateBulkUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewCreateBulkUserForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Create User (CSV)");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createUser" data-toggle="tab">Create Users in Bulk</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="createUser">
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
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload User CSV</h4>';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Users");
    generateFormEnd();

 echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the User's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;User Email,User First Name,User Last Name,Admin Status</p>
    <h5>Admin Status should be one of two numbers</h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;0: Is not an Admin, will be only a faculty member</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;1: Is an Admin and a faculty member</p>

    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds,1</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmer@lssu.edu,Steve,Ballmer,0</h5> 
";


}

?>
