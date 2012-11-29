<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

		<title>&#9658; <?php echo $video['title']['$t']; ?> - <?php echo he($app_name); ?></title>

		<!-- Bootstrap -->
		<script type="text/javascript" src="assets/javascript/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-responsive.min.css" type="text/css" />

		<link rel="stylesheet" href="assets/stylesheets/styles.css" type="text/css" />

		<script type='text/javascript' src='assets/javascript/tubeplayer/jQuery.tubeplayer.min.js'></script>
		<script type='text/javascript' src="assets/javascript/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
		<script type='text/javascript' src="assets/javascript/blur.min.js"></script>

		<meta property="title" content="<?php echo $video['title']['$t']; ?> - <?php echo he($app_name); ?>" />
		<meta property="description" content="
			<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?> <?php echo $site_description; ?>

			<?php echo $video['media$group']['media$description']['$t']; ?>
		" />
		<!-- These are Open Graph tags. -->
		<meta property="og:title" content="<?php echo $video['title']['$t']; ?> - <?php echo he($app_name); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
		<meta property="og:image" content="<?php //$image = (isset($video['media$group']['media$thumbnail'][0]['url'])) ? $video['media$group']['media$thumbnail'][0]['url'] : AppInfo::getUrl('/logo.png'); ?>" />
		<meta property="og:site_name" content="<?php echo he($app_name); ?>" />
		<meta property="og:description" content="
			<?php echo $video['title']['$t']; ?> - <?php echo $site_title; ?> <?php echo $site_description; ?>

			<?php echo $video['media$group']['media$description']['$t']; ?>
		" />
		<meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />

		<!--[if IE]>
			<script type="text/javascript">
				var tags = ['header', 'section'];
				while(tags.length)
					document.createElement(tags.pop());
			</script>
		<![endif]-->
	</head>
	<body>
		<div id="fb-root"></div>
		<script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo AppInfo::appID(); ?>', // App ID
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
          window.location = window.location;
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

	<div id="background" style="background-image: url('<?php echo $video['media$group']['media$thumbnail'][1]['url']; ?>');"></div>
	
	<div id="wrapper">

		<header class="clearfix">

			<h3><strong><?php echo $site_title; ?></strong> <?php echo $site_description; ?></h3>

		</header>

		<section id="player">
				<div id="player-yt"></div>
		</section>


		<?php if (isset($basic)) { ?>

		<section id="interact">

			<h3>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong></h3>
			<p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></p>

				<a href="#" class="love">Love</a>
				<a href="#" class="next">Next</a>

				<div id="share-app">
					<ul>
						<li>
							<a href="#" class="facebook-button" id="postToWall" data-url="<?php echo AppInfo::getUrl(); ?>">
								<span class="plus">Post to Wall</span>
							</a>
						</li>
						<li>
							<a href="#" class="facebook-button speech-bubble" id="sendToFriends" data-url="<?php echo AppInfo::getUrl(); ?>">
								<span class="speech-bubble">Send To Friends</span>
							</a>
						</li>
						<li>
							<a href="#" class="facebook-button apprequests" id="sendRequest" data-message="Test this awesome app">
								<span class="apprequests">Invite Friends</span>
							</a>
						</li>
					</ul>
				</div>

			</section>

		<?php } else { ?>

			<section id="login">
				<h3>Welcome</h3>
				<strong>Login to skip, love and share.</strong>
				<div class="fb-login-button" data-scope="user_likes,user_photos"></div>
			</section>

		<?php } ?>


		<h1 id="video_title"><?php echo $video['title']['$t']; ?></h1>
		<h2 id="video_author"><?php echo $video['author'][0]['name']['$t']; ?></h2>

		<div id="video_description"><?php echo $video['media$group']['media$description']['$t']; ?></div>

	</div>



	
	<script type="text/javascript">
	$(function() {
		jQuery("#player-yt").tubeplayer({
			width: '100%', // the width of the player
			height: '100%', // the height of the player
			allowFullScreen: "true", // true by default, allow user to go full screen
			showControls: 0,
			autoPlay: true,
			showInfo: false,
			modestbranding: true,
			initialVideo: "<?php echo $video['media$group']['yt$videoid']['$t']; ?>", // the video that is loaded into the player
			preferredQuality: "default",// preferred quality: default, small, medium, large, hd720
			onPlay: function(id){}, // after the play method is called
			onPause: function(){}, // after the pause method is called
			onStop: function(){}, // after the player is stopped
			onSeek: function(time){}, // after the video has been seeked to a defined point
			onMute: function(){}, // after the player is muted
			onUnMute: function(){}, // after the player is unmuted
			onPlayerEnded: nextVideo
		});

	  $(".next").click(function(e) {
	    e.preventDefault();
	    nextVideo();
	  });

	  $(".love").click(function(e) {
	    e.preventDefault();
	    alert('love');
	    // nextVideo();
	  });

	  function nextVideo() {
	    console.log('loading next virtual page.');
	    $.ajax({
	    url: 'api/video.php',
	    success: function(data) {
	      var video = jQuery.parseJSON(data);
	      //alert(video['media$group']['yt$videoid']['$t']);  //media$group.yt$videoid.$t
	      jQuery("#player-yt").tubeplayer("play", video.media$group.yt$videoid.$t);

	      History.pushState('&#9658; ' + data,video.title.$t + ' - <?php echo $site_title; ?>',video.slug);

	      $('#video_title').html(video.title.$t);
	      $('#video_author').html(video.author[0].name.$t);
	      $('#video_description').html(video.media$group.media$description.$t);

	      $('#background').css('background-image', 'url(' + video.media$group.media$thumbnail[1].url + ')');

	      _gaq.push(['_trackPageview','/' + video.slug]);

	    },
	    error: function(data) {
	      // On error do a full refresh
	      window.location = '/';
	    }
	  });
		}

		$('#background').blurjs({
			source: 'body',
			radius: 20,
			overlay: 'rgba(255,255,255,0.4)'
		});
	});


	// Facebook

	      function logResponse(response) {
	        if (console && console.log) {
	          console.log('The response was', response);
	        }
	      }

	      $(function(){
	        // Set up so we handle click on the buttons
	        $('#postToWall').click(function() {
	          FB.ui(
	            {
	              method : 'feed',
	              link   : $(this).attr('data-url')
	            },
	            function (response) {
	              // If response is null the user canceled the dialog
	              if (response !== null) {
	                logResponse(response);
	              }
	            }
	          );
	        });

	        $('#sendToFriends').click(function() {
	          FB.ui(
	            {
	              method : 'send',
	              link   : $(this).attr('data-url')
	            },
	            function (response) {
	              // If response is null the user canceled the dialog
	              if (response !== null) {
	                logResponse(response);
	              }
	            }
	          );
	        });

	        $('#sendRequest').click(function() {
	          FB.ui(
	            {
	              method  : 'apprequests',
	              message : $(this).attr('data-message')
	            },
	            function (response) {
	              // If response is null the user canceled the dialog
	              if (response !== null) {
	                logResponse(response);
	              }
	            }
	          );
	        });
	      });
	</script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-36632734-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	 ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	</script>
	</body>
</html>
