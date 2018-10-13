<?php

if (isset($_GET['user_id']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";

	$req = $db->prepare("UPDATE users SET last_connexion = NOW() WHERE id = ?");
	$req->execute(array(htmlspecialchars($_GET['user_id'])));
}

?>