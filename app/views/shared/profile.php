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