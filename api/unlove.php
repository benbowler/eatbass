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
//$this->data = array_merge($user, $newdata);

//$col->insert(array("_id" => $_GET['username']), $user);

$insert = array(
	'_id' => $_GET['username'] . $_GET['video_id'],
/*	'video' => $_GET['video_id'],
	'user' => $_GET['username']*/
);

try {
   $col->remove($insert, true);
   echo json_encode(array('response' => true));
} catch(MongoCursorException $e) {
	//$error = (strstr($e, 'duplicate')) ? 'duplicate' : 'other' ;
   echo json_encode(array('response' => false, 'error' => $e));
}

$m->close();