<nav class="navbar navbar-expand-lg navbar-dark mb-3 bg-dark">
	<div class="container">
		<a class="navbar-brand" href="<?php echo URLROOT; ?>"><?= SITENAME ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
			aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo URLROOT; ?>">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<?php if(isset($_SESSION['user_id'])) : ?>
					<li class="nav-item">
						<a class="nav-link" href="#">Welcome <?php echo $_SESSION['user_name']; ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
					</li>
				<?php else : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
