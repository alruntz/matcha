<?php
if ((isset($_SESSION['logged']) && isset($_SESSION['logged']) == true) || !isset($_SESSION['logged']))
{
	include "app/view/register.php";
}
?>