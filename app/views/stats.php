<?php include('shared/head.php'); ?>
		<div id="fb-root"></div>
		<?php /*
		<script type="text/javascript">
	  window.fbAsyncInit = function() {
		FB.init({
		  appId      : '<?php echo $appID; ?>', // App ID
		  channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/assets/channel.html', // Channel File
		  status     : true, // check login status
		  cookie     : true, // enable cookies to allow the server to access the session
		  xfbml      : true // parse XFBML
		});

		// Listen to the auth.login which will be called when the user logs in
		// using the Login button
		FB.Event.subscribe('auth.login', function(response) {
		  // We want to reload the page now so PHP can read the cookie that the
		  // Javascript SDK sat. But we don't want to use
		  // window.location.reload() because if this is in a canvas there was a
		  // post made to this page and a reload will trigger a message to the
		  // user asking if they want to send data again.
		  window.location = window.location; // +'/<?php echo $video['slug']; ?>';
		});

		FB.Canvas.setAutoGrow();
	  };

	  // Load the SDK Asynchronously
	  (function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js";
		fjs.parentNode.insertBefore(js, fjs);
	  }(document, 'script', 'facebook-jssdk'));
	  </script>
	  */ ?>

	<div id="page">

		<div id="notifications"></div>

		<div id="wrapper">

			<header class="clearfix">

				<a href="/"><h3><?php echo $site_title; ?><em> <?php echo $site_description; ?></em></h3></a>

				<!-- <div class="fb-like"></div> -->

			</header>

			<section id="text">
				
			<?php/*
				if(!$info) {
					echo "info not found :(";
				} else {
					echo $info;
				}
				*/?>

			<?php foreach ($users as $user) : ?>

				<?php // var_dump($user); ?>

				<?php echo $user['points'] . ' ' . $user['first_name'] . '<br />'; ?>

			<?php endforeach; ?>

			</section>

			<?php include('shared/footer.php'); ?>

		</div>
	</div>

<?php include('shared/foot.php'); ?>