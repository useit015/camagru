<?php

class Users extends Controller {

	public function __construct() {
		$this->userModel = $this->model('User');
		$this->h = new UsersHelper($this->userModel);
	}

	public function index() {
		redirect('pages');
	}

	public function register() {
		if (!isLoggedIn()) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'name' => trim($_POST['name']),
					'email' => trim($_POST['email']),
					'password' => trim($_POST['password']),
					'confirm_password' => trim($_POST['confirm_password']),
					'vkey' => md5(time().trim($_POST['email'])),
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];
				$data['name_err'] = $this->h->validateName($data['name']);
				$data['email_err'] = $this->h->validateEmail($data['email']);
				$data['password_err'] = $this->h->validatePassword($data['password']);
				$data['confirm_password_err'] = $this->h->validateConfirmPassword($data['password'], $data['confirm_password']);
				if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
					$url = URLROOT.'/users/verify/'.$data['vkey'];
					$mail = new Mailer('mail_confirm', $data['email'], $url);
					if ($mail->send() && $this->userModel->register($data)) {
						flash('reset_success', 'Please check your email');
						redirect('users/login');
					} else
						die('Oups .. something went wrong !');
				} else
					$this->view('users/register', $data);
			} else {
				$data = [
					'name' => '',
					'email' => '',
					'password' => '',
					'confirm_password' => '',
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];
				$this->view('users/register', $data);
			}
		} else
			redirect('posts');
	}

	public function login() {
		if (!isLoggedIn()) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'email' => trim($_POST['email']),
					'password' => trim($_POST['password']),
					'email_err' => '',
					'password_err' => ''
				];
				$data['email_err'] = $this->h->loginValidateEmail($data['email']);
				$data['password_err'] = $this->h->loginValidatePassword($data['password']);
				if (empty($data['email_err']) && empty($data['password_err'])) {
					$loggedInUser = $this->userModel->login($data['email'], $data['password']);
					if ($loggedInUser) {
						$this->h->createUserSession($loggedInUser);
						redirect('posts');
					} else {
						$data['password_err'] = 'Incorrect email or password';
						$this->view('users/login', $data);
					}
				} else {
					$this->view('users/login', $data);
				}
			} else {
				$data = [
					'email' => '',
					'password' => '',
					'email_err' => '',
					'password_err' => ''
				];
				$this->view('users/login', $data);
			}
		} else
			redirect('posts');
	}

	public function settings($id) {
		if ($id == $_SESSION['user_id']) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (!isset($_POST['token']) || $_POST['token'] != $_SESSION['token'])
					redirect('pages');
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				if (isset($_POST['password_change']) && $_POST['password_change'] == 'change') {
					$data = [
						'id' => $id,
						'name' => $_SESSION['user_name'],
						'email' => $_SESSION['user_email'],
						'number' => $_SESSION['user_nbr'],
						'notif' => $_SESSION['user_notif'],
						'old_password' => $_POST['old_password'],
						'new_password' => $_POST['new_password'],
						'confirm_new_password' => $_POST['confirm_new_password'],
						'old_password_err' => '',
						'new_password_err' => '',
						'confirm_new_password_err' => '',
						'password_update' => true
					];
					$data['old_password_err'] = $this->h->validateExistingPassword($_POST['old_password']);
					$data['new_password_err'] = $this->h->validatePassword($data['new_password']);
					$data['confirm_new_password_err'] = $this->h->validateConfirmPassword($data['new_password'], $data['confirm_new_password']);
					if (empty($data['old_password_err']) && empty($data['new_password_err']) && empty($data['confirm_new_password_err'])) {
						$password = password_hash($data['new_password'], PASSWORD_DEFAULT);
						if ($this->userModel->updatePassword($id, $password)) {
							flash('password_changed', 'Your password has been updated successfuly');
							redirect('posts');
						} else
							die('Oups .. something went wrong !');
					} else
						$this->view('users/settings', $data);
				} else {
					$data = [
						'id' => $id,
						'name' => trim($_POST['name']),
						'email' => trim($_POST['email']),
						'number' => trim($_POST['number']),
						'notif' => $_POST['notif'] == 'check' ? 1 : 0,
						'image' => $_SESSION['user_img'],
						'name_err' => '',
						'email_err' => '',
						'image_err' => '',
						'number_err' => '',
						'password_update' => false
					];
					if ($_FILES['image']['tmp_name'])
						if ($image = $this->userModel->uploadImage($_FILES['image'], $_SESSION['user_name']))
							$data['image'] = $image;
					$data['name_err'] = $this->h->validateName($data['name']);
					$data['email_err'] = $data['email'] != $_SESSION['user_email'] ? $this->h->validateEmail($data['email']) : '';
					if (empty($data['email_err']) && empty($data['name_err'])) {
						if ($data['email'] != $_SESSION['user_email']) {
							$data['vkey'] = md5(time().trim($_POST['email']));
							$url = URLROOT.'/users/verify/'.$data['vkey'];
							$mail = new Mailer('mail_confirm', $data['email'], $url);
							if ($this->userModel->unVerifyUser($data) && $mail->send())
								flash('email_changed', 'Please verify your new email');
							else
								die('Oups .. something went wrong !');
						}
						if ($this->userModel->updateUser($id, $data)) {
							$this->h->updateSession($data);
							$this->view('users/settings', $data);
						} else
							die('Oups .. something went wrong !');
					} else
						$this->view('users/settings', $data);
				}
			} else {
				$data = [
					'id' => $id,
					'name' => $_SESSION['user_name'],
					'email' => $_SESSION['user_email'],
					'number' => $_SESSION['user_nbr'],
					'notif' => $_SESSION['user_notif'],
					'password_update' => false
				];
				$this->view('users/settings', $data);
			}
		} else
			redirect('pages');
	}

	public function forgot() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'email' => trim($_POST['email']),
				'selector' => bin2hex(random_bytes(8)),
				'token' => random_bytes(32),
				'expires' => date('U') + 3600 // expires in 1 hour
			];
			$data['url'] = URLROOT.'/users/reset_password/'.$data['selector'].'_'.bin2hex($data['token']);
			$data['token'] = password_hash($data['token'], PASSWORD_DEFAULT);
			$mail = new Mailer('pwd_reset', $data['email'], $data['url']);
			if ($mail->send($data) &&  $this->userModel->createResetToken($data)) {
				flash('email_sent', 'Please verify your email');
				$this->view('users/forgot');
			} else
				die('Oups .. something went wrong !');
		} else
			$this->view('users/forgot');
	}

	public function reset_password($stuff = -1) {
		if ($stuff === -1)
			redirect('pages');
		list($sel, $val) = explode('_', $stuff);
		if (!empty($sel) && !empty($val) && ctype_xdigit($sel) === true && ctype_xdigit($val) === true) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'sel' => $sel,
					'val' => $val,
					'password' => trim($_POST['password']),
					'confirm_password' => trim($_POST['confirm_password']),
					'password_err' => '',
					'confirm_password_err' => ''
				];
				$data['password_err'] = $this->h->validatePassword($data['password']);
				$data['confirm_password_err'] = $this->h->validateConfirmPassword($data['password'], $data['confirm_password']);
				if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
					$user = $this->userModel->checkResetToken($data);
					$password = password_hash($data['password'], PASSWORD_DEFAULT);
					if ($user && $this->userModel->resetPassword($user->email, $password)) {
						flash('reset_success', 'Your password has been modified successfully');
						$this->view('users/login');
					} else
						$this->view('users/reset', $data);
				} else
					$this->view('users/reset', $data);
			} else {
				$data = [
					'sel' => $sel,
					'val' => $val,
					'password' => '',
					'confirm_password' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];
				$this->view('users/reset', $data);
			}
		} else
			redirect('pages');
	}

	public function verify($key = -1) {
		if ($key != -1 && $this->userModel->verifyUser($key)) {
			flash('register_success', 'Your email has been verified .. you can now log in');
			redirect('users/login');
		} else
			die('Bad request ..');
	}

	public function logout() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && $_POST['token'] == $_SESSION['token'])
			$this->h->destroySession();
		redirect('users/login');
	}

}

?>
