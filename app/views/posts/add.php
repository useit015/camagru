<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="card card-body bg-light mt-5 add_container">
		<a href="<?php echo URLROOT; ?>/posts" class="add_container-del"><i class="fa fa-times-circle"></i></a>
		<form action="<?php echo URLROOT; ?>/posts/add/<?php echo $_SESSION['user_id']; ?>" method="post" enctype="multipart/form-data">
			<div class="row p-3">
				<div class="col-md-6 canvas_container">
					<i class="fa fa-upload upload-icon"></i>
					<img class="canvas_sup" draggable="true">
					<canvas class="photo"></canvas>
					<video class="player"></video>
				</div>
				<div class="col-md-3 col-sm-6 d-flex flex-column justify-content-around">
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/1.png" class="form-check-input hidden sup" name="super" id="super1">
						<label for="super1" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/1.png" alt="" class="sup">
						</label>
					</div>
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/2.png" class="form-check-input hidden sup" name="super" id="super2">
						<label for="super2" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/2.png" alt="" class="sup">
						</label>
					</div>
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/3.png" class="form-check-input hidden sup" name="super" id="super3">
						<label for="super3" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/3.png" alt="" class="sup">
						</label>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 d-flex flex-column justify-content-around">
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/4.png" class="form-check-input hidden sup" name="super" id="super4">
						<label for="super4" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/4.png" alt="" class="sup">
						</label>
					</div>
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/5.png" class="form-check-input hidden sup" name="super" id="super5">
						<label for="super5" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/5.png" alt="" class="sup">
						</label>
					</div>
					<div class="form-check d-flex align-items-center justify-content-center">
						<input type="radio" value="<?php echo URLROOT; ?>/public/img/sup/6.png" class="form-check-input hidden sup" name="super" id="super6">
						<label for="super6" class="form-check-label">
							<img src="<?php echo URLROOT; ?>/public/img/sup/6.png" alt="" class="sup">
						</label>
					</div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-md-8">
						<div class="row my-2 mb-3 justify-content-around">
							<div class="form-group col-md-3 my-2">
								<label for="red">Red:</label>
								<input type="range" class="form-control-range" id="redSlider" value="0" min="0" max="100" name="red">
							</div>
							<div class="form-group col-md-3 my-2">
								<label for="gre">Green:</label>
								<input type="range" class="form-control-range" id="greenSlider" value="0" min="0" max="100" name="gre">
							</div>
							<div class="form-group col-md-3 my-2">
								<label for="blu">Blue:</label>
								<input type="range" class="form-control-range" id="blueSlider" value="0" min="0" max="100" name="blu">
							</div>
						</div>
						<div class="row my-2 mb-3 justify-content-around">
							<div class="form-group col-md-3 my-2">
								<label for="bri">Brightness:</label>
								<input type="range" class="form-control-range" id="briSlider" value="0" min="-100" max="100" name="bri">
							</div>
							<div class="form-check col-md-3 my-2 pl-5 pt-3 d-flex align-items-center">
								<input class="form-check-input filter" type="checkbox" value="split" id="splitCheck">
								<label class="form-check-label" for="splitCheck">Split Colors</label>
							</div>
							<div class="form-check col-md-3 my-2 pl-5 pt-3 d-flex align-items-center">
								<input class="form-check-input filter" type="checkbox" value="invert" id="invertCheck">
								<label class="form-check-label" for="invertCheck">Invert Colors</label>
							</div>
						</div>
				</div>
				<div class="col-md-4">
					<a href="#" class="btn btn-block btn-lg btn-light reset-filters my-4">Reset filters</a>
					<a href="#" class="btn btn-block btn-lg btn-light capture-mode my-4">Switch to retro</a>
				</div>
				<div class="col-md-6">
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-md-6">
					<div class="form-group upload-grp">
						<span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
						<input name="image" type="file" class="imgInput" value="<?php echo $_SESSION['user_img']; ?>"/>
						<div class="custom-file-upload btn btn-block btn-lg btn-primary mb-4">Upload Image</div>
					</div>
					<div class="form-group capture-grp">
						<input name="imageData" type="text" class="imgInputData" value=""/>
						<div class="capture btn btn-block btn-lg btn-primary mb-4">Capture</div>
					</div>
					<div class="hidden">
						<input type="hidden" name="x" id="x">
						<input type="hidden" name="y" id="y">
						<input type="hidden" name="type" value="camera" id="type">
					</div>
				</div>
				<div class="col-md-6">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
					<input type="submit" value="Publish" class="btn btn-block btn-lg btn-success capture-btn mb-4" disabled>
				</div>
			</div>
		</form>
		<audio class="snap" src="<?php echo URLROOT; ?>/public/img/snap.mp3" hidden></audio>
	</div>
	<div class="strip my-5">
		<?php foreach($data['posts'] as $post) : ?>
			<a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->id; ?>">
				<img src="<?php echo URL.'/'.$post->img; ?>" alt="handsome">
			</a>
		<?php endforeach; ?>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>