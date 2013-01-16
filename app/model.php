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
    public function get_videos_by_channel($channel, $limit = 10)
    {
        $this->_connect();
        $this->col = $this->db->videos;

        return $this->col->find()->sort(array($by => 1))->limit($limit);

        $this->_close();
    }
    public function get_top_videos_by($by = 'ytPlays', $asc = 1, $limit = 10, $time = false)
    {
        $this->_connect();
        $this->col = $this->db->videos;

        if($time) {

        }

        return $this->col->find()->sort(array($by => 1))->limit($limit);

        $this->_close();
    }
    public function user($email, $user = false)
    {
        $this->_connect();
        $this->col = $this->db->users;

        if(!$user) {
            return false;
        }


        $user['_id'] = $user['id'];
        $user['email'] = ($email['email']) ? $email['email'] : false ;
        $user['subscribed'] = false;

        try {
            // Insert if unique
            $this->col->insert((object) $user, true);
        } catch(MongoCursorException $e) {
            // Else get current user
            $user = $this->col->findOne(array('_id' => $user['id']));
        }

        if(!$user['subscribed'] && isset($user['email'])) {

            //die(var_dump($user));
            require_once 'modules/mailchimp-api-class/examples/inc/config.inc.php'; //contains apikey
            require_once 'modules/mailchimp-api-class/examples/inc/MCAPI.class.php';

            $api = new MCAPI($apikey);

            $merge_vars = array('FNAME'=>$user['first_name'], 'LNAME'=>$user['last_name'], 'FREQ'=>'Weekly' 
                             /* 'GROUPINGS'=>array(
                                    //array('name'=>'Music:', 'groups'=>implode(',', $music)),
                                    //array('id'=>22, 'groups'=>'Trains'),
                                    ) */
                                );

            // By default this sends a confirmation email - you will not see new members
            // until the link contained in it is clicked!
            $retval = $api->listSubscribe('0f213b0888', $user['email'], $merge_vars, $email_type='html', $double_optin=false, $update_existing=true, $replace_interests=true, $send_welcome=true);
            /*
            if ($api->errorCode){
                echo "Unable to load listSubscribe()!\n";
                echo "\tCode=".$api->errorCode."\n";
                echo "\tMsg=".$api->errorMessage."\n";
            } else {
                echo "Subscribed - look for the confirmation email!\n";
            }
            */
            $user['subscribed'] = true;

            $this->col->update(array('_id' => $user['id']), $user);

            // pass first visit value
            $user['first_visit'] = true;

        }

        return $user;

        $this->_close();
    }

    public function get_profile($user, $limit = 5)
    {
        $this->col = $this->db->loves;

        $videos = $this->col->find(array('user' => $user))->limit($limit); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

        $return = array();

        foreach ($videos as $key => $video) {
            $this->col_videos = $this->db->videos;

            $return[$key] = $this->col_videos->findOne(array('_id' => $video['video']));
        }
        return $return;

        $this->m->close();
    }

    public function get_users($limit = 10)
    { 
        $this->_connect();
        $this->col = $this->db->users;

        $users = $this->col->find()->limit($limit); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

        return $users;

        $this->m->close();
    }
    public function get_top_users($limit = 10)
    { 
        $this->_connect();
        $this->col = $this->db->users;

        $users = $this->col->find()->sort(array('points' => -1))->limit($limit); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

        return $users;

        $this->m->close();
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
    }
    private function _close()
    {
        $this->m->close();
    }
}