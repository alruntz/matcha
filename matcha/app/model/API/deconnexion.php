<?php
	session_start();
	$_SESSION['logged'] = false;
	$_SESSION['login'] = null;
	$_SESSION['password'] = null;
	header('location: ../../../index.php');
?>
