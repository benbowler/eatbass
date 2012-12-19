<?php

// Mongo
# get the mongo db name out of the env
$mongo_url = parse_url(getenv("MONGOHQ_URL"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("MONGOHQ_URL"));
$db  = $m->$dbname;
$col = $db->loves;

$videos = $col->find(array('user' => $_GET['user'])); //->limit(1)->skip(rand(-1, $col->count()-1))->getNext();

foreach ($videos as $video) {
	var_dump($video);
	//$col_videos = $db->videos;

	//var_dump($col_videos->find(array('_id' => $video->video)));
}
//echo json_encode($video);

$m->close();