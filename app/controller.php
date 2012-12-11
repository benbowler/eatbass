<?php // controller

class controller
{
    protected $model;
    protected $view;
    public function __construct()
    {
        $this->model = new model();

        $this->data['site_title'] = '#eatbass'; //he($app_name);
        $this->data['site_description'] = "bass music tv";

        /* Do FB */
        $this->_fb();
    }
    private function view($view, $data)
    {
        extract($this->data);
        include("views/$view.php");
    }
    // controller
    public function index($slug)
    {
        $this->data['slug'] = $slug;
        $this->data['video'] = $this->model->get_video($slug);

        //var_dump($slug);
        //die(var_dump($this->data['video']));

        if($this->data['basic'] && !$slug) {
            // @todo: redirect if logged in... else ... show video in the backgound (Muted) with login button over
            header("Location: http://" . $_SERVER['SERVER_NAME'] . "/" . $this->data['video']['slug']); /* Redirect browser */
            exit;
        }

        // Move to sitemap?
        //$this->data['top_plays_this_week'] = $this->model->get_top_videos_by('ytPlays', 10, (60 * 60 * 24 * 7));

        //die(var_dump($this->data['top_plays_this_week']));
        //$this->data['top_plays'] = $this->model->get_top_videos_by('ytPlays',);
        //$this->data['top_likes'] = $this->model->get_video($slug);

        $this->view('video', $this->data); //$this->model->get());
    }

    /* User Controller */
    public function u($slug)
    {
        $this->view('video', $this->data); //$this->model->get());
    }

    /* User Controller */
    public function stats($slug)
    {
        echo 'stats';

        $stats = explode(":", $slug);

        if(!$stats[0]) {
            die('Stat details not specified.');
        }



        //$stats = $this->model->

        //$this->view('profile', $this->data); //$this->model->get());
    }

    private function _fb()
    {
        /* FB */

        // Provides access to app specific values such as your app id and app secret.
        // Defined in 'AppInfo.php'
        require_once('AppInfo.php');

        // This provides access to helper functions defined in 'utils.php'
        require_once('utils.php');


        /*****************************************************************************
        *
        * The content below provides examples of how to fetch Facebook data using the
        * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
        * do so.  You should change this section so that it prepares all of the
        * information that you want to display to the user.
        *
        ****************************************************************************/

        require_once('modules/facebook-php-sdk/src/facebook.php');

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
          $this->data['basic'] = $facebook->api('/me');

          if($this->data['basic']) {
            $email = $facebook->api('/me?fields=email');
            /*
            //Create Query
            $params = array(
                'method' => 'fql.query',
                'query' => "SELECT name FROM page WHERE page_id IN (SELECT uid, page_id, type FROM page_fan WHERE uid=me()) AND type='musician/band'",
            );
            //Run Query
            $results = $facebook->api($params);
            $music = array();
            foreach ($results as $result) {
                array_push($music, $result['name']);
            }*/

            $this->data['user'] = $this->model->user($email, $this->data['basic']);
          }
        } catch (FacebookApiException $e) {
          // If the call fails we check if we still have a user. The user will be
          // cleared if the error is because of an invalid accesstoken
          if (!$facebook->getUser()) {
            header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
            exit();
          }
        }

        /*
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
        */

        }

        // Fetch the basic info of the app that they are using
        $this->data['app_info'] = $facebook->api('/'. AppInfo::appID());
        $this->data['app_name'] = idx($this->data['app_info'], 'name', '');

        $this->data['user_id'] = $user_id;

        $this->data['appID'] = AppInfo::appID();
        $this->data['getUrl'] = AppInfo::getUrl();
    }
}