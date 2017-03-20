<?php

function getFacultyName($userID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE isFaculty AND userID = ?"))
	{
		$stmt->bind_param('i', $userID);

		if ($stmt->execute())
		{
			$stmt->bind_result($userFirstName, $userLastName);

			$stmt->store_result();

			$stmt->fetch();

			return "$userLastName $userFirstName";
		}
	}
}

?>
