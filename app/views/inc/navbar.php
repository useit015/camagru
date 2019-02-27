<nav class="navbar navbar-expand-lg navbar-dark mb-3 pb-3 bg-dark">
	<div class="container">
		<a class="ml-3 navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
		<ul class="navbar-nav ml-auto">
			<?php if(isset($_SESSION['user_id'])) : ?>
				<li class="nav-item mx-3 nav-menu-icon">
					<a href="<?php echo URLROOT; ?>/posts/add/<?php echo $_SESSION['user_id'];?>" class="nav-link">
						<i class="fa fa-camera"></i>
					</a>
				</li>
				<li class="nav-item mx-3 nav-menu-icon">
					<a class="nav-link" href="<?php echo URLROOT; ?>/users/settings/<?php echo $_SESSION['user_id']; ?>">
						<i class="fa fa-cogs"></i>
					</a>
				</li>
				<li class="nav-item mx-3 nav-menu-icon">
					<form action="<?php echo URLROOT; ?>/users/logout" method="post">
						<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
						<button class="nav-link logout-btn">
							<i class="fa fa-sign-out"></i>
						</button>
					</form>
				</li>
			<?php else : ?>
				<li class="nav-item">
					<a class="nav-link mx-3" href="<?php echo URLROOT; ?>/users/register">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link mx-3" href="<?php echo URLROOT; ?>/users/login">Login</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</nav>
