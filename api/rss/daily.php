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

$description = "

<img src=\"{$video['media$group']['media$thumbnail'][1]['url']}\" />

<h1>{$video['title']['$t']} #eatbass bass music tv</h1>

<p>{$video['media$group']['media$description']['$t']}</p>
";
//echo json_encode($video);

$m->close();
?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0">

	<channel>
	<title>#eatbass top track today</title>
	<description>#eatbass top track today</description>
	<link>http://www.domain.com/link.htm</link>
	<lastBuildDate><?php echo date("r"); ?></lastBuildDate>
	<pubDate><?php echo date("r", 0); ?></pubDate>

	<?php foreach ($videos as $video) { ?>
	<item>
		<title><?php echo $video['title']['$t']; ?> #eatbass</title>
		<description>
			<?php echo utf8_encode(htmlentities($description,ENT_COMPAT,'utf-8')); ?>
			</description>
		<link><?php echo $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></link>
		<guid isPermaLink="true"><?php echo $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></guid>
		<pubDate><?php echo date("r", $video['date']->sec); ?></pubDate>
	</item>
	<?php } ?>


	</channel>
</rss>