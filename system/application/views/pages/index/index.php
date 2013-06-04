        <article role="main" style="background-image: url(/stream.php)">
			<header>
				<h1>G+V</h1>
			</header>
			<footer>
				<aside class="ticker">
					<ul>
						<li><span title="mood" class="mood"><i class="fontelico-emo-wink"></i></span></li>
						<li><span class="tweet"><?= $lastfm ?> <a href="http://www.last.fm/user/goramandvincent/"><i class="icon-lastfm-sign"></i></a></span></li>
						<li><span class="tweet"><?= $twitter;?> <a href="http://www.twitter.com/goramandvincent"><i class="icon-twitter-sign"></i></a></span></li>
						<?php if(isset($github) && $github):?>
						<li><span class="tweet"><?= $github;?> <a href="http://www.github.com/enable"><i class="icon-github-sign"></i></a></span></li>
						<?php endif;?>
						<li><time><i id="image-date" class="icon-time"></i> <?= date('Y-m-d H:i:s', filemtime('webcam.jpg'));?></time></li>
						<li>
							<div style="float:left">Built with <a href="">Raspberry PI</a> | </div>
						<!-- AddThis Button BEGIN -->
						<div class="addthis_toolbox addthis_default_style" style="float: left; width:auto;min-width: 200px;">
							<a class="addthis_button_preferred_1"></a>
							<a class="addthis_button_preferred_2"></a>
							<a class="addthis_button_preferred_3"></a>
							<a class="addthis_button_preferred_4"></a>
							<a class="addthis_button_compact"></a>
							<a class="addthis_counter addthis_bubble_style"></a>
						</div>
						</li>
					</ul>
				</aside>
				<aside class="credit">
					<ul class="float">
						<li><a href="" class="gv">Goram+Vincent</a></li>
					</ul>
				</aside>
			</footer>
		</article>
