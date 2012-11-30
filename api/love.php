<?php

// Mongo
# get the mongo db name out of the env
$mongo_url = parse_url(getenv("MONGOHQ_URL"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("MONGOHQ_URL"));
$db  = $m->$dbname;
$col = $db->videos;

//$video = $col->find()->limit(1)->skip(rand(-1, $col->count()-1))->getNext();

//echo $video['slug'];

var_dump($_GET);

$m->close();