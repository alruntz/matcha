<?php

function get_rand_tags($tags_available)
{
	$arr_rand = array_rand($tags_available, rand(1, 10));
	$ret = "";
	$i = 0;
	if ($arr_rand != null || is_array($arr_rand))
	{
		foreach($arr_rand as $val)
		{
			$ret .= $tags_available[$val];
			if ($i + 1 < count($arr_rand))
				$ret .= ';';
			$i++;
		}
	}
	return $ret;
}

if (isset($_POST['user_json']) && isset($_POST['coord']))
{
	echo "2";
	$user = str_replace("\\", '', $_POST['user_json']);
	if (($user = json_decode($user)) != null)
	{
		echo "3";
		include "database.php";
		include "../app/model/functions/connexion_db.php";
		include "../app/model/functions/user_functions.php";
		//var_dump($user->results[0]);
		//echo $_POST['user_json'];

		$tags_available = array("voiture", "programmation", "jeux-videos", "mode", "cuisine", "sport", "lecture", "cinema", "rap", "rock", "jazz", "peinture");
		$table=$db->prepare("SHOW TABLE STATUS LIKE 'users'");
		$table->execute();
		$id = $table->fetch()["Auto_increment"];
		$username = $user->results[0]->login->username;
		$login = $user->results[0]->email;
		$password = $user->results[0]->login->password;
		$validate = 1;
		$mail = $user->results[0]->email;

		$id_user = $id;
		$first_name = $user->results[0]->name->first;
		$last_name = $user->results[0]->name->last;
		$friends = "";
		$tags = get_rand_tags($tags_available);
		$country = "france";
		$town = $user->results[0]->location->city;
		$photo_profile = $user->results[0]->picture->large;
		$phone = $user->results[0]->cell;
		$date_birth = $user->results[0]->dob->date;
		$job = "Inconnue";
		$sex = $user->results[0]->gender;
		if ($sex == "male")
			$sex_preference = "female";
		else
			$sex_preference = "male";

		$req = $db->prepare('INSERT INTO users (username, login, password, validate, mail) VALUES (?, ?, ?, ?, ?)');
		if (!$req->execute(array($username, $login, hash_password($password), $validate, $mail)))
			echo "Erreur user";	
		else
			echo "Success user";
		$req = $db->prepare('INSERT INTO user_infos (id_user, first_name, last_name, friends, tags, country, town, location, photo_profile, phone, job, date_birth, sex, sex_preference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if (!$req->execute(array($id_user, $first_name, $last_name, $friends, $tags, $country, $town, $_POST['coord'], $photo_profile, $phone, $job, $date_birth, $sex, $sex_preference)))
			echo "Erreur user_infos";
		else
			echo "Success user_infos";
	}
}
?>
