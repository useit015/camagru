<?php require APPROOT.'/views/inc/header.php'; ?>
<?php flash('post_message'); ?>

	<div class="row mb-3">
		<div class="col-md-6">
			<h1>Gallery</h1>
		</div>
		<div class="col-md-6">
			<a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
				<i class="fa fa-pencil"></i> Add Pic
			</a>
		</div>
	</div>
	<div class="gallery">
		<?php foreach($data['posts'] as $post) : ?>
			<div class="gallery-item">
				<a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>">
					<img class="gallery-image" src="<?php echo URL.'/'.$post->postImg; ?>">
					<div class="gallery-item-overlay"></div>
					<div class="gallery-item-details fadeIn-bottom fadeIn-left">
						<h3>By: <?php echo $post->userName; ?></h3>
						<p><?php echo time_elapsed_string($post->postCreated); ?></p>
						<div class="gallery-item-details_interaction">
							<div class="gallery-item-details_like">
								<i class="fa fa-thumbs-up pb-3 px-3 pt-1 gallery-item-details_like-icon"></i>
								<span><?php echo $post->postLikeCount; ?></span>
							</div>
							<div class="gallery-item-details_cmnt">
								<i class="fa fa-comment pb-3 px-3 gallery-item-details_cmnt-icon"></i>
								<span><?php echo $post->postCmntCount; ?></span>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>