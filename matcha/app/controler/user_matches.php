<?php
	include  "app/view/search.php";
	if (user_visible($my_user_infos))
		include "app/view/user_matches.php";
	else
		echo '<h1 class="text-danger">Tu dois renseigner tous les champs dans ton profil pour faire une recherche !!</h1>';
	
?>