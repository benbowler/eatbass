	<script type="text/javascript" src="assets/javascript/app.js?cache=<?php echo filemtime('assets/javascript/app.js') ?>"></script>
	<script type="text/javascript">

	$(function () {

		// Setup video and user objects
		$.site = { 
			title : "<?php echo $site_title; ?>",
			description : "<?php echo $site_description; ?>",
		};
		$.user = { 
			_id : "<?php echo $user['_id']; ?>",
			logged_in : <?php echo ($user) ? 'true' : 'false' ; ?>,
			subscribed : <?php echo ($user['subscribed']) ? 'true' : 'false' ; ?>,
			opengraph : <?php echo ($user['opengraph'] == '') ? "'first'" : $user['opengraph'] ; ?>
		};
		$.video = { 
			_id : "<?php echo $video['_id']; ?>",
			slug : "<?php echo $video['slug']; ?>",
			title : <?php echo json_encode($video['title']['$t']); ?>,
	        author : "<?php echo $video['author'][0]['name']['$t']; ?>",
            description : <?php echo json_encode($video['media$group']['media$description']['$t']); ?>,
            html_description : <?php echo json_encode($video['html_description']); ?>,
			picture : "<?php echo str_replace('http', 'https', $video['media$group']['media$thumbnail'][1]['url']); ?>",
			ytID : "<?php echo $video['media$group']['yt$videoid']['$t']; ?>"
		};

		// @todo: Check if this is needed?
		$.alertify = alertify;

		// Start app!!
		app();

	});

	</script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-36632734-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	 ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	</script>
	</body>
</html>