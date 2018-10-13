<?php
	require_once("app/model/functions/chat.php");
	require_once("app/model/functions/menu.php");
?>

<div class="menu">
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container"> 
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span> 
			</button>
			<a target="_blank" href="#" class="navbar-brand">Matcha</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="?page=index">Accueil</a></li>
<?php
if (isset($_SESSION['logged']) && $_SESSION['logged'] == false || !isset($_SESSION['logged']))
{
?>
				<li><a href="index.php?page=connexion">Connexion</a></li>
				<li><a href="index.php?page=register" target="_blank">S'inscrire</a></li>
<?php
}
?>  
			 
<?php
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
{
?>
			<li><a href="index.php?page=user_matches">Matches</a></li>
			<li><a href="index.php?page=chat">Chat</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-user"></span> 
						<strong>Account</strong>
						<span class="glyphicon glyphicon-chevron-down"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<div class="navbar-login">
								<div class="row">
									<div class="col-lg-4">
										<center>
										<img src=<?php echo '"' . $my_user_infos->photo_profile  . '"'?> class="img-rounded" id="photo_profile" alt="img_profile" width="304" height="236"> 
									</center>
									</div>
									<div class="col-lg-8">
									<p class="text-left"><strong><?php echo $my_user_infos->first_name . ' ' . $my_user_infos->last_name;  ?></strong></p>
									<p class="text-left small"><?php echo $my_user->mail; ?></p>
										<p class="text-left">
											<a href=<?php echo '"index.php?page=user_infos&user_id=' . $my_user->id . '"';?> class="btn btn-primary btn-block btn-sm">Profil</a>
										</p>
										<p class="text-left small"><a href="index.php?page=connexion&type=new_password">Changer mot de passe</a></p>
									</div>
								</div>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<div class="navbar-login navbar-login-session">
								<div class="row">
									<div class="col-lg-12">
										<p>
											<a href="app/model/API/deconnexion.php" class="btn btn-danger btn-block">Deconnexion</a>
										</p>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</li>
			<!-- // -->
			<?php 
			$likes = get_tnotif_like($db, $my_user->id);
			$likesCount = 0;
			foreach($likes as $val)
				if ($val->view == 0)
					$likesCount++;
			?>
			
				<li class="dropdown" id="menu_notif_friendsreq">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" id="toggle_notif_friendsreq">
						<span class="glyphicon glyphicon-heart-empty"></span> 
						<strong id="text_notif_friendsreq">Likes (<?php echo $likesCount; ?>)</strong>
						<span class="glyphicon glyphicon-chevron-down"></span>
					</a>
					
					<ul class="dropdown-menu pre-scrollable" id="notifs_friendsreq" style="max-height: 350px">
						<?php
						show_notifications_friendsreq($db, $my_user->id,  $friends_requests);
					?>
				</ul>
			</li>
			
			<!-- notifications chat -->
			<?php $all_messages_no_view = get_messages_no_view($db, get_my_user_id($db));?>
				<li class="dropdown" id="menu_notif_chat">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" id="toggle_notif_chat">
						<span class="glyphicon glyphicon-envelope"></span> 
						<strong id="text_notif_chat">Notif. chat (<?php echo count($all_messages_no_view); ?>)</strong>
						<span class="glyphicon glyphicon-chevron-down"></span>
					</a>
					<ul class="dropdown-menu pre-scrollable" id="notifs_chat" style="max-height: 350px">
						<?php show_notifications_chat($db, get_my_user_id($db), $all_messages_no_view); ?>
					</ul>
				</li>

			

			<!-- notifications views-->
			<?php $all_views = get_views($db, get_my_user_id($db), 0);?>
				<li class="dropdown" id="menu_notif_view">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" id="toggle_notif_view">
						<span class="glyphicon glyphicon-eye-open"></span> 
						<strong id="text_notif_view">Notif. vues (<?php echo count($all_views); ?>)</strong>
						<span class="glyphicon glyphicon-chevron-down"></span>
					</a>
			<ul class="dropdown-menu pre-scrollable" id="notifs_view" style="max-height: 350px">
				<?php show_notifications_view($db, get_my_user_id($db), $all_views); ?>
			</ul>
		</li>
	</ul>
<?php
}
?>

				
		</div>
	</div>
</div>
</div>
