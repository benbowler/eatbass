<?php
// Shedular to get channel subscriptions

class api {

	public function __construct()
	{
		# get the mongo db name out of the env
		$this->mongo_url = parse_url(getenv("PARAM3"));
		$this->dbname = str_replace("/", "", $this->mongo_url["path"]);

		$this->_connect();

		$this->channelId = "eatbassnow";
	}

	private function _connect()
	{
		# connect
		$this->m   = new Mongo(getenv("PARAM3"));
		$this->db  = $this->m->{$this->dbname};
	}

	public function testupdate()
	{

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
		   $this->col->insert($insert);
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
		   $this->col->remove($insert);
		   echo json_encode(array('response' => true));
		} catch(MongoCursorException $e) {
			//$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		   echo json_encode(array('response' => false, 'error' => $e));
		}

		$this->m->close();
	}

	public function comment()
	{
		if(!$_POST['video'] || !$_POST['video'] || !$_POST['comment']) {
			die(json_encode(array('response' => false, 'error' => 'nodata')));
		}

		$this->col = $this->db->comments;

		$insert = array(
			'_id' => $_POST['video'] . date('-d-m-Y-') . $_POST['user'],
			'video' => $_POST['video'],
			'user' => $_POST['user'],
			'comment' => $_POST['comment'],
			'date' =>  new MongoDate()
		);

		try {
		   $this->col->insert($insert);
		   echo json_encode(array('response' => true));
		} catch(MongoCursorException $e) {
			$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
		   echo json_encode(array('response' => false, 'error' => $error));
		}

		$this->m->close();
	}

	public function featured()
	{
		$this->col = $this->db->videos;

		//$video = $this->col->sort(array('date' => 1))->find(array('featured' => true));
		$video = $this->col->find(array('featured' => true))->sort(array('date' => -1))->limit(1)->getNext();

		echo json_encode($video);

		$this->m->close();
	}

	public function video()
	{
		$this->col = $this->db->videos;

		if($_POST['video']) {
			$video = $this->col->findOne(array('video' => $_POST['video']));
		} else {
			$startDate = new MongoDate(strtotime(date("Y-m-d", mktime()) . " - 182 day"));
			//die(var_dump($startDate));
			$count = $this->col->find(array('date' => array( '$gt' => $startDate ) ))->count();

			$video = $this->col->find( array('date' => array( '$gt' => $startDate ) ) )->sort(array('date' => -1))->skip(rand(-1, $count-1))->getNext();
		}

		echo json_encode($video);

		$this->m->close();
	}

	public function videos()
	{
		$this->col = $this->db->videos;

		$videos = $this->col->find()->limit(10)->sort(array('random' => 1))->skip($_POST['skip']);

		echo json_encode(iterator_to_array($videos));

		$this->m->close();
	}
/*
	public function next()
	{
		$this->col = $this->db->videos;

		$startDate = new MongoDate(date("Y-m-d", mktime()) . " - 365 day");

		$video = $this->col->find(array('publised.$t' >= $startDate))->sort(array('random' => 1))->limit(1)->getNext();
		die(var_dump($video));

		echo $video['slug'];

		$this->m->close();
	}
	*/

	public function points()
	{
		if(!$_POST['method'] || !$_POST['user'] || !$_POST['video']) {
			die(json_encode(array('response' => false, 'error' => 'Missing data')));
		}

		$this->col = $this->db->points;

		$method = $_POST['method'];
		$user = $_POST['user'];
		$video = $_POST['video'];

		$id = $method . $user . $video;

		switch ($method) {
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
				$id = $method . date('-d-m-Y-') . $user;
		        $points = 20;
		        break;
		    case 'invite':
				$id = $method . date('-d-m-Y-') . $user;
		        $points = 50;
		        break;
		    default:
		    	die(json_encode(array('response' => false, 'error' => 'Invalid method')));
		}

		$insert = array(
			'_id' => $id,
			'method' => $method,
			'points' => $points,
			'user' => $user,
			'date' =>  new MongoDate()
		);

	    $this->col->insert($insert);

	    //$c->insert(array("_id" => 1));
		//$c->insert(array("_id" => 1));

        $response = $this->db->lastError();

        if(is_null($response['err'])) {
		    echo json_encode(array('response' => true));

		    // Give points only if
			$this->_give_points($_POST['user'], $points);
		} else {
		    $error = (strstr($response['err'], 'duplicate')) ? 'duplicate' : 'other' ;
		    echo json_encode(array('response' => false, 'error' => $error));
		}

		$this->m->close();
	}
	/* @tod: calc points on a backend process an update in js only */
	private function _give_points($user_id, $points)
	{
		$this->col = $this->db->users;

		$user = $this->col->findOne(array('_id' => $user_id));

		if(is_numeric($user['points'])) {
			$total_points = $user['points'] + $points;
		} else {
			$total_points = $points;
		}

		$update = array('$set' => array('points' => "$total_points"));

		$this->col->update(array('_id' => $user_id), $update); //, array('upsert' => true));

		$response = $this->db->lastError();
	}
	/* Super unsafe without auth! */
    public function user()
    {
		$this->col = $this->db->users;

		$user = $this->col->findOne(array('_id' => $_POST['user']));

        echo json_encode($user);
        
		$this->m->close();
    } 

	public function userpoints()
	{
		if(!$_POST['user']) {
			die(json_encode(array('response' => 'unspecified user')));
		}

		$this->col = $this->db->users;

		$user = $this->col->findOne(array('_id' => $_POST['user'])); //$this->col->find()->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		if(!$user['points']) {
			$user['points'] = 0;
		}

		echo $user['points'];

		$this->m->close();
	}

	// @todo: Profile data response..

	public function profile()
	{
		//die('your loved videos are being saved and will show up here sooooon ;)');
		$this->col = $this->db->loves;

		$videos = $this->col->find(array('user' => $_POST['user'])); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();

		$return = array();

		foreach ($videos as $video) {
			//var_dump($video['video']);
			array_push($return, $video['video']); 
			//echo json_encode($video);
			//$this->col_videos = $this->db->videos;

			//var_dump($this->col_videos->find(array('_id' => $video->video)));
		}
		echo json_encode($return);

		$this->m->close();
	}

	public function profilehtml()
	{
		if(!$_POST['user']) {
			die('false'); //json_encode(array('response' => 'unspecified user')));
		}

		// die(var_dump($_POST['user']));
		$this->col = $this->db->loves;

		$videos = $this->col->find(array('user' => $_POST['user']))->limit(50);
		//die(var_dump($query));

		//$videos = array_reverse((array)); //->limit(1)->skip(rand(-1, $this->col->count()-1))->getNext();
		//die(var_dump($videos));

		foreach ($videos as $video) {

			echo $this->_video_view($video['video']);
		}

		$this->m->close();
	}

	private function _video_view($video_id)
	{
		//var_dump($video_id);

		$this->col_video = $this->db->videos;
		$video = $this->col_video->findOne(array('_id' => $video_id));

		//var_dump($video);
		if($video) {

			$data = array(
						'slug' => $video['slug'],
						'title' => $video['title']['$t'],
				        'author' => $video['author'][0]['name']['$t'],
			            'description' => substr($video['media$group']['media$description']['$t'], 0, 50),
			            'html_description' => $video['html_description'],
						'picture' => str_replace('http', 'https', $video['media$group']['media$thumbnail'][0]['url']),
						'ytID' => $video['media$group']['yt$videoid']['$t']
						);

			$return = "
			<div>
				<img src='{$data['picture']}' />
				<h4>
					<a href='/{$data['slug']}'>{$data['title']}</a>
					<a href='#'>{$data['author']}</a>
				</h4>
				<p>{$data['description']}</p>
			</div>
			";

			return $return;

		}

		return false;

	}
	public function setopengraph()
	{
		if(!$_POST['user'] || !$_POST['opengraph']) {
			die(json_encode(array('response' => 'nopostdata')));
		}

		$this->col = $this->db->users;
		
		$update = array('$set' => array('opengraph' => $_POST['opengraph']));

		$this->col->update(array('_id' => $_POST['user']), $update); //, array('upsert' => true));

		$response = $this->db->lastError();

		if($response['err'] == null) {
			echo json_encode(array('response' => true));
		} else {
			echo json_encode(array('response' => false));
		}

		$this->m->close();
	}

	public function deleteopengraph()
	{
		if(!$_POST['accesstoken']) {
			die(json_encode(array('response' => 'noaccesstoken')));
		}

		if(!$_POST['actionid']) {
			die(json_encode(array('response' => 'noactionid')));
		}

        $data ='access_token='.$_POST['accesstoken'];

        $ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/" . $_POST['actionid']);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$reply=curl_exec($ch);
		curl_close($ch);

		echo json_encode(array('response' => $reply));
	}

	public function setemailfrequency()
	{
		if(!$_POST['user'] || !$_POST['emailfrequency']) {
			die(json_encode(array('response' => 'nopostdata')));
		}

		$user = $_POST['user'];

		//die(json_encode($user));

        require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/mailchimp-api-class/examples/inc/config.inc.php'; //contains apikey
        require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/mailchimp-api-class/examples/inc/MCAPI.class.php';

        $api = new MCAPI($apikey);

		/*
		$retval = $api->lists();
		 
		if ($api->errorCode){
			echo "Unable to load lists()!";
			echo "\n\tCode=".$api->errorCode;
			echo "\n\tMsg=".$api->errorMessage."\n";
		} else {
			echo "Lists that matched:".$retval['total']."\n";
			echo "Lists returned:".sizeof($retval['data'])."\n";
			foreach ($retval['data'] as $list){
				echo "Id = ".$list['id']." - ".$list['name']."\n";
				echo "Web_id = ".$list['web_id']."\n";
				echo "\tSub = ".$list['stats']['member_count'];
				echo "\tUnsub=".$list['stats']['unsubscribe_count'];
				echo "\tCleaned=".$list['stats']['cleaned_count']."\n";
			}
		}
		*/

        if($_POST['emailfrequency'] == 'Never') {
        	// Do unsubscripe..

        	$retval = $api->listUnsubscribe('0f213b0888', $user['email']);

			if ($api->errorCode){
			    echo "Unable to load listUnsubscribe()!\n";
				echo "\tCode=".$api->errorCode."\n";
				echo "\tMsg=".$api->errorMessage."\n";
			} else {
			    echo "Returned: ".$retval."\n";
			}

        } else {

	        $merge_vars = array('FNAME'=>$user['first_name'], 'LNAME'=>$user['last_name'], 'FREQ'=>$_POST['emailfrequency']
	                         /* 'GROUPINGS'=>array(
	                                //array('name'=>'Music:', 'groups'=>implode(',', $music)),
	                                //array('id'=>22, 'groups'=>'Trains'),
	                                ) */
	                            );

	        // By default this sends a confirmation email - you will not see new members
	        // until the link contained in it is clicked!
	        $retval = $api->listSubscribe('0f213b0888', $user['email'], $merge_vars, $email_type='html', $double_optin=false, $update_existing=true, $replace_interests=true, $send_welcome=true);
	      	
	        if ($api->errorCode) { 
				die(json_encode(array('response' => false, 'error' => "Mailchimp: ".$api->errorMessage)));
			}
	        /*
	        if ($api->errorCode){
	            echo "Unable to load listSubscribe()!\n";
	            echo "\tCode=".$api->errorCode."\n";
	            echo "\tMsg=".$api->errorMessage."\n";
	        } else {
	            echo "Subscribed - look for the confirmation email!\n";
	        }
			*/
	    }

	    // Update our records..
		$this->col = $this->db->users;
		
		$update = array('$set' => array('emailfrequency' => $_POST['emailfrequency']));

		$this->col->update(array('_id' => $user['_id']), $update); //, array('upsert' => true));

		$response = $this->db->lastError();

		//die(json_encode($response));

		if($response['err'] == null) {
			echo json_encode(array('response' => true));
		} else {
			echo json_encode(array('response' => false));
		}

		$this->m->close();
	}




	public function indexchannels() 
	{
		// Config
		$userId = "eatbassnow";

		$slugs = explode(':', $_SERVER['REQUEST_URI']);

		$startIndex = $slugs[2];
		$totalResults = $slugs[3];

		echo "Featured videos<br />";

		//echo file_get_contents('https://gdata.youtube.com/feeds/api/users/eatbassnow/playlists?v=2');
		$featured = $this->_api_request('https://gdata.youtube.com/feeds/api/playlists/PL8euV8agVxcfI3I-h9thKkkVbzXky-GvS?v=2&alt=json');

		$this->_store_featured($featured);

		echo 'Index Channels<br />';

		$this->total_channels = 0;
		$this->total = 0;

		$subscriptions = $this->_api_request("https://gdata.youtube.com/feeds/api/users/{$this->channelId}/subscriptions?v=2&alt=json&start-index=$startIndex&max-results=$totalResults");

		//$totalResults = $subscriptions->feed->{'openSearch$totalResults'}->{'$t'};

		foreach($subscriptions->feed->entry as $subscription) {

			// Store channel
			// $this->_store_channel($subscription);
			// Store Videos
			//$this->_store_videos($videos);

			$channelId = $subscription->{'yt$username'}->{'$t'};
			echo "Storing $channelId <br />";


				$startIndex_v = 1;
                $totalResults_v = 2;

                $this->count_videos = 0;

                while($startIndex_v < $totalResults_v) {
                    echo $startIndex_v . '<' . $totalResults_v;

                    $videos = $this->_api_request("http://gdata.youtube.com/feeds/api/users/$channelId/uploads?v=2&alt=json&start-index=$startIndex_v&max-results=50");

                    $startIndex_v = $startIndex_v + 50;
                    $totalResults_v = $videos->feed->{'openSearch$totalResults'}->{'$t'};

                    //////////////////////////// REENABLE VIDEOS UPDATING
                    $this->_store_videos($videos);
                }

			$this->total_channels++;
		}

		echo "Imported {$this->total_channels} channels.<br />";

		// Close connection
		$this->m->close();
	}

	function _store_channel($channel)
	{
		//die(file_get_contents("http://www.youtube.com/$channel_id"));
		//replace space between words with +

		//$channel_id = $subscription->{'yt$username'}->{'$t'};
		//$query = "cat+video"; 
		//$start = 0;
		/*
		this url will give you json response with 4 results each time.
		u have to change the $start like 0, 4, 8,...
		use json_decode() and get it in array
		*/
		//echo $channel_id;
		//$url = 'https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q='.$channel_id.'+site:facebook.com&start=0';

		//die(var_dump(json_decode(file_get_contents($url))));
		/*

		$channel_id = $subscription->{'yt$username'}->{'$t'};

		$html = file_get_contents("http://www.youtube.com/user/{$channel_id}");

		$dom = new DomDocument();
		$dom->loadHTML($html);
		$finder = new DomXPath($dom);
		
		$nodes = $finder->query("//*[contains(@class, 'yt-c3-profile-custom-url')]");

		foreach ($nodes as $node) {
			die($node);
			//echo $node->saveXML();
		}
		die();

		// Select collection
		$this->col = $this->db->channels;
		*/
		
		//die(var_dump($channel_id));

		//$channel['_id'] = $channel;


		// Get by class name: yt-c3-profile-custom-url from DOM

		//die(var_dump($this->_api_request("https://www.googleapis.com/youtube/v3/channels?part=snippet&id={$channel_id}&key=AIzaSyDuMSI5Hv5hRdpsDUEmN8q1U2RlOy23RB4")));

		die(var_dump($channel));

		$channel->_id = $channel->id->{'$t'};
		$channel->random = rand(0,1000);
		$channel->slug = $this->_to_ascii($channel->title->{'$t'});

		$channel->date = new MongoDate(strtotime($channel->published->{'$t'}));
		$channel->updated = new MongoDate(date('U'));
		//$channel->date = ISODate(date('U', strtotime($channel->published->{'$t'}));

		include_once($_SERVER['DOCUMENT_ROOT'].'/app/modules/lib_autolink/lib_autolink.php');
		$channel->html_description = nl2br(autolink(($channel->{'media$group'}->{'media$description'}->{'$t'})));

		$channel->ytFavorites = $channel->{'yt$statistics'}->favoriteCount;
		$channel->ytViews = $channel->{'yt$statistics'}->viewCount;
		$channel->ytLikes = $channel->{'yt$rating'}->numLikes;
		$channel->ytDislikes = $channel->{'yt$rating'}->numDislikes;

		try {
		  $this->col->update(array('_id' => $channel->_id), $channel, array("upsert" => true));
			$response = $this->db->lastError();

			if($response['updatedExisting'] === true) {
		  		echo "$this->count Updated {$channel->title->{'$t'}}<br />";
			} else {
		  		echo "$this->count Added {$channel->title->{'$t'}}<br />";
			}
		} catch(MongoCursorException $e) {
		  $this->col->update(array('_id' => $channel->_id), $channel);
		  echo "$this->count Error {$video->title->{'$t'}} $e<br />";
		}

	}

	function _store_videos($videos)
	{
		// Select collection
		$this->col = $this->db->videos;
		
		foreach($videos->feed->entry as $video) {
			if($video->{'media$group'}->{'yt$duration'}->seconds <= 600) {

				//$this->_store_tags($video->{'media$group'}->{"media$keywords"}->{"$t" });

				$video->_id = $video->id->{'$t'};
				$video->random = rand(0,1000);
				$video->slug = $this->_to_ascii($video->title->{'$t'});

				$video->date = new MongoDate(strtotime($video->published->{'$t'}));
				$video->updated = new MongoDate(date('U'));
				//$video->date = ISODate(date('U', strtotime($video->published->{'$t'}));

				include_once($_SERVER['DOCUMENT_ROOT'].'/app/modules/lib_autolink/lib_autolink.php');
				$video->html_description = nl2br(autolink(($video->{'media$group'}->{'media$description'}->{'$t'})));

				$video->ytFavorites = $video->{'yt$statistics'}->favoriteCount;
				$video->ytViews = $video->{'yt$statistics'}->viewCount;
				$video->ytLikes = $video->{'yt$rating'}->numLikes;
				$video->ytDislikes = $video->{'yt$rating'}->numDislikes;

				try {
				  $this->col->update(array('_id' => $video->_id), $video, array("upsert" => true));
					$response = $this->db->lastError();

					if($response['updatedExisting'] === true) {
				  		echo "$this->count Updated {$video->title->{'$t'}}<br />";
					} else {
				  		echo "$this->count Added {$video->title->{'$t'}}<br />";
					}
				} catch(MongoCursorException $e) {
				  $this->col->update(array('_id' => $video->_id), $video);
				  echo "$this->count Error {$video->title->{'$t'}} $e<br />";
				}
			} else {
			  echo "$this->count Skipped {$video->title->{'$t'}} (Too long)<br />";
			}
			$this->count++;
			$this->total++;
		}
	}

	function _store_tags($tags)
	{
		$tags = explode(',', $tags);
		foreach ($tags as $tag) {
			# pull wikipedia??
		}
	}

	function _store_featured($videos)
	{

		// Select collection
		$this->col = $this->db->videos;

		$this->col->remove(array('featured' => true)); // , array("justOne" => true));
			
			foreach($videos->feed->entry as $video) {
				//if($video->{'media$group'}->{'yt$duration'}->seconds <= 600) {

					$video->_id = $video->id->{'$t'};
					$video->slug = $this->_to_ascii($video->title->{'$t'});

					$video->date = new MongoDate(strtotime($video->published->{'$t'}));
					$video->updated = new MongoDate(date('U'));
					//$video->date = ISODate(date('U', strtotime($video->published->{'$t'}));

					include_once($_SERVER['DOCUMENT_ROOT'].'/app/modules/lib_autolink/lib_autolink.php');
					$video->html_description = nl2br(autolink(($video->{'media$group'}->{'media$description'}->{'$t'})));

					$video->ytFavorites = $video->{'yt$statistics'}->favoriteCount;
					$video->ytViews = $video->{'yt$statistics'}->viewCount;
					$video->ytLikes = $video->{'yt$rating'}->numLikes;
					$video->ytDislikes = $video->{'yt$rating'}->numDislikes;

					$video->featured = true;

					try {
					  $this->col->insert($video);
					  echo "$this->count Feature Added {$video->title->{'$t'}}<br />";
					} catch(MongoCursorException $e) {
					  $this->col->update(array('_id' => $video->_id), $video);
					  echo "$this->count Feature Updated {$video->title->{'$t'}}<br />";
					}
				//} else {
				//  echo "$this->count Skipped {$video->title->{'$t'}} (Too long)<br />";
				//}
				$this->count++;
				$this->total++;
			}
	}

	public function index()
	{
		// Index likes
		$this->col = $this->db->users;
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
