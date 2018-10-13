<?php

function show_matches($db, $matches, $nb)
{
	if ($nb == -1)
		$nb = count($matches);
	else if ($nb > count($matches))
		$nb  = count($matches);
	echo '
	<section id="team" class="pb-5">
	<div class="container">  
	<div class="row">';

	for ($i = 0; $i < $nb; $i++)
	{
		if (user_visible($matches[$i]['user_infos']) && !is_blocked($db, get_my_user_id($db), $matches[$i]['user_infos']->id))
		{
			echo '
			<div class="col-xs-12 col-sm-6 col-md-4">
			  <div class="image-flip" ontouchstart="this.classList.toggle(\'hover\');">
				<div class="mainflip">
				  <div class="frontside">
					<div class="card">
					  <div class="card-body text-center">
						<p><img class="img-fluid" src="' . $matches[$i]['user_infos']->photo_profile . '" alt="card image"></p>
						<h4 class="card-title">' . $matches[$i]['user_infos']->first_name . '</h4>';
			$tags = get_tags($matches[$i]['user_infos']);
			for ($j = 0; $j < count($tags); $j++)
				echo '#' . $tags[$j] . ' ';
			 echo '    
						<div class="rank-label-container">
						  <span class="label label-default rank-label">Score matches : ' . $matches[$i]['score'] . '</span>
						</div>
						<div class="rank-label-container">
						  <span class="label label-default rank-label">Popularité : ' . intval(get_popularity($db, $matches[$i]['user_infos']->id_user)) . '</span>
						</div>
					 </div>
				   </div>
				</div>
				<div class="backside">
				  <div class="card">
					<div class="card-body text-center">
					  <h4 class="card-title">' . $matches[$i]['user_infos']->first_name . '</h4>' .
					  '<p class="card-text">';
					  if ($matches[$i]['user_infos']->bio != "")
					  	echo $matches[$i]['user_infos']->bio;
					  echo ' </p>
					  <ul class="list-inline">
						<li class="list-inline-item">
						  <a class="social-icon text-xs-center" href="index.php?page=user_infos&user_id=' 
							. $matches[$i]['user_infos']->id_user . '">
							<p><span class="glyphicon glyphicon-user"></span>Voir le profil</p>
						  </a>
						</li>
					  </ul>
				   </div>
				 </div>
			   </div>
			 </div>
			</div>
			</div>';
		}
		else
			$nb++;
	}
	echo ' </div></div></section>';
}

function get_score($user_infos, $my_user_infos)
{
	$score = 0;
	$tags_user = explode(";", $user_infos->tags);
	$tags_my_user = explode(";", $my_user_infos->tags);
	$score += (count(array_intersect($tags_user, $tags_my_user)) * 2);
	if (strtolower($my_user_infos->town) == $user_infos->town)
		$score += 10;
	if (strtolower($my_user_infos->country) == strtolower($user_infos->country))
		$score += 3;
	$dist = distanceInKm(explode(",", $my_user_infos->location)[1], explode(",", $my_user_infos->location)[0],
							explode(",", $user_infos->location)[1], explode(",", $user_infos->location)[0]);
	if (100 - $dist > 0)
		$score += (100 - $dist) / 10;
	return $score;
}

function get_matches($db, $my_user_infos)
{
	$users = array();
	$req_users = $db->prepare('SELECT * FROM user_infos');
	if ($req_users->execute())
	{
		while ($val = $req_users->fetch(PDO::FETCH_OBJ))
		{
			if (user_visible($val))
			{
				$score = get_score($val, $my_user_infos);
				if ($score != 0)
				{
					if ($val->sex == $my_user_infos->sex_preference || $my_user_infos->sex_preference == "bi")
						array_push($users, array("user_infos" => $val, "score" => $score));
				}
			}
		}
		tri_by_matches($users, true);
	}
	return $users;
}

/* $filters = array avec keys ex: Array(Array("column" => "data", "value_min" => "data", "value_max" => "data")) */
function get_matches_by_filters($db, $filters, $my_user_infos)
{
	$ret = array();
	$location = array();
	$matches_val = array();
	$popularity_val = array();
	$tags_val = array();
	$and = "";
	foreach($filters as $val)
	{
		if ($val['column'] == "date_birth")
			$and .= get_matches_by_date($db, $val, $ret, $my_user_infos);
		else if ($val['column'] == "matches")
			$matches_val = $val;
		else if ($val['column'] == "location")
			$location = $val;
		else if ($val['column'] == "popularity")
			$popularity_val = $val;
		else if ($val['column'] == "tags")
			$tags_val = $val;
	}

	$req = $db->prepare("SELECT * FROM user_infos WHERE $and");
	if ($req->execute(array($val['value_min'], $val['column'], $val['value_max'])))
	{
		while ($user_infos = $req->fetch(PDO::FETCH_OBJ))
		{
			if ($user_infos->sex == $my_user_infos->sex_preference || $my_user_infos->sex_preference == "bi")
				array_push($ret, array("user_infos" => $user_infos, 
										"score" => get_score($user_infos, $my_user_infos),
										"my_user_infos" => $my_user_infos,
										"db" => $db));
		}
	}
	if (count($location) > 0)
		$ret = get_matches_by_location($location, $ret, $my_user_infos);
	$ret = get_matches_by_scoreMatches($ret, $my_user_infos, $matches_val);
	$ret = get_matches_by_scorePopularity($db, $ret, $popularity_val);
	$ret = get_matches_by_tags($ret, $my_user_infos, $tags_val);
	return $ret;
}

function get_matches_by_location($location, $arr, $my_user_infos)
{
	$ret = Array();
	for($i = 0; $i < count($arr); $i++)
	{
		$dist = distanceInKm(explode(",", $my_user_infos->location)[1], explode(",", $my_user_infos->location)[0], 
			explode(",", $arr[$i]['user_infos']->location)[1], explode(",", $arr[$i]['user_infos']->location)[0]);
		if ($dist >= $location['value_min'] && $dist <= $location['value_max'])
			array_push($ret, $arr[$i]);
	}
	return $ret;
}

function get_matches_by_tags($arr, $my_user_infos, $value)
{
	$ret = Array();
	$my_tags = explode(';', $my_user_infos->tags);
	foreach ($arr as $val) {
		$target_tags = explode(';', $val['user_infos']->tags);
		$tags_communs = count(array_intersect($my_tags, $target_tags));
		if ($tags_communs >= $value['value_min'] && $tags_communs <= $value['value_max'])
			array_push($ret, $val);
	}
	return $ret;
}

function get_matches_by_scoreMatches($arr, $my_user_infos, $value)
{
	$ret = Array();
	foreach ($arr as $val) {
		$scoreMatches = get_score($val['user_infos'], $my_user_infos);
		if ($scoreMatches >= $value['value_min'] && $scoreMatches <= $value['value_max'])
			array_push($ret, $val);
	}
	return $ret;
}

function get_matches_by_scorePopularity($db, $arr, $value)
{
	$ret = Array();
	foreach ($arr as $val)
	{
		$scorePopularity = intval(get_popularity($db, $val['user_infos']->id_user));
		if ($scorePopularity >= $value['value_min'] && $scorePopularity <= $value['value_max'])
			array_push($ret, $val);
	}
	return $ret;
}

function get_matches_by_date($db, $val)
{
	return ("(SELECT YEAR(CURRENT_TIMESTAMP) - YEAR(date_birth) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(date_birth, 5))) >= " . htmlspecialchars($val['value_min']) . " AND (SELECT YEAR(CURRENT_TIMESTAMP) - YEAR(date_birth) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(date_birth, 5))) <= " . htmlspecialchars($val['value_max']));
	/*if ($req->execute(array($val['value_min'], $val['value_max'])))
	{
		while ($user_infos = $req->fetch(PDO::FETCH_OBJ))
		{
			if ($user_infos->sex == $my_user_infos->sex_preference || $my_user_infos->sex_preference == "bi")
				array_push($tab, array("user_infos" => $user_infos, 
					"score" => get_score($user_infos, $my_user_infos),
					"my_user_infos" => $my_user_infos));
		}
	}*/
}

function get_distance($user_infos1, $user_infos2)
{
	$lng1 = floatval(explode(",", $user_infos1->location)[0]);
	$lng2 = floatval(explode(",", $user_infos2->location)[0]);
	$alt1 = floatval(explode(",", $user_infos1->location)[1]);
	$alt2 = floatval(explode(",", $user_infos2->location)[1]);
	return sqrt(pow ($lng1 - $lng2, 2) + pow ($alt1 - $alt2, 2));
}

function degreesToRadians($degrees) {
	return $degrees * pi() / 180;
}

function distanceInKm($lat1, $lon1, $lat2, $lon2) {
	$earthRadiusKm = 6371;

	$dLat = degreesToRadians($lat2-$lat1);
	$dLon = degreesToRadians($lon2-$lon1);

	$lat1 = degreesToRadians($lat1);
	$lat2 = degreesToRadians($lat2);

	$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
	$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
	return $earthRadiusKm * $c;
}


function cmp_matches_desc($a, $b)
{
	return ($b['score'] - $a['score']);
}

function cmp_matches_asc($a, $b)
{
	return ($a['score'] - $b['score']);
}

function cmp_date_desc($a, $b)
{
	return strtotime($a['user_infos']->date_birth) - strtotime($b['user_infos']->date_birth);
}

function cmp_date_asc($a, $b)
{
	return strtotime($a['user_infos']->date_birth) - strtotime($b['user_infos']->date_birth);
}

function cmp_tags_desc($a, $b)
{
	return (count(explode(";", $b['user_infos']->tags)) - count(explode(";", $a['user_infos']->tags)));
}

function cmp_tags_asc($a, $b)
{
	return (count(explode(";", $a['user_infos']->tags)) - count(explode(";", $b['user_infos']->tags)));
}

function cmp_distance_desc($a, $b)
{
	$dist1 = get_distance($a['user_infos'], $a['my_user_infos']); 
	$dist2 = get_distance($b['user_infos'], $b['my_user_infos']);

	if ($dist1 > $dist2)
		return 1;
	if ($dist1 < $dist2)
		return -1;
	if ($dist1 == $dist2)
		return 0;
}

function cmp_distance_asc($a, $b)
{
	$dist1 = get_distance($a['user_infos'], $a['my_user_infos']); 
	$dist2 = get_distance($b['user_infos'], $b['my_user_infos']);

	if ($dist1 > $dist2)
		return -1;
	if ($dist1 < $dist2)
		return 1;
	if ($dist1 == $dist2)
		return 0;
}

function cmp_popularity_desc($a, $b)
{
	return (get_popularity($b['db'], $b['user_infos']->id_user) - get_popularity($a['db'], $a['user_infos']->id_user));
}

function cmp_popularity_asc($a, $b)
{
	return (get_popularity($a['db'], $a['user_infos']->id_user) - get_popularity($b['db'], $b['user_infos']->id_user));
}


function tri_by_date(&$matches, $desc)
{
	if ($desc == true)
		usort($matches, "cmp_date_desc");
	else
		usort($matches, "cmp_date_asc");
}

function tri_by_tags(&$matches, $desc)
{
	if ($desc == true)
		usort($matches, "cmp_tags_desc");
	else
		usort($matches, "cmp_tags_asc");
}

function tri_by_matches(&$matches, $desc)
{
	if ($desc == true)
		usort($matches, "cmp_matches_desc");
	else
		usort($matches, "cmp_matches_asc");
}

function tri_by_distance(&$matches, $desc)
{
	if ($desc == true)
		usort($matches, "cmp_distance_desc");
	else
		usort($matches, "cmp_distance_asc");
}

function tri_by_popularity(&$matches, $desc)
{
	if ($desc == true)
		usort($matches, "cmp_popularity_desc");
	else
		usort($matches, "cmp_popularity_asc");
}

?>
