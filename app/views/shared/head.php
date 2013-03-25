<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />
		<meta name="apple-mobile-web-app-capable" content="yes" />

		<title><?php echo $title_tag; ?></title>

		<!-- Bootstrap -->
		<!--<script type="text/javascript" src="assets/javascript/jquery-1.7.1.min.js"></script>-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />

		<script type="text/javascript" src="assets/bootstrap-switch/static/js/bootstrapSwitch.js"></script>

		<!--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css' />-->
		<link rel="stylesheet" href="assets/stylesheets/styles.css?cache=<?php echo filemtime('assets/stylesheets/styles.css') ?>" type="text/css" />

		<!-- @todo: do we need this? include on https.. <link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/js/google-code-prettify/prettify.css"> -->

		<script type="text/javascript" src="assets/javascript/tubeplayer/jQuery.tubeplayer.min.js"></script>
		<script type="text/javascript" src="assets/javascript/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
		<script type="text/javascript" src="assets/javascript/spin.js/spin.js"></script>
		<script type="text/javascript" src="assets/javascript/jquery.spin.js"></script>

		<!--[if lte IE 7]>
		<script type="text/javascript" src="assets/icomoon/lte-ie7.js"></script>
		<![endif]-->

		<link rel="stylesheet" type="text/css" href="assets/javascript/alertify.js/themes/alertify.core.css" />
		<link rel="stylesheet" type="text/css" href="assets/javascript/alertify.js/themes/alertify.bootstrap.css" />
		<script type="text/javascript" src="assets/javascript/alertify.js/lib/alertify.min.js"></script>

		<?php // @todo: conditional load tour plugin ?>
		<script type="text/javascript" src="assets/jQueryTourPlugin/js/jTour.js"></script>
		<link rel="stylesheet" src="assets/jQueryTourPlugin/css/theme1/style.css">

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