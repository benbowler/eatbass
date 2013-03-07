				<div class="navbar navbar-inverse">
	              <div class="navbar-inner header">
	                <div class="container">
	                	<!--
	                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                  </a>
	              		-->
				      <a class="brand" href="#"><?php echo $site_title; ?><em> <?php echo $site_description; ?></em></a>
	                  <div class="nav-collapse collapse navbar-responsive-collapse">
	                  	<!--
	                    <ul class="nav">
	                      <li class="active"><a href="#">filters</a></li>
	                      <li class="dropdown">
	                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Filters <b class="caret"></b></a>
	                        <ul class="dropdown-menu">
	                          <li><a href="#">new</a></li>
	                          <li><a href="#">hot</a></li>
	                          <li class="divider"></li>
	                          <li class="nav-header">personal</li>
	                          <li><a href="#">loves</a></li>
	                        </ul>
	                      </li>
	                    </ul>
	                	-->
	                	<!--
	                    <form class="navbar-search pull-left" action="">
	                      <input type="text" class="search-query span2" placeholder="Search">
	                    </form>
	                	-->
	                    <ul class="nav pull-right">
	                    	<!--
							<li><i class="icon-fast-forward icon-white"></i> <a href="#" class="skip">skip</a></li>
							<li><i class="icon-heart icon-white"></i> <a href="#" class="love">love</a><sup>+10</sup></li>
							<li><i class="icon-share icon-white"></i> <a href="#" class="share">share</a><sup>+50</sup></li>
							-->
							<li><a href="#" class="skip"><i class="icon-last"></i></a></li>
							<li><a href="#" class="love"><i class="icon-heart-2"></i></a></li>
	                        <li class="divider-vertical"></li>

						<?php if (isset($basic)) { ?>
							<li><a href="#"><?php echo $user['points']; ?> points</a></li>
							<li class="divider-vertical"></li>
	                        <li class="dropdown">

		                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                        	<img id="picture" src="https://graph.facebook.com/<?php echo $basic['username']; ?>/picture?type=square" />
		                        	<?php echo $basic['first_name']; ?>
		                        	<b class="caret"></b>
		                        </a>
		                        <ul class="dropdown-menu">
		                          <li><a href="#">points</a></li>
		                          <li><a href="#">badges</a></li>
		                          <li class="divider"></li>
		                          <li><a href="#">profile</a></li>
		                        </ul>
	                        </li>
	                    <?php } else { ?>
	                    <?php } ?>
	                    </ul>
	                  </div><!-- /.nav-collapse -->
	                </div>
	              </div><!-- /navbar-inner -->
	            </div>