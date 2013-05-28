
var index = 0;
var preload = new Array();
var num_of_images = images.length;
var orginal_date = $('#image-date').html();
var stop_play = false;

for(var i=0; i<num_of_images;i++)
{
	preload[i] = new Image();
	preload[i].src = images[i];
}	

function nextImage(index)
{
	if(index <= num_of_images)
	{
		if(images[index] && !stop_play)
		{
			$('#image-date').html(index+" of "+num_of_images);
			$('article').css({'background-image': 'url('+images[index]+')'});
			index++;
			setTimeout(function(){nextImage(index)},190);
		}

		if(!stop_play)
		{
			stop_play = true;
			return true;
		}
	}
}

$('#rewind').click(function(e){
	nextImage(index);
});

$('#stop').click(function(e){
	stop_play = true;
});

$('#play').click(function(e){
	if(preload.length == num_of_images)
	{
		$('#image-date').html(orginal_date);
		$('article').css({'background-image':'url(/webcam.jpg)'});
	}
});