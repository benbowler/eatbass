<?php
/*
var options = {
   host: 'gdata.youtube.com',
   port: 80,
   path: '/feeds/api/users/' + config.youtube_channel + '/subscriptions?v=2&alt=json-in-script&format=5&start-index=1&max-results=50'
};

Shedular to get channel subscriptions

*/



/* mongodb */

try {
// connect to MongoHQ assuming your MONGOHQ_URL environment
// variable contains the connection string
$connection_url = getenv("MONGOHQ_URL");

// create the mongo connection object
$m = new Mongo($connection_url);

// extract the DB name from the connection path
$url = parse_url($connection_url);
$db_name = preg_replace('/\/(.*)/', '$1', $url['path']);

// use the database we connected to
$db = $m->selectDB($db_name);

echo "<h2>Collections</h2>";
echo "<ul>";

// print out list of collections
$cursor = $db->listCollections();
$collection_name = "";
foreach( $cursor as $doc ) {
  echo "<li>" .  $doc->getName() . "</li>";
  $collection_name = $doc->getName();
}
echo "</ul>";

// print out last collection
if ( $collection_name != "" ) {
  $collection = $db->selectCollection($collection_name);
  echo "<h2>Documents in ${collection_name}</h2>";

  // only print out the first 5 docs
  $cursor = $collection->find();
  $cursor->limit(5);
  echo $cursor->count() . ' document(s) found. <br/>';
  foreach( $cursor as $doc ) {
    echo "<pre>";
    var_dump($doc);
    echo "</pre>";
  }
}

// disconnect from server
$m->close();
} catch ( MongoConnectionException $e ) {
die('Error connecting to MongoDB server');
} catch ( MongoException $e ) {
die('Mongo Error: ' . $e->getMessage());
} catch ( Exception $e ) {
die('Error: ' . $e->getMessage());
}








// https://www.googleapis.com/youtube/v3/subscriptions
$userId = "eatbassnow";



// Cycle through subscriptions and videos
$subscriptions = _apiRequest("https://gdata.youtube.com/feeds/api/users/$userId/subscriptions?v=2&alt=json");

foreach($subscriptions->feed->entry as $subscription) {
    //var_dump($subscription);

    $channelId = $subscription->{'yt$username'}->{'$t'};

    $videos = _apiRequest("http://gdata.youtube.com/feeds/api/users/$channelId/uploads?v=2&alt=json");

    //http://gdata.youtube.com/feeds/api/users/nba/uploads/-/sports/playoffs?v=2&alt=json

    foreach($videos->feed->entry as $video) {
        die(var_dump($video));
        //echo $video->title->{'$t'} . "<br />";
    }
}


function _apiRequest($url) {
    return json_decode(file_get_contents($url));
}
