<?php 

date_default_timezone_set('UTC');

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateStudentsTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateStudentsTable($mysqli)
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
                getStudentsTable($mysqli);


        echo '      
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    ';
}

function getStudentsTable($mysqli)
{
    echo '
        <div class="panel panel-default">
                        <div class="panel-heading" id="Courses"> 
                        	Students
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="students">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
        ';


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

	if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName, studentEmail, studentSemester FROM students WHERE studentSemester = ?"))
	{
		$stmt->bind_param('s', $semester);

		if($stmt->execute())
		{
			$stmt->bind_result($studentFirstName, $studentLastName, $studentEmail, $studentSemester);
			$stmt->store_result();

			while($stmt->fetch())
			{
   	     		echo '
					<tr class="gradeA">
  						<td>' . $studentFirstName . '</td>
  						<td>' . $studentLastName . '</td>
  						<td>' . $studentEmail . '</td>
  						<td>' . $studentSemester . '</td>
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
        $(\'#students\').DataTable({
            responsive: true
        });
    });
    </script>
    ';
}

?>
