<?php include('shared/head.php'); ?>
		<script type="text/javascript" src="assets/javascript/app.js?cache=<?php echo filemtime('assets/javascript/app.js') ?>"></script>

		<div id="fb-root"></div>
		<script type="text/javascript">
		$(function () {

		    window.fbAsyncInit = function() {
			FB.init({
			  appId      : '<?php echo $appID; ?>', // App ID
			  channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/assets/channel.html', // Channel File
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  xfbml      : true // parse XFBML
			});

			// @todo: remove this??
			FB.Event.subscribe('auth.login', function(response) {
			  window.location = window.location; //+'/<?php echo $video['slug']; ?>';
			});

			FB.getLoginStatus(function(response) {

				// Setup video and user objects
				$.site = { 
					title : "<?php echo $site_title; ?>",
					description : "<?php echo $site_description; ?>",
				};

				// @todo: Check if this is needed?
				$.alertify = alertify;

				<?php if (isset($basic)) { ?>

					$.user = { 
						_id : "<?php echo $user['_id']; ?>",
						email: "<?php echo $user['email']; ?>",
						logged_in : <?php echo ($user) ? 'true' : 'false' ; ?>,
						subscribed : <?php echo ($user['subscribed']) ? 'true' : 'false' ; ?>,
						opengraph : <?php echo ($user['opengraph'] == '' || $user['opengraph'] == 'first') ? "'first'" : $user['opengraph'] ; ?>,
						emailfrequency : "<?php echo ($user['emailfrequency'] == '' || $user['emailfrequency'] == 'first') ? 'first' : $user['emailfrequency'] ; ?>"
					};

				<?php } else { ?>

					$.user = false;

				<?php } ?>

				$.video = { 
					_id : "<?php echo $video['_id']; ?>",
					slug : "<?php echo $video['slug']; ?>",
					title : <?php echo json_encode($video['title']['$t']); ?>,
			        author : "<?php echo $video['author'][0]['name']['$t']; ?>",
		            description : <?php echo json_encode($video['media$group']['media$description']['$t']); ?>,
		            html_description : <?php echo json_encode($video['html_description']); ?>,
					picture : "<?php echo str_replace('http', 'https', $video['media$group']['media$thumbnail'][1]['url']); ?>",
					ytID : "<?php echo $video['media$group']['yt$videoid']['$t']; ?>"
				};

				if (response.status === 'connected') {
				    // the user is logged in and has authenticated your
				    // app, and response.authResponse supplies
				    // the user's ID, a valid access token, a signed
				    // request, and the time the access token 
				    // and signed request each expire
				    var uid = response.authResponse.userID;
				    var accessToken = response.authResponse.accessToken;

				    $.user.accesstoken = accessToken;

					if(!$.user.logged_in) {
						// @todo: need to double up??
					    FB.login(function(response) {
			                var url = [location.protocol, '//', location.host, '/', $.video.slug].join(''); // , location.pathname
			                window.location = url;
			            }, {scope: 'email,user_likes,publish_actions'});
					}

				    //console.log('I am logged in and connected '+uid);
				} else if (response.status === 'not_authorized') {
				    // the user is logged in to Facebook, 
				    // but has not authenticated your app
				    console.log('I am logged in but not authorised');
				} else {
				    // the user isn't logged in to Facebook.
				    console.log('I am logged out completely');
				}

				// Start app
				app();

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

		});
	  </script>

		<div id="notifications"></div>

		<?php include('shared/header.php'); ?>

		<section id="player">
			<div id="player-yt"></div>
		</section>

		<?php include('shared/footer.php'); ?>


		<section id="video_info">
			
			<h1 class="video_title"><?php echo $video['title']['$t']; ?></h1>
			<?php /* @todo: channel pages   <a href="/channel:<?php echo $video['title']['$t']; ?>" class="channel"><h1 id="video_title"><?php echo $video['title']['$t']; ?></h1></a>  */ ?>
			<a href="http://youtube.com/user/<?php echo $video['author'][0]['name']['$t']; ?>" target="_blank"><h2 class="video_channel"><?php echo $video['author'][0]['name']['$t']; ?></h2></a>
			<div class="video_description"><?php echo $video['html_description']; ?></div>

		</section>

	<?php
	if (isset($basic)) {
		// Include profile view
		include('shared/profile.php');

	} else { ?>

		<section id="login">
			<div>

				<p>win <strong>music</strong>, <strong>tickets</strong> and <strong>merch</strong><br />
					by watching the latest bass music videos</p>

				<div id="fb-login-wrapper">
					<div id="facebook-login-btb">
						<a href="#" class="fb-js-login">watch with <span>facebook</span></a>
					</div>
				</div>

				<div class="hidden-phone" id="fb-facepile-wrapper">
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=603643742984470";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				</div>

				<a href="#" id="fb-js-login-ad" class="fb-js-login">
					<img src="/assets/info/winning_ad_dnba2013.jpg" />
				</a>

			</div>
		</section>

		<!-- seo stuff -->
		<section>
			<h1>top videos</h1>
			<?php foreach ($top_plays as $video) { ?>
				<a href="/<?php echo $video['slug']; ?>" alt="<?php echo $video['title']['$t']; ?>"><?php echo $video['title']['$t']; ?></a><br />
			<?php } ?>
		</section>
		<?php /*
		<section>
			<?php //var_dump($top_ikes); ?>
		</section>

		<section>
			<?php echo $site_about; ?>
		</section>
		*/ ?>

	<?php } ?>

<?php include('shared/foot.php'); ?>
