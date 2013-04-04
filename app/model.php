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
    public function user($email, $user = false, $likes, $extendedaccesstoken)
    {
        if(!$user) {
            return false;
        }

        $this->_connect();
        $this->col = $this->db->users;

        $user['_id'] = $user['id'];
        $user['email'] = ($email['email']) ? $email['email'] : false ;
        $user['subscribed'] = false;
        $user['extendedaccesstoken'] = $extendedaccesstoken;
        //$user['fb_opengraph'] = false;

        // Store likes and array of likes
        $user['likes'] = $likes;

        $this->store_likes($user['_id'], $likes);

        /*

        try {
            $this->col->update(array('_id' => $user['_id']), $user, array("upsert" => true));

            $response = $this->db->lastError();
            /*
            if($response['updatedExisting'] === true) {
                echo "$this->count Updated {$channel->title->{'$t'}}<br />";
            } else {
                echo "$this->count Added {$channel->title->{'$t'}}<br />";
            }
            die(var_dump($response));
        } catch(MongoCursorException $e) {
          $this->col->update(array('_id' => $channel->_id), $channel);
          echo "$this->count Error {$video->title->{'$t'}} $e<br />";
        }
        */

        $existing_user = $this->col->findOne(array('_id' => $user['_id']));

        if($existing_user) {
            $user = array_merge($user, $existing_user);
        }

        $this->col->insert($user);

        //$user = $this->col->findOne(array('_id' => $user['_id']));

        return $user;

        $this->_close();
    }

    private function store_likes($user_id, $likes)
    {

        foreach ($likes['data'] as $like) {
            if($like['category'] == "Musician/band" || $like['category'] == "Record label") {

                $this->col = $this->db->likes;
                $this->col->update(array('_id' => $like['id']), $like, array("upsert" => true));

                $this->col = $this->db->connections;
                $this->col->update(array('_id' => $user_id.$like['id']), array('user' => $user_id, 'like' => $like['id']), array("upsert" => true));
            }
        }

        $user['likesnamearray'] = implode(',', $user['likesnamearray']);
        $user['likesidarray'] = implode(',', $user['likesidarray']);
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