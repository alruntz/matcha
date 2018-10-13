<?php
if (isset($_GET['id_friend']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	include "../../model/functions/user_functions.php";
	session_start();
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && isset($_GET['id_friend']))
	{
		$my_user = get_user_obj($db, get_my_user_id($db));
		$my_user_infos = get_user_infos_obj($db, get_my_user_id($db));
		if (isset($_GET['type']) && $_GET['type'] == "add")
			add_friend_request($db, $my_user, $my_user_infos, $_GET['id_friend']);
		else if (isset($_GET['type']) && $_GET['type'] == "delete")
			remove_friend($db, $my_user, $my_user_infos, $_GET['id_friend']);
		header('location: ../../../index.php?page=user_infos&user_id=' . $_GET['id_friend']);
	}
	else
		header('location: ../../../index.php');
}
?>