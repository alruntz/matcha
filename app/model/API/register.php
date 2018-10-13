<?php
include "../../../config/database.php";
include "../functions/connexion_db.php";
include "../functions/user_functions.php";

//echo "yolo";
if (isset($_POST['mail']) && isset($_POST['last_name']) && isset($_POST['first_name'])
	&& isset($_POST['password']) && isset($_POST['confirm_password']))
{
//	echo "ok";
	if (register_valid($_POST['mail'], $_POST['first_name'], $_POST['last_name'], $_POST['password'],
		$_POST['confirm_password'], $db))
	{
//		echo "aa";
		register($_POST['mail'], $_POST['first_name'], $_POST['last_name'], $_POST['password'], $db);
		header('location: ../../../index.php');
	}
	else
	{
//		echo "oo";
		header('location: ../../../index.php?page=register&error=1');
	}
}
$db = null;
?>
