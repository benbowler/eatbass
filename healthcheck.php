<?php

# connect
$this->m   = new Mongo(getenv("PARAM3"));
$this->db  = $this->m->{$this->dbname};


$this->col = $this->db->videos;
$video = $this->col->findOne(array('featured' => true));

if($video) {
	echo 'up';
} else {
	echo 'dberror?';
}