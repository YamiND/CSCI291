<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateRubricTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateRubricTable($mysqli)
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
                getRubricTable($mysqli);


        echo '      
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    ';
}

function getRubricTable($mysqli)
{
    echo '
        <div class="panel panel-default">
                        <div class="panel-heading" id="Courses"> 
                        	Courses
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="rubrics">
                                <thead>
                                    <tr>
                                        <th>Rubric Name</th>
                                        <th>Course Name</th>
                                    </tr>
                                </thead>
                                <tbody>
        ';
        
	if ($stmt = $mysqli->prepare("SELECT rubricName, courseID FROM rubrics"))
	{
		if($stmt->execute())
		{
			$stmt->bind_result($rubricName, $courseID);
			$stmt->store_result();

			while($stmt->fetch())
			{
   	     		echo '
					<tr class="gradeA">
  						<td>' . $rubricName . '</td>
  						<td>' . getCourseName($courseID, $mysqli) . '</td>
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
        $(\'#rubrics\').DataTable({
            responsive: true
        });
    });
    </script>
    ';
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
