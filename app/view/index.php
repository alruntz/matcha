<?php
include "app/model/functions/match.php";
$matches = get_matches($db, $my_user_infos);

//$birthDate = explode("-", $my_user_infos->date_birth);
//$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));

//$filter_date = array(array("column" => "date_birth", "value_min" => $age - 5, "value_max" => $age + 5));
//$matches_date = get_matches_by_filters($db, $filter_date, $my_user_infos);
//tri_by_date($matches_date, true);
?>

<!--div-->
	<!--h2 style="background-color: white; " >Suggestions - Date</h2-->
<?php 
//show_matches($db, $matches_date, 6);
?>

<!--/div-->
<div>
	<h2 style="background-color: white; ">Suggestions - Score</h2>
<?php 
tri_by_matches($matches, true);
show_matches($db, $matches, 6);
?>
