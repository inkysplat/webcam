        <article id="camara-canvas" role="main" style="background-image: url(/stream.php?static=true)">
			<header>
				<h1>G+V</h1>
				<aside class="controls">
					<div class="message">
						<input type="text" placeholder="write new message.." style="display: none;">
						<span></span> <a id="write-message"><i class="icon-pencil"></i></a>
					</div>
				</aside>
			</header>
			<footer>
				<aside class="ticker">
					<ul>
						<li>
							<strong>Current Users:</strong> <span id="current-users"></span>
						</li>
						<li>
							<strong>Office Mood:</strong> <span title="mood" class="mood"><i class="fontelico-emo-wink"></i></span>
						</li>
						<li>
							<strong>Lastfm:</strong> <span id="lastfm" class="tweet"><?= $lastfm ?> <a href="http://www.last.fm/user/goramandvincent/"><i class="icon-lastfm-sign"></i></a></span>
						</li>
						<li>
							<strong>Twitter:</strong> <span id="twitter" class="tweet"><?= $twitter;?> <a href="http://www.twitter.com/goramandvincent"><i class="icon-twitter-sign"></i></a></span>
						</li>
						<?php if(isset($github) && $github):?>
						<li>
							<strong>Github:</strong> <span id="github" class="tweet"><?= $github;?> <a href="http://www.github.com/enable"><i class="icon-github-sign"></i></a></span>
						</li>
						<?php endif;?>
						<li>
							<time><i id="image-date" class="icon-time"></i> <?= date('Y-m-d H:i:s', filemtime('webcam.jpg'));?></time>
						</li>
						<li>
							<div style="float:left">Built with <a href="">Raspberry PI</a></div>
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

		<audio autoplay>
			<source src="/audio/get_lucky_clip.ogg" type="audio/ogg">
		  	<source src="/audio/get_lucky_clip.mp3" type="audio/mpeg">
			Your browser does not support the audio element.
		</audio>
