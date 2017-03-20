<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true))
    {
        viewExportAllStudentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}


function viewExportAllStudentForm($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Export All Student Data");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#exportData" data-toggle="tab">Export All Student Data</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="exportData">
                                    <br>
            ';
				generateFormStart("../includes/userFunctions/exportAllStudents", "post");
					generateFormHiddenInput("confirmAll", "1");
			    	generateFormButton("confirmExport", "Confirm Export all Students");
				generateFormEnd();
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

?>
