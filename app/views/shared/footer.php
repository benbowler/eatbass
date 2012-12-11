	<script type="text/javascript">
	$(function () {
		jQuery("#player-yt").tubeplayer({
			width: '100%', // the width of the player
			height: '100%', // the height of the player
			allowFullScreen: "true", // true by default, allow user to go full screen
			showControls: 0,
			autoPlay: true,
			showInfo: false,
			modestbranding: true,
			initialVideo: "<?php echo $video['media$group']['yt$videoid']['$t']; ?>", // the video that is loaded into the player
			preferredQuality: "default", // preferred quality: default, small, medium, large, hd720
			onPlay: function (id) {}, // after the play method is called
			onPause: function () {}, // after the pause method is called
			onStop: function () {}, // after the player is stopped
			onSeek: function (time) {}, // after the video has been seeked to a defined point
			onMute: function () {}, // after the player is muted
			onUnMute: function () {}, // after the player is unmuted
			onPlayerEnded: nextVideo,
			onErrorNotFound: nextVideo, // if a video cant be found
			onErrorNotEmbeddable: nextVideo, // if a video isnt embeddable
			onErrorInvalidParameter: nextVideo, // if we've got an invalid param
			mute: true,
		});

		$(".skip").click(function (e) {
			e.preventDefault();
			$(".skip").html('Skipping..');
			nextVideo();
		});

		$(".love").click(function (e) {
			e.preventDefault();
			var currentState = $(".love").html();
			loveVideoToggle(currentState);
		});

		// Setup video and user objects
		$.user = { 
			_id : "<?php echo $user['_id']; ?>",
		};
		$.video = { 
			_id : "<?php echo $video['_id']; ?>",
			slug : "<?php echo $video['slug']; ?>"
		}; 

		function nextVideo() {
			console.log('loading video + next virtual page.');
			$.ajax({
				url: 'api/video.php',
				success: function (data) {
					var video = jQuery.parseJSON(data);
					//alert(video['media$group']['yt$videoid']['$t']);  //media$group.yt$videoid.$t
					jQuery("#player-yt").tubeplayer("play", video.media$group.yt$videoid.$t);

					$.video = { 
						_id : video._id,
						slug : video.slug
					}; 

					<?php if (isset($basic)) { ?>
						History.pushState(data, '\u25BA ' + video.title.$t + ' - <?php echo $site_title; ?>', video.slug);
					<?php } ?>
					
					$('#video_title').html(video.title.$t);
					$('#video_author').html(video.author[0].name.$t);
					$('#video_description').html(video.media$group.media$description.$t);

					$('#background-blur').css('background-image', 'url(' + video.media$group.media$thumbnail[1].url + ')');

					_gaq.push(['_trackPageview', '/' + video.slug]);
			
					requestData = {
						user : $.user._id,
						video : $.video._id
					};

					$.ajax({
						data: requestData,
						url: 'api/lovestate.php',
						success: function (data) {
							var lovestate = jQuery.parseJSON(data);
							if(lovestate.response == true) {
								$(".love").html('Loved');
							} else {
								$(".love").html('Love');
							}
						}
					});

					$(".skip").html('Skip');

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

			if(currentState == 'Love')
			{

				$(".love").html('Loving..');

				$.ajax({
					data: requestData,
					url: 'api/love.php',
					success: function (data) {
						$("#output").html(data);
						$(".love").html('Loved');
					},
					error: function (data) {
						alert('Error loving track :(');

						$(".love").html('Love');
					}
				});

			} else {

				$(".love").html('Meh..');

				$.ajax({
					data: requestData,
					url: 'api/unlove.php',
					success: function (data) {
						$("#output").html(data);
						$(".love").html('Love');
					},
					error: function (data) {
						alert('Error un-loving track :(');

						$(".skip").html('Loved');
					}
				});
			}
		}


		function shareVideo(currentState) {
			console.log('Sharing video');

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
/*
			requestData = {
				user : $.user._id,
				video : $.video._id
			};

			if(currentState == 'Love')
			{

				$(".love").html('Loving..');

				$.ajax({
					data: requestData,
					url: 'api/love.php',
					success: function (data) {
						$("#output").html(data);
						$(".love").html('Loved');
					},
					error: function (data) {
						alert('Error loving track :(');

						$(".love").html('Love');
					}
				});

			} else {

				$(".love").html('Meh..');

				$.ajax({
					data: requestData,
					url: 'api/unlove.php',
					success: function (data) {
						$("#output").html(data);
						$(".love").html('Love');
					},
					error: function (data) {
						alert('Error un-loving track :(');

						$(".skip").html('Loved');
					}
				});
			}
			*/
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