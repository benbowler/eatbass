<?php // model

class Model
{
    protected $mysqli;
    public function __contruct()
    {
    }
    public function get_video($slug = false)
    {
        $this->_connect();
        $this->col = $this->db->videos;

        if($slug) {

          return $this->col->findOne(array('slug' => $slug)); // ->limit(1)->skip(rand(-1, $col->count()-1))->getNext();

        } else {

          return $this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

        }

        $this->_close();
    }
    public function get_top_videos_by($by = 'ytPlays', $limit = 10)
    {
        $this->_connect();
        $this->col = $this->db->videos;

        return $this->col->sort(array('by' => 1))->limit($limit);

        $this->_close();
    }
    public function user($email, $user = false)
    {
        $this->_connect();
        $this->col = $this->db->users;

        if(!$email || !$user) {
            return false;
        }

        require_once 'modules/mailchimp-api-class/examples/inc/config.inc.php'; //contains apikey
        require_once 'modules/mailchimp-api-class/examples/inc/MCAPI.class.php';

        $api = new MCAPI($apikey);

        $merge_vars = array('FNAME'=>$user['first_name'], 'LNAME'=>$user['last_name'], 
                          'GROUPINGS'=>array(
                                array('name'=>'Music:', 'groups'=>implode(',', $music)),
                                //array('id'=>22, 'groups'=>'Trains'),
                                )
                            );

        // By default this sends a confirmation email - you will not see new members
        // until the link contained in it is clicked!
        $retval = $api->listSubscribe('0f213b0888', $user['email'], $merge_vars, $email_type='html', $double_optin=true, $update_existing=true, $replace_interests=true, $send_welcome=true);

        /*
        if ($api->errorCode){
            echo "Unable to load listSubscribe()!\n";
            echo "\tCode=".$api->errorCode."\n";
            echo "\tMsg=".$api->errorMessage."\n";
        } else {
            echo "Subscribed - look for the confirmation email!\n";
        }
        */

        $user['_id'] = $user['username'];

        try {
            $this->col->insert((object) $user, true);

            return (object) $user;
            //echo "Added {$video->title->{'$t'}}<br />";
        } catch(MongoCursorException $e) {
            //echo "Can't save the same video twice!<br />";

            return $this->col->findOne(array('_id' => $user['username']));
        }
/*
          return $this->col->findOne(array('slug' => $slug)); // ->limit(1)->skip(rand(-1, $col->count()-1))->getNext();

          $video = $this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

          // @todo: redirect if logged in... else ... show video in the backgound (Muted) with login button over
          header("Location: http://" . $_SERVER['SERVER_NAME'] . "/" . $video['slug']); /* Redirect browser 
            exit;

        }
*/
        $this->_close();
    }
    public function test()
    {
    }

    private function _connect()
    {
        // Mongo
        # get the mongo db name out of the env
        $mongo_url = parse_url(getenv("MONGOHQ_URL"));
        $dbname = str_replace("/", "", $mongo_url["path"]);

        # connect
        $this->m   = new Mongo(getenv("MONGOHQ_URL"));
        $this->db  = $this->m->$dbname;


        /*
        # get the mongo db name out of the env
        $mongo_url = parse_url(getenv("MONGOHQ_URL"));
        $dbname = str_replace("/", "", $mongo_url["path"]);

        # connect
        $m   = new Mongo(getenv("MONGOHQ_URL"));
        $db  = $m->$dbname;
        $col = $db->videos;
        */
    }
    private function _close()
    {

        //var_dump($col->limit(49)->skip(rand(0, 49))->find());

        // $videos_popular = $col->find()->limit(10)->sort(array('yt$statistics' => array('viewCount' => 1)));  //->skip(rand(-1, $col->count()-1))->getNext();

        /*
          foreach($video as $video) {
            echo "<li>" . $video['title']['$t'] . "</li>";
          }
          */

        $this->m->close();
    }
}