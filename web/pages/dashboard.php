<?php
// Our constants
include("../includes/customizations.php");

echo '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>' . aliasSystem . ' - Dashboard</title>
            <!-- Header Information, CSS, and JS -->
            ';

            include("../includes/header.php");
    echo '
        </head>

        <body>

            <div id="wrapper">

        	<!-- Navigation Menu -->
        ';
                include('../includes/navPanel.php'); 
    echo '
                <div id="page-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Dashboard</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
            ';
	echo ' 
               <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-university fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">' . getNumCurrentStudents($mysqli) . ' </div>
                                    <div>Current Students</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewCurrentStudents">
                            <div class="panel-footer">
                                <span class="pull-left">View Students</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
	';
			
    echo '
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

        </body>

        </html>
    ';
?>
