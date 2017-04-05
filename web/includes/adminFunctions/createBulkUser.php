<?php

include_once '../dbConnect.php';
include_once '../functions.php';

ini_set("auto_detect_line_endings", true);

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	// If our login succeeds and we're an admin, process the csv
	parseCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creation Failed, invalid permissions';
   	header('Location: ../../pages/createBulkUser');
}

function parseCSV($mysqli)
{
	$filename = "usersCreated.csv";
	$fp = fopen('php://output', 'w');

	$_SESSION['success'] = 'User Accounts Created';
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	if($_FILES['csvFile']['error'] == 0)
	{
    	$name = $_FILES['csvFile']['name'];
    	$ext = strtolower(end(explode('.', $_FILES['csvFile']['name'])));
	    $type = $_FILES['csvFile']['type'];
	    $tmpName = $_FILES['csvFile']['tmp_name'];

    	// check the file is a csv
    	if($ext === 'csv')
		{
			$userCSV = array_map('str_getcsv', file($tmpName));
			foreach($userCSV as $i => $data)
			{

				// The CSV Format should be: Email,First Name, Last Name, isAdmin (true or false)
				// You don't need to specify the isAdmin though, it should default to false

				// Example:
				// tpostma@lssu.edu,Tyler,Postma,true
				// faculty@lssu.edu,Faculty,Member,false
				// faculty2@lssu.edu,Facult2,Member2
			
				$userEmail = $userCSV[$i][0];
				$userFirstName  = $userCSV[$i][1];
				$userLastName = $userCSV[$i][2];
				$isAdmin = $userCSV[$i][3];

				$value = createUserAccount($userEmail, $userFirstName, $userLastName, $isAdmin, $mysqli);

				$row = explode(',', $value);
				fputcsv($fp, $row);
			}
    	}
		else
		{
   			$_SESSION['fail'] = 'Account Creation Failed, file uploaded does not end in .csv';
   			header('Location: ../../pages/createBulkUser');
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Account Creation Failed, file upload error';
   		header('Location: ../../pages/createBulkUser');
	}
}

function createUserAccount($userEmail, $userFirstName, $userLastName, $isAdmin, $mysqli)
{
	$password = randomString();	
	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	$hashedPassword = hash("sha512", $password . $randomSalt);

	if ($isAdmin == 1)
	{
		$isAdmin = true;
	}
	else
	{
		$isAdmin = false;
	}

	$isFaculty = true;
	
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users where userEmail = ?"))
	{
		$stmt->bind_param('s', $userEmail);

		if($stmt->execute())
		{
			$stmt->store_result();

			if ($stmt->num_rows < 1)
			{
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, isAdmin, isFaculty, userSalt) VALUES (?, ?, ?, ?, ?, ?, ?)"))
				{
					$stmt->bind_param('ssssiis', $userEmail, $hashedPassword, $userFirstName, $userLastName, $isAdmin, $isFaculty, $randomSalt); 
					if($stmt->execute())    // Execute the prepared query.
					{
						return "$userEmail, $password";
					}
				}
			}
		}
		else
		{
   			$_SESSION['fail'] = 'Account Creation Failed, select failure';
   			header('Location: ../../pages/createBulkUser');
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Account Creation Failed, database select error';
   		header('Location: ../../pages/createBulkUser');
	}
}

function randomString($length = 8) 
{
	// This function is used to generate a random password
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) 
	{
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

?>
