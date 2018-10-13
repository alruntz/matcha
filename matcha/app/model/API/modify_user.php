<?php
session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && isset($_GET['type']))
{
	include "../../../config/database.php";
	include "../../model/functions/connexion_db.php";
	include "../../model/functions/user_functions.php";
	$id_user = get_my_user_id($db);
	$id_user_infos = get_user_infos_obj($db, $id_user)->id;
	if ($_GET['type'] == "about" && isset($_POST['date_birth']) 
		&& isset($_POST['town']) && isset($_POST['country']) && isset($_POST['mail']) && isset($_POST['phone'])
		&& isset($_POST['sex']) && isset($_POST['sex_preference']) && isset($_POST['last_name']) && isset($_POST['job']) && isset($_POST['bio']))
	{
		$location_js = file_get_contents("http://www.mapquestapi.com/geocoding/v1/address?key=THXRwZZJh3pJggG4nCCtA1c7EaFC2FEM&location=" . $_POST['town']);
		$location_js = json_decode($location_js);
		$location = $location_js->results[0]->locations[0]->latLng->lat . ',' . $location_js->results[0]->locations[0]->latLng->lng;
		echo $location;
		$req = $db->prepare('UPDATE user_infos SET date_birth = ?, town = ?, location = ?, country = ?, phone = ?, sex = ?,
												sex_preference = ?, last_name = ?, job = ?, bio = ? WHERE id = ?');
		$req->execute(array(htmlspecialchars($_POST['date_birth']), htmlspecialchars($_POST['town']), $location , htmlspecialchars($_POST['country']), htmlspecialchars($_POST['phone']), htmlspecialchars($_POST['sex']), htmlspecialchars($_POST['sex_preference']), 
			htmlspecialchars($_POST['last_name']), htmlspecialchars($_POST['job']), htmlspecialchars($_POST['bio']), $id_user_infos));

		$req = $db->prepare('UPDATE users SET mail = ? WHERE id = ?');
		$req->execute(array($_POST['mail'], $id_user));
	}

	else if ($_GET['type'] == "tags" && isset($_POST['tags_values']))
	{
		$req = $db->prepare('UPDATE user_infos SET tags = ? WHERE id = ?');
		$req->execute(array(htmlspecialchars(str_replace(",", ";", $_POST['tags_values'])), $id_user_infos));
	}

	else if ($_GET['type'] == "photo" && isset($_FILES["fileToUpload"]))
	{
		$nb = count(get_photos($db, $my_user->id)->fetchall());
		if ($nb < 5)
		{
			$target_dir = "../../../uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$target_file = $target_dir . $_SESSION['login'] . uniqid() . $imageFileType;
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			    if($check !== false) {
			       // echo "File is an image - " . $check["mime"] . ".";
			        $uploadOk = 1;
			    } else {
			        echo "File is not an image.";
			        $uploadOk = 0;
			    }
			}
			// Check if file already exists
			if (file_exists($target_file)) {
			    echo "Sorry, file already exists.";
			    $uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
			    echo "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			    	$req = $db->prepare("INSERT INTO user_photos (id_user, path) VALUES (?, ?)");
			    	$req->execute(array(htmlspecialchars($id_user), htmlspecialchars(substr($target_file, 9))));
			        header('location: ../../../index.php?page=user_infos&user_id='. $id_user);
			    } else {
			        echo "Sorry, there was an error uploading your file.";
			    }
			}
		}
	}
	else if (isset($_GET['type']) == "photo_profile" && isset($_POST['photo_profile']))
	{
		$req = $db->prepare("UPDATE user_infos SET photo_profile = ? WHERE id = ?");
		$req->execute(array(htmlspecialchars($_POST['photo_profile']), $id_user));
	}
	else if (isset($_GET['type']) == "delete_photo" && isset($_POST['delete_photo']))
	{
		$req = $db->prepare("DELETE FROM user_photos WHERE id = ?");
		$req->execute(array(htmlspecialchars($_POST['delete_photo'])));
	}
	header('location: ../../../index.php?page=user_infos&user_id='. $id_user);
}
?>
