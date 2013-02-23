<?php
# get the mongo db name out of the env
$mongo_url = parse_url(getenv("PARAM3"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("PARAM3"));
$db  = $m->{$dbname};


$col = $db->videos;
$video = $col->findOne(array('featured' => true));

if($video) {
	echo 'up';
} else {
	echo 'dberror?';
}