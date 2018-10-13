<?php
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	include "../../model/functions/user_functions.php";
	include "../../model/functions/chat.php";

	if (isset($_POST['id_user_author']) && isset($_POST['id_user_target']) && isset($_POST['message']))
	{
		$tabFriends = explode(';', get_user_infos_obj($db, $_POST['id_user_author'])->friends);
		if (in_array($_POST['id_user_target'], $tabFriends))
		{
			$req = $db->prepare('INSERT INTO chat_message (id_user_author, id_user_target, message, date_send) VALUES (?, ?, ?, ?)');
			$req->execute(array(htmlspecialchars($_POST['id_user_author']), htmlspecialchars($_POST['id_user_target']), 
				htmlspecialchars($_POST['message']), date("Y-m-d H:i:s")));
			echo $db->lastInsertId();
		}
	}
	if (isset($_GET['id_user_author']) && isset($_GET['id_user_target']) && isset($_GET['last_message']))
	{
		show_messages_lasts($db, $_GET['id_user_author'], $_GET['id_user_target'], $_GET['last_message']);
	}
	if (isset($_GET['action']) && $_GET['action'] == 'show_friends' && isset($_GET['id_user_author']))
	{
		show_friends($db, $_GET['id_user_author']);
	}
	if (isset($_GET['action']) && $_GET['action'] == 'view_message' && isset($_GET['id']))
	{
		$req = $db->prepare("UPDATE chat_message SET view = '1' WHERE id = ?");
		$req->execute(array($_GET['id']));
	}

?>