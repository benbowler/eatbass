<?php
header('Content-Type: application/rss+xml; charset=UTF-8');
date_default_timezone_set('UTC');

// Mongo
# get the mongo db name out of the env
$mongo_url = parse_url(getenv("MONGOHQ_URL"));
$dbname = str_replace("/", "", $mongo_url["path"]);

# connect
$m   = new Mongo(getenv("MONGOHQ_URL"));
$db  = $m->$dbname;
$col = $db->videos;

$query = array('date' => array( '$gte' => new MongoDate(time()-86400) ) );

$videos = $col->find($query)->sort(array('ytLikes' => -1))->limit(1); //     skip(rand(-1, $col->count()-1))->getNext();

//die(var_dump($videos));
//echo json_encode($video);

$m->close();
?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0">

	<channel>
	<title><?php echo date("r"); ?>s hot video #eatbass</em></title>
	<description>#eatbass top track today</description>
	<link><?php echo 'http://' . $_SERVER['SERVER_NAME'] . '/api/rss/daily.php'; ?></link>
	<lastBuildDate><?php echo date("r"); ?></lastBuildDate>
	<pubDate><?php echo date("r"); ?></pubDate>

	<?php foreach ($videos as $video) { ?>
	<item>
		<title><?php echo $video['title']['$t']; ?> #eatbass</title>
		<description><![CDATA[<?php echo $video['media$group']['media$description']['$t']; ?>]]></description>
		<media:content url="<?php echo $video['media$group']['media$thumbnail'][3]['url']; ?>"
			xmlns:media="http://search.yahoo.com/mrss/"
			medium="image"
			type="image/jpg"
			height="<?php echo $video['media$group']['media$thumbnail'][3]['height']; ?>"
			width="<?php echo $video['media$group']['media$thumbnail'][3]['width']; ?>" />
			<?php /* <media:title type="html"><?php echo $video['title']['$t']; ?></media:title> */ ?>
		<link><?php echo 'http://' . $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></link>
		<guid isPermaLink="true"><?php echo 'http://' . $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></guid>
		<pubDate><?php echo date("r", $video['date']->sec); ?></pubDate>
	</item>
	<?php } ?>


	</channel>
</rss>