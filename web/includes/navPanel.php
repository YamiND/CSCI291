<?php
include_once '../includes/dbConnect.php';
include_once '../includes/functions.php';
include_once '../includes/panelSessionMessages.php';
include_once '../includes/formTemplate.php';
include_once '../includes/rubricFunctions.php';
include_once '../includes/studentFunctions.php';
include_once '../includes/facultyFunctions.php';

sec_session_start();

if (login_check($mysqli) == true)
{

    //TODO: Update the aliasSystem link to something more appropriate
    //TODO: Update the email link to something more appropriate
    echo '

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">' . aliasSystem . '</a>
                </div>
                <!-- /.navbar-header -->
                <ul class="nav navbar-top-links navbar-right">
    				<a href="settings"> ' . htmlentities($_SESSION['userEmail']) . '</a>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="settings"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="../includes/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
            				<li>
				                <a href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard <span class="fa arrow"></span></a>
    							<ul class="nav nav-second-level">
				    				<li>
    									<a href="dashboard">My Dashboard</a>
    								</li>
				    			</ul>	
           					 </li>
    ';

	if (isAdmin($mysqli))
    {
		// We will show administrative links here
		// I should fix the adminPasswordReset

    echo '
            <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> Users <span class="fa arrow"></span></a>
    			<ul class="nav nav-second-level">
    				<li>
    					<a href="createBulkUser">Create User (CSV Upload)</a>
    				</li>
    				<li>
    					<a href="adminPasswordReset">Reset User Password</a>
    				</li>
    				<li>
    					<a href="viewUserTables">View All Users</a>
    				</li>
    			</ul>	
            </li>
            <li>
                <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> View Tables <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="viewAllCourses">View all Courses</a>
                    </li>
                    <li>
                        <a href="viewAllRubrics">View all Rubrics</a>
                    </li>
                    <li>
                        <a href="viewAllStudents">View all Students</a>
                    </li>
                    <li>
                        <a href="viewCurrentStudents">View Current Students</a>
                    </li>
                </ul>   
            </li>
        ';
    }
	if (isFaculty($mysqli))
    {
	// All links that are not administrative should go here
    echo '
            <li>
                <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Current Semester Students <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="createBulkStudent">Add Student (CSV Upload)</a>
                    </li>
                    <li>
                        <a href="viewRubricGrades">View Rubric Grades</a>
                    </li>
                    <li>
                        <a href="assignRubricGrades">Assign Rubric Grades</a>
                    </li>
                    <li>
                        <a href="viewExportCurrentStudents">Export Current Student Grades</a>
                    </li>
                </ul>   
            </li>
            <li>
                <a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Previous Semester Students <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="viewOldRubricGrades">View Old Rubric Grades</a>
                    </li>
                    <li>
                        <a href="viewExportAllStudents">Export All Student Grades</a>
                    </li>
                </ul>   
            </li>
    	';
    }

	echo '
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
	';
}
else
{
   	$url = "login"; 
	header("Location:$url");
}
?>
