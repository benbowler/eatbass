// F'd

function app()
{

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
		mute: true
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
						if(lovestate.response === true) {
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
				var json = jQuery.parseJSON(data);
				if(json.response) {
					alertify.success('+1 point for playing');
					updatePoints();
				}
			},
			error: function (data) {

				alertify.error('error scoring points :(');

				//alert('Error un-loving track :(');  /// @todo: custom alert

				//$(".skip").html('loved');
			}
		});
	}

	function updatePoints() {
		console.log('updating user points');

		requestData = {
			user : $.user._id
		};

		$.ajax({
			type: 'POST',
			data: requestData,
			url: '/api:userpoints',
			success: function (data) {
				$("#points").html(data);
				//$(".love").html('love');
				//alertify.success('+10 points');
			}
			/* error updating points?
			error: function (data) {

				//alertify.error('error scoring points :(');

				//alert('Error un-loving track :(');  /// @todo: custom alert

				//$(".skip").html('loved');
			}*/
		});
	}
}