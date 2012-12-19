<?php
// Shedular to get channel subscriptions

class api {

	public function __construct()
	{
		# get the mongo db name out of the env
		$mongo_url = parse_url(getenv("MONGOHQ_URL"));
		$dbname = str_replace("/", "", $mongo_url["path"]);

		# connect
		$m   = new Mongo(getenv("MONGOHQ_URL"));
		$this->db  = $m->$dbname;
	}

	public function love()
	{

	}

	public function lovestate()
	{

	}

	public function unlove()
	{

	}

	public function next()
	{

	}

	public function video()
	{

	}

	public function profile()
	{

	}

	public function schedule() 
	{
		// Config
		$userId = "eatbassnow";

		// Select collection
		$this->col = $this->db->videos;

		echo 'Schedule<br />';

		# insert a document
		//$visit = array( "ip" => 'blergy' );
		//$col->insert($visit);
		$this->total = 0;

		// Cycle through subscriptions and videos
		$subscriptions = $this->_api_request("https://gdata.youtube.com/feeds/api/users/$userId/subscriptions?v=2&alt=json");

		foreach($subscriptions->feed->entry as $subscription) {
			//var_dump($subscription);

			$channelId = $subscription->{'yt$username'}->{'$t'};
			echo "Begining $channelId <br />";

			$startIndex = 1;
			$totalResults = 2;

			$this->count = 0;

			while($startIndex < $totalResults) {
				echo $startIndex . '<' . $totalResults;

				$videos = $this->_api_request("http://gdata.youtube.com/feeds/api/users/$channelId/uploads?v=2&alt=json&start-index=$startIndex&max-results=50");

				$startIndex = $startIndex + 50;
				$totalResults = $videos->feed->{'openSearch$totalResults'}->{'$t'};

				$this->_store_videos($videos);
			}
		}

		echo "Total $total Imported";

		// Close connection
		$this->m->close();
	}


	function _store_videos($videos) {
			
			foreach($videos->feed->entry as $video) {
				if($video->{'media$group'}->{'yt$duration'}->seconds <= 600) {

				  $video->_id = $video->id->{'$t'};
				  $video->slug = $this->_to_ascii($video->title->{'$t'});

				  $video->date = new MongoDate(strtotime($video->published->{'$t'}));
				  //$video->date = ISODate(date('U', strtotime($video->published->{'$t'}));

				  $video->ytFavorites = $video->{'yt$statistics'}->favoriteCount;
				  $video->ytViews = $video->{'yt$statistics'}->viewCount;
				  $video->ytLikes = $video->{'yt$rating'}->numLikes;
				  $video->ytDislikes = $video->{'yt$rating'}->numDislikes;

				  try {
					  $this->col->insert($video, true);
					  echo "$this->count Added {$video->title->{'$t'}}<br />";
				  } catch(MongoCursorException $e) {
					  $this->col->update(array('_id' => $video->_id), $video);
					  echo "$this->count Updated {$video->title->{'$t'}}<br />";
				  }
				} else {
				  echo "$this->count Skipped {$video->title->{'$t'}} (Too long)<br />";
				}
				$this->count++;
				$this->total++;
			}
	}
	// Functions
	function _api_request($url) {
		return json_decode(file_get_contents($url));
	}

	//setlocale(LC_ALL, 'en_US.UTF8');
	function _to_ascii($str, $replace=array(), $delimiter='-') {
	  if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	  }

	  $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	  $clean = strtolower(trim($clean, '-'));
	  $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	  return $clean;
	}
}
