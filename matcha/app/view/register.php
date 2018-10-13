<div class="container">
	<div class="row main">
		<div class="panel-heading">
           <div class="panel-title text-center">
           		<h1 class="title">Et si tu tentais ta chance ?</h1>
           		<br / >
           		<?php if (isset($_GET['error']))
           		{
           			if ($_GET['error'] == 1)
           			{
           		?>
           		<p>La combinaison utilisateur/mot de passe est incorrecte, ou bien le mail existe déjà.</p>
           		<?php 
           			} 
           		}
           		?>
           	</div>
        </div> 
		<div class="main-login main-center">
			<form class="form-horizontal" method="post" action="app/model/api/register.php">
				
				<div class="form-group">
					<label for="name" class="cols-sm-2 control-label">Your Last-Name</label>
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Enter your Name"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="name" class="cols-sm-2 control-label">Your First-Name</label>
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="first_name" id="first_name"  placeholder="Enter your Name"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="cols-sm-2 control-label">Your Email</label>
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="mail" id="mail"  placeholder="Enter your Email"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="password" class="cols-sm-2 control-label">Password</label>
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
							<input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
					<div class="cols-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
							<input type="password" class="form-control" name="confirm_password" id="confirm"  placeholder="Confirm your Password"/>
						</div>
					</div>
				</div>

				<div class="form-group ">
					<input type="submit" value="Register" class="btn btn-primary btn-lg btn-block login-button"/ >
				</div>
				<div class="login-register">
		            <a href="index.php">Login</a>
		         </div>
			</form>
		</div>
	</div>
</div>
