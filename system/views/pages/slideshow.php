		<div class="container" id="page">
			<h1>Goram + Vincent Webcam</h1>
			<div>
				<img src="/webcam.jpg" id="webcam-time-lapse"><br/>
				<span id="image-counter">0</span>
				<br/><br/>
			</div>
		</div>
		


		<script src="/image_list.php<?= isset($_GET['date'])?'?date='.$_GET['date']:'';?>"></script>
		<script type="text/javascript">			
			var index = 0;
			var preload = new Array();
			var eImage = document.getElementById("webcam-time-lapse");
			var eCounter = document.getElementById("image-counter");
			var num_of_images = images.length;
			eCounter.innerHTML = '0 out of '+num_of_images;
			for(var i=0; i<num_of_images;i++)
			{
				preload[i] = new Image();
				preload[i].src = images[i];
			}
			
			function nextImage(index)
			{
				if(index <= num_of_images)
				{
					eImage.src = preload[index].src;
					eCounter.innerHTML = index+' out of '+num_of_images;
					index++;
					setTimeout(function(){nextImage(index)},500);
				}
			}
			nextImage(index);
		</script>