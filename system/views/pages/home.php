        <div class="container">
            <h1>Goram + Vincent Webcam</h1>
            <img src="/webcam.jpg" alt="Webcam Image" width="640" height="480" title="<?= date('Y-m-d H:i:s',filemtime('webcam.jpg'))?>">
	    
            <blockquote><?= $twitter[0]['text'];?> [<a href="http://www.twitter.com/goramandvincent" title="Twitter">twitter</a>]</blockquote>
            <blockquote><?= $lastfm['recenttracks']['track'][0]['artist']['#text'].' - '.$lastfm['recenttracks']['track'][0]['name']?> [<a href="http://www.last.fm/user/goramandvincent/" title="last.fm">lastfm</a>]</blockquote>
            <a href="/index.php?page=slideshow">View Timelapse</a>
            <!-- <?= $counter;?> -->
        </div>
