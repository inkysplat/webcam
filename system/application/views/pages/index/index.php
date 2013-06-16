        <article id="camara-canvas" role="main" style="background-image: url(/stream.php?static=true)">
			<header>
				<h1>G+V</h1>
				<aside class="controls">
					<div class="visitor-message">
						<input type="text" placeholder="write new message.." style="display: none;">
						<span></span> <a id="write-message"><i class="icon-pencil"></i></a>
					</div>
				</aside>
			</header>

			<footer>
				<aside class="ticker">
						<div class="message" id="counter">
							<strong>Current Users:</strong> <span id="current-users"></span>
						</div>
						<div class="message" id="twitter">
							<strong>Twitter:</strong> <span class="tweet"><a href="http://www.twitter.com/goramandvincent"><?= $twitter;?> <i class="icon-twitter-sign"></i></a></span>
						</div>
						<div class="message" id="blog">
							<strong>Blog:</strong> <span class="tweet"><a href="<?= $blog['url'];?>" title="<?= $blog['title'];?>"><?= $blog['title']?> <i class="icon-rss-sign"></i></a></span>
						</div>
						<div class="message" id="github" style="<?= $github == ''?'display:none;':'';?>">
							<strong>Github:</strong> <span class="tweet"><a href="http://www.github.com/enable"><?= $github;?> <i class="icon-github-sign"></i></a></span>
						</div>
						<div class="message" id="time">
							<time><i id="image-date" class="icon-time"></i> <?= date('Y-m-d H:i:s', filemtime('webcam.jpg'));?></time>
						</div>
						<div class="message" id="made">
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
						</div>

						<div class="message" id="blank"></div>

						<div class="image-message" id="lastfm">
							<?php if($lastfm['url'] && $lastfm['url'] != ''):?>
								<img src="<?= $lastfm['url'];?>" alt="<?= $lastfm['caption'];?>">
							<?php endif; ?>
							<span class="tweet"><a href="http://www.last.fm/user/goramandvincent/"><?= $lastfm['caption'] ?> <i class="icon-lastfm-sign"></i></a></span>
						</div>

						<div class="image-message" id="instagram">
							<img src="<?= $instagram['url']?>" alt="<?= $instagram['caption'];?>">
							<span class="tweet"><a href="http://www.instagram.com/goramandvincent/"><?= $instagram['caption'];?> <i class="icon-instagram-sign"></i></a></span>
						</div>
				</aside>
				<aside class="credit">
					<ul class="float">
						<li><a href="" class="gv">Goram+Vincent</a></li>
					</ul>
				</aside>
			</footer>
		</article>

		<audio id="get-lucky-audio">
			<source src="/audio/get_lucky_clip.ogg" type="audio/ogg">
		  	<source src="/audio/get_lucky_clip.mp3" type="audio/mpeg">
			Your browser does not support the audio element.
		</audio>
