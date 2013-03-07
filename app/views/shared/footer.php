				<div class="navbar navbar-inverse">
	              <div class="navbar-inner footer">
	                <div class="container">
		                    <form class="navbar-form pull-left" action="">
		                    	<div class="input-prepend input-append">
			                    	<span class="add-on"><i class="icon-bubbles"></i></span>
			                        <input type="text" class="span3" placeholder="what do you think?">
			                        <button class="btn" type="button">share</button>
			                    </div>
		                    </form>
	                    <ul class="nav pull-right">
			                <?php if (isset($basic)) { ?>
			                	<li><strong id="fb-status"></strong></li>
								<li>
									<a href="#">
										social sharing 
										<div id="toggleopengraph" class="switch switch-mini" data-on="primary" data-off="info">
										    <input type="checkbox" checked />
										</div>
									</a>
									<!--<a href="#" class="toggleopengraph"><?php echo ($user['opengraph']) ? 'turn facebook sharing off' : 'turn facebook sharing on' ; ?></a></li>-->
								</li>
							<?php } ?>
	                    	<li class="divider-vertical"></li>
	                    	<li><a href="#"><i class="icon-info"></i> <span id="video_title"><?php echo $video['title']['$t']; ?></span> </a></li>
	                    </ul>
	                </div>
	              </div><!-- /navbar-inner -->
	            </div>
	<footer>
		<a href="/info:privacy-policy" target="_blank">privacy policy</a> |
		<a href="/info:terms-of-service" target="_blank">terms of service</a>
		<!--<a href="/info:channels" target="_blank">channels</a>-->
		<a href="/info:winning" target="_blank"><i class="icon-trophy"></i><em>winning</em></a>
		<a href="/info:support" tartget="_blank"><i class="icon-sad"></i><em>support</em></a>
		<a href="https://facebook.com/eatbassnow" target="_blank"><i class="icon-facebook"></i><em>facebook</em></a>
		<a href="https://twitter.com/eatbassnow" target="_blank"><i class="icon-twitter"></i><em>twitter</em></a>
	</footer>