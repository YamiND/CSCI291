<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateCourseTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateCourseTable($mysqli)
{
	echo '
        <!-- DataTables CSS -->
        <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

        <!-- DataTables JavaScript -->
        <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
        <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
        ';
                getCourseTable($mysqli);


        echo '      
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    ';
}

function getCourseTable($mysqli)
{
    echo '
        <div class="panel panel-default">
                        <div class="panel-heading" id="Courses"> 
                        	Courses
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="courses">
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Semester</th>
										<th>Year</th>
                                    </tr>
                                </thead>
                                <tbody>
        ';
        
	if ($stmt = $mysqli->prepare("SELECT courseName, courseSemester, courseYear FROM courses"))
	{
		if($stmt->execute())
		{
			$stmt->bind_result($courseName, $courseSemester, $courseYear);
			$stmt->store_result();

			while($stmt->fetch())
			{
   	     		echo '
					<tr class="gradeA">
  						<td>' . $courseName . '</td>
  						<td>' . $courseSemester . '</td>
  						<td>' . $courseYear . '</td>
            		</tr>
					';
			}			
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

    echo ' 
                   </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->

    <script>
    $(document).ready(function() {
        $(\'#courses\').DataTable({
            responsive: true
        });
    });
    </script>
    ';
}

?>
