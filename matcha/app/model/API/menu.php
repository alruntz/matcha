<?php

header('Access-Control-Allow-Origin: *');

include "../../../config/database.php";
include "../../model/functions/connexion_db.php";
include "../../model/functions/user_functions.php";
include "../../model/functions/menu.php";
include "../../model/functions/chat.php";

if (isset($_GET['action']) && isset($_GET['id_user']))
{
	if ($_GET['action'] == "show_notif_chat")
	{
		show_notifications_chat($db, $_GET['id_user'],  get_messages_no_view($db, $_GET['id_user']));
	}
	else if ($_GET['action'] == "nb_notif_chat")
	{
		echo (count(get_messages_no_view($db, $_GET['id_user'])));
	}
	else if ($_GET['action'] == "nb_notif_view")
	{
		echo (count(get_views($db, $_GET['id_user'], 0)));
	}
	else if ($_GET['action'] == "show_notif_view")
	{
		show_notifications_view($db, $_GET['id_user'],  get_views($db, $_GET['id_user'], 0));
	}
	else if ($_GET['action'] == "show_notif_friendsreq")
	{
		show_notifications_friendsreq($db, $_GET['id_user'],  get_tfriend_requests($db, $_GET['id_user']));
	}
	else if ($_GET['action'] == "nb_notif_friendsreq")
	{
		$likes = get_tnotif_like($db, $_GET['id_user']);
		$likesCount = 0;
		foreach($likes as $val)
			if ($val->view == 0)
				$likesCount++;
		echo ($likesCount);
	}
}

if ($_GET['action'] == 'view_view' && isset($_GET['id']))
{
	$req = $db->prepare("UPDATE profile_view SET view = '1' WHERE id = ?");
	$req->execute(array($_GET['id']));
}
else if ($_GET['action'] == 'view_like' && isset($_GET['id']) && isset($_GET['id']))
{
	/*$req = $db->prepare("UPDATE friend_request SET view = '1' WHERE id = ?");
	$req->execute(array($_GET['id']));*/
	$req = $db->prepare("DELETE FROM notif_like WHERE id = ?");
	$req->execute(array($_GET['id']));
}

?>