// App!

function app()
{

    if(!$.user.logged_in) {
        // Do login dependant stuff
        $.tubeplayer.defaults.afterReady = function($player){
            //jQuery("#player-yt").tubeplayer("mute");
        };

        $('#page-blur').blurjs();

    } else {

        $.tubeplayer.defaults.afterReady = function($player){
            //jQuery("#player-yt").tubeplayer("unmute");

            scorePoints('return', '+20 for logging in today', 'come back again tomorrow for +20');
            /*
            alertify.confirm( 'invite your friends for +50 points', function (e) {
                if (e) {
                    console.log('invited users' + e);

                    inviteUsers();
                } else {
                    console.log('invites cancelled');
                    //after clicking Cancel
                }
            });
            */

            // <div class="fb-like" data-href="http://facebook.com/eatbassnow" data-send="true" data-width="450" data-show-faces="true"></div>
        };

        $.alertify.log('welcome to #eatbass');

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
        theme: "light",
        color: "white",
        modestbranding: false,
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
        $(".skip").html('skipping');
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
        //viewProfile();
    });

    // Actions
    function nextVideo() {
        console.log('loading video + next virtual page.');
        jQuery("#player-yt").tubeplayer("pause");

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/api:video',
            success: function (video) {
                console.log(video);

                jQuery("#player-yt").tubeplayer("play", video.media$group.yt$videoid.$t);

                $.video = {
                    _id : video._id,
                    slug : video.slug,
                    title : video.title.$t,
                    author : video.author[0].name.$t,
                    description : video.media$group.media$description.$t,
                    html_description : video.html_description,
                    picture : video.media$group.media$thumbnail[1].url
                };

                //if (video.html_description) { video.html_description } else { video.media$group.media$thumbnail[1].url }

                // Update page
                if($.user.logged_in) {
                    History.pushState(video, '\u25BA ' + video.title.$t + ' | ' + $.site.title, video.slug);
                }
                
                $('#video_title').html(video.title.$t);
                $('#video_author').html(video.author[0].name.$t);
                $('#video_description').html(video.html_description);
                $('.channel').attr('href', 'http://youtube.com/user/'+video.author[0].name.$t);

                var picture = video.media$group.media$thumbnail[1].url.replace('http', 'https');
                console.log(picture);
                $('#background-blur').css('background-image', 'url(' + picture + ')');

                _gaq.push(['_trackPageview', '/' + video.slug]);
                
                // Get current love state
                requestData = {
                    user : $.user._id,
                    video : $.video._id
                };

                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    data: requestData,
                    url: '/api:lovestate',
                    success: function (data) {
                        if(data.response === true) {
                            $(".love").html('loved');
                        } else {
                            $(".love").html('love');
                        }
                    }
                });

                $(".skip").html('skip');

                // Send points
                scorePoints('play', '+1 point for playing');
                doOpenGraph('video.watches');

            },
            error: function (data) {

                console.log('error'+data);

                $.alertify.error('error loading video :(');

                // On error do a full refresh
                //window.location = '/';
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
            $(".love").html('loving');
            requestUrl = '/api:love';
        } else {
            $(".love").html('meh');
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

                    //doOpenGraph('like');

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

    // Facebook Share functionality
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

    function inviteUsers() {

        FB.ui({
            method: 'apprequests',
            message: 'win music, tickets and merch by listening to the music you love',
            filters: ['app_non_users'],
            title: '#eatbass bass music tv'
        },
        function (response) {
            if (response && response.request_ids) {
                //if sucess do something
                //How many people did the user invited?
                var $howManyInvites = String(requests).split(',').length;

                scorePoints('invite', '+50 for inviting friends', 'thanks for inviting friends');
            } else {
                //  alert('canceled');
                return false;
            }
        });
        return false;

    }

    function doOpenGraph(recipe) {

        openGraphRecipe = {
            video : document.URL
            /*
            recipe : document.URL,
            title : $.video.title,
            author : $.video.autho,
            description : $.video.description,
            other : false
            */
        };
        console.log(openGraphRecipe);

        // FB Open Graph Action
        FB.api('/me/video.watches', 'post',
            openGraphRecipe,
            function(response) {
                console.log(response);
                if (!response || response.error) {
                    //alert('Error occured');
                    //fbJsLogin();
                }
                else {
                    //alert('Action was successful! Action ID: ' + response.id);
                }
            });
                    /*
        openGraphRecipe = {
            recipe : document.URL,
            title : $.video.title,
            author : $.video.autho,
            description : $.video.description,
            other : false
        };
        console.log(openGraphRecipe);

        // FB Open Graph Action
        FB.api('/me/eatbass:like', 'post',
            openGraphRecipe,
            function(response) {
                console.log(response);
                if (!response || response.error) {
                    alert('Error occured');
                    //fbJsLogin();
                }
                else {
                    alert('Action was successful! Action ID: ' + response.id);
                }
            });
*/
    }

    // Internal functions

    function scorePoints(apiMethod, successMessage, failMessage) {
        console.log('registerring points: ' + apiMethod);

        requestData = {
            method : apiMethod,
            user : $.user._id,
            video : $.video._id
        };

        console.log(requestData);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: requestData,
            url: '/api:points',
            success: function (data) {
                //$('#output').html(data);

                console.log('scored points '+data);

                if(data.response === true) {
                    $.alertify.log(successMessage);

                    updatePoints();
                } else {
                    if(failMessage) {
                        $.alertify.log(failMessage);
                    }
                }
            },
            error: function (data) {
                console.log('failed connecting to api');

                $.alertify.log('error connecting to #eatbass');
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
                //$("#points").stop().css("background-color", "#FFFF9C").animate({ backgroundColor: "#FFFFFF"}, 1500);
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

    // Facebook

    function logResponse(response) {
        if (console && console.log) {
            console.log('The response was', response);
        }
    }

    // Set up so we handle click on the buttons
    $('#fb-js-login').click(function (e) {
        e.preventDefault();

        fbJsLogin();
    });

    function fbJsLogin()
     {
            FB.login(function(response) {
                window.location = window.location;
            }, {scope: 'email,user_likes,publish_actions'});
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