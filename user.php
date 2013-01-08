<?php
session_start();

include 'core/autoload.base.php';

function done($link, $message) {
	$page = new gtf\Page;
	$page->def('link', $link);
	$page->def('message', $message);
	$page->template('view/done.phtml');

	exit();
}

function error($link, $message) {
	$page = new gtf\Page;
	$page->def('link', $link);
	$page->def('message', $message);
	$page->template('view/error.phtml');

	exit();
}

if (isset($_SESSION['admin']) && $_SESSION['admin']) {
	if (isset($_POST['action']) && $_POST['action'] == 'logout') {
		unset($_SESSION['admin']);

		done(gtf\Router::site('user.php'), 'Successfully logout...');
	}
	gtf\Router::dispatch('UserController');
} else {
	if (file_exists('core/admin.config.php')) {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$page = new gtf\Page;
			$page->template('view/setup.phtml');
		} else {
			$passcode = include 'core/admin.config.php';
			if (isset($_POST['code']) && $_POST['code'] == $passcode) {
				$_SESSION['admin'] = true;

				done(gtf\Router::site('user.php'), 'Successfully login...');
			} else {
				error(gtf\Router::site('user.php'), 'Wrong passcode.');
			}
		}
	} else {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$page = new gtf\Page;
			$page->template('view/setup.phtml');
		} else {
			if (isset($_POST['code'])) {
				$passcode = $_POST['code'];
				file_put_contents('core/admin.config.php', "<?php return \"$passcode\";");

				done(gtf\Router::site('user.php'), 'Successfully authorized.');
			} else {
				error(gtf\Router::site('user.php'), 'Wrong parameter!');
			}
		}
	}
}
