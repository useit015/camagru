<?php

class Post {
	private $db;
	private $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

	public function __construct() {
		$this->db = new Database();
	}

	public function getPosts() {
		$this->db->query('SELECT *,
						posts.id as postId,
						users.id as userId,
						users.name as userName,
						posts.img as postImg,
						posts.created_at as postCreated,
						users.created_at as userCreated
						FROM posts
						INNER JOIN users
						ON posts.user_id = users.id
						ORDER BY posts.created_at DESC');
		return $this->db->resultSet();
	}

	public function addPost($data) {
		$this->db->query('INSERT INTO posts (user_id, img) VALUES (:user_id, :img)');
		$this->db->bind(':img', $data['image']);
		$this->db->bind(':user_id', $data['user_id']);
		return $this->db->execute();
	}

	public function updatePost($data) {
		$this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
		$this->db->bind(':id', $data['id']);
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':body', $data['body']);
		return $this->db->execute();
	}

	public function getPostById($id) {
		$this->db->query('SELECT * FROM posts WHERE id = :id');
		$this->db->bind(':id', $id);
		return $this->db->single();
	}

	public function deletePost($id) {
		$this->db->query('DELETE FROM posts WHERE id = :id');
		$this->db->bind(':id', $id);
		return $this->db->execute();
	}

	public function uploadImage($file) {
		$name = $file['name'];
		$tmpName = $file['tmp_name'];
		$size = $file['size'];
		$err = $file['err'];
		$type = $file['type'];
		$ext = strtolower(end(explode('.', $name)));
		if (in_array($ext, $this->allowed) && !$err && $size < 5000000) {
			$dest = 'uploads/'.uniqid('', true).'.'.$ext;
			move_uploaded_file($tmpName, dirname(dirname(APPROOT)).'/'.$dest);
			return $dest;
		} else {
			return false;
		}
	}

	function saveImage64($data){
		list($type, $data) = explode(';', $data);
		list(,$ext) = explode('/', $type);
		list(,$data) = explode(',', $data);
		$dest = 'uploads/'.uniqid('', true).'.'.$ext;
		file_put_contents(dirname(dirname(APPROOT)).'/'.$dest, base64_decode($data));
		return $dest;
	}
}

?>