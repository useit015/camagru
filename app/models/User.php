<?php

class User {
	private $db;
	private $allowed = ['jpg', 'jpeg', 'png'];

	public function __construct() {
		$this->db = new Database();
	}

	public function register($data) {
		$this->db->query('INSERT INTO users (name, email, password, img, vkey) VALUES (:name, :email, :password, :img, :vkey)');
		$this->db->bind(':name', $data['name']);
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':img', 'camagru/public/img/profile-default.jpg');
		$this->db->bind(':vkey', $data['vkey']);
		$this->db->bind(':password', $data['password']);
		return $this->db->execute();
	}

	public function login($email, $password) {
		$this->db->query('SELECT * FROM users WHERE email = :email AND verified = 1');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		if (password_verify($password, $row->password))
			return $row;
		else
			return false;
	}

	public function updateUser($id, $data) {
		$this->db->query('UPDATE users SET name = :name, email = :email, number = :number, notif = :notif, img = :img WHERE id = :id');
		$this->db->bind(':name', $data['name']);
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':number', $data['number']);
		$this->db->bind(':notif', $data['notif']);
		$this->db->bind(':img', $data['image']);
		$this->db->bind(':id', $id);
		return $this->db->execute();
	}

	public function updatePassword($id, $password) {
		$this->db->query('UPDATE users SET password = :password WHERE id = :id');
		$this->db->bind(':password', $password);
		$this->db->bind(':id', $id);
		return $this->db->execute();
	}

	public function findUserByEmail($email) {
		$this->db->query('SELECT * FROM users WHERE email = :email');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		return ($this->db->rowCount() > 0);
	}

	public function verifyUser($key) {
		$this->db->query('UPDATE users SET verified = 1 WHERE vkey = :vkey AND verified = 0');
		$this->db->bind(':vkey', $key);
		return $this->db->execute();
	}

	public function unVerifyUser($data) {
		$this->db->query('UPDATE users SET email = :email, verified = 0, vkey = :vkey WHERE id = :id');
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':vkey', $data['vkey']);
		$this->db->bind(':id', $data['id']);
		return $this->db->execute();
	}

	public function getUserById($id) {
		$this->db->query('SELECT * FROM users WHERE id = :id');
		$this->db->bind(':id', $id);
		return $this->db->single();
	}

	public function resetPassword($email, $password) {
		$this->db->query('UPDATE users SET password = :password WHERE email = :email');
		$this->db->bind(':password', $password);
		$this->db->bind(':email', $email);
		return $this->db->execute();
	}

	public function checkResetToken($data) {
		$this->db->query('SELECT * FROM pwdReset WHERE selector = :selector AND expires >= :expires');
		$this->db->bind(':selector', $data['sel']);
		$this->db->bind(':expires', date('U'));
		$row = $this->db->single();
		if (password_verify(hex2bin($data['val']), $row->token))
			return $row;
		else
			return false;
	}

	public function createResetToken($data) {
		if ($this->cleanReset($data['email'])) {
			$this->db->query('INSERT INTO pwdReset (email, selector, token, expires) VALUE (:email, :selector, :token, :expires)');
			$this->db->bind(':email', $data['email']);
			$this->db->bind(':selector', $data['selector']);
			$this->db->bind(':token', $data['token']);
			$this->db->bind(':expires', $data['expires'], PDO::PARAM_INT);
			return $this->db->execute();
		} else
			return false;
	}

	public function cleanReset($email) {
		$this->db->query('DELETE FROM pwdReset WHERE email = :email');
		$this->db->bind(':email', $email);
		return $this->db->execute();
	}

	public function uploadImage($file, $user) {
		$name = $file['name'];
		$tmpName = $file['tmp_name'];
		$size = $file['size'];
		$err = $file['err'];
		$type = $file['type'];
		$ext = strtolower(end(explode('.', $name)));
		if (in_array($ext, $this->allowed) && !$err && $size < 1000000) {
			$dest = 'uploads/'.$user.'-'.uniqid('', true).'.'.$ext;
			move_uploaded_file($tmpName, dirname(dirname(APPROOT)).'/'.$dest);
			return $dest;
		} else
			return false;
	}

}

?>