<?php

class LoginController extends gtf\AbstractController {

	public function __call($method, $uri) {
		if (self::isVerified()) {
			return false;
		} else {
			$this->page->template('view/login.phtml');
			return true;
		}
	}

	public function login() {
		try {
			$dbh = gtf\PDOBox::get();

			$name = $_REQUEST['name'];
			$password = $_REQUEST['password'];

			$stat = $dbh->prepare('select * from admin where name=:name and password=:password');
			$stat->execute(array(':name'=>$name, ':password'=>$password));
			$row = $stat->fetch();
			if ($row) {
				$_SESSION['name'] = $row['name'];
				$_SESSION['role'] = $row['role'];

				$this->done('Login successfully...', gtf\Router::site('index.php'));
			} else {
				$this->error('No such user!');
        return true;
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
      return true;
		}
	}

	public function logout() {
		unset($_SESSION['name']);
		unset($_SESSION['role']);
		
		return $this->index();
	}

	public static function isVerified() {
		if (array_key_exists('name', $_SESSION)) {
			return true;
		} else {
			return false;
		}
	}
}
