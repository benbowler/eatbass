<?php // index

require_once($_SERVER['DOCUMENT_ROOT'].'/app/model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/controller.php');
//require_once('app/config.php');

ini_set('display_errors', 0);

// routes
$segments = $_GET['slug'];
$route = 'index';
//unset($segments[1]);

$controller = new controller();
$controller->$route($segments);