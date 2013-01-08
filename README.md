
Dormlog, a demo project powered by gtf.
========

Gtf is a library trying to implement a flex template engine and router classes for a certain web project. It's an idea inspired by the name of green tea.

It's not a project cool enough for you to enjoy the code. It's a mix of my thoughts in the year 2012~2013. I didn't know where the project leads to. Hope it will became useful after practice.

It's nice to hear from you.

## Usage

Download the source and put it into a folder that could be access via PHP Server, like Apache with PHP module.

Then modify the configuration to fit the root of the project. The configuration file could be found in the 'core' directory, whose name is site.config.php . Change the resUri and baseUri field to your project's root path relative to the server root.

	<?php
	$conf = array();
	$conf['resUri'] = '/dorm/assets';
	$conf['baseUri'] = '/dorm';
	return $conf;

Finally, access the application via [http://localhost/dorm/index.php](http://localhost/dorm/index.php) (if your base path is dorm).
