<div class="gallery-container">
	<?php if(isset($gallery)):?>
		<ul id="gallery-dates">
			<?php
				$d = new DateTime();
				for($i=1;$i<8;$i++): 
					$d->modify('-1 day');?>
				<li><a href="/camera/gallery/date/<?= $d->format('Y-m-d');?>"><?= $d->format('D');?></a></li>
			<?php endfor;?>
		</ul>
		<div class="play-control">
			<a href="/camera/slideshow/date/<?=$date;?>"><i class="icon-play"></i></a>
		</div>
		<div class="clearfix"></div>
		<hr>
		<ul id="gallery">
			<?php if(is_array($images) && count($images) > 0):?>
				<?php foreach($images as $image):?>
				<li>
					<a href="/camera/gallery/view/<?= $image['image_id'];?>" alt="<?= $image['datetime'];?>">
						<img src="<?=$image['url'];?>" width="160" height="140">
					</a>
				</li>
				<?php endforeach;?>
			<?php else:?>
				<li><h2>No Images Found</h2></li>
			<?php endif;?>
		</ul>
	<?php endif;?>

	<?php if(isset($view)):?>
		<a href="/camera/gallery" target="_self" id="back-btn">back</a>
		<hr>
		<img src="<?= $image['url'];?>">
	<?php endif;?>
</div>