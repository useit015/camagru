<?php

class UsersHelper {

	public function __construct(User $model) {
		$this->userModel = $model;
	}

	public function createUserSession($user) {
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_email'] = $user->email;
		$_SESSION['user_name'] = $user->name;
		$_SESSION['user_img'] = $user->img;
		$_SESSION['user_notif'] = $user->notif;
		$_SESSION['user_nbr'] = $user->number;
		$_SESSION['token'] = bin2hex(random_bytes(32));
	}

	public function updateSession($data) {
		$_SESSION['user_email'] = $data['email'];
		$_SESSION['user_name'] = $data['name'];
		$_SESSION['user_img'] = $data['image'];
		$_SESSION['user_notif'] = $data['notif'];
		$_SESSION['user_nbr'] = $data['number'];
		$_SESSION['token'] = bin2hex(random_bytes(32));
	}

	public function destroySession() {
		unset($_SESSION['user_id']);
		unset($_SESSION['user_email']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_nbr']);
		unset($_SESSION['user_notif']);
		unset($_SESSION['user_img']);
		unset($_SESSION['token']);
		session_destroy();
	}

	public function validatePassword($password) {
		if (empty($password)) {
			return 'Password shall not be empty';
		} else if (strlen($password) < 8 || strlen($password) > 12) {
			return 'Passwords must be between 8 and 12 characters long';
		} elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]+$/', $password)) {
			return 'Passwords must contain at least one letter, one number and one special char';
		} else {
			return '';
		}
	}

	public function validateEmail($email) {
		if (empty($email)) {
			return 'Email shall not be empty';
		} elseif ($this->userModel->findUserByEmail($email)) {
			return 'Email is already taken';
		} else {
			return '';
		}
	}

	public function validateName($name) {
		if (empty($name)) {
			return 'Name shall not be empty';
		} elseif (strlen($name) < 6 || strlen($name) > 10) {
			return 'Name must be between 6 and 10 characters long';
		} else {
			return '';
		}
	}

	public function validateConfirmPassword($password, $confPassword) {
		if (empty($confPassword)) {
			return 'Please confirm your password';
		} elseif ($password != $confPassword) {
			return 'Passwords do not match';
		} else {
			return '';
		}
	}

	public function validateExistingPassword($password) {
		if (empty($password)) {
			return 'Password shall not be empty';
		} elseif (!$this->userModel->login($_SESSION['user_email'], $password)) {
			return 'Wrong password';
		} else {
			return '';
		}
	}

	public function loginValidateEmail($email) {
		if (empty($email)) {
			return 'Email shall not be empy';
		} elseif (!$this->userModel->findUserByEmail($email)) {
			return 'No user found';
		} else {
			return '';
		}
	}

	public function loginValidatePassword($password) {
		if (empty($password)) {
			return 'Password shall not be empy';
		} else {
			return '';
		}
	}

}

?>