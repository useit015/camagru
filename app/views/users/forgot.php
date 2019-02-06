<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="row">
		<div class="col-md-8 mx-auto">
			<?php flash('email_sent'); ?>
			<div class="card card-body bg-light mt-5">
				<h2>Reset your password</h2>
				<p>An email will be sent to you with instruction to reset your password.</p>
				<form action="<?php echo URLROOT; ?>/users/forgot" method="post">
					<div class="row">
						<div class="form-group col-md-8">
							<input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email ...">
						</div>
						<div class="col">
							<button type="submit" class="btn btn-dark btn-lg btn-block">
								<i class="fa fa-paper-plane" aria-hidden="true"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>