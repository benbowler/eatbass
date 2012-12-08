<?php // index

require_once($_SERVER['DOCUMENT_ROOT'].'/app/model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/controller.php');
//require_once('app/config.php');

ini_set('display_errors', 0);

// routes
$slug = $_GET['slug'];

if(strstr($slug, ':')) {
	$explode = explode(':', $slug);
	$route = $explode[0];
	$slug = $explode[1];
} else {
	$route = 'index';
}

$controller = new controller();
$controller->$route($slug);