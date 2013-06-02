<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>

	var api = '/proxy.php?uri=http://webcam.gandvclients.co.uk/camera/list/date/2013-05-29/format/jsonp/limit/20/full/true';

	var playQueue = function(){
		this.get();
	}
	playQueue.prototype.queue = [];
	playQueue.prototype.get = function(){

		$.getJSON(api, function(data){
			console.log(data);
		});
		
	}

	new playQueue;

</script>