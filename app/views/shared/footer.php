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
			logged_in : <?php echo ($user) ? true : false ; ?>,
			subscribed : <?php echo ($user['subscribed']) ? true : false ; ?>

		};
		$.video = { 
			_id : "<?php echo $video['_id']; ?>",
			slug : "<?php echo $video['slug']; ?>",
			ytID : "<?php echo $video['media$group']['yt$videoid']['$t']; ?>"
		};

		jQuery("#player-yt").tubeplayer({
			width: '100%', // the width of the player
			height: '100%', // the height of the player
			allowFullScreen: "true", // true by default, allow user to go full screen
			showControls: 0,
			autoPlay: true,
			showInfo: false,
			modestbranding: true,
			initialVideo: $.video.ytID, // the video that is loaded into the player
			preferredQuality: "default", // preferred quality: default, small, medium, large, hd720
			//onPlay: setWatchVideo, // after the play method is called
			//onPause: stopWatchVideo, // after the pause method is called
			//onStop: function () {}, // after the player is stopped
			//onSeek: function (time) {}, // after the video has been seeked to a defined point
			//onMute: stopWatchVideo, // after the player is muted
			//onUnMute: setWatchVideo, // after the player is unmuted
			onPlayerEnded: nextVideo, // after the video completely finishes
			onErrorNotFound: nextVideo, // if a video cant be found
			onErrorNotEmbeddable: nextVideo, // if a video isnt embeddable
			onErrorInvalidParameter: nextVideo, // if we've got an invalid param
			mute: true,
		});

		$(".skip").click(function (e) {
			e.preventDefault();
			$(".skip").html('skipping..');
			nextVideo();
		});

		$(".love").click(function (e) {
			e.preventDefault();
			var currentState = $(".love").html();
			loveVideoToggle(currentState);
		});

		$(".share").click(function (e) {
			e.preventDefault();
			// var currentState = $(".love").html();
			shareVideo();
		});

		function nextVideo() {
			console.log('loading video + next virtual page.');
			$.ajax({
				type: 'POST',
				url: '/api:video',
				success: function (data) {
					var video = jQuery.parseJSON(data);

					jQuery("#player-yt").tubeplayer("play", video.media$group.yt$videoid.$t);

					$.video = { 
						_id : video._id,
						slug : video.slug
					}; 

					// Update page
					if($.user.logged_in) {
						History.pushState(data, '\u25BA ' + video.title.$t + ' | <?php echo $site_title; ?>', video.slug);
					}
					
					$('#video_title').html(video.title.$t);
					$('#video_author').html(video.author[0].name.$t);
					$('#video_description').html(video.media$group.media$description.$t);

					$('#background-blur').css('background-image', 'url(' + video.media$group.media$thumbnail[1].url + ')');

					_gaq.push(['_trackPageview', '/' + video.slug]);
					
					// Get current love state
					requestData = {
						user : $.user._id,
						video : $.video._id
					};

					$.ajax({
						type: 'POST',
						data: requestData,
						url: '/api:lovestate',
						success: function (data) {
							var lovestate = jQuery.parseJSON(data);
							if(lovestate.response == true) {
								$(".love").html('loved');
							} else {
								$(".love").html('love');
							}
						}
					});

					// Send points
					scorePoints('play');

					$(".skip").html('skip');

				},
				error: function (data) {
					// On error do a full refresh
					window.location = '/';
				}
			});
		}

		function loveVideoToggle(currentState) {
			console.log('Changing video state:' + currentState);

			requestData = {
				user : $.user._id,
				video : $.video._id
			};

			if(currentState == 'love')
			{
				$(".love").html('loving..');
				requestUrl = '/api:love';
			} else {
				$(".love").html('meh..');
				requestUrl = '/api:unlove';
			}

			$.ajax({
				type: 'POST',
				data: requestData,
				url: requestUrl,
				success: function (data) {
					//////// Points and notify
					if(currentState == 'love')
					{
						$(".love").html('loved');
					} else {
						$(".love").html('love');
					}
				},
				error: function (data) {
					alertify.error('error lovering track :(');

					$(".love").html('love');
				}
			});
		}


		function shareVideo() {
			console.log('Sharing video');

			FB.ui({
				method: 'feed',
				link: $(this).attr('data-url')
			},function (response) {
				// If response is null the user canceled the dialog
				if (response) {
					console.log('Shared ' + response);
				} else {
					console.log('Share canceled');
				}
			});
		}

		function scorePoints(apiMethod) {
			console.log('registerring points: ' + apiMethod);

			requestData = {
				method : apiMethod,
				user : $.user._id,
				video : $.video._id
			};

			$.ajax({
				type: 'POST',
				data: requestData,
				url: '/api:points',
				success: function (data) {
					$("#output").html(data);
					//$(".love").html('love');
					alertify.success('+10 points');
				},
				error: function (data) {

					alertify.error('error scoring points :(');

					//alert('Error un-loving track :(');  /// @todo: custom alert

					//$(".skip").html('loved');
				}
			});
		}

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