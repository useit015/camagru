<?php

class Setup {
	private $db;

	public function __construct() {
		$this->db = new Database();
	}

	public function execute() {
		$this->createUsers();
		$this->createPosts();
		$this->createPwdReset();
		$this->createComments();
		$this->createLikes();
	}

	public function createUsers() {
		$sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`users` ( ';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT, ';
		$sql .= '`name` VARCHAR(255) NOT NULL, ';
		$sql .= '`email` VARCHAR(255) NOT NULL, ';
		$sql .= '`password` VARCHAR(255) NOT NULL,';
		$sql .= '`img` VARCHAR(255) NOT NULL,';
		$sql .= '`number` VARCHAR(255),';
		$sql .= '`notif` BOOLEAN NOT NULL DEFAULT TRUE,';
		$sql .= '`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,';
		$sql .= '`verified` tinyint(1) NOT NULL DEFAULT 0,';
		$sql .= '`vkey` VARCHAR(255) NOT NULL,';
		$sql .= 'PRIMARY KEY (`id`))';
		$this->db->query($sql);
		$this->db->execute();
	}

	public function createPosts() {
		$sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`posts` ( ';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT, ';
		$sql .= '`user_id` INT NOT NULL, ';
		$sql .= '`img` VARCHAR(255) NOT NULL, ';
		$sql .= '`likeCount` INT NOT NULL DEFAULT 0 , ';
		$sql .= '`cmntCount` INT NOT NULL DEFAULT 0 , ';
		$sql .= '`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
		$sql .= 'PRIMARY KEY (`id`))';
		$this->db->query($sql);
		$this->db->execute();
	}

	public function createPwdReset() {
		$sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`pwdReset` ( ';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT , ';
		$sql .= '`email` VARCHAR(255) NOT NULL , ';
		$sql .= '`selector` VARCHAR(255) NOT NULL , ';
		$sql .= '`token` VARCHAR(255) NOT NULL , ';
		$sql .= '`expires` INT NOT NULL , ';
		$sql .= 'PRIMARY KEY (`id`))';
		$this->db->query($sql);
		$this->db->execute();
	}

	public function createLikes() {
		$sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`likes` ( ';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT , ';
		$sql .= '`user_id` INT NOT NULL , ';
		$sql .= '`post_id` INT NOT NULL , ';
		$sql .= '`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
		$sql .= 'PRIMARY KEY (`id`))';
		$this->db->query($sql);
		$this->db->execute();
		$this->db->query('DROP TRIGGER IF EXISTS `likeDecrement`');
		$this->db->execute();
		$this->db->query('DROP TRIGGER IF EXISTS `likeIncrement`');
		$this->db->execute();
		$sql = 'CREATE TRIGGER `likeDecrement` AFTER DELETE ON `camagru`.`likes` FOR EACH ROW ';
		$sql .= 'BEGIN UPDATE posts SET likeCount = likeCount - 1 WHERE posts.id = old.post_id AND posts.likeCount > 0;END';
		$this->db->query($sql);
		$this->db->execute();
		$sql = 'CREATE TRIGGER `likeIncrement` AFTER INSERT ON `camagru`.`likes` FOR EACH ROW ';
		$sql .= 'BEGIN SET @nbr := (SELECT COUNT(*) FROM likes WHERE likes.`post_id` = NEW.`post_id`);';
		$sql .= 'UPDATE camagru.`posts` SET camagru.`posts`.`likeCount` = @nbr WHERE posts.`id` = NEW.post_id;END';
		$this->db->query($sql);
		$this->db->execute();
	}

	public function createComments() {
		$sql = 'CREATE TABLE IF NOT EXISTS `camagru`.`comments` ( ';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT , ';
		$sql .= '`user_id` INT NOT NULL , ';
		$sql .= '`post_id` INT NOT NULL , ';
		$sql .= '`body` TEXT NOT NULL , ';
		$sql .= '`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
		$sql .= 'PRIMARY KEY (`id`))';
		$this->db->query($sql);
		$this->db->execute();
		$this->db->query('DROP TRIGGER IF EXISTS `commentDecrement`');
		$this->db->execute();
		$this->db->query('DROP TRIGGER IF EXISTS `commentIncrement`');
		$this->db->execute();
		$sql = 'CREATE TRIGGER `commentDecrement` AFTER DELETE ON `camagru`.`comments` FOR EACH ROW ';
		$sql .= 'BEGIN UPDATE posts SET cmntCount = cmntCount - 1 WHERE posts.id = old.post_id AND posts.cmntCount > 0;END';
		$this->db->query($sql);
		$this->db->execute();
		$sql = 'CREATE TRIGGER `commentIncrement` AFTER INSERT ON `camagru`.`comments` FOR EACH ROW ';
		$sql .= 'BEGIN SET @nbr := (SELECT COUNT(*) FROM comments WHERE comments.`post_id` = NEW.`post_id`);';
		$sql .= 'UPDATE camagru.`posts` SET camagru.`posts`.`cmntCount` = @nbr WHERE posts.`id` = NEW.post_id;END';
		$this->db->query($sql);
		$this->db->execute();
	}
}

?>