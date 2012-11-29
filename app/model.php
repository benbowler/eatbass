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

          $video = $this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

          // @todo: redirect if logged in... else ... show video in the backgound (Muted) with login button over
          header("Location: http://" . $_SERVER['SERVER_NAME'] . "/" . $video['slug']); /* Redirect browser */
            exit;

        }

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