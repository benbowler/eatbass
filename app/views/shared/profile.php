<section id="profile">
	<!--<a href="#" class="play_all">play all</a>-->
	<a href="#" class="exit">exit</a>
	<div class="profile">
		<img id="picture" src="https://graph.facebook.com/<?php echo $basic['username']; ?>/picture?type=square" /><?php echo $basic['first_name']; ?> <em id="points"><?php echo $user['points']; ?></em>
	</div>
	<div id="profile_videos">
		Loading...
	</div>
</section>