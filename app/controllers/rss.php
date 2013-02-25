<?php
// rss

class rss {

	public function __construct()
	{
		// Set formats
		header('Content-Type: application/rss+xml; charset=UTF-8');
		date_default_timezone_set('UTC');

		# get the mongo db name out of the env
		$this->mongo_url = parse_url(getenv("PARAM3"));
		$this->dbname = str_replace("/", "", $this->mongo_url["path"]);

		# connect
		$this->m   = new Mongo(getenv("PARAM3"));
		$this->db  = $this->m->{$this->dbname};
	}
    private function view($view)
    {
        extract($this->data);
        include($_SERVER['DOCUMENT_ROOT']."/app/views/$view.php");
    }

	public function daily()
	{
		$this->col = $this->db->videos;

		$this->data['feed_title'] = date("l") . "s hot video #eatbass";
		$this->data['feed_description'] = date("l") . "s hot video #eatbass";

		$feed_published = strtotime("today");
		$feed_begin = $feed_published-604800;

		//die($feed_update . " akdhjfkjadh " . $feed_published);

		$query = array('date' => array( '$gt' => new MongoDate($feed_begin), '$lt' => new MongoDate($feed_published) ) );

		$this->data['videos'] = $this->col->find($query)->sort(array('ytLikes' => -1))->limit(1); //     skip(rand(-1, $col->count()-1))->getNext();

		//die(var_dump($videos));
		//echo json_encode($video);

		$this->view('rss');

		$this->m->close();
	}

	public function weekly()
	{
		$this->col = $this->db->videos;

		$this->data['feed_title'] = "this weeks hot videos #eatbass";
		$this->data['feed_description'] = "this weeks hot videos #eatbass";

		$feed_published = strtotime("today");
		$feed_begin = $feed_published-604800;

		//die($feed_update . " akdhjfkjadh " . $feed_published);

		$query = array('date' => array( '$gt' => new MongoDate($feed_begin), '$lt' => new MongoDate($feed_published) ) );

		$this->data['videos'] = $this->col->find($query)->sort(array('ytLikes' => -1))->limit(5); //     skip(rand(-1, $col->count()-1))->getNext();

		//die(var_dump($videos));
		//echo json_encode($video);

		$this->view('rss');

		$m->close();
	}

	public function uploads()
	{
		$this->col = $this->db->videos;

		$this->data['feed_title'] = "latest videos #eatbass";
		$this->data['feed_description'] = "latest videos #eatbass";

		//$feed_published = strtotime("today");
		//$feed_begin = $feed_published-604800;

		//die($feed_update . " akdhjfkjadh " . $feed_published);

		$query = array('date' => array( '$gt' => new MongoDate($feed_begin), '$lt' => new MongoDate($feed_published) ) );

		$this->data['videos'] = $this->col->find()->sort(array('date' => -1))->limit(10); //     skip(rand(-1, $col->count()-1))->getNext();

		//die(var_dump($videos));
		//echo json_encode($video);

		$this->view('rss');

		$m->close();
	}
}
