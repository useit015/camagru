<nav class="navbar navbar-expand-lg navbar-dark mb-3 bg-dark">
	<div class="container">
		<a class="navbar-brand" href="<?= URLROOT ?>"><?= SITENAME ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
			aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?= URLROOT ?>">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= URLROOT ?>/pages/about">About</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?= URLROOT ?>/users/register">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= URLROOT ?>/users/login">Login</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
