<?php
// Shedular to get channel subscriptions

// Config
$userId = "eatbassnow";

echo 'Schedule<br />';

# get the mongo db name out of the env
$mongo_url = parse_url(getenv("MONGOHQ_URL"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("MONGOHQ_URL"));
$db  = $m->$dbname;
$col = $db->videos;

# insert a document
//$visit = array( "ip" => 'blergy' );
//$col->insert($visit);


// Cycle through subscriptions and videos
$subscriptions = _api_request("https://gdata.youtube.com/feeds/api/users/$userId/subscriptions?v=2&alt=json&limit=10");

foreach($subscriptions->feed->entry as $subscription) {
    //var_dump($subscription);

    $channelId = $subscription->{'yt$username'}->{'$t'};

    $videos = _api_request("http://gdata.youtube.com/feeds/api/users/$channelId/uploads?v=2&alt=json");

    //http://gdata.youtube.com/feeds/api/users/nba/uploads/-/sports/playoffs?v=2&alt=json

    foreach($videos->feed->entry as $video) {
        //var_dump($video);
        //echo $video->title->{'$t'} . "<br />";

        //die(_mongo_insert('videos', (array) $video));
        //$col->insert($video);

        $video->_id = $video->id->{'$t'};
        $video->slug = _to_ascii($video->title->{'$t'});
        $video->unixdate = date('U', strtotime($video->published->{'$t'}));

        try {
            $col->insert($video, true);
            echo "Added {$video->title->{'$t'}}<br />";
        } catch(MongoCursorException $e) {
            echo "Can't save the same video twice!<br />";
        }
    }
}

# disconnect
$m->close();


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

