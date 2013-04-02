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
        $this->data['site_about'] = "Watch the latest bass music news, reviews, interviews and listen to the latest tracks. Just login to watch, love and share videos with friends. Every month we offer up some music, merch or tickets and every play, love and share enters you to win so what are you waiting for? Login to watch now.";

        /* Do FB */
        $this->_fb();
    }
    private function view($view)
    {
        extract($this->data);
        include("views/$view.php");
    }
    // controller
    public function index($slug)
    {
        $this->data['slug'] = $slug;
        $this->data['video'] = $this->model->get_video($slug);
        if($this->data['video'] == false) {
            //die('404');?
        }

        $video = $this->data['video'];
        if($this->data['basic'] && !$slug) {
            // @todo: redirect if logged in... else ... show video in the backgound (Muted) with login button over
            header("Location: http://" . $_SERVER['SERVER_NAME'] . "/" . $this->data['video']['slug']); // Redirect browser
            die();
        }

        //$this->data['profile'] = $this->model->get_profile($this->data['user']->_id, $limit);
        
        //var_dump($slug);
        //var_dump($this->data['video']);

        // Move to sitemap?
        //$this->data['top_plays_this_week'] = $this->model->get_top_videos_by('ytPlays', 10, (60 * 60 * 24 * 7));

        //die(var_dump($this->data['top_plays_this_week']));
        //$this->data['top_likes'] = $this->model->get_video($slug);

        if($slug) {
            $description = "{$video['title']['$t']} {$this->data['site_title']} | {$this->data['site_description']}

            {$video['media$group']['media$description']['$t']}
            ";
            
            $this->data['meta_tags'] = array(
                'og:type' => 'video.other',
                'og:url' => "https://" . $_SERVER['HTTP_HOST'] . "/" . $slug,
                'og:image' => $video['media$group']['media$thumbnail'][2]['url'],
                'og:title' => $video['title']['$t'] . ' ' . $this->data['site_title'],
                'og:site_name' => $this->data['site_title'],
                //'og:description' =>  $description,
                'fb:app_id' => $this->data['appID'],
                'title' => $video['title']['$t'] . ' ' . $this->data['site_title'],
                'description' => $this->data['site_about'],
            );

            $this->data['title_tag'] = "&#9658; " . $video['title']['$t'] . ' ' . $this->data['site_title'];
        } else {
            $description = "{$this->data['site_title']} | {$this->data['site_description']}

            watch, love and share for points

            win music, tickets and merch every month
            ";
            
            $this->data['meta_tags'] = array(
                'title' => $this->data['site_title'] . ' | ' . $this->data['site_description'],
                'description' => $description,
                'og:type' => 'website',
                'og:url' => "https://" . $_SERVER['HTTP_HOST'] . "/",
                'og:image' => "https://" . $_SERVER['HTTP_HOST'] . "/assets/images/share.jpg",
                'og:site_name' => $this->data['site_title'],
                'og:title' => $this->data['site_title'] . ' | ' . $this->data['site_description'],
                'og:description' =>  $description,
                'fb:app_id' => $this->data['appID'],
            );

            $this->data['title_tag'] = $this->data['site_title'] . ' | ' . $this->data['site_description'];

            $this->data['top_plays'] = $this->model->get_top_videos_by('ytPlays');
        }

        //$this->data['profile'] = $this->model->get_profile($this->data['basic']['id']);

        //die(var_dump($this->data['user']));

        $this->view('video', $this->data); //$this->model->get());
    }

    /* User Controller */
    public function user($slug)
    {
        die('user');
        $this->view('video', $this->data); //$this->model->get());
    }

    /* User Controller */
    public function channel($slug)
    {
        $this->view('channel', $this->data); //$this->model->get());
    }

    public function api($slug)
    {
        $route = explode(":", $slug);

        require_once($_SERVER['DOCUMENT_ROOT'].'/app/controllers/api.php');
        $api = new api();
        $api->$route[0]($route[1]);
    }

    public function rss($slug)
    {
        $route = explode(":", $slug);

        require_once($_SERVER['DOCUMENT_ROOT'].'/app/controllers/rss.php');
        $rss = new rss();
        $rss->$route[0]($route[1]);
    }

    /* User Controller */
    public function stats($slug)
    {
        //echo 'stats<br />';

        $stats = explode(":", $slug);

        if($stats[0] == 'leaderboard') {
            //echo 'leaderboard';

            $this->data['users'] = $this->model->get_top_users();
            //var_dump($users);

            $this->view('stats', $this->data); //$this->model->get());
        }

        /*

        if(!$stats[0]) {
            die('Stat details not specified.');
        }
        //            0     1    2   3     4
        //      stats:model:stat:asc:limit:time
        // eg   stats:get_top_videos_by:ytPlays:asc:10

        if($stats[3] > 100) {
            die('Not allowed more than 100!');
        }

        $asc = ($stats[2] == 'asc') ? 1 : -1 ;

        $videos = get_top_videos_by($stats[1], $asc, $stats[4], @$stats[4]);

        var_dump($videos);
        */



        //$stats = $this->model->

        //$this->view('profile', $this->data); //$this->model->get());
    }

    public function sitemap($format)
    {
        $this->data['top_loves'] = $this->model->get_top_videos_by('ytLoves', 1, 1000);
        $this->data['top_plays'] = $this->model->get_top_videos_by('ytPlays', 1, 4000);

        $this->view("sitemap_{$format}", $this->data); //$this->model->get());
    }

    public function info($file)
    {
        $this->data['info'] = false;
        
        if(file_exists("assets/info/$file.html")) {
            $this->data['info'] = file_get_contents("assets/info/$file.html");
        }
        $this->view('info', $this->data); //$this->model->get());
    }

    public function admin()
    {
        foreach ($this->model->get_users(1000) as $user) {
            echo "{$user['points']},{$user['first_name']}<br />";
        }
    }

    private function _fb()
    {
        /* FB */

        // Provides access to app specific values such as your app id and app secret.
        // Defined in 'AppInfo.php'
        require_once($_SERVER['DOCUMENT_ROOT'].'/app/AppInfo.php');

        // This provides access to helper functions defined in 'utils.php'
        require_once($_SERVER['DOCUMENT_ROOT'].'/app/utils.php');


        /*****************************************************************************
        *
        * The content below provides examples of how to fetch Facebook data using the
        * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
        * do so.  You should change this section so that it prepares all of the
        * information that you want to display to the user.
        *
        ****************************************************************************/

        require_once($_SERVER['DOCUMENT_ROOT'].'/app/modules/facebook/src/facebook.php');

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
                    $likes = $facebook->api('/me/likes');

                    //die(var_dump($likes));
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

                    $extendedaccesstoken = $facebook->getExtendedAccessToken();

                    $this->data['user'] = $this->model->user($email, $this->data['basic'], $likes, $extendedaccesstoken);
                }
            } catch (FacebookApiException $e) {
            // If the call fails we check if we still have a user. The user will be
            // cleared if the error is because of an invalid accesstoken
            /*
            if (!$facebook->getUser()) {
                header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
                exit();
            }
            */
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