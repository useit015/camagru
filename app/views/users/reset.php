<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="row">
		<div class="col-md-6 mx-auto">
			<?php flash('register_success'); ?>
			<?php flash('reset_success'); ?>
			<div class="card card-body bg-light mt-5">
				<h2>Reset Password</h2>
				<form action="<?php echo URLROOT; ?>/users/reset_password/<?php echo $data['sel'].'_'.$data['val'];?>" method="post">
					<input type="hidden" name="selector" value="<?php echo $data['sel']; ?>">
					<input type="hidden" name="validator" value="<?php echo $data['val']; ?>">
					<div class="form-group">
						<label for="password">Password: <sup>*</sup></label>
						<input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
						<span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirm password: <sup>*</sup></label>
						<input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
						<span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
					</div>
					<div class="row">
						<div class="col">
							<input type="submit" value="Reset" class="btn btn-lg btn-success btn-block">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>