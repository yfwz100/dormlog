<?php

require_once 'core/autoload.base.php';

$page = new gtf\Page;

$create_dormlog = <<<SQL
CREATE TABLE `dormlog` (
	`id` INTEGER PRIMARY KEY,
	`date` DATETIME NOT NULL,
	`detail` TEXT,
	`amount` REAL
)
SQL;

try {
	$dbh = new SQLite3('data/dormlog.db', SQLITE3_OPEN_READWRITE);
	$dbh->execute($create_dormlog);
} catch (Exception $e){
	$page->def('message', $e->getMessage());
	$page->template('view/error.phtml');
	die();
}

$page->def('message', 'Done!');
$page->template('view/done.phtml');
