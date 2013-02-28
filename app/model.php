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

            
            return $this->col->find(array('featured' => true))->sort(array('date' => -1))->limit(1)->getNext();

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
    public function user($email, $user = false, $likes)
    {
        if(!$user) {
            return false;
        }

        $this->_connect();
        $this->col = $this->db->users;

        $user['_id'] = $user['id'];
        $user['email'] = ($email['email']) ? $email['email'] : false ;
        $user['subscribed'] = false;
        //$user['fb_opengraph'] = false;

        // @todo: Always add user likes?
        $user['likes'] = $likes;

        $existing_user = $this->col->findOne(array('_id' => $user['_id']));

        if($existing_user) {
            $user = $existing_user;
        } else {
            // @todo: upsert instead?
            $this->col->insert($user);
        }


        /* Delete
        $this->col->remove(array('0._id' => '1025514613'));
        $response = $this->db->lastError();

        die(var_dump($response));
        */
        
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
            $update = array('$set' => array('subscribed' => true));

            $this->col->update(array('_id' => $user['id']), $update);

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
        $mongo_url = parse_url(getenv("PARAM3"));
        $dbname = str_replace("/", "", $mongo_url["path"]);

        # connect
        $this->m   = new Mongo(getenv("PARAM3"));
        $this->db  = $this->m->$dbname;
    }
    private function _close()
    {
        $this->m->close();
    }
}