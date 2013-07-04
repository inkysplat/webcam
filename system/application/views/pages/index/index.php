        <article id="camara-canvas" role="main" style="background-image: url(/stream.php?static=true)">
			<header>
				<h1>G+V</h1>
				<aside class="controls">
					<?php /**<div class="visitor-message">
						<input type="text" placeholder="write new message.." style="display: none;">
						<span></span> <a id="write-message"><i class="icon-pencil"></i></a>
					</div>**/ ?>
		          <div class="buzz-message">
		          	<span>HONK!!</span> <i class="icon-bullhorn icon-2x"></i>
		          </div>
		          <div class="clearfix"></div>
		          <div class="image-list">
		          	<a href="/camera/gallery/date/<?= date('Y-m-d');?>" class="fancybox" data-fancybox-type="iframe">
		          		<i class="icon-th icon-2x"></i>
		          	</a>
		          </div>
				</aside>
			</header>

			<footer>
				<aside class="ticker">
						<div class="message" id="counter">
							<strong>Current Users:</strong> <span id="current-users"></span> <i class="icon-user"></i>
						</div>
						<div class="message" id="twitter">
							<strong>Twitter:</strong> <span class="tweet"><a href="http://www.twitter.com/goramandvincent"><?= $twitter;?></a> <i class="icon-twitter-sign"></i></span>
						</div>
						<div class="message" id="blog">
							<strong>Blog:</strong> <span class="tweet"><a href="<?= $blog['url'];?>" title="<?= $blog['title'];?>"><?= $blog['title']?></a> <i class="icon-rss-sign"></i></span>
						</div>
						<div class="message" id="github" style="<?= $github == ''?'display:none;':'';?>">
							<strong>Github:</strong> <span class="tweet"><a href="http://www.github.com/enable"><?= $github;?></a> <i class="icon-github-sign"></i></span>
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
							<span class="tweet"><a href="http://www.last.fm/user/goramandvincent/"><?= $lastfm['caption'] ?></a> <i class="icon-lastfm-sign"></i></span>
						</div>

						<div class="image-message" id="instagram">
							<img src="<?= $instagram['url']?>" alt="<?= $instagram['caption'];?>">
							<span class="tweet"><a href="http://www.instagram.com/goramandvincent/"><?= $instagram['caption'];?></a> <i class="icon-instagram-sign"></i></span>
						</div>
				</aside>
				<aside class="credit">
					<ul class="float">
						<li><a href="" class="gv">Goram+Vincent</a></li>
					</ul>
				</aside>
			</footer>
		</article>

		<?php foreach($sounds as $id=>$sound):?>
			<audio id="<?= $id;?>" preload="auto" autobuffer>
				<?php if(isset($sound['mp3'])):?>
				<source src="<?= $sound['mp3'];?>" type="audio/mpeg">
				<?php endif;?>
				<?php if(isset($sound['ogg'])):?>
				<source src="<?= $sound['ogg'];?>" type="audio/ogg">
				<?php endif;?>
				Your browser does not support the audio element.
			</audio>
		<?php endforeach;?>
