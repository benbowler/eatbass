<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0">

	<channel>
	<title><?php echo date("l"); ?>s hot video #eatbass</title>
	<description>#eatbass top track today</description>
	<link><?php echo 'https://' . $_SERVER['SERVER_NAME'] . '/api/rss/daily.php'; ?></link>
	<lastBuildDate><?php echo date("r"); ?></lastBuildDate>
	<pubDate><?php echo date("r"); ?></pubDate>

	<?php foreach ($videos as $video) { ?>
	<item>
		<title><?php echo htmlspecialchars($video['title']['$t']); ?> #eatbass</title>
		<description><![CDATA[<?php echo $video['media$group']['media$description']['$t']; ?>]]></description>
		<media:content url="<?php echo $video['media$group']['media$thumbnail'][3]['url']; ?>"
			xmlns:media="http://search.yahoo.com/mrss/"
			medium="image"
			type="image/jpg"
			height="<?php echo $video['media$group']['media$thumbnail'][3]['height']; ?>"
			width="<?php echo $video['media$group']['media$thumbnail'][3]['width']; ?>" />
			<?php /* <media:title type="html"><?php echo $video['title']['$t']; ?></media:title> */ ?>
		<link><?php echo 'https://' . $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></link>
		<guid isPermaLink="true"><?php echo 'http://' . $_SERVER['SERVER_NAME'] . '/' . $video['slug']; ?></guid>
		<pubDate><?php echo date("r", $video['date']->sec); ?></pubDate>
	</item>
	<?php } ?>


	</channel>
</rss>