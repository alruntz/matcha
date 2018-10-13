<br/ >
<br/>
<?php
if (isset($_GET['user_id']))
{
	if ($user = get_user_obj($db, $_GET['user_id']))
		if ($user_infos = get_user_infos_obj($db, $user->id))
		{
			if ($user->id != $my_user->id)
				view_profile($db, $user->id);
?>
<div class="container-user-infos">
	<div class="row">

	<!-- Contenedor -->
	<ul id="accordion" class="accordion">
	<li>
<div class="col col_4 iamgurdeep-pic">
<img class="img-responsive iamgurdeeposahan" alt="iamgurdeeposahan" <?php echo 'src="' . $user_infos->photo_profile  . '"'?>/ >
<div class="edit-pic">
<?php if ($user->id != $my_user->id) { ?>
<a href="#report" class="fa fa-bug"></a>
<a href=<?php echo '"app/model/API/report_and_block.php?type=block&target=' . $user->id . '"'; ?> class="fa fa-ban"></a>
<?php } ?>
<!--a href="https://twitter.com/gurdeeposahan1" target="_blank" class="fa fa-twitter"></a>
<a href="https://plus.google.com/u/0/105032594920038016998" target="_blank" class="fa fa-google"></a-->
</div>
<div class="username">
	<h2><?php echo $user_infos->last_name . " " . $user_infos->first_name ?>  <small><i class="fa fa-map-marker"></i> <?php echo $user_infos->country . " (" . $user_infos->town . ")"; ?></small></h2>
	<p><i class="fa fa-briefcase"></i> <?php echo $user_infos->job;  ?></p>
	<p><i class="fa fa-fire"></i> <?php echo intval(get_popularity($db, $user->id));  ?></p>

	<?php
	if ($user->id != $my_user->id)
	{
		$tab_usersBlocked = explode(';', $my_user_infos->users_blocked);
		$friends = explode(";", $my_user_infos->friends);

		if ((is_array($friends) && in_array($user->id, $friends)) || get_if_liked($db, $my_user, $user->id))
			echo '<a <?php echo href="app/model/API/add_friend.php?type=delete&id_friend=' . $user_infos->id_user .'" class="btn-o"> <i class="fa fa-user-plus"></i>Unlike</a>';
		else if (!in_array($user->id, $tab_usersBlocked))
			echo '<a <?php echo href="app/model/API/add_friend.php?type=add&id_friend=' . $user_infos->id_user .'" class="btn-o"> <i class="fa fa-user-plus"></i>Like</a>';
		if (is_connected($db, $user->id))
			echo '  <small><i class="fa fa-plug" style="color:green;"></i> Connected</small>';
		else
			echo '  <small><i class="fa fa-plug text-danger" style="color:red;"></i> Disonnected (' . $user->last_connexion . ')</small>';	
		if (in_array($user->id, $tab_usersBlocked))
			echo '<br /><small><i style="color:red;">Vous avez bloqué cet utilisateur !</i></small>';	
	}
?>

</div>

</div>

	</li>
		<li>
			
  				<div class="link"><i class="fa fa-globe"></i>About<i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">

			
				<?php 
				if ($user->id == get_my_user_id($db))
				{
					echo ' 
					<li>
							 <form action="app/model/API/modify_user.php?type=about" method="post" id="about_form"> 
							<center>
							  <li>
							  <br />
								<p class="text-white bg-dark">Date of Birth</p>
									<input type="text" id="date" class="form-control-plaintext" name="date_birth" value="' . $user_infos->date_birth . '">
							  </li>
							  <li>
							  	<br />
								<p class="text-white bg-dark">Town</p>
									<input type="text" class="form-control-plaintext" name="town" value="' . $user_infos->town . '">
							  </li>
							  <li>
							  	<br />
								<p class="text-white bg-dark">Country</p>
									<input type="text" class="form-control-plaintext" name="country" value="' . $user_infos->country . '">
							  </li>
							  <li>
							  <br />
								<p class="text-white bg-dark">Email</p>
									<input type="text" class="form-control-plaintext"  name="mail" value="' . $user->mail . '">
							  </li>
							  <li>
							  <br />
								<p class="text-white bg-dark">Phone</p>
									<input type="text" class="form-control-plaintext"  name="phone" value="' . $user_infos->phone . '">
							  </li>
							  <li>
							  <br />
								<p class="text-white bg-dark">Job</p>
									<input type="text" class="form-control-plaintext"  name="job" value="' . $user_infos->job . '">
							  </li>
							  <br />
							   <li>
							  <br />
								<p class="text-white bg-dark">last_name</p>
									<input type="text" class="form-control-plaintext"  name="last name" value="' . $user_infos->last_name . '">
							  </li>
							  <br />
							   <li>
							  <br />
								<p class="text-white bg-dark">Sexe</p>
									<select name="sex">
										<option '; if ($user_infos->sex == "male") echo 'selected="selected"'; echo 'value="male">male</option>
										<option '; if ($user_infos->sex == "female") echo 'selected="selected"'; echo 'value="female">female</option>
										<option '; if ($user_infos->sex == "bi") echo 'selected="selected"'; echo 'value="bi">bi</option>
									</select>
							  </li>
							  <br />
							   <li>
							  <br />
								<p class="text-white bg-dark">Cherche</p>
									<select name="sex_preference">
										<option '; if ($user_infos->sex_preference == "male") echo 'selected="selected"'; echo 'value="male">male</option>
										<option '; if ($user_infos->sex_preference == "female") echo 'selected="selected"'; echo 'value="female">female</option>
										<option '; if ($user_infos->sex_preference == "bi") echo 'selected="selected"'; echo ' value="bi">bi</option>
									</select>
							  </li>
							  <br />
							  <li>
							  <br />
								<p class="text-white bg-dark">Bio</p>
									<textarea style="resize:none;" rows="4" cols="50" maxlength="255" name="bio" form="about_form">' . $user_infos->bio . '</textarea>
							  </li>
							  <br />
							  <input type="submit" class="form-control-plaintext" value="Save">
						   </form>
						   </center>';
				}
				else
				{
				?>
				<li><a href="#"><i class="fa fa-calendar left-none"></i> Date of Birth : <?php echo $user_infos->date_birth; ?></a></li>
				<li><a href="#">Address : <?php echo strtoupper($user_infos->town) . ', ' . $user_infos->country; ?></a></li>
				<li><a href="#">Phone : +33<?php echo $user_infos->phone; ?></a></li>
				<li><a href="#">Sexe : <?php echo $user_infos->sex; ?></a></li>
				<li><a href="#">Cherche : <?php echo $user_infos->sex_preference; ?></a></li>
				<li><a href="#">Biographie : <?php echo $user_infos->bio; ?></a></li>
				<?php } 
					$last_location = get_last_location($db, $user->id);
					if ($last_location != null)
						echo '<li><a href="#">Last location : ' . $last_location->location . '</a></li>';
				?>

			</ul>	
		</li>
		<li class="default open">
			<div class="link"><i class="fa fa-code"></i>Tags et interets<i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">
				<li>
<?php 	
			if ($user->id == get_my_user_id($db))
			{
				$tags = str_replace(";", ",", $user_infos->tags);
				echo '<form class="form-tags" action="app/model/API/modify_user.php?type=tags" method="post" onkeypress="if (event.keyCode == 13) {return false;}">
				<input type="test" name="tags_values" value="' . $tags . '" data-role="tagsinput"/ >
				<input type="submit" value="Save" / >
				</form>';
			}
			else
			{
				echo '<a href="#">';
				$tags = get_tags($user_infos);
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span class="tags">#' . $tags[$i] . '</span>';
					if ($i + 1 != count($tags))
						echo ' ';
				}
				echo '</a>';
			}
?>
				</li>
			</ul>
		</li>
		<!-- photos -->
		<li>
<?php
			$photos = get_photos($db, $user->id);
			$nb = count($photos->fetchall());
			$photos = get_photos($db, $user->id);
			$i = 0;
?>
	<div class="link"><i class="fa fa-picture-o"></i>Photos <small><?php echo $nb ?></small><i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">
				<li class="photosgurdeep">
<?php
			while ($photo = $photos->fetch(PDO::FETCH_OBJ))
			{
				if ($i == 5)
					break;
				echo '<a href="#popup' . $i . '"><img class="img-responsive iamgurdeeposahan" alt="iamgurdeeposahan" src="' . $photo->path . '"/ ></a>' . PHP_EOL;
				$i++;
			}
			if ($nb - $i > 0)
			{
?>
				<!--<a class="view-all" href="#" ><?php echo ($nb - $i) . '+'; ?>-->
<?php
			}
?>
				</a>
				</li>
				<?php
					if ($user->id == get_my_user_id($db) && $nb < 5)
					{
				?>
				<li>		
           				 	<form action="app/model/API/modify_user.php?type=photo" method="post" enctype="multipart/form-data">
           				 		<div class="form-group">
    								<input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
    								<input type="submit" value="Upload Image" name="submit">
    							</div>
							</form>
				</li>
				<?php
			}
				?>
			</ul>
		</li>
		
		<!-- end photos -->
<?php
		$friends = get_friends($db, $user->id);
		$nb = count($friends);
		$j = 0;
?>
	<li><div class="link"><i class="fa fa-users"></i>Friends <small><?php echo $nb; ?></small><i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">
				<li class="photosgurdeep">
<?php
		for ($i = 0; $i < $nb; $i++)
		{
			if ($i >= 9)
				break;
			echo '<a href="index.php?page=user_infos&user_id=' . $friends[$i]->id . '"><img class="img-responsive iamgurdeeposahan" alt="iamgurdeeposahan" src="' . get_user_infos_obj($db, $friends[$i]->id)->photo_profile  . '"></a>' . PHP_EOL;	
			$j++;
		}
?>				
<?php			
		if ($nb - $j > 0)
		{
?>
			<a class="view-all" href="#popup-friends" target="_blank"><?php echo ($nb - $j) . '+'; ?></a>
<?php
		}
?>
				</li>
			</ul>
		</li>
	</ul>
	</div>
</div>
<!-- photos click -->
		<?php
			$photos = get_photos($db, $user->id);
			$i = 0;

			while ($photo = $photos->fetch(PDO::FETCH_OBJ))
			{
				echo '<div id="popup'. $i . '" class="overlay">
							<div class="popup-photo">
								<a class="close" href="#">x</a>
								<center><img class="photo-user-pres" src="' . $photo->path . '" /></center>';
				if ($user->id == get_my_user_id($db))
				{
					?>
						<form action="app/model/API/modify_user.php?type=photo_profile" method="post" enctype="multipart/form-data">
           				 	<div class="form-group">
    							<input type="hidden" name="photo_profile" value=<?php echo '"' . $photo->path . '"'?>/ >
    							<input type="submit" value="Changer photo de profile" name="submit"/ >
    						</div>
						</form>
						<form action="app/model/API/modify_user.php?type=delete_photo" method="post" enctype="multipart/form-data">
							<input type="hidden" name="delete_photo" value=<?php echo '"' . $photo->id . '"'?>>
							<input type="submit" name="submit" value="Supprimer"/ >
						</form>
					<?php
				}
				$i++;
				echo '</div></div>';
			}
		?>
		<!-- end photos click -->

		
		<!-- friends click -->

		<div id="popup-friends" class="overlay">
			<div class="popup-friends">
				<a class="close" href="#">X</a>	
					<div class="friends-container">			
							<?php
							for ($i = 0; $i < $nb; $i++)
							{
								echo '<a href="index.php?page=user_infos&user_id=' . $friends[$i]->id . '"><img class="img-responsive friends-all" alt="iamgurdeeposahan" src="' . get_user_infos_obj($db, $friends[$i]->id)->photo_profile  . '"></a>' . PHP_EOL;	
							}
							?>
					</div>
			</div>
		</div>

		<!-- end friends click-->


		<!-- Reports-->

		<div id="report" class="overlay">
			<div class="popup-report">
				<a class="close" href="#">X</a>	
					<div class="report-container">			
							<form action=<?php echo '"app/model/API/report_and_block.php?type=report&target=' . $user->id . '"'; ?> method="post">
 								<div class="form-group">
  								  <fieldset class="form-group">
								    <legend>Reporter l'utilisateur</legend>
								    <div class="form-check">
								      <label class="form-check-label">
								        <input type="radio" class="form-check-input" name="optionsRadios" id="reportFeeding" value="reportFeeding" checked>
								        Intentional feeding
								      </label>
								    </div>
								    <div class="form-check">
								    <label class="form-check-label">
								        <input type="radio" class="form-check-input" name="optionsRadios" id="reportVerbal" value="reportVerbal">
								        Verbal abuse
								      </label>
								    </div>
								    <div class="form-check-label">
								    <label class="form-check-label">
								        <input type="radio" class="form-check-input" name="optionsRadios" id="reportCheat" value="reportCheat">
								       Anti-jeu/Cheat
								      </label>
								    </div>
								  </fieldset>
								  <input type="submit"/ >
  								</div>
  							</form>
					</div>
			</div>
		</div>

		<!-- end reports -->
<?php

	}
}
?>
