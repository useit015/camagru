<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="row my-4">
		<div class="col">
			<h1 class="text-center display-2">Gallery</h1>
		</div>
	</div>
	<?php flash('post_message'); ?>
	<?php flash('email_changed'); ?>
	<?php flash('password_changed'); ?>
	<div class="gallery mb-5">
		<?php foreach($data['posts'] as $post) : ?>
			<div class="gallery-item">
				<a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>">
					<img class="gallery-image" src="<?php echo URL.'/'.$post->postImg; ?>">
					<div class="gallery-item-details">
						<div class="gallery-img_container d-flex justify-content-center align-items-start">
							<div class="cmnt-img" style="background-image:url(<?php echo URL.'/'.$post->userImg; ?>)"></div>
						</div>
						<h5><?php echo $post->userName; ?><span><?php  echo time_elapsed_string($post->postCreated); ?></span></h5>
					</div>
					<h5></h5>
					<div class="gallery-item-details_like">
						<i class="fa fa-thumbs-up gallery-item-details_like-icon px-2"></i>
						<span><?php echo $post->postLikeCount; ?></span>
					</div>
					<div class="gallery-item-details_cmnt">
						<i class="fa fa-comment gallery-item-details_cmnt-icon px-2"></i>
						<span><?php echo $post->postCmntCount; ?></span>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<script src="<?php echo URLROOT; ?>/js/infinite.js"></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>