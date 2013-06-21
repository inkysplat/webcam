
	var index = 0;
	var preload = new Array();
	var num_of_images = images.length;
	var stop = false;

	for(var i=0; i<num_of_images;i++)
	{
		preload[i] = new Image();
		preload[i].src = images[i];
	}

	function nextImage(index)
	{
		if(index <= num_of_images)
		{
			if(images[index] && !stop)
			{
				console.log('image '+images[index]);
				$('#image-date').html(index+" of "+num_of_images);
				$('article').css({'background-image': 'url('+images[index]+')'});
				index++;
				setTimeout(function(){nextImage(index)},190);
			}
		}
	}

	if(num_of_images == preload.length){
		window.load = nextImage(0);
	}

	$('#stop-btn').click(function(){
		if(!stop)
		{
			stop = true;
			$(this).removeClass('icon-stop');
			$(this).addClass('icon-play');
		}else{
			stop = false;
			$(this).removeClass('icon-play');
			$(this).addClass('icon-stop');
			nextImage(index);
		}
	})
