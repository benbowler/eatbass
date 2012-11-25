<?php
/*
var options = {
   host: 'gdata.youtube.com',
   port: 80,
   path: '/feeds/api/users/' + config.youtube_channel + '/subscriptions?v=2&alt=json-in-script&format=5&start-index=1&max-results=50'
};

Shedular to get channel subscriptions

*/



// Config
$userId = "eatbassnow";

echo 'Schedule';



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

          try {
              $col->insert($video, true);
          } catch(MongoCursorException $e) {
              echo "Can't save the same person twice!\n";
          }
      }
  }




  # print all existing documents
  $data = $col->find();
  foreach($data as $video) {
    echo "<li>" . $video['title']['$t'] . "</li>";
  }

  # disconnect
  $m->close();


/*




*/





// Functions

function _api_request($url) {
    return json_decode(file_get_contents($url));
}

/*


function _mongo_insert($collection, $array)
{
  try {
    $connection_url = getenv("MONGOHQ_URL");

    // create the mongo connection object
    $m = new Mongo($connection_url);
    $db = $m->selectDB('app9314556');

    $collection = new MongoCollection($m, $collection);
    ////

    $m->close();
  } catch ( MongoConnectionException $e ) {
    die('Error connecting to MongoDB server');
  } catch ( MongoException $e ) {
    die('Mongo Error: ' . $e->getMessage());
  } catch ( Exception $e ) {
    die('Error: ' . $e->getMessage());
  }
}

*/

