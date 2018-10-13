<?php

function show_notifications_chat($db, $id_user,  $all_messages_no_view)
{
	for ($i = 0; $i < count($all_messages_no_view); $i++)
	{
		$user_infos = get_user_infos_obj($db, $all_messages_no_view[$i]->id_user_author);
		echo'
			<li class="li_notif_chat", id="li_notif_chat' . $all_messages_no_view[$i]->id . '">
				<div class="navbar-login">
					<div class="row">
							<center>
						<div class="col-lg-4">
					
							<a href="index.php?page=user_infos&user_id=' . $user_infos->id_user . '">
							<img src="' . $user_infos->photo_profile . '" class="img-rounded" alt="img_profile" width="304" height="236"> 
							</a>
						
						</div>
						</center>

						<div class="col-lg-8">
							<h4 class="align-middle">' . $user_infos->first_name . ' ' . $user_infos->last_name . '</h4>
							<span class="align-middle">' . substr($all_messages_no_view[$i]->message, 0, 30) . '</span>
							<br/><a href="index.php?page=chat&id_target=' . $user_infos->id_user . '"><div class="glyphicon glyphicon-text-background text-success"></div></a>
							<a href="javascript:remove_notif(\'chat\','. $all_messages_no_view[$i]->id .');"><div class="fa fa-remove text-danger"></div></a>
						</div>
					</div>
				</div>
			</li>';
			if ($i + 1 < count($all_messages_no_view))
					echo '<li class="divider"></li>';
	}
}

function show_notifications_view($db, $id_user,  $all_views)
{
	for ($i = 0; $i < count($all_views); $i++)
	{
		$user_infos = get_user_infos_obj($db, $all_views[$i]->id_user_viewer);
		echo'
			<li class="li_notif_view", id="li_notif_view' . $all_views[$i]->id . '">
				<div class="navbar-login">
					<div class="row">
							<center>
						<div class="col-lg-4">
					
							<a href="index.php?page=user_infos&user_id=' . $user_infos->id_user . '">
							<img src="' . $user_infos->photo_profile . '" class="img-rounded" alt="img_profile" width="304" height="236"> 
							</a>
						
						</div>
						</center>

						<div class="col-lg-8">
							<h4 class="align-middle">' . $user_infos->first_name . ' ' . $user_infos->last_name . '</h4>
							<strong>' . $all_views[$i]->date_view . '</strong>
							</br><a href="javascript:remove_notif(\'view\','. $all_views[$i]->id .');"><div class="fa fa-remove text-danger"></div></a>
						</div>
					</div>
				</div>
			</li>';
			if ($i + 1 < count($all_views))
					echo '<li class="divider"></li>';
	}
}

function show_notifications_friendsreq($db, $id_user,  $friends_requests)
{
	/*for ($i = 0; $i < count($friends_requests); $i++)
	{
		if ($friends_requests[$i]->view == 0)
		{
			$user_infos = get_user_infos_obj($db, $friends_requests[$i]->id_sender);
			echo' 
			<center>
			<li class="li_notif_friendsreq", id="li_notif_friendsreq' . $friends_requests[$i]->id . '">
				<div class="navbar-login">
					<div class="row">
						<div class="col-lg-4">
							<a href="index.php?page=user_infos&user_id=' . $user_infos->id_user . '"><img src="' . $user_infos->photo_profile . '" />
							</a>
						</div>
						<div class="col-lg-8">
						<h1 class="text-center">
						<b>Vous a like </b>
						<a href="javascript:remove_notif(\'friendsreq_remove\',' . $friends_requests[$i]->id . ');">
							<div class="fa fa-remove text-danger"></div>
						</a>
						</div>
					</div>
				</div>
			</li>
			</center>';
			if ($i + 1 < count($friends_requests))
				echo '<li class="divider"></li>';
		}
	}*/
	$likes = get_tnotif_like($db, $id_user);
	for ($i = 0; $i < count($likes); $i++)
	{
		if ($likes[$i]->view == 0)
		{
			$user_infos = get_user_infos_obj($db, $likes[$i]->id_sender);
			echo' 
			<center>
			<li class="li_notif_friendsreq", id="li_notif_friendsreq' . $likes[$i]->id . '">
				<div class="navbar-login">
					<div class="row">
						<div class="col-lg-4">
							<a href="index.php?page=user_infos&user_id=' . $user_infos->id_user . '"><img src="' . $user_infos->photo_profile . '" />
							</a>
						</div>
						<div class="col-lg-8">
						<h1 class="text-center">';
			if ($likes[$i]->value == 1)
				echo '<b>Vous a like </b>';
			else
				echo '<b>Vous a unlike </b>';
			echo '
					<a href="javascript:remove_notif(\'friendsreq_remove\',' . $likes[$i]->id . ');">
						<div class="fa fa-remove text-danger"></div>
					</a>
					</div>
					</div>
					</div>
				</li>
			</center>';
			if ($i + 1 < count($likes))
				echo '<li class="divider"></li>';
		}		
	}
}

?>