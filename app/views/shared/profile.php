<section id="profile">
	<div>
		<!--<a href="#" class="play_all">play all</a>-->
		<div class="profile">
			<img id="picture" src="https://graph.facebook.com/<?php echo $basic['username']; ?>/picture?type=square" />
			<?php echo $basic['first_name']; ?>
		</div>
		<div id="acheievements">
			<h3><span class="points"><?php echo $user['points']; ?></span> points</h3>
		</div>
		<div id="settings">
			<h4>social sharing</h4>
			<div id="toggleopengraph" class="switch switch-mini" data-on="primary" data-off="info">
				    <input type="checkbox" <?php echo ($user['opengraph'] == "true") ? 'checked' : '' ; ?> />
				</div>
			<!--
			<p>videos you watch will automatically be shared with your friends.</p>
				
			<h3>email frequency</h3><p>set how often you want #eatbass updates by email.</p> \
            <select id="email_frequency"> \
                <option>Never</option> \
                <option>Monthly</option> \
                <option selected>Weekly</option> \
                <option>Daily</option> \
            </select>
        	-->
		</div>
		<div id="profile_videos">

			<?php /* foreach ($profile as $video): ?>
				<div class="video">
					<img src="<?php echo $video['media$group']['media$thumbnail'][0]['url']; ?>" />
					<a href="/<?php echo $video['slug']; ?>"><h4><?php echo $video['title']['$t']; ?></h4></a>
					<a href="http://youtube.com/user/<?php echo $video['author'][0]['name']['$t']; ?>" class="channel" target="_blank"><h4><?php echo $video['author'][0]['name']['$t']; ?></h4></a>
				</div>
			<?php endforeach; */ ?>
		</div>
	</div>
</section>