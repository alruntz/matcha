<?php
if (isset($_GET['id_request']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	include "../../model/functions/user_functions.php";
	session_start();
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
	{
		$req = $db->prepare('DELETE FROM friend_request WHERE id = ?');
		$req->execute(array(htmlspecialchars($_GET['id_request'])));
	}
	header('location: ../../../index.php');
}
?>