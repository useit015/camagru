<?php require APPROOT.'/views/inc/header.php'; ?>

	<img class="card-img-top" src="<?php echo URL.'/'.$data['post']->img; ?>">
	<p><?php echo $data['post']->body; ?></p>
	<?php if ($data['post']->user_id == $_SESSION['user_id']) : ?>
		<hr>
		<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>
		<form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" method="post">
			<input type="submit" value="Delete" class="btn btn-danger ">
		</form>
	<?php endif; ?>
	<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>

<?php require APPROOT.'/views/inc/footer.php'; ?>