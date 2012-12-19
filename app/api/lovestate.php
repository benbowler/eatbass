<?php

// Mongo
# get the mongo db name out of the env
$mongo_url = parse_url(getenv("MONGOHQ_URL"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("MONGOHQ_URL"));
$db  = $m->$dbname;
$col = $db->loves;

//$user = $this->col->findOne(array('_id' => $_GET['username'])); 

//$user = $col->find()->limit(1)->skip(rand(-1, $col->count()-1))->getNext();

//echo $video['slug'];

//$user['loves'][$_GET['video_id']];

//$newdata = array("loves" => array($_GET['video_id']));
//$data = array_merge($user, $newdata);

//$col->insert(array("_id" => $_GET['username']), $user);

$query = array(
	'_id' => $_GET['user'] . $_GET['video'],
);

try {
   $count = $col->count($query);
} catch(MongoCursorException $e) {
	$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
   die(json_encode(array('response' => false, 'error' => $error)));
}

if($count == 1) {
   echo json_encode(array('response' => true));
} else {
	echo json_encode(array('response' => false));
}

$m->close();