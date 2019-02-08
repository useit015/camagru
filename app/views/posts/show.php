<?php require APPROOT.'/views/inc/header.php'; ?>

	<div class="my-5">
		<?php flash('post_message'); ?>
		<div class="main_img-container">
			<img class="main_img-img" src="<?php echo URL.'/'.$data['post']->img; ?>">
			<div class="main_img-overlay"></div>
			<div class="main_img-details">
				<div class="up">
					<?php if ($data['post']->user_id == $_SESSION['user_id']) : ?>
						<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="main_img-details_edit">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a>
						<form action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" method="post">
							<button type="submit" class="main_img-details_del">
								<i class="fa fa-trash" aria-hidden="true"></i>
							</button>
						</form>
					<?php endif; ?>
					<a href="<?php echo URLROOT; ?>/posts" class="main_img-details_back">
						<i class="fa fa-times-circle"></i>
					</a>
				</div>
				<form action="<?php echo $data['url']; ?>" method="post" class="down">
					<!-- <span class="like_count"><?php //echo $data['post']->cmntCount; ?></span> -->
					<input type="hidden" name="like" value="<?php echo $data['userLikes']; ?>">
					<button type="submit" class="main_img-details_like <?php echo $data['userLikes'] != 0 ? 'active' : ''; ?>">
						<i class="fa fa-thumbs-up" aria-hidden="true"></i>
					</button>
				</form>
			</div>
			<div class="cmnt-time"><?php echo time_elapsed_string($data['post']->created_at).' by '.$data['user']->name; ?></div>
		</div>
	</div>
	<div class="container">
		<?php foreach($data['comments'] as $cmnt) : ?>
			<div class="row">
				<div class="my-2 mb-3 cmnt">
					<div class="cmnt-img_container d-flex justify-content-center align-items-start">
						<div class="cmnt-img" style="background-image:url(<?php echo URL.'/'.$cmnt->userImg; ?>)"></div>
					</div>
					<div class="cmnt-body px-3 py-2 mx-2">
						<span class="lead mr-1"><?php echo $cmnt->userName; ?></span>
						<span><?php echo $cmnt->cmntBody; ?></span>
						<?php if ($cmnt->userId == $_SESSION['user_id']) : ?>
							<form method="post" action="<?php echo URLROOT.'/posts/cmnt_del/'.$cmnt->cmntId ?>" class="cmnt-del">
								<input type="hidden" name="comment" value="<?php echo $cmnt->cmntId; ?>">
								<input type="hidden" name="post_id" value="<?php echo $data['post']->id; ?>">
								<button type="submit" class="cmnt-del_btn">
									<i class="fa fa-times-circle" aria-hidden="true"></i>
								</button>
							</form>
						<?php endif; ?>
						<div class="cmnt-time"><?php echo time_elapsed_string($cmnt->cmntCreated); ?></div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="row mt-3">
			<div class="my-2 cmnt align-items-start">
				<div class="cmnt-img_container d-flex justify-content-center align-items-start">
					<div class="cmnt-img" style="background-image:url(<?php echo URL.'/'.$_SESSION['user_img']; ?>)"></div>
				</div>
				<form action="<?php echo URLROOT.'/posts/show/'.$data['post']->id; ?>" method="post" class="ml-3 cmnt-input">
					<div class="form-group m-0">
						<input type="text" name="comment" id="comment" class="form-control form-control-lg <?php echo (!empty($data['comment_err'])) ? 'is-invalid' : ''; ?>" placeholder="Write a comment...">
						<button type="submit" class="cmnt-submit">
							<span class="fa fa-paper-plane cmnt-submit_icon"></span>
						</button>
						<span class="invalid-feedback cmnt-feedback"><?= $data['comment_err'] ?></span>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php require APPROOT.'/views/inc/footer.php'; ?>