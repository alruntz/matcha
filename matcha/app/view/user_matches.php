<?php
	include "app/model/functions/match.php";
    //$filters = array(array("column" => "date_birth", "value_min" => 30, "value_max" => 50));
    //$filters = array(array("column" => "tags", "values" => "voiture;mode"));
	//$matches = get_matches_by_filters($db, $filters, $my_user_infos);
    if (isset($_POST['filter_age_min'])) // aucune importance au post, sert juste a verifier si on a cherché par le formulaire.
    {
		$filters = array(array("column" => "date_birth", "value_min" => $_POST['filter_age_min'],
								"value_max" => $_POST['filter_age_max']),
						array("column" => "location", "value_min" => $_POST['filter_location_min'],
								"value_max" => $_POST['filter_location_max']),
                        array("column" => "matches", "value_min" => $_POST['filter_matches_min'],
                                "value_max" => $_POST['filter_matches_max']),
                        array("column" => "popularity", "value_min" => $_POST['filter_popularity_min'],
                                "value_max" => $_POST['filter_popularity_max']),
                        array ("column" => "tags", "value_min" => $_POST['filter_tags_min'],
                                "value_max" => $_POST['filter_tags_max']));
        $matches = get_matches_by_filters($db, $filters, $my_user_infos);
        //tri_by_date($matches, true);
    	echo "<br /><br />";
        if (isset($_POST['tri']))
        {
            if ($_POST['tri'] == "matchesDesc")
                tri_by_matches($matches, true);
            else if ($_POST['tri'] == "matchesAsc")
                tri_by_matches($matches, false);
            else  if ($_POST['tri'] == "ageDesc")
                 tri_by_date($matches, true);
            else  if ($_POST['tri'] == "ageAsc")
                 tri_by_date($matches, false);
            else  if ($_POST['tri'] == "tagsDesc")
                 tri_by_tags($matches, true);
            else  if ($_POST['tri'] == "tagsAsc")
                 tri_by_tags($matches, false);
            else if ($_POST['tri'] == "locationDesc")
                 tri_by_distance($matches, true);
            else if ($_POST['tri'] == "locationAsc")
                 tri_by_distance($matches, false);
            else if ($_POST['tri'] == "popularityDesc")
                tri_by_popularity($matches, true);
            else if ($_POST['tri'] == "popularityAsc")
                tri_by_popularity($matches, false);
        }
        show_matches($db, $matches, -1);
    }
?>
