<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8" />
	<?php require '../core/autoload.base.php'; use gtf\Router; ?>
	<title>Error!</title>
	<link rel="stylesheet" type="text/css" href="<?=Router::res('css/bootstrap.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?=Router::res('css/info.css')?>" />
</head>
<body class="container">
	<div class="page-header">
		<h1 style="text-align:center">[?_?]</h1>
	</div>
	<div class="content">
		<div class="well">
			<p>
				So sorry, it seems that you've enter the wrong place. This is not the place that we used to provide serices...
			</p>
		</div>
		<div class="more">
			<a class="btn btn-primary" href="<?=Router::site('index.php')?>">Back to our home page.</a>
		</div>
	</div>
</body>
</html>