

// var resize = function(){
// 	$window = $(window);
// 	$('#cctv li').css({
// 		height: $window.height(),
// 		width: $window.width()
// 	});
// }

// resize();

// $(window).resize(resize);

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

function getCurrentUsers()
{
	$.ajax({
	  url: "/visitor/current/interval/20",
	  context: document.body
	}).done(function(data) {
	  $('#current-users').html(data);
	});
	setTimeout(function(){getCurrentUsers()},'10000');
}

function getApiData()
{
	$.getJSON('/api/ajax', function(data) {
		$('#lastfm').html(data.lastfm);
		$('#twitter').html(data.twitter);
	});
	setTimeout(function(){getApiData()},'20000');
}

function apiTicker(){
	$('.ticker ul li:first').slideUp( function () { 
		$(this).appendTo($('.ticker ul')).slideDown(); 
	});
}
var messageTimestamp = 0;
function getMessage(){
	$.getJSON('/visitor/getMessage/timestamp/'+messageTimestamp,
		function(data){
			$('.message span').html(data.msg);
			messageTimestamp = data.timestamp;
			$('#get-lucky-audio').play();
		});
	setTimeout(function(){getMessage()},'2000');
}

if(localClient)
{
	var streamUrl = 'http://192.168.0.250:8443/?action=stream';
}else{
	var streamUrl = '/stream.php';
}
$('#camara-canvas').css({'background-image':"url("+streamUrl+")"});
$('.ticker').fadeIn();

$.ajax({
  url: "/visitor/add",
  context: document.body
}).done(function(){getCurrentUsers()});

getApiData();
getMessage();

setInterval(function(){ 
	apiTicker () 
}, 5000);

$('.message .icon-pencil').click(function(){
	$('.message span').hide();
	$('.message input').show();
	$('.message input').keypress(function (e) {
	  if (e.which == 13) {
	    var val = $('.message input').val();
	    $.ajax({
	    	url: '/visitor/postMessage/message/'+encodeURI(val)
	    }).done(function(data){
	    	$('.message input').hide();
	    	$('.message span').show();
	    });
	  }
	});
});




///////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

var env = {
	twitter: 'http://webcam.gandvclients.co.uk/api/fetch/service/twitter/?callback=?',
	lastfm: 'http://webcam.gandvclients.co.uk/api/fetch/service/lastfm/?callback=?'
}

///////////////

var ps = (function(){
	var stack = [];
	return {
		subscribe: function(data){
			stack.push(data);
		},
		publish: function(data){
			for(var i = 0; i < stack.length; i++){
				var obj = stack[i];	
				if(data.ev == obj.ev){
					obj.inst[obj.callback](data);
				}
			}
		},
		unsubscribe: function(data){
			var tmp;
			for(var i = 0; i < stack.length; i++){
				if(!data == stack[i].inst){
					tmp.push(stack[i]);
				} else {
					console.log('unsubscribe');
					console.log(stack[i]);
				}
			}
			stack = tmp;
		}
	}
})();

///////////////

var preloader = function(data){
	var stack = 0;
	var imgs = [];
	$.each(data.playQueue, function(){
		stack++;
		var img = new Image();
		img.src = this.src;
		img.addEventListener('load', function(){
			if(!--stack > 0){
				data.callback(imgs);
			}
		}, false);
		imgs.push(img);
	});
}




var playQueue = function(){}
playQueue.prototype.queue = [];
playQueue.prototype.rx = function(){
	console.log('get playQueue');	
	$.getJSON('/camera/interval/interval/days/length/24', function(data){
		new preloader({
			images: data.reverse()
		})
	});
}

new playQueue;



// var cctv = function(pq){
// 	this.pq = pq;
// 	this.show();	
// }
// cctv.prototype.show = function(){
// 	// this.unsubscribe();
// 	var that = this;
// 	$.getJSON('/playQueue.php', function(data){
// 		new preloader({
// 			playQueue: data.reverse(),	
// 			callback: function(imgs){
// 				var display = function(){
// 					var img = imgs.pop();
// 					$('<li />').appendTo('#cctv ul').css({
// 						backgroundImage: 'url(' + img.src + ')'
// 					}).fadeIn('slow');
// 					new ticker({
// 						slctr: '.time .text',
// 						str: img.src.substring(58, (img.src.length-4))
// 					});				
// 					setTimeout(function(){
// 						if(imgs.length > 0){
// 							display();
// 						} else {
// 							setTimeout(function(){
// 								that.show();
// 								console.log('again');
// 							}, 2800);
// 						}
// 					}, 2800)
// 				}
// 				display();
// 			}
// 		});
// 	});
// }

// new cctv(new playQueue);

// ///////////////

// var ticker = function(data){
	
// 	var $el = $(data.slctr);

// 	var states = [{
// 		state: 'rwd',
// 		chars: $el.html().split('')
// 	},{
// 		state: 'ff',
// 		chars: data.str.split('').reverse()
// 	}];

// 	var type = function(o){
// 		setTimeout(function(){
// 			var html = $el.html();
// 			switch(o.state){
// 				case 'ff':
// 					html += o.chars.pop();
// 				break;
// 				case 'rwd':
// 					o.chars.pop();
// 					html =  o.chars.join('');
// 				break;
// 			} 
// 			$el.html(html);
// 			if(o.chars.length > 0){
// 				type(o);
// 			} else {
// 				if(states[++i]){
// 					type(states[i]);
// 				} else {
// 					if(data.callback){
// 						data.callback();
// 					}
// 				}
// 			}
// 		}, 30);
// 	}

// 	var i;
// 	type(states[i = 0]);

// }



// //////////////////

// var tweet = function(){
// 	// ps.subscribe({ 
// 	// 	inst: this, 
// 	// 	ev: 'timeOut', 
// 	// 	callback: 'TX'
// 	// });
// }
// tweet.prototype.TX = function(){
// 	console.log('tx called');
// 	$.getJSON(env.twitter).done(function(data){
// 		console.log(data);
// 	});
// };

// new tweet;

// ///////////////


// var timer = (function(){

// 	var go = true;

// 	function timeOut(){
// 		ps.publish({
// 				ev: 'timeOut',
// 				time: Date.now()
// 		});
// 	}

// 	timeOut();

// 	setInterval(function(){
// 		if(go) timeOut();
// 	}, 60000);

// 	return {
// 		pause: function(){
// 			go = false;
// 		},
// 		play: function(){
// 			go = true;
// 		}
// 	}  

// })();


// var index = 0;
// var preload = new Array();
// var num_of_images = images.length;
// var orginal_date = $('#image-date').html();
// var stop_play = false;

// for(var i=0; i<num_of_images;i++)
// {
// 	preload[i] = new Image();
// 	preload[i].src = images[i];
// }	

// function nextImage(index)
// {
// 	if(index <= num_of_images)
// 	{
// 		if(images[index] && !stop_play)
// 		{
// 			$('#image-date').html(index+" of "+num_of_images);
// 			$('article').css({'background-image': 'url('+images[index]+')'});
// 			index++;
// 			setTimeout(function(){nextImage(index)},190);
// 		}
// 	}
// }

// $('#rewind').click(function(e){
// 	nextImage(index);
// });

// $('#stop').click(function(e){
// 	stop_play = true;
// });


