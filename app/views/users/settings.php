<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="row">
		<div class="col mx-auto">
			<div class="card card-body bg-light mt-5 pt-5">
				<form action="<?php echo URLROOT; ?>/users/settings/<?php echo $data['id']; ?>" method="post"  enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-4 d-flex align-items-center justify-content-center px-4">
							<div class="form-group settings-img" style="background-image:url(<?php echo URL.'/'.$_SESSION['user_img']; ?>);"></div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="name">Login:</label>
								<input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
								<span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
							</div>
							<div class="form-group">
								<label for="email">Email:</label>
								<input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
								<span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
							</div>
							<div class="form-group">
								<label for="number">Number:</label>
								<input type="text" name="number" class="form-control form-control-lg <?php echo (!empty($data['number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['number']; ?>">
								<span class="invalid-feedback"><?php echo $data['number_err']; ?></span>
							</div>
							<div class="form-check notif">
								<input type="checkbox" class="form-check-input notif" name="notif" id="notif" <?php echo $data['notif'] ? 'checked' : ''; ?> value="<?php echo $data['notif'] ? 'check' : 'uncheck'; ?>">
								<label class="form-check-label" for="notif">Receive Notifications</label>
							</div>
						</div>
					</div>
					<div class="row mt-5">
						<div class="col-md-4">
							<div class="form-group">
								<span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
								<input name="image" type="file" class="imgInput" value="<?php echo $_SESSION['user_img']; ?>"/>
								<div class="custom-file-upload btn btn-block btn-lg btn-primary">Change Image</div>
							</div>
						</div>
						<div class="col-md-4">
							<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
							<input type="submit" value="Update" class="btn btn-success btn-block btn-lg">
						</div>
						<div class="col-md-4">
							<a href="#" class="btn btn-light btn-block btn-lg settings-change_password">Change password ?</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="overlay" style="display:<?php echo $data['password_update'] ? 'block' : 'none'; ?>;">
		<div class="change_password-box p-3">
			<button class="likes-close_btn">
				<i class="fa fa-times-circle" aria-hidden="true"></i>
			</button>
			<form action="<?php echo URLROOT; ?>/users/settings/<?php echo $data['id']; ?>" method="post" class="mt-4 mx-3">
				<input type="hidden" name="password_change" value="change">
				<div class="form-group">
					<label for="old_password">Old Password:</label>
					<input type="password" value="<?php echo $data['old_password']; ?>" name="old_password" class="form-control form-control-lg <?php echo (!empty($data['old_password_err'])) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"><?php echo $data['old_password_err']; ?></span>
				</div>
				<div class="form-group">
					<label for="new_password">New Password:</label>
					<input type="password" value="<?php echo $data['new_password']; ?>" name="new_password" class="form-control form-control-lg <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"><?php echo $data['new_password_err']; ?></span>
				</div>
				<div class="form-group">
					<label for="confirm_new_password">Confirm New Password:</label>
					<input type="password" value="<?php echo $data['confirm_new_password']; ?>" name="confirm_new_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_new_password_err'])) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"><?php echo $data['confirm_new_password_err']; ?></span>
				</div>
				<div class="input-group mt-4 mb-3">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
					<input type="submit" value="Update" class="btn btn-success btn-block btn-lg mt-3">
				</div>
			</form>
		</div>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>