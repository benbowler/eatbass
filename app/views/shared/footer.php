				<div class="navbar navbar-inverse navbar-fixed-bottom">
	              <div class="navbar-inner footer">
	                <div class="container">

		                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                </a>

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

	                    	<li class="dropdown">

		                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                        	<i class="icon-help"></i>
		                        </a>
		                        <ul class="dropdown-menu">
									<li><a href="/info:privacy-policy" target="_blank">privacy policy</a></li>
									<li><a href="/info:terms-of-service" target="_blank">terms of service</a></li>
									<li class="divider"></li>
									<li><a href="/info:channels" target="_blank">channels</a></li>
									<li><a href="/info:winning" target="_blank"><i class="icon-trophy"></i> winning</a></li>
									<li><a href="/info:support" tartget="_blank"><i class="icon-sad"></i> support</a></li>
									<li><a href="https://facebook.com/eatbassnow" target="_blank"><i class="icon-facebook"></i> facebook</a></li>
									<li><a href="https://twitter.com/eatbassnow" target="_blank"><i class="icon-twitter"></i> twitter</a></li>
		                        </ul>
	                        </li>
	                    </ul>
	                </div>
	              </div><!-- /navbar-inner -->
	            </div>