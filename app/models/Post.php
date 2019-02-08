<?php

class Post {
	private $db;
	private $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

	public function __construct() {
		$this->db = new Database();
	}

	public function getPosts() {
		$this->db->query('SELECT
						posts.id as postId,
						users.id as userId,
						users.name as userName,
						posts.img as postImg,
						posts.cmntCount as postCmntCount,
						posts.likeCount as postLikeCount,
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

	public function getPostComments($id) {
		$this->db->query('SELECT 
						comments.id as cmntId,
						comments.body as cmntBody,
						comments.created_at as cmntCreated,
						comments.user_id as userId,
						users.name as userName,
						users.img as userImg
						FROM comments, users
						WHERE comments.post_id = :id
						AND users.id = comments.user_id
						ORDER BY comments.created_at DESC');
		$this->db->bind(':id', $id);
		return $this->db->resultSet();
	}

	public function addLike($like) {
		$this->db->query('INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)');
		$this->db->bind(':user_id', $like['user_id']);
		$this->db->bind(':post_id', $like['post_id']);
		return $this->db->execute();
	}

	public function unlikePost($post, $user) {
		$this->db->query('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
		$this->db->bind(':user_id', $user);
		$this->db->bind(':post_id', $post);
		return $this->db->execute();
	}

	public function userLikes($post, $user) {
		$this->db->query('SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id');
		$this->db->bind(':user_id', $user);
		$this->db->bind(':post_id', $post);
		$this->db->execute();
		return $this->db->rowCount();
	}

	public function addComment($comment) {
		$this->db->query('INSERT INTO comments (user_id, post_id, body) VALUES (:user_id, :post_id, :body)');
		$this->db->bind(':user_id', $comment['user_id']);
		$this->db->bind(':post_id', $comment['post_id']);
		$this->db->bind(':body', $comment['body']);
		return $this->db->execute();
	}

	public function deleteComment($comment, $user) {
		$this->db->query('DELETE FROM comments WHERE id = :id AND user_id = :user_id');
		$this->db->bind(':id', $comment);
		$this->db->bind(':user_id', $user);
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