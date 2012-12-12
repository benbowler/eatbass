<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

		<title><?php echo $title_tag; ?></title>

		<!-- Bootstrap -->
		<script type="text/javascript" src="assets/javascript/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-responsive.min.css" type="text/css" />

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="assets/stylesheets/styles.css" type="text/css" />

		<script type='text/javascript' src='assets/javascript/tubeplayer/jQuery.tubeplayer.min.js'></script>
		<script type='text/javascript' src="assets/javascript/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
		<script type='text/javascript' src="assets/javascript/blur.min.js"></script>

		<link rel="stylesheet" type="text/css" href="assets/javascript/gritter/css/gritter.css" />
		<script type="text/javascript" src="assets/javascript/gritter/js/jquery.gritter.js"></script>

		<?php foreach ($meta_tags as $property => $content) { ?>
			<meta property="<?php echo $property; ?>" content="<?php echo $content; ?>" />
		<?php } ?>

		<!--[if IE]>
			<script type="text/javascript">
				var tags = ['header', 'section'];
				while(tags.length)
					document.createElement(tags.pop());
			</script>
		<![endif]-->
	</head>
	<body>