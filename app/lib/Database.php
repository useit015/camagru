<?php

class Database {
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dsn = DB_DSN;
	private $dbh;
	private $stmt;
	private $err;
	private $tables = ['users', 'posts', 'comments', 'likes', 'pwdReset'];

	public function __construct() {
		$options = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];
		try {
			$this->dbh = new PDO($this->dsn, $this->user, $this->pass, $options);
			foreach($this->tables as $table) {
				$res = $this->dbh->query("SHOW TABLES LIKE '$table'");
				if (!$res || $res->rowCount() === 0)
					redirect('pages/setup');
			}
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public function query($sql) {
		$this->stmt = $this->dbh->prepare($sql);
	}

	public function bind($param, $value, $type = null) {
		if (is_null($type)) {
			switch ($true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute() {
		return $this->stmt->execute();
	}

	public function resultSet() {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function single() {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}

	public function rowCount() {
		return $this->stmt->rowCount();
	}

}

?>