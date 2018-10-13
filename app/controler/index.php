<?php
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
	{
		if (user_visible($my_user_infos))
			include "app/view/index.php";
		else
			echo '<h1 class="text-danger">Tu dois renseigner tous les champs dans ton profil pour profiter de toutes les fonctionnalitées !</h1>';
	}
?>
