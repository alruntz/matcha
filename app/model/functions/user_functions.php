<?php
function hash_password($passwd)
{
	return (hash("whirlpool", "Wdnfr_pasmi".$passwd));
}

function connexion($login, $password, $db)
{
	$password = hash_password($password);
	echo $password;
	$req = $db->prepare("SELECT login, password, validate FROM users");
	$req->execute();
	while ($val = $req->fetch(PDO::FETCH_OBJ))
	{
		if ($val->login == $login)
			echo "<br />" . $val->password;
		if ($val->login == $login && $val->password == $password && $val->validate == 1)
		{
			$req->closeCursor();
			return true;
		}
	}
	return false;
}

function format_mail_valid($mail)
{
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		return (0);
	return (1); 
}

function user_exist($mail, $db)
{
	$req = $db->prepare("SELECT * FROM users");
	$req->execute();
	while ($val = $req->fetch(PDO::FETCH_OBJ))
	{
		if ($mail == $val->login)
		{
			$req->closeCursor();
			return true;
		}
	}
	return false;
}

function user_visible($user_infos)
{
	if ($user_infos->first_name != "" && $user_infos->last_name != ""
		&& $user_infos->photo_profile != "" && $user_infos->country != ""
		&& $user_infos->town != "" && $user_infos->date_birth != "")
		return true;
	return false;
}

function register($mail, $first_name, $last_name, $password, $db)
{
	//$password = hash_password($password);
	$key_suscrib = rand(0, 9999999);
	$req = $db->prepare("INSERT INTO users (login, password, mail, token)
		VALUES (?, ?, ?, ?)");
	$req->execute(array(htmlspecialchars($mail), htmlspecialchars($password), htmlspecialchars($mail), $key_suscrib));
	$req = $db->prepare("INSERT INTO user_infos (first_name, last_name, id_user) VALUES (?, ?, (SELECT MAX(id) FROM users))");
	$req->execute(array(htmlspecialchars($first_name), htmlspecialchars($last_name)));
	send_mail_inscription($mail, $db, $key_suscrib);
}

function register_valid($mail, $first_name, $last_name, $pass1, $pass2, $db)
{
	if ($pass1 == $pass2 && strlen($first_name) >= 1 && strlen($last_name) >= 1 && strlen($pass1) >= 5
		&& !user_exist($mail, $db))
	{
			if (format_mail_valid($mail) && preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $pass1)
			&& strlen($mail) <= 75 && strlen($pass1) <= 25 && strlen($first_name) <= 50 && strlen($last_name) <= 50)
				return true;
	}
	return false;
}

function send_mail($db, $to, $message, $subject)
{
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $to))
		$passage_ligne = "\r\n";
	else
		$passage_ligne = "\n";
	$message .= $passage_ligne;

	mail($to, $subject, $message);
}

function key_exist($db, $key)
{
	$req = $db->prepare("SELECT * FROM users");
	$req->execute();
	while ($val = $req->fetch(PDO::FETCH_OBJ))
	{
		if ($key == $val->token)
		{
			$req->closeCursor();
			return true;
		}
	}
	return false;
}

function get_user_obj_by_key($db, $key)
{
	$req = $db->prepare("SELECT * FROM users");
	$req->execute();
	while ($val = $req->fetch(PDO::FETCH_OBJ))
	{
		if ($key == $val->token)
		{
			$req->closeCursor();
			return $val;
		}
	}
	return null;
}

function send_mail_inscription($to, $db, $key_suscrib)
{
	$link = "http://localhost:8008/matcha/index.php?key_suscr=$key_suscrib";
	send_mail($db, $to, "Bienvenue sur Matcha ! Clique sur ce  lien pour valider ton inscription: $link", "Matcha - Bienvenue !");
}

function send_mail_forgot_password($to, $db, $key)
{
	$link = "http://localhost:8008/matcha/index.php?page=connexion&type=new_password&key=$key";
	send_mail($db, $to, "Pour changer ton mot de passe: $link", "Matcha - Mot de passe oublie");
}

function get_key_register($db, $key_suscrib)
{
	$req = $db->prepare("SELECT * FROM users WHERE token = ?");
	if ($req->execute(array(htmlspecialchars($key_suscrib))))
	{
		$user = $req->fetch(PDO::FETCH_OBJ);
		$req = $db->prepare("UPDATE users SET validate = '1' WHERE id = '$user->id'");
		if ($req->execute())
		{
			$_SESSION['logged'] = true;
			$_SESSION['login'] = $user->login;
			header('location: index.php');
		}
	}
}

function get_my_user_id($db)
{
	$login = $_SESSION['login'];
	$req = $db->prepare("SELECT id FROM users WHERE login = ?");
	$req->execute(array($login));
	$ret = $req->fetch()[0];
	$req->closeCursor();
	return $ret;
}

function get_user_obj($db, $id)
{
	$req = $db->prepare("SELECT * FROM users WHERE id = ?");
	if ($req->execute(array(htmlspecialchars($id))))
	{
		$ret = $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $ret;
	}
	return null;
}

function get_user_infos_obj($db, $id)
{
	$req = $db->prepare("SELECT * FROM user_infos WHERE id_user = ?");
	if ($req->execute(array(htmlspecialchars($id))))
	{
		$ret = $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $ret;
	}
	return null;
}

function get_tags($user_infos)
{
	return (explode(";", $user_infos->tags));
}

function get_photos($db, $id_user)
{
	$req = $db->prepare("SELECT * FROM user_photos WHERE id_user = '$id_user'");
	if ($req->execute())
		return ($req);
	return (null);
}

function get_friends($db, $id_user)
{
	$ret = array();
	$req = $db->prepare("SELECT friends FROM user_infos WHERE id_user = '$id_user'");
	if ($req->execute())
	{
		$res = $req->fetch()[0];
		$req->closeCursor();
		if ($res != '')
		{
			$tab = explode(";", $res);
			if ($tab != null)
			{
				for ($i = 0; $i < count($tab); $i++)
					array_push($ret, get_user_obj($db, $tab[$i]));
				return ($ret);
			}
		}
			return (null);
	}
	return (null);	
}

function get_tfriend_requests($db, $target)
{
	$ret = array();
	$req = $db->prepare('SELECT * FROM friend_request WHERE id_target = ?');
	if ($req->execute(array(htmlspecialchars($target))))
	{
		while ($val = $req->fetch(PDO::FETCH_OBJ))
		{
			array_push($ret, $val);
		}
		$req->closeCursor();
	}
	return $ret;
}

function get_sfriend_requests($db, $sender)
{
	$ret = array();
	$req = $db->prepare('SELECT * FROM friend_request WHERE id_sender = ?');
	if ($req->execute(array(htmlspecialchars($sender))))
	{
		while ($val = $req->fetch(PDO::FETCH_OBJ))
		{
			array_push($ret, $val);
		}
		$req->closeCursor();
	}
	return $ret;
}

function get_if_liked($db, $my_user, $target)
{

	$friends = get_sfriend_requests($db, $my_user->id);
	foreach ($friends as $val) {
		if ($val->id_target == $target)
			return true;
	}
	return false;
}

function get_tnotif_like($db, $target)
{
	$ret = array();
	$req = $db->prepare('SELECT * FROM notif_like WHERE id_target = ?');
	if ($req->execute(array($target)))
	{
		while ($val = $req->fetch(PDO::FETCH_OBJ))
			array_push($ret, $val);
		$req->closeCursor();
	}
	return $ret;
}

function add_friend_request($db, $my_user, $my_user_infos, $id_target)
{
	/*security*/
	$friends_req = get_sfriend_requests($db, $my_user->id);
	for ($i = 0; $i < count($friends_req); $i++)
		if ($friends_req[$i]->id_target == $id_target)
			return;
	/*end security*/

	//Si la cible a envoyée une requete vers my_user, les deux deviennent amis, on supprime l'ancienne requete.
	$friends_req = get_tfriend_requests($db, $my_user->id);

	if (!is_blocked($db, $id_target, $my_user->id))
	{
		$req = $db->prepare('INSERT INTO notif_like (id_sender, id_target, value) VALUES (?, ?, ?)');
		$req->execute(array($my_user->id, htmlspecialchars($id_target), 1));
	}

	for ($i = 0; $i < count($friends_req); $i++)
	{
		if ($friends_req[$i]->id_sender == $id_target)
		{
			$target = get_user_obj($db, $id_target);
			$target_infos = get_user_infos_obj($db, $id_target);
			add_friend($db, $my_user, $my_user_infos, $id_target);
			add_friend($db, $target, $target_infos, $my_user->id);
			$req = $db->prepare('DELETE FROM friend_request WHERE id_sender = ? AND id_target = ?');
			$req->execute(array(htmlspecialchars($id_target), $my_user->id));
			return;
		}
	}

	$req = $db->prepare('INSERT INTO friend_request (id_sender, id_target) VALUES (?, ?)');
	$req->execute(array($my_user->id, htmlspecialchars($id_target)));
}

function add_friend($db, $my_user, $my_user_infos, $id_friend)
{
	$friends = get_friends($db, $my_user->id);
	if ($friends == null || !in_array($id_friend, $friends))
	{
		$friends = $my_user_infos->friends;
		if ($friends == null || $friends == '')
			$friends = $id_friend;
		else
			$friends .= ';' . $id_friend;
		$req = $db->prepare('UPDATE user_infos SET friends = ? WHERE id_user = ?');
		$req->execute(array($friends, $my_user->id));
	}
}

function remove_friend($db, $my_user, $my_user_infos, $id_friend)
{
	//On renvoi une requete d'ami via l'API sans vue. Et supprime les amis.
	$str_friend1 = $my_user_infos->friends;
	$str_friend2 = get_user_infos_obj($db, $id_friend)->friends;
	$arr1 = explode(";", $str_friend1);
	$arr2 = explode(";", $str_friend2);

	unset($arr1[array_search($id_friend, $arr1)]);
	unset($arr2[array_search($my_user->id, $arr2)]);

	$str_friend1 = implode($arr1);
	$str_friend2 = implode($arr2);

	$req = $db->prepare("UPDATE user_infos SET friends = ? WHERE id_user = ?; UPDATE user_infos SET friends = ? WHERE id_user = ?");
	$req->execute(array($str_friend1, $my_user->id, $str_friend2, $id_friend));
	$req->closeCursor();

	if (!is_blocked($db, $id_friend, $my_user->id))
	{
		$req = $db->prepare('INSERT INTO notif_like (id_sender, id_target, value) VALUES (?, ?, ?)');
		$req->execute(array($my_user->id, htmlspecialchars($id_friend), 0));
		$req->closeCursor();
	}
	
	$srequest = get_sfriend_requests($db, $my_user->id);
	foreach ($srequest as $val) 
	{
		if ($val->id_target == $id_friend)
		{
			$req = $db->prepare("DELETE FROM friend_request WHERE id_sender = ? AND id_target = ?");
			$req->execute(array($my_user->id, htmlspecialchars($id_friend)));
			return ;
		}
	}
	add_friend_request($db, get_user_obj($db, $id_friend), get_user_infos_obj($db, $id_friend), $my_user->id);
}

function get_views($db, $id_user, $n)
{
	$ret = array();
	if ($n < 2)
	{
		$req = $db->prepare("SELECT * FROM  profile_view WHERE id_user = ? AND view = ?");
		if ($req->execute(array($id_user, $n)))
		{
			while ($val = $req->fetch(PDO::FETCH_OBJ))
				array_push($ret, $val);
			$req->closeCursor();
			return ($ret);
		}
	}
	return (null);
}

function view_profile($db, $id_user)
{
	if (!is_blocked($db, $id_user, get_my_user_id($db)))
	{
		$req = $db->prepare("SELECT * FROM profile_view WHERE id_user = ? AND id_user_viewer = ?");
		if($req->execute(array($id_user, get_my_user_id($db))))
		{
			if ($val = $req->fetch(PDO::FETCH_OBJ))
			{
				$req2 = $db->prepare("UPDATE profile_view SET view = 0, date_view = ? WHERE id = ?");
				$req2->execute(array(date("Y-m-d H:i:s"), $val->id));
			}
			else
			{
				$req2 = $db->prepare("INSERT INTO profile_view (id_user, id_user_viewer, date_view) VALUES (?, ?, ?)");
				$req2->execute(array($id_user, get_my_user_id($db), date("Y-m-d H:i:s")));
			}
			$req->closeCursor();
		}
		$req = $db->prepare("UPDATE user_infos SET nb_views = nb_views + 1 WHERE id_user = ?");
		$req->execute(array($id_user));
	}
}

function is_blocked($db, $my_user_id, $target_id)
{
	$usersBlocked = get_user_infos_obj($db, $my_user_id)->users_blocked;
	$tab_usersBlocked = explode(';', $usersBlocked);
	if (count($tab_usersBlocked > 1))
	{
		if (in_array($target_id, $tab_usersBlocked))
			return true;
	}
	else if ($usersBlocked == $target_id || $usersBlocked == ';' . $target_id)
		return true;
	return false;
}

function is_connected($db, $id_user)
{
	$user = get_user_obj($db, $id_user);
	if ($user != null)
	{
		$current = date("Y-m-d H:i:s");
		$current = strtotime($current);
		$last = strtotime($user->last_connexion);
		/*echo $current . " OKCURRENT ";
		echo $last  . "OKLAST";*/
		if (round(abs($current - $last) / 60,2) > 121)
			return false;
		return true;
	}
	return false;
}

function get_last_location($db, $id_user)
{
	$req = $db->prepare("SELECT * FROM locations WHERE id_user = ?");
	if ($req->execute(array(htmlspecialchars($id_user))))
	{
		$ret =  $req->fetch(PDO::FETCH_OBJ);
		$req->closeCursor();
		return $ret;
	}
	return null;
}

/* Le score de popularitee se calcule de cette maniere : nb_friends * 5 + (nb_views / 4) */
function get_popularity($db, $id_user)
{
	$user = get_user_infos_obj($db, $id_user);
	$nb_friends = count(get_friends($db, $id_user));
	if ($user->nb_views <= 0)
		return $nb_friends * 5;
	else
		return ($nb_friends * 5) + ($user->nb_views / 4);
}
?>
