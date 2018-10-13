<br />
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					
					<?php
						if (!isset($_GET['type']))
						{
					?>

					<center><h3 class="panel-title">Connexion</h3></center>
				</div>
				<div class="panel-body">
					<form accept-charset="UTF-8" role="form" action="app/model/API/connexion.php" method="post">
					<fieldset>
						<div class="form-group">
							<input class="form-control" placeholder="Login" name="login" type="text">
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="Password" name="password" type="password" value="">
						</div>
						<a href="?page=connexion&type=pass_forbidden">Mot de passe oublié ?</a>
						<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
					</fieldset>
					</form>
					
					<?php
						}
						else if (isset($_GET['type']) && $_GET['type'] == "pass_forbidden")
						{
							if (isset($_GET['error']))
							{
								if ($_GET['error'] == 1)
									echo "<b>Le login/mail n'existe pas !</b>";
							}
					?>
						<center><h3 class="panel-title">Mot de passe oublié</h3></center>
				</div>
				<div class="panel-body">
					<form accept-charset="UTF-8" role="form" action="app/model/API/pass_forbidden.php" method="post">
					<fieldset>
						<div class="form-group">
							<input class="form-control" placeholder="Login" name="login" type="text">
						</div>
						<input class="btn btn-lg btn-success btn-block" type="submit" value="Envoyer le mail">
					</fieldset>
					</form>
					
					<?php
						}
						else if (isset($_GET['type']) && $_GET['type'] == "new_password")
						{
							if ((isset($_GET['key']) && key_exist($db, $_GET['key'])) || (isset($_SESSION['logged']) && $_SESSION['logged'] == true))
							{
								if (isset($_GET['error']))
								{
									if ($_GET['error'] == 1)
										echo "<b>Le mot de passe n'est pas correcte !</b>";
								}
								else 
								{
					?>
						<center><h3 class="panel-title">Remplacement du mot de passe</h3></center>
				</div>
				<div class="panel-body">
					<form accept-charset="UTF-8" role="form" action="app/model/API/pass_forbidden.php" method="post">
					<fieldset>
						<div class="form-group">
							<input class="form-control" placeholder="Password" name="password" type="password">
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="Confirmation" name="password_conf" type="password">
						<?php if (isset($_GET['key'])) { ?>
							<input type="hidden" name="key" value=<?php echo '"' . $_GET['key'] . '"'?>/ >
						<?php } ?>
						</div>
						<input class="btn btn-lg btn-success btn-block" type="submit" value="Changer le mot de passe">
					</fieldset>
					</form>

				<?php
							}
						}
						else
							echo "<b>WTF ? Tu pensais m'avoir comme ca ?</b>";
					}
				?>

				</div>
			</div>
		</div>
	</div>
</div>
