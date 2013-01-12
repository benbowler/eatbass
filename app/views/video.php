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
		  window.location = window.location; //+'/<?php echo $video['slug']; ?>';
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

	<div id="page-blur">

		<div id="background-blur" style="background-image: url('<?php echo str_replace('http', 'https', $video['media$group']['media$thumbnail'][1]['url']); ?>');"></div>

		<div id="notifications"></div>

		<div id="wrapper">

			<header class="clearfix">

				<h3><?php echo $site_title; ?><em> <?php echo $site_description; ?></em></h3>

				<!-- <div class="fb-like"></div> -->

				<section id="user">

					<em class="sup">+50</em><a href="#" class="share">share</a>
					<em class="sup">+10</em><a href="#" class="love">love</a>
											<a href="#" class="skip">skip</a>

					<?php if (isset($basic)) { ?>
						<a href="<?php echo "/u:" . $basic['username']; ?>" alt="<?php echo $basic['first_name']; ?> on <?php echo $site_title; ?>" class="profile">
							<img id="picture" src="https://graph.facebook.com/<?php echo $basic['username']; ?>/picture?type=square" /><?php echo $basic['first_name']; ?> <em id="points"><?php echo $user['points']; ?></em>
						</a>
					<?php } ?>

				</section>

			</header>

			<section id="player">
					<div id="player-yt"></div>
			</section>

			<section id="text">
				
				<h1 id="video_title"><?php echo $video['title']['$t']; ?></h1>
				<?php /* @todo: channel pages   <a href="/channel:<?php echo $video['title']['$t']; ?>" class="channel"><h1 id="video_title"><?php echo $video['title']['$t']; ?></h1></a>  */ ?>
				<a href="http://youtube.com/user/<?php echo $video['author'][0]['name']['$t']; ?>" class="channel" target="_blank"><h2 id="video_author"><?php echo $video['author'][0]['name']['$t']; ?></h2></a>

				<div id="video_description"><?php echo $video['html_description']; ?></div>

				<!-- <div class="fb-comments" data-href="" data-width="470" data-num-posts="2"></div>-->

				<div id="output"></div>
				<?php //var_dump($video); ?>

			</section>

			<?php include('shared/footer.php'); ?>

		</div>
	</div>

	<?php
	if (isset($basic)) {
		// Include profile view
		include('shared/profile.php');
	}
	?>

	<?php if (!isset($basic)) { ?>

		<section id="login">
			<div>
				<h3><?php echo $site_title; ?><em> <?php echo $site_description; ?></em></h3>

				<strong><div class="fb-login-button" data-scope="email,user_likes,publish_actions">Log In</div> to watch</strong>

				<a href="#" id="fb-js-login">
					<img src="/assets/info/winning_ad_jan.jpg" />
				</a>

				<div class="fb-facepile" data-href="https://apps.facebook.com/eatbass" data-max-rows="1" data-width="250" data-colorscheme="dark"></div>

				<p>win <strong>music</strong>, <strong>tickets</strong> and <strong>merch</strong><br />
					by listening to the music you love</p>

				<!-- Facepile -->
			</div>
		</section>

		<section>
			<h1>top videos</h1>
			<?php /*  foreach ($top_plays as $video) { ?>
				<a href="/<?php echo $video['slug']; ?>" alt="<?php echo $video['title']['$t']; ?>"><?php echo $video['title']['$t']; ?></a><br />
			<?php } */ ?>
		</section>

		<section>
			<?php //var_dump($top_ikes); ?>
		</section>

	<?php } ?>

<?php include('shared/foot.php'); ?>