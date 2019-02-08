<?php

class Posts extends Controller {

	public function __construct() {
		if (!isLoggedIn())
			redirect('users/login');
		$this->postModel = $this->model('Post');
		$this->userModel = $this->model('User');
	}

	public function index() {
		$posts = $this->postModel->getPosts();
		$data = [
			'posts' => $posts
		];
		$this->view('posts/index', $data);
	}

	public function add() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'super' => $_POST['super'],
				'user_id' => $_SESSION['user_id'],
				'x' => $_POST['x'],
				'y' => $_POST['y'],
				'image_err' => ''
			];
			if ($_POST['type'])
				$data['image'] = $this->postModel->uploadImage($_FILES['image']);
			else
				$data['image'] = $this->postModel->saveImage64($_POST['imageData']);
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
		} else {
			$data = [
				'title' => '',
				'body' => ''
			];
			$this->view('posts/add', $data);
		}
	}

	public function edit($id = -1) {
		if ($id == -1)
			redirect('posts');
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'id' => $id,
				'title' => trim($_POST['title']),
				'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				'title_err' => '',
				'body_err' => ''
			];
			if (empty($data['title'])) {
				$data['title_err'] = 'Please enter title';
			}
			if (empty($data['body'])) {
				$data['body_err'] = 'Please enter body text';
			}
			if (empty($data['title_err']) && empty($data['body_err'])) {
				if ($this->postModel->updatePost($data)) {
					flash('post_message', 'Post Updated');
					redirect('posts');
				} else {
					die('Ouups .. something went wrong !');
				}
			} else {
				$this->view('posts/edit', $data);
			}
		} else {
			$post = $this->postModel->getPostById($id);
			if ($post->user_id != $_SESSION['user_id'])
				redirect('posts');
			$data = [
				'id' => $id,
				'title' => $post->title,
				'body' => $post->body
			];
			$this->view('posts/edit', $data);
		}
	}

	public function show($id = -1) {
		if ($id !== -1) {
			$post = $this->postModel->getPostById($id);
			$user = $this->userModel->getUserById($post->user_id);
			$comments = $this->postModel->getPostComments($id);
			$data = [
				'post' => $post,
				'user' => $user,
				'comments' => $comments,
				'comment_err' => '',
				'userLikes' => $this->postModel->userLikes($id, $_SESSION['user_id'])
			];
			$data['url'] = URLROOT.'/posts/show/'.$data['post']->id;
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
						sendCommentNotification($data['user']->email, $data['url']);
						flash('post_message', 'Comment Added');
						$this->view('posts/show', $data);
					} else
						die('Oups!! something went wrong..');
				} elseif (isset($_POST['like'])) {
					if ($_POST['like'] != 0) {
						if ($this->postModel->unlikePost($id, $_SESSION['user_id'])) {
							$data['userLikes'] = 0;
							sendLikeNotification($data['user']->email, $data['url']);
							flash('post_message', 'Like removed', 'alert alert-danger');
							$this->view('posts/show', $data);
						} else
							die('Oups!! something went wrong..');
					} else {
						$like = [
							'user_id' => $_SESSION['user_id'],
							'post_id' => $id
						];
						if ($this->postModel->addLike($like)) {
							$data['userLikes'] = 1;
							flash('post_message', 'Like Added');
							$this->view('posts/show', $data);
						} else
							die('Oups!! something went wrong..');
					}
				}
			} else
				$this->view('posts/show', $data);
		} else
			redirect('posts');
	}

	public function delete($id = -1) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id !== -1) {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$post = $this->postModel->getPostById($id);
			if ($post->user_id != $_SESSION['user_id'])
				redirect('posts');
			if ($this->postModel->deletePost($id)) {
				flash('post_message', 'Post Removed');
				redirect('posts');
			} else
				die('Ouups .. something went wrong !');
		} else
			redirect('posts');
	}

	public function cmnt_del($id = -1) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id !== -1) {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if (isset($_POST['comment']) && isset($_POST['post_id']) && $_POST['comment'] === $id) {
				if ($this->postModel->deleteComment($id, $_SESSION['user_id'])) {
					flash('post_message', 'Comment Removed', 'alert alert-danger');
					redirect('posts/show/'.$_POST['post_id']);
				} else
					die('Ouups .. something went wrong !');
			} else
				redirect('posts');
		} else
			redirect('posts');
	}

}

?>