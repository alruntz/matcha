<?php
include "../../../config/database.php";
include "../functions/connexion_db.php";
include "../functions/user_functions.php";

session_start();

if (isset($_POST['login']))
{
	if (user_exist($_POST['login'], $db))
	{
		$key = rand(0, 9999999);
		$req = $db->prepare('UPDATE users SET token = ? WHERE mail = ?');
		$req->execute(array(htmlspecialchars($key), htmlspecialchars($_POST['login'])));
		send_mail_forgot_password($_POST['login'], $db, $key);
		header('location: ../../../index.php');
	}
	else
		header('location: ../../../index.php?page=connexion&type=pass_forbidden&error=1');
}
else if (isset($_POST['password']) && isset($_POST['password_conf']))
{
	if ($_POST['password'] == $_POST['password_conf'] && preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password']))
	{
		if (isset($_POST['key']))
		{
			if (($user = get_user_obj_by_key($db, $_POST['key'])) != null)
			{
				$req = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
				$req->execute(array(htmlspecialchars($_POST['password']), $user->id));
			}
		}
		else if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
		{
			$id = get_my_user_id($db);
			$req = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
			$req->execute(array(htmlspecialchars($_POST['password']), $id));
		}
	}
	else
	{
		if (isset($_POST['key']))
			header('location: ../../../index.php?page=connexion&type=new_password&key=' . $_POST['key'] . '&error=1');
		else
			header('location: ../../../index.php?page=connexion&type=new_password&error=1');
	}
	header('location: ../../../index.php?page=index');
}
?>