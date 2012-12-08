<?php
header('Content-Type: application/rss+xml; charset=ISO-8859-1');
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
	<title>#eatbass top track today</title>
	<description>#eatbass top track today</description>
	<link>http://www.domain.com/link.htm</link>
	<lastBuildDate><?php echo date("D, d M Y H:i:s T"); ?></lastBuildDate>
	<pubDate><?php echo date("D, d M Y H:i:s T", 0); ?></pubDate>

	<?php foreach ($videos as $video) { ?>
	<item>
		<title><?php echo $video['title']['$t']; ?> #eatbass</title>
		<description>

			<img src="<?php echo $video['media$group']['media$thumbnail'][1]['url']; ?>" />

			<h1><?php echo $video['title']['$t']; ?> #eatbass bass music tv</h1>

			<p><?php echo $video['media$group']['media$description']['$t']; ?></p>
			</description>
		<link><?php echo $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></link>
		<guid isPermaLink="true"><?php echo $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></guid>
		<pubDate><?php echo date("D, d M Y H:i:s T", $video['date']->sec); ?></pubDate>
	</item>
	<?php } ?>


	</channel>
</rss>