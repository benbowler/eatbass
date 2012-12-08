<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

		<title>&#9658; <?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?></title>

		<!-- Bootstrap -->
		<script type="text/javascript" src="assets/javascript/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-responsive.min.css" type="text/css" />

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="assets/stylesheets/styles.css" type="text/css" />

		<script type='text/javascript' src='assets/javascript/tubeplayer/jQuery.tubeplayer.min.js'></script>
		<script type='text/javascript' src="assets/javascript/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
		<script type='text/javascript' src="assets/javascript/blur.min.js"></script>

		<?php /* foreach ($meta_tags as $meta) { ?>
			<meta property="<?php ?>" content="" />
		<?php } */ ?>

		<meta property="title" content="<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?>" />
		<meta property="description" content="
			<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?> <?php echo $site_description; ?>

			<?php echo $video['media$group']['media$description']['$t']; ?>
		" />
		<!-- These are Open Graph tags. -->
		<meta property="og:title" content="<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo $getUrl; ?>" />
		<meta property="og:image" content="<?php echo $video['media$group']['media$thumbnail'][1]['url']; ?>" />
		<meta property="og:site_name" content="<?php echo $site_title; ?>" />
		<meta property="og:description" content="
			<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?> <?php echo $site_description; ?>

			<?php echo $video['media$group']['media$description']['$t']; ?>
		" />
		<meta property="fb:app_id" content="<?php echo $appID; ?>" />

		<!--[if IE]>
			<script type="text/javascript">
				var tags = ['header', 'section'];
				while(tags.length)
					document.createElement(tags.pop());
			</script>
		<![endif]-->
	</head>
	<body>