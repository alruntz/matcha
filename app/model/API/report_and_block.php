<?php
include "../../../config/database.php";
include "../functions/connexion_db.php";
include "../functions/user_functions.php";

session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && isset($_GET['type']) && isset($_GET['target']))
{
	if ($_GET['type'] == "report" && isset($_POST['optionsRadios']))
	{
		send_mail($db, "runtz.a@gmail.com", "L'utilisateur n." . get_my_user_id($db) . " a report l'utilisateur n." . $_GET['target'] . " type de report : " . $_POST['optionsRadios'], "REPORT");
	}
	else if ($_GET['type'] == "block")
	{
		$my_id = get_my_user_id($db);
		$my_user_infos = get_user_infos_obj($db, $my_id);
		$my_user = get_user_obj($db, $my_id);
		$users_blocked = $my_user_infos->users_blocked;
		$tab = explode(';', $users_blocked);
		$b = false;
		if (count($tab) > 0)
		{
			if (!in_array($_GET['target'], $tab))
			{
				array_push($tab, $_GET['target']);
				$b = true;
			}
			else
				unset($tab[array_search($_GET['target'], $tab)]);
			$str = implode(';', $tab);
		}
		else
		{
			$str = $_GET['target'];
			$b = true;
		}
		$req = $db->prepare('UPDATE user_infos SET users_blocked = ? WHERE id_user = ?');
		$req->execute(array($str, get_my_user_id($db)));
		if ($b == true)
		{
			$tabFriends = explode(';', $my_user_infos->friends);
			if (get_if_liked($db, $my_user, $_GET['target']) || in_array($_GET['target'], $tabFriends))
				remove_friend($db, $my_user, $my_user_infos, $_GET['target']);
		}
	}
	header('location: ../../../index.php?page=user_infos&user_id=' . $_GET['target']);
}
else
	echo "Tu fous quoi ici ??";

?>