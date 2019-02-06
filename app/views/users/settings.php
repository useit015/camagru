<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="row">
		<div class="col mx-auto">
			<div class="card card-body bg-light mt-5">
				<h2 class="card-title text-center">Settings</h2>
				<hr class="py-3">
				<form action="<?php echo URLROOT; ?>/users/settings/<?php echo $data['id']; ?>" method="post"  enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-4 d-flex align-items-center justify-content-center">
							<div class="form-group settings-img" style="background-image:url(<?php echo URL.'/'.$_SESSION['user_img']; ?>);"></div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="name">Name:</label>
								<input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
								<span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
							</div>
							<div class="form-group">
								<label for="email">Email:</label>
								<input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
								<span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="**********">
								<span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
							</div>
							<div class="form-group">
								<label for="number">Number:</label>
								<input type="text" name="number" class="form-control form-control-lg <?php echo (!empty($data['number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['number']; ?>">
								<span class="invalid-feedback"><?php echo $data['number_err']; ?></span>
							</div>
						</div>
					</div>
					<div class="row mt-5">
						<div class="col-md-4">
							<div class="form-group">
								<span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
								<input name="image" type="file" class="imgInput" value="<?php echo $_SESSION['user_img']; ?>"/>
								<div class="custom-file-upload btn btn-block btn-primary">Change Image</div>
							</div>
						</div>
						<div class="col-md-4">
							<input type="submit" value="Update" class="btn btn-success btn-block">
						</div>
						<div class="col-md-4">
							<a href="<?= URLROOT ?>/users/login" class="btn btn-light btn-block">Change password ?</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>