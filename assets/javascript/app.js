// App!

function app()
{

	if(!$.user.logged_in) {
		// Do login dependant stuff
		$.tubeplayer.defaults.afterReady = function($player){
			jQuery("#player-yt").tubeplayer("mute");
		};

		$('#page-blur').blurjs();

	} else {

		$.tubeplayer.defaults.afterReady = function($player){
			jQuery("#player-yt").tubeplayer("unmute");
		};

		if(!scorePoints('return', '+100 for logging in today')) {
			$.alertify.log('log in again tomorrow for +100 points');
		} else {
			$.alertify.log('log in tomorrow for +100 points');
		}

		
		$('#background-blur').blurjs({
			source: 'body',
			radius: 20,
			overlay: 'rgba(255,255,255,0.4)'
		});
	}


	// Prepair video
	jQuery("#player-yt").tubeplayer({
		width: '100%', // the width of the player
		height: '100%', // the height of the player
		allowFullScreen: "true", // true by default, allow user to go full screen
		showControls: 0,
		autoPlay: true,
		showInfo: false,
		protocol: 'https',
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

	// Triggers
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

	$(".profile").click(function (e) {
		e.preventDefault();
		// var currentState = $(".love").html();
		viewProfile();
	});

	// Actions
	function nextVideo() {
		console.log('loading video + next virtual page.');
		jQuery("#player-yt").tubeplayer("pause");

		$.ajax({
			type: 'POST',
			url: '/api:video',
			success: function (data) {
				var video = jQuery.parseJSON(data);

				jQuery("#player-yt").tubeplayer("play", video.media$group.yt$videoid.$t);

				$.video = {
					_id : video._id,
					slug : video.slug,
					title : video.title.$t,
					picture : video.media$group.media$thumbnail[1].url
				};

				// Update page
				if($.user.logged_in) {
					History.pushState(data, '\u25BA ' + video.title.$t + ' | ' + $.site.title, video.slug);
				}
				
				$('#video_title').html(video.title.$t);
				$('#video_author').html(video.author[0].name.$t);
				$('#video_description').html(htmlFormat(video.media$group.media$description.$t));
				$('.channel').attr('href', 'http://youtube.com/user/'+video.media$group.media$description.$t);

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

				$(".skip").html('skip');

				// Send points
				scorePoints('play', '+1 point for playing');

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
					// Send points
					scorePoints('love', '+10 point for loving');

					$(".love").html('loved');
				} else {

					$(".love").html('love');
				}
			},
			error: function (data) {
				$.alertify.error('error lovering track :(');

				$(".love").html('love');
			}
		});
	}


	function shareVideo() {
		console.log('Sharing video ' + document.URL);

		FB.ui({
			method: 'feed',
			name: $.video.title + ' ' + $.site.title,
			picture: $.video.picture,
			link: document.URL,
			caption: 'dicover more like ' + $.video.title + ' on #eatbass and win music, merch and tickets.'
			//message: 'message',
			//description: 'Deskriptions'
		},function (response) {
			// If response is null the user canceled the dialog
			if (response) {
				// Send points
				scorePoints('share', '+50 point for sharing');

				console.log('Shared ' + response);
			} else {
				console.log('Share canceled');
			}
		});
	}

	// Internal functions

	function scorePoints(apiMethod, successMessage) {
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
				//$("#output").html(data);
				//$(".love").html('love');
				
				var json = jQuery.parseJSON(data);
				if(json.response) {
					$.alertify.log(successMessage); //json.successMessage
					//alert(successMessage+ 'blah');

					updatePoints();
				}
			},
			error: function (data) {

				console.log(data);
				return false;

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
				$("#points").fadeOut(100).fadeIn(500);
				//$(".love").html('love');
				//$.alertify.success('+10 points');
			}
			/* error updating points?
			error: function (data) {

				//$.alertify.error('error scoring points :(');

				//alert('Error un-loving track :(');  /// @todo: custom alert

				//$(".skip").html('loved');
			}*/
		});
	}

	// Page stuff

	// Profile

	// hide with js if not profile
	$("#profile").hide();

	function viewProfile() {
		console.log('loading profile');

		$('#page-blur').blurjs();
		$("#profile").fadeIn();

		// @todo: load userdata?

		requestData = {
			user : $.user._id
		};

		$.ajax({
			type: 'POST',
			data: requestData,
			url: '/api:profile',
			success: function (data) {

				$("#profile_videos").html(data);

				//$("#points").fadeOut(100).fadeIn(500);
				//$(".love").html('love');
				//$.alertify.success('+10 points');
			},
			error: function (data) {

				$.alertify.error('error loading profile :(');

				//alert('Error un-loving track :(');  /// @todo: custom alert

				//$(".skip").html('loved');
			}
		});
	}

	// Formatting
	/*

	function htmlFormat (str, is_xhtml) {

		alert('formatting');
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
		toReturn = (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');

		console.log(toReturn);
		return toReturn;
	}

	*/


	// Facebook

	function logResponse(response) {
		if (console && console.log) {
			console.log('The response was', response);
		}
	}

	/*
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
	*/
	$('.recommend').click(function () {
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
}