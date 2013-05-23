<article role="main" style="background-image: url(http://webcam.gandvclients.co.uk/webcam.jpg)">
</article>
		

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="/image_list.php<?= isset($_GET['date'])?'?date='.$_GET['date']:'';?>"></script>
		<script type="text/javascript">			
			var index = 0;
			var preload = new Array();
			//var eImage = document.getElementById("webcam-time-lapse");
			//var eCounter = document.getElementById("image-counter");
			var num_of_images = images.length;
			//eCounter.innerHTML = '0 out of '+num_of_images;
			for(var i=0; i<num_of_images;i++)
			{
				preload[i] = new Image();
				preload[i].src = images[i];
			}
			
			function nextImage(index)
			{
				if(index <= num_of_images)
				{
					$('article').css({'background-image': 'url('+images[index]+')'});
					//eCounter.innerHTML = index+' out of '+num_of_images;
					index++;
					setTimeout(function(){nextImage(index)},250);
				}
			}
			nextImage(index);
		</script>