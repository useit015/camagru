<?php require APPROOT.'/views/inc/header.php'; ?>

<div class="card card-body bg-light mt-5 add_container">
	<a href="<?php echo URLROOT; ?>/posts" class="add_container-del"><i class="fa fa-times-circle"></i></a>
	<form action="<?php echo URLROOT; ?>/posts/add" method="post" enctype="multipart/form-data">
		<div class="row p-3 mb-3">
			<div class="col-md-6 canvas_container">
				<img class="canvas_sup" draggable="true">
				<canvas class="photo"></canvas>
				<video class="player"></video>
			</div>
			<div class="col-md-3 d-flex flex-column justify-content-around">
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/1.png" class="form-check-input" name="super" id="super1">
					<label for="super1" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/1.png" alt="" class="sup">
					</label>
				</div>
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/2.png" class="form-check-input" name="super" id="super2">
					<label for="super2" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/2.png" alt="" class="sup">
					</label>
				</div>
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/3.png" class="form-check-input" name="super" id="super3">
					<label for="super3" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/3.png" alt="" class="sup">
					</label>
				</div>
			</div>
			<div class="col-md-3 d-flex flex-column justify-content-around">
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/4.png" class="form-check-input" name="super" id="super4">
					<label for="super4" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/4.png" alt="" class="sup">
					</label>
				</div>
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/5.png" class="form-check-input" name="super" id="super5">
					<label for="super5" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/5.png" alt="" class="sup">
					</label>
				</div>
				<div class="form-check d-flex align-items-center justify-content-center">
					<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/6.png" class="form-check-input" name="super" id="super6">
					<label for="super6" class="form-check-label">
						<img src="<?php echo URLROOT; ?>/public/img/sup/6.png" alt="" class="sup">
					</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group upload-grp">
					<span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
					<input name="image" type="file" class="imgInput" value="<?php echo $_SESSION['user_img']; ?>"/>
					<div class="custom-file-upload btn btn-block btn-primary">Upload Image</div>
				</div>
				<div class="form-group capture-grp">
					<input name="imageData" type="text" class="imgInputData" value=""/>
					<div class="capture btn btn-block btn-primary">Capture</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="x" id="x">
					<input type="hidden" name="y" id="y">
				</div>
			</div>
			<div class="col-md-4">
				<a href="#" class="btn btn-block btn-dark capture-mode">Switch to retro</a>
			</div>
			<div class="col-md-4">
				<input type="submit" value="Take Pic" class="btn btn-block btn-success">
			</div>
		</div>
	</form>
</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>