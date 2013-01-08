<?php

use gtf\Router;

class UserController extends gtf\AbstractController {

	public function index() {
		try {
			$dbh = gtf\PDOBox::get();
			$stat = $dbh->query('SELECT * FROM `admin`');
			$users = array();
			while($row=$stat->fetch()) {
				$users[] = $row;
			}
			$this->page->def('users', $users);
			$this->page->template('view/user.phtml');
		} catch(Exception $e) {
			$this->error($e->getMessage());
		}
	}

	public function del() {
		try {
			$dbh = gtf\PDOBox::get();
			$stat = $dbh->prepare('DELETE FROM `admin` WHERE `id`=:id');
			$stat->execute(array(
				':id' => $_REQUEST['id']
			));
			if ($stat->rowCount() > 0) {
				$this->done('Successful!', gtf\Router::site('user.php'));
			} else {
				$this->error();
			}
		} catch(Exception $e) {
			$this->error($e->getMessage());
		}
	}

	public function update($path) {
		if (isset($_REQUEST['password']) && isset($_REQUEST['role'])) {
			try {
				$dbh = gtf\PDOBox::get();
				$stat = $dbh->prepare('UPDATE `admin` SET `password`=:password,`role`=:role WHERE `id`=:id');
				$stat->execute(array(
					':password' => $_REQUEST['password'],
					':role' => $_REQUEST['role'],
					':id' => $_REQUEST['id']
				));
				if ($stat->rowCount() > 0) {
					$this->done("Successfully update the user#{$_REQUEST['id']}",
						gtf\Router::site('user.php'));
				} else {
					$this->error();
				}
			} catch(Exception $e) {
				$this->error($e->getMessage());
			}
		} else {
			if (! isset($path[0])) {
				$this->error('Wrong user id.');
				return;
			}
			$id = $path[0];
			try {
				$dbh = gtf\PDOBox::get();
				$stat = $dbh->prepare('SELECT * FROM `admin` WHERE `id`=:id');
				$stat->execute(array(':id'=>$id));
				$user = $stat->fetch();
				if ($user != null) {
					$this->page->def('user', $user);
					$this->page->template('view/user-edit.phtml');
				} else {
					$this->error('No such user.'.$id);
				}
			} catch(Exception $e) {
				$this->error($e->getMessage());
			}
		}
	}

	public function add() {
		try {
			$dbh = gtf\PDOBox::get();
			$stat = $dbh->prepare('INSERT INTO `admin` VALUES(null,:name,:password,:role)');
			$stat->execute(array(
				':name'     => $_REQUEST['username'],
				':password' => $_REQUEST['passowrd'],
				':role'		=> $_REQUEST['role']
			));
			if ($stat->rowCount() > 0) {
				$this->done('Successful!', gtf\Router::site('user.php'));
			} else {
				$this->error();
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
	}
}