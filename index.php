<?php // index

require_once('app/config.php');
require_once('app/model.php');
require_once('app/controller.php');

ini_set('display_errors', 0);

// routes
$segments = $_GET['slug'];
$route = 'index';
//unset($segments[1]);

/* FB */

      // Provides access to app specific values such as your app id and app secret.
        // Defined in 'AppInfo.php'
        require_once('app/AppInfo.php');

        // This provides access to helper functions defined in 'utils.php'
        require_once('app/utils.php');


        /*****************************************************************************
         *
         * The content below provides examples of how to fetch Facebook data using the
         * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
         * do so.  You should change this section so that it prepares all of the
         * information that you want to display to the user.
         *
         ****************************************************************************/

        require_once('app/modules/facebook-php-sdk/src/facebook.php');

        $facebook = new Facebook(array(
          'appId'  => AppInfo::appID(),
          'secret' => AppInfo::appSecret(),
          'sharedSession' => true,
          'trustForwarded' => true,
        ));

        $user_id = $facebook->getUser();
        if ($user_id) {
          try {
            // Fetch the viewer's basic information
            $basic = $facebook->api('/me');
          } catch (FacebookApiException $e) {
            // If the call fails we check if we still have a user. The user will be
            // cleared if the error is because of an invalid accesstoken
            if (!$facebook->getUser()) {
              header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
              exit();
            }
          }

          // This fetches some things that you like . 'limit=*" only returns * values.
          // To see the format of the data you are retrieving, use the "Graph API
          // Explorer" which is at https://developers.facebook.com/tools/explorer/
          $likes = idx($facebook->api('/me/likes?limit=4'), 'data', array());

          // This fetches 4 of your friends.
          $friends = idx($facebook->api('/me/friends?limit=4'), 'data', array());

          // And this returns 16 of your photos.
          $photos = idx($facebook->api('/me/photos?limit=16'), 'data', array());

          // Here is an example of a FQL call that fetches all of your friends that are
          // using this app
          $app_using_friends = $facebook->api(array(
            'method' => 'fql.query',
            'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
          ));
        }

        // Fetch the basic info of the app that they are using
        $app_info = $facebook->api('/'. AppInfo::appID());

        $app_name = idx($app_info, 'name', '');


/* END FB */

$controller = new controller();
$controller->$route($segments);