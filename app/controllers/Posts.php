<?php

class Posts extends Controller {
	private $perPage = 5;

	public function __construct() {
		$this->postModel = $this->model('Post');
		$this->userModel = $this->model('User');
	}

	public function index() {
		$posts = $this->postModel->getPostsN(0, $this->perPage);
		$data = [
			'posts' => $posts
		];
		$this->view('posts/index', $data);
	}

	public function pages($page = -1) {
		if ($page !== -1) {
			header('Content-type: text/json');
			$posts = $this->postModel->getPostsN($page, $this->perPage);
			foreach ($posts as $post)
				$post->postCreated = time_elapsed_string($post->postCreated);
			echo json_encode($posts);
		}
	}

	public function add($id = -1) {
		if ($id == $_SESSION['user_id']) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
					$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
					$data = [
						'super' => $_POST['super'],
						'user_id' => $_SESSION['user_id'],
						'x' => $_POST['x'],
						'y' => $_POST['y'],
						'image_err' => ''
					];
					if ($_POST['type'] == 'camera')
						$data['image'] = $this->postModel->saveImage64($_POST['imageData']);
					else
						$data['image'] = $this->postModel->uploadImage($_FILES['image']);
					watermark(dirname(dirname(APPROOT)).'/'.$data['image'], $data['super'], $data['x'], $data['y']);
					if (empty($data['image']))
						$data['image_err'] = 'Please upload an image';
					if (empty($data['image_err'])) {
						if ($this->postModel->addPost($data)) {
							flash('post_message', 'Post Added');
							redirect('posts');
						} else
							die('Ouups .. something went wrong !');
					} else
						$this->view('posts/add', $data);
				} else
					redirect('pages');
			} else {
				$data = [
					'posts' => $this->postModel->getUserPosts($_SESSION['user_id'])
				];
				$this->view('posts/add', $data);
			}
		} else
			redirect('posts');
	}

	public function show($id = -1) {
		if ($id !== -1) {
			$post = $this->postModel->getPostById($id);
			$user = $this->userModel->getUserById($post->user_id);
			$comments = $this->postModel->getPostComments($id);
			$likes = $this->postModel->getPostLikes($id);
			$data = [
				'post' => $post,
				'user' => $user,
				'likes' => $likes,
				'comments' => $comments,
				'comment_err' => '',
				'userLikes' => $this->postModel->userLikes($id, $_SESSION['user_id'])
			];
			$data['url'] = URLROOT.'/posts/show/'.$data['post']->id;
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
					$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
					if (isset($_POST['comment'])) {
						$comment = [
							'user_id' => $_SESSION['user_id'],
							'post_id' => $id,
							'body' => trim($_POST['comment']),
						];
						if (empty($comment['body'])) {
							$data['comment_err'] = 'Comment shall not be empty.';
							$this->view('posts/show', $data);
						} elseif ($this->postModel->addComment($comment)) {
							$data['comments'] = $this->postModel->getPostComments($id);
							if ($data['user']->notif) {
								$mail = new Mailer('comment_notif', $data['user']->email, $data['url']);
								$mail->send();
							}
							flash('post_message', 'Comment Added');
							$this->view('posts/show', $data);
						} else
							die('Oups!! something went wrong..');
					} elseif (isset($_POST['like'])) {
						if ($_POST['like'] != 0) {
							if ($this->postModel->unlikePost($id, $_SESSION['user_id'])) {
								$data['userLikes'] = 0;
								$data['likes'] = $this->postModel->getPostLikes($id);
								flash('post_message', 'Like removed', 'alert alert-danger');
								$this->view('posts/show', $data);
							} else
								die('Oups!! something went wrong..');
						} else {
							$like = [
								'user_id' => $_SESSION['user_id'],
								'post_id' => $id
							];
							if ($data['userLikes'] == 0) {
								if ($this->postModel->addLike($like)) {
									$data['userLikes'] = 1;
									$data['likes'] = $this->postModel->getPostLikes($id);
									if ($data['user']->notif) {
										$mail = new Mailer('like_notif', $data['user']->email, $data['url']);
										$mail->send();
									}
									flash('post_message', 'Like Added');
									$this->view('posts/show', $data);
								} else
									die('Oups!! something went wrong..');
							} else
								$this->view('posts/show', $data);
						}
					}
				} else
					redirect('pages');
			} else
				$this->view('posts/show', $data);
		} else
			redirect('posts');
	}

	public function delete($id = -1) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id !== -1) {
			if (isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$post = $this->postModel->getPostById($id);
				if ($post->user_id != $_SESSION['user_id'])
					redirect('posts');
				if ($this->postModel->deletePost($id)) {
					flash('post_message', 'Post Removed');
					redirect('posts');
				} else
					die('Oups!! something went wrong..');
			} else
				redirect('pages');
		} else
			redirect('posts');
	}

	public function cmnt_del($id = -1) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id !== -1) {
			if (isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				if (isset($_POST['comment']) && isset($_POST['post_id']) && $_POST['comment'] === $id) {
					if ($this->postModel->deleteComment($id, $_SESSION['user_id'])) {
						flash('post_message', 'Comment Removed', 'alert alert-danger');
						redirect('posts/show/'.$_POST['post_id']);
					} else
						die('Oups!! something went wrong..');
				} else
					redirect('posts');
			} else
				die('SUPRISE MOTHERFUCKER!!');
		} else
			redirect('posts');
	}

}

?>