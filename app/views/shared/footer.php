	<script type="text/javascript" src="assets/javascript/app.js"></script>
	<script type="text/javascript">

	$(function () {

		alertify.log('starting #eatbass');

		// Setup video and user objects
		$.site = { 
			title : "<?php echo $site_title; ?>",
			description : "<?php echo $site_description; ?>",
		};
		$.user = { 
			_id : "<?php echo $user['_id']; ?>",
			logged_in : <?php echo ($user) ? 'true' : 'false' ; ?>,
			subscribed : <?php echo ($user['subscribed']) ? 'true' : 'false' ; ?>

		};
		$.video = { 
			_id : "<?php echo $video['_id']; ?>",
			slug : "<?php echo $video['slug']; ?>",
			ytID : "<?php echo $video['media$group']['yt$videoid']['$t']; ?>"
		};

		app();

		<?php if (!isset($basic)) { ?>

			$.tubeplayer.defaults.afterReady = function($player){
				jQuery("#player-yt").tubeplayer("mute");
			}	

			$('#page-blur').blurjs();

		<?php } else { ?>

			$('#background-blur').blurjs({
				source: 'body',
				radius: 20,
				overlay: 'rgba(255,255,255,0.4)'
			});

		<?php } ?>

	});


	// Facebook

	function logResponse(response) {
		if (console && console.log) {
			console.log('The response was', response);
		}
	}

	$(function () {
		// Set up so we handle click on the buttons
		$('#postToWall').click(function () {
			FB.ui({
				method: 'feed',
				link: $(this).attr('data-url')
			},

			function (response) {
				// If response is null the user canceled the dialog
				if (response !== null) {
					logResponse(response);
				}
			});
		});

		$('#sendToFriends').click(function () {
			FB.ui({
				method: 'send',
				link: $(this).attr('data-url')
			},

			function (response) {
				// If response is null the user canceled the dialog
				if (response !== null) {
					logResponse(response);
				}
			});
		});

		$('#sendRequest').click(function () {
			FB.ui({
				method: 'apprequests',
				message: $(this).attr('data-message')
			},

			function (response) {
				// If response is null the user canceled the dialog
				if (response !== null) {
					logResponse(response);
				}
			});
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