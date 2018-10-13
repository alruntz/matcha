<?php

if (isset($_GET['id_user']) && isset($_GET['type']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	if  ($_GET['type'] == "get")
	{
		$req = $db->prepare("SELECT * FROM locations WHERE id_user = ?");
		if ($req->execute(array(htmlspecialchars($_GET['id_user']))))
		{
			$location = $req->fetch(PDO::FETCH_OBJ);
			if ($location != null)
			{
				echo json_encode($location);
			}
			else
				echo "null";
		}
		else
			echo "null";
	}
	else if ($_GET['type'] == "set" && isset($_GET['location']))
	{
		$req = $db->prepare("SELECT * FROM locations WHERE id_user = ?");
		$req->execute(array($_GET['id_user']));
		if ($req->fetch() == null)
		{
			$req = $db->prepare("INSERT INTO locations (id_user, location, last_date) VALUES (?, ?, NOW())");
			$req->execute(array(htmlspecialchars($_GET['id_user']), htmlspecialchars($_GET['location'])));
		}
		else
		{
			$req = $db->prepare("UPDATE locations SET location = ?, id_user = ?, last_date = NOW()");
			$req->execute(array(htmlspecialchars($_GET['location']), htmlspecialchars($_GET['id_user'])));
		}
	}
	//echo "pas good";
}

?>