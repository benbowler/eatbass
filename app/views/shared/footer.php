				<div class="navbar navbar-inverse navbar-fixed-bottom">
	              <div class="navbar-inner footer">
	                <div class="container">

		                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                    <span class="icon-bar"></span>
		                </a>
		                <?php /* ?>
	                    <form class="navbar-form pull-left" action="">
	                    	<div class="input-prepend input-append">
		                    	<span class="add-on"><i class="icon-bubbles"></i></span>
		                        <input type="text" class="span3" placeholder="what do you think?">
		                        <button class="btn" type="button">share</button>
		                    </div>
	                    </form>
	                    <?phpm */ ?>

	                    <ul class="nav pull-left hidden-phone">
			                	<li><a id="video_status"></a></li>
	                    </ul>

	                    <ul class="nav pull-right">

	                    	<li><a class="video_title info thin-right"><?php echo $video['title']['$t']; ?></a></li>
	                    	<li><a class="info thin-left"><i class="icon-info"></i></a></li>
	                    	<li><a class="video_channel"><?php echo $video['author'][0]['name']['$t']; ?></a></li>

	                    	<li class="divider-vertical hidden-phone"></li>

							<!--<li><a href="https://twitter.com/eatbassnow" target="_blank"><i class="icon-google-plus"></i><span class="hide">google plus</span></a></li>-->
							<li class="hidden-phone"><a class="thin-right" href="https://facebook.com/eatbassnow" target="_blank"><i class="icon-facebook"></i><span class="hide">facebook</span></a></li>
							<li class="hidden-phone"><a class="thin" href="https://twitter.com/eatbassnow" target="_blank"><i class="icon-twitter"></i><span class="hide">twitter</span></a></li>
							<li class="hidden-phone"><a class="thin-left" href="https://untitled.eatbass.com" target="_blank"><i class="icon-tumblr"></i><span class="hide">tumblr</span></a></li>
							

							<li class="divider-vertical hidden-phone"></li>
	                    	<li class="dropdown hidden-phone">

		                        <a class="dropdown-toggle thin-left" data-toggle="dropdown">
		                        	<i class="icon-help"></i>
		                        </a>
		                        <ul class="dropdown-menu">
									<li><a href="/info:privacy-policy" target="_blank">privacy policy</a></li>
									<li><a href="/info:terms-of-service" target="_blank">terms of service</a></li>
									<li class="divider"></li>
									<li><a href="/info:press" target="_blank">press</a></li>
									<li class="divider"></li>
									<li><a href="/info:channels" target="_blank">channels</a></li>
									<li><a href="/info:winning" target="_blank">winning</a></li>
									<li><a href="/info:support" tartget="_blank">support</a></li>
		                        </ul>
	                        </li>
	                    </ul>

	                </div>
	              </div><!-- /navbar-inner -->
	            </div>