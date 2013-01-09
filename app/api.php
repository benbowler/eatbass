<?php
// Shedular to get channel subscriptions

class api {

	public function __construct()
	{
		# get the mongo db name out of the env
		$this->mongo_url = parse_url(getenv("MONGOHQ_URL"));
		$this->dbname = str_replace("/", "", $this->mongo_url["path"]);

		# connect
		$this->m   = new Mongo(getenv("MONGOHQ_URL"));
		$this->db  = $this->m->{$this->dbname};
	}

	public function love()
	{
		$this->col = $this->db->loves;

		$insert = array(
			'_id' => $_POST['user'] . $_POST['video'],
			'video' => $_POST['video'],
			'user' => $_POST['user']
		);

		try {
		   $this->col->insert($insert, true);
		   echo json_encode(array('response' => true));
		} catch(MongoCursorException $e) {
			$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		   echo json_encode(array('response' => false, 'error' => $error));
		}

		$this->m->close();
	}

	public function lovestate()
	{
		$this->col = $this->db->loves;

		$query = array(
			'_id' => $_POST['user'] . $_POST['video'],
		);

		try {
		   $count = $this->col->count($query);
		} catch(MongoCursorException $e) {
			$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		   die(json_encode(array('response' => false, 'error' => $error)));
		}

		if($count == 1) {
		   echo json_encode(array('response' => true));
		} else {
			echo json_encode(array('response' => false));
		}

		$this->m->close();
	}

	public function unlove()
	{
		$this->col = $this->db->loves;

		$insert = array(
			'_id' => $_POST['user'] . $_POST['video'],
		/*	'video' => $_POST['video_id'],
			'user' => $_POST['username']*/
		);

		try {
		   $this->col->remove($insert, true);
		   echo json_encode(array('response' => true));
		} catch(MongoCursorException $e) {
			//$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		   echo json_encode(array('response' => false, 'error' => $e));
		}

		$this->m->close();
	}

	public function next()
	{
		$this->col = $this->db->videos;

		$video = $this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		echo $video['slug'];

		$this->m->close();
	}

	public function points()
	{
		$this->col = $this->db->points;

		$method = $_POST['method'];

		switch ($_POST['method']) {
		    case 'play':
		        $points = 1;
		        break;
		    case 'love':
		        $points = 10;
		        break;
		    case 'share':
		        $points = 50;
		        break;
		    case 'return':
		        $points = 100;
		        $method = 'return'.date('dmY');
		        break;
		    default:
		    	die(json_encode(array('response' => false)));
		}

		$insert = array(
			'_id' => $method . $_POST['user'] . $_POST['video'],
			'method' => $method,
			'points' => $points,
			'date' =>  new MongoDate()
		);

		try {
		    $this->col->insert($insert, true);
		    $this->_give_points($_POST['user'], $points);
		    echo json_encode(array('response' => true));
		} catch(MongoCursorException $e) {
		    $error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		    echo json_encode(array('response' => false, 'error' => $error));
		}

		$this->m->close();
	}

	private function _give_points($user_id, $points)
	{
		$this->col = $this->db->users;

		$user = $this->col->findOne(array('_id' => $user_id)); //$this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		if($user['points']) {
			$total_points = $user['points'] + $points;
		} else {
			$total_points = $points;
		}

		$update = array('points' => $total_points);
		
		try {
		   $this->col->update(array('_id' => $user_id), array('$set' => $update));
		   return true;
		} catch(MongoCursorException $e) {
		   return false;
		}
	}

	public function userpoints()
	{
		$this->col = $this->db->users;

		$user = $this->col->findOne(array('_id' => $_POST['user'])); //$this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		if(!$user['points']) {
			$user['points'] = 0;
		}

		echo $user['points'];

		$this->m->close();
	}

	public function video()
	{
		$this->col = $this->db->videos;

		$video = $this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		echo json_encode($video);

		$this->m->close();
	}

	public function profile()
	{
		die('your loved videos are being saved and will show up here sooooon ;)');
		$this->col = $this->db->loves;

		$videos = $this->col->find(array('user' => $_POST['user'])); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		foreach ($videos as $video) {
			var_dump($video);
			//$this->col_videos = $this->db->videos;

			//var_dump($this->col_videos->find(array('_id' => $video->video)));
		}
		//echo json_encode($video);

		$this->m->close();
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
		//$this->col->insert($visit);
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
				  $video->updated = new MongoDate(date('U'));
				  //$video->date = ISODate(date('U', strtotime($video->published->{'$t'}));

				  include_once('modules/lib_autlink/lib_autolink.php');
				  $video->html_description = nl2br(autolink(($video->{'media$group'}->{'media$description'}->{'$t'})));

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
