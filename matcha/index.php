<?php
session_start();
include "config/database.php";
include "app/model/functions/connexion_db.php";
include "app/model/functions/user_functions.php";

/* includes conditions */
if (isset($_GET['page']))
{
	if ($_GET['page'] == "chat")
		include "app/model/functions/chat.php";
}
/*  */

if (isset($_GET['key_suscr']))
	get_key_register($db, $_GET['key_suscr']);


if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
{
	$my_user = get_user_obj($db, get_my_user_id($db));
	$my_user_infos = get_user_infos_obj($db, get_my_user_id($db));
	echo '<input type="hidden" value="' . $my_user->id . '" id="my_user_id"/ >';
}
else
{
	$my_user = null;
	$my_user_infos = null;
}
?>

<html>
  <head>
	<link href="resources/css/menu.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>	
	
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

	<!-- Bootstrap Date-Picker Plugin -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

	<link href="resources/css/principal.css" rel="stylesheet">

	<!-- CSS CONDITIONS -->
<?php
if (isset($_GET['page']))
{
	if ($_GET['page'] == "user_infos")
	{
		echo '<link href="resources/css/user_infos.css" rel="stylesheet">';
		if (isset($_GET['user_id']) && $_GET['user_id'] == $my_user->id)
			echo '<link href="resources/css/bootstrap-tagsinput.css" rel="stylesheet">';
	}
	if ($_GET['page'] == "connexion")
		echo '<link href="resources/css/connexion.css" rel="stylesheet">';
	if ($_GET['page'] == "user_matches" || $_GET['page'] == "index")
		echo '<link href="resources/css/user_matches.css" rel="stylesheet">';
	if ($_GET['page'] == "chat")
		echo '<link href="resources/css/chat.css" rel="stylesheet">';
	if ($_GET['page'] == "register")
		echo '<link href="resources/css/register.css" rel="stylesheet">';
}else
	echo '<link href="resources/css/user_matches.css" rel="stylesheet">';
?>
	<!-- END CSS CONDITIONS -->
  </head>

  <body>
<?php
include "app/controler/menu.php";
echo '<br /><br /><br />';
if (isset($_GET['page']) && is_file('app/controler/' . $_GET['page'] . '.php'))
	include 'app/controler/' . $_GET['page'] . '.php';
else
	include "app/controler/index.php";
?>
  </body>

<!-- SCRIPT CONDITIONS -->
<script src="resources/js/menu.js"></script>
<script src="resources/js/location.js"></script>
<script src="resources/js/update_connected.js"></script>
<?php
if (isset($_GET['page']))
{
	if ($_GET['page'] == "user_infos")
	{
		echo '<script src="resources/js/user_infos.js"></script>';
		if (isset($_GET['user_id']) && $_GET['user_id'] == $my_user->id)
			echo '<script src="resources/js/bootstrap-tagsinput.js"></script>';
	}
	if ($_GET['page'] == "chat")
		echo '<script src="resources/js/chat.js"></script>';
	if ($_GET['page'] == "user_matches.php")
		echo '<script src="resources/js/search.js"></script>';
}
?>
	<!-- END SCRIPT CONDITIONS -->
</html>
