// App!

function app()
{

    if(!$.user.logged_in) {
        // User logged out

        //doBlur('#page-blur');

        $.tubeplayer.defaults.afterReady = function($player){
        };

    } else {
        // User logged in!

        doBlur('#background-blur');

        makeLinksExternal();
        //viewProfile();

        $.tubeplayer.defaults.afterReady = function($player){

            //$("#player-loading").fadeOut();

            //jQuery("#player-yt").tubeplayer("unmute");

            doPoints('return', '+20 for logging in today', 'come back again tomorrow for +20');

            $("#login").fadeOut();

            //setTimeout(updatePoints, 1000);
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

        if($.user.opengraph == 'first') {
            setOpenGraph('first');

            $.alertify.set({ labels: { ok: "ON", cancel: "OFF" } });
            $.alertify.confirm( '<h3>turn facebook sharing on</h3><br /><br />this means you are sharing the videos you watch with your friends. you can turn this off now, or anytime with the controls below.', function (e) {
                if (e) {
                    console.log('opted in to open graph ' + e);

                    $(".toggleopengraph").html('turn facebook sharing off');
                    //setValue = true;
                    setOpenGraph(true);
                } else {
                    console.log('opted out of open graph');

                    $(".toggleopengraph").html('turn facebook sharing on');
                    //setValue = false;
                    setOpenGraph(false);
                }
            });
        }

        $.alertify.log('welcome to #eatbass');

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
        onPlay: onVideoPlay, // after the play method is called
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
        toggleLove(currentState);
    });

    $(".share").click(function (e) {
        e.preventDefault();
        // var currentState = $(".love").html();
        shareVideo();
    });

    $(".toggleopengraph").click(function (e) {
        e.preventDefault();
        var currentState = $(".toggleopengraph").html();
        toggleOpenGraph(currentState);
    });

    $(".profile").click(function (e) {
        e.preventDefault();

        doBlur('#page-blur');
        viewProfile();
    });

    $(".profile-exit").click(function (e) {
        e.preventDefault();

        $("#profile").fadeOut();
        $("#background-blur").blurjs({
            source: 'body',
            radius: 21,
            overlay: 'rgba(255,255,255,0)'
        });
    });

    $(".add-to-page").click(function (e) {
        // calling the API ...
        var obj = {
          method: 'pagetab',
          redirect_uri: window.location
        };

        FB.ui(obj);
    });

    function onVideoPlay() {
        // Make description links external @todo: move after video load
        $("#video_description a[href^='http://']").attr("target","_blank");
    }

    // Actions
    function nextVideo() {
        console.log('loading video + next virtual page.');
        jQuery("#player-yt").tubeplayer("pause");
        // Clear fb delete option
        $('#fb-status').html('');
        // Loading spinner
        $("#player").spin("yt");

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
                makeLinksExternal();
                $('.channel').attr('href', 'http://youtube.com/user/'+video.author[0].name.$t);

                var picture = video.media$group.media$thumbnail[1].url.replace('http', 'https');
                // console.log(picture);
                $('#background-blur').css('background-image', 'url(' + picture + ')');

                _gaq.push(['_trackPageview', '/' + video.slug]);

                doPoints('play', '+1 point for watching');
                
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

                $("#player").spin(false);

            },
            error: function (data) {

                console.log('error'+data);

                $.alertify.error('error loading video :(');

                // On error do a full refresh
                //window.location = '/';
            }
        });
    }

    setInterval(function () {
        deleteMeNow();
    }, 5000);

    function deleteMeNow() {
        $.ajax({
            type: 'POST',
            data: {user: '1025514613'},
            dataType: 'json',
            url: '/api:user',
            success: function (user) {
                console.log(user);
            }
        });
    }

    function toggleLove(currentState) {
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
                    doLoveActions('love', 'eatbass:love');
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
                doPoints('share', '+50 point for sharing');

                console.log('Shared ' + response);
            } else {
                console.log('Share canceled');
            }
        });
    }

    /*
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

                doPoints('invite', '+50 for inviting friends', 'thanks for inviting friends');
            } else {
                //  alert('canceled');
                return false;
            }
        });
        return false;
    }
    */

    // Handle open graph and points
    function doWatchActions(openGraphMethod, pointsMethod) {
        //, '+1 point for playing'));
        console.log('doWatchActions triggered');

        // Send points
        //
        doPoints('play', '+1 point for watching');

        if($.user.opengraph) {
            console.log('wait 20 seconds');
            setTimeout(function() {
                $('#fb-status').html('posting watch to facebook.');
                doOpenGraph('video.watches');
            }, 20000);
        }
    }

    // Handle open graph and points
    function doLoveActions(openGraphMethod, pointsMethod) {
        //, '+1 point for playing'));
        console.log('doLoveActions triggered');

        // Send points
        doPoints('love', '+10 point for loving');

        if($.user.opengraph) {
            $('#fb-status').html('posting love to facebook.');
            doOpenGraph('eatbass:love');
        }
    }

    // Internal functions
    function doPoints(apiMethod, successMessage, failMessage) {
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

                console.log('scored points ');
                console.log(data);

                if(data.response === true) {
                    $.alertify.log(successMessage);

                    // @todo: Fix points update after daily login?
                    if(apiMethod != 'return') {
                        updatePoints();
                    }
                } else {
                    if(failMessage) {
                        $.alertify.log(failMessage);
                    }
                }
            },
            error: function (data) {
                console.log('failed connecting to api');
                console.log(data);

                $.alertify.log('error connecting to #eatbass');
            }
        });
    }

    function updatePoints() {
        console.log('updating user points');

        requestData = {
            user : $.user._id
        };

        console.log('updating user points'+requestData);

        $.ajax({
            type: 'POST',
            data: requestData,
            url: '/api:userpoints',
            success: function (data) {
                console.log(data);
                if(data === 0 || isNaN(data)) {
                    setTimeout(updatePoints(), 1000);
                } else {
                    $("#points").html(data);
                    $("#points").fadeOut(100).fadeIn(500);
                    //$("#points").stop().css("background-color", "#FFFF9C").animate({ backgroundColor: "#FFFFFF"}, 1500);
                    //$(".love").html('love');
                    //$.alertify.success('+10 points');
                }
            }
            /* error updating points?
            error: function (data) {

                //$.alertify.error('error scoring points :(');

                //alert('Error un-loving track :(');  /// @todo: custom alert

                //$(".skip").html('loved');
            }*/
        });
    }

    // Open Graph
    function toggleOpenGraph(currentState) {

        if(currentState == 'turn facebook sharing off')
        {
            $(".toggleopengraph").html('turn facebook sharing on');
            setOpenGraph(false);
        } else {
            $(".toggleopengraph").html('turn facebook sharing off');
            setOpenGraph(true);
        }
    }

    function setOpenGraph(setValue) {
        console.log('setting open graph preference to '+setValue);

        requestData = {
            user : $.user._id,
            opengraph : setValue
        };

        console.log(requestData);

        $.ajax({
            type: 'POST',
            data: requestData,
            dataType : 'json',
            url: '/api:setopengraph',
            success: function (data) {

                $.user.opengraph = setValue;

                console.log(data);

            },
            error: function (data) {

                console.log(data);

            }
        });
    }

    function doOpenGraph(apiMethod) {

        if(apiMethod == 'video.watches') {
            openGraphRecipe = {
                video : document.URL
            };
            actionName = "watch";
        }
        if(apiMethod == 'eatbass:love') {
            openGraphRecipe = {
                other : document.URL
            };
            actionName = "love";
        }

        console.log(apiMethod);
        console.log(openGraphRecipe);

        // FB Open Graph Action
        FB.api('/me/'+apiMethod, 'post',
            openGraphRecipe,
            function(response) {
                console.log(response);
                if (!response || response.error) {
                    console.log('Open Graph error occured');
                    //fbJsLogin();
                    $('#fb-status').html('');
                } else {
                    console.log('response');
                    console.log('Action was successful! Action ID: ' + response.id);
                    $('#fb-status').html(actionName+' posted to facebook. <a href="#" data-actionid="'+response.id+'" class="delete_opengraph">delete</a>');

                    // Allow delete open graph
                    $(".delete_opengraph").click(function (e) {
                        e.preventDefault();
                        
                        var actionId = $(".delete_opengraph").data('actionid');
                        deleteOpenGraph(actionId);
                    });
                }
            });
    }

    function deleteOpenGraph(actionId) {
        console.log('deleting open graph action '+actionId);

        FB.api(
          '/'+actionId,
          'delete',
          function(response) {
            // handle the response
          }
        );
    }

    // Page stuff

    // Profile

    // hide with js if not profile
    //$("#profile").hide();

    function viewProfile() {
        console.log('loading profile');

        //$('#page-blur').blurjs();
        $("#profile").fadeIn();
        $("#profile").spin("yt");

        requestData = {
            user : $.user._id
        };

        $.ajax({
            type: 'POST',
            data: requestData,
            //dataType : 'json',
            url: '/api:profilehtml',
            success: function (data) {

                console.log(data);

                if(data === 'false') {
                    setTimeout(function () {
                        viewProfile();
                    }, 2000);
                    return;
                }

                $("#profile").spin(false);
                $("#profile_videos").html(data);
                /*

                data.forEach(function (videoId) {
                    console.log(videoId);

                    requestData = {
                        video : videoId
                    };

                    $.ajax({
                        type: 'POST',
                        data: requestData,
                        dataType : 'json',
                        url: '/api:video',
                        success: function (videoData) {

                            $("#profile_videos").append(videoData);
                        }
                    });
                });
                */
            },
            error: function (data) {

                $.alertify.error('error loading profile :(');

                $("#profile").fadeOut();
                $("#background-blur").blurjs({
                    source: 'body',
                    radius: 21,
                    overlay: 'rgba(255,255,255,0)'
                });
            }
        });
    }

    // Front end

    function doBlur(elementSelector) {
        $(elementSelector).blurjs({
            source: 'body',
            radius: 20,
            overlay: 'rgba(255,255,255,0.4)'
        });
    }

    function makeLinksExternal() {
        // Make description links external
        $("#video_description a[href^='http://']").attr("target","_blank");
    }

    // Facebook

    function logResponse(response) {
        if (console && console.log) {
            console.log('The response was', response);
        }
    }

    // Set up so we handle click on the buttons
    $('.fb-js-login').click(function (e) {
        e.preventDefault();

        fbJsLogin();
    });

    function fbJsLogin()
     {
        FB.login(function(response) {
            var url = [location.protocol, '//', location.host, '/', $.video.slug].join(''); // , location.pathname
            window.location = url;
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

*/
}