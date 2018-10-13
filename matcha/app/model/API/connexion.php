<?php
if (isset($_POST['login']) && isset($_POST['password']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	include "../../model/functions/user_functions.php";
	session_start();
	if (connexion($_POST['login'], $_POST['password'], $db))
	{
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['password'] = $_POST['password'];
		$_SESSION['logged'] = true;
		header('location: ../../../index.php');
	}
	else
		echo "Utilisateur inconnu";
}
?>
