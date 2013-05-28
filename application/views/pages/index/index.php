        <article role="main" style="background-image: url(/webcam.jpg)">
			<header>
				<h1>G+V</h1>
				<aside class="controls">
					<menu>
						<ul class="float">
							<li><a id="rewind"><i class="icon-step-backward"></i></a></li>
							<li class="active"><a id="play"><i class="icon-play"></i></a></li>
							<li><a id="stop"><i class="icon-stop"></i></a></li>
						</ul>
					</menu>
				</aside>
			</header>
			<footer>
				<aside class="ticker">
					<ul>
						<li><span title="mood" class="mood"><i class="fontelico-emo-wink"></i></span></li>
						<li><span class="tweet"><?= $lastfm ?> <a href="http://www.last.fm/user/goramandvincent/"><i class="icon-lastfm-sign"></i></a></span></li>
						<li><span class="tweet"><?= $twitter;?> <a href="http://www.twitter.com/goramandvincent"><i class="icon-twitter-sign"></i></a></span></li>
						<li><time><i id="image-date" class="icon-time"></i> <?= date('Y-m-d H:i:s', filemtime('webcam.jpg'));?></time></li>
						<li>Built with <a href="">Raspberry PI</a> | <a href="#">Share</a></li>
					</ul>
				</aside>
				<aside class="credit">
					<ul class="float">
						<li><a href="" class="gv">Goram+Vincent</a></li>
						<!-- <li><a href="" class="rp">Raspberry Pi</a></li> -->
					</ul>
				</aside>
			</footer>
		</article>
