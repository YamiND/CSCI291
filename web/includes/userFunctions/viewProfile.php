<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true)
    {
        viewProfile($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewProfile($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("User Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#viewProfile" data-toggle="tab">My Profile</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="myProfile">
                                <br>
        ';
                                getUserProfile($_SESSION['userID'], $mysqli);
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

function getUserProfile($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName, userEmail FROM users WHERE userID = ?"))
    {   
        // Query database for announcement details to be modified
        $stmt->bind_param('i', $userID);

        $stmt->execute();

        $stmt->bind_result($userFirstName, $userLastName, $userEmail);
        
        $stmt->store_result();
        
        while ($stmt->fetch())
        {
            generateFormStart();
                generateFormInputDiv("First Name", "text", "userFirstName", $userFirstName, "disabled");
                generateFormInputDiv("Last Name", "text", "userLastName", $userLastName, "disabled");
                generateFormInputDiv("Email", "email", "userEmail", $userEmail, "disabled");
            generateFormEnd();
        }
    }
}

?>
