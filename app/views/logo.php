<?php include('shared/head.php'); ?>
		<div id="fb-root"></div>
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

	  <style type="text/css">
	  #logo {
	  	width: 316px;
	  	margin-top: 25%;
	  }
	  #login > #logo > h3 {
	  		font-size: 4.5em;
	  		font-weight: 600;

	  		text-shadow: none;
	  }
	  	#logo > em {
	  		font-size: 3.22em;
	  		font-weight: 300;
	  		position: relative;
	  		bottom: 0.5em;
	  		
	  		text-shadow: none;
	  }
	  </style>
	<div id="page-blur">

		<div id="background-blur" style="background-image: url('<?php echo $video['media$group']['media$thumbnail'][1]['url']; ?>');"></div>
<!--
		<section id="login">
			<div id="logo">
				<h3><?php echo $site_title; ?></h3>
				<em><?php echo $site_description; ?></em>
				<!--
				<p><strong>watch</strong>, <strong>love</strong> and <strong>share</strong> for points</p>
				<p>win <strong>downloads</strong>, <strong>tickets</strong> and <strong>merch</strong></p>

				<strong><div class="fb-login-button" data-scope="email,user_likes">Log In</div> to watch now</strong>

				<fb:facepile href="http://eatbass.com" width="300" max_rows="1"></fb:facepile>
			--

				<!-- Facepile --
			</div>
		</section>
	-->

	</div>

<?php include('shared/footer.php'); ?>