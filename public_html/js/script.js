
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
var userTimestamp = 0;
function getCurrentUsers()
{
	$.ajax({
	  url: "/visitor/current/timestamp/"+userTimestamp,
	  context: document.body
	}).done(function(data) {
	  $('#current-users').html(data.count);
	  userTimestamp = data.timestamp;
	});
	setTimeout(function(){getCurrentUsers()},'8000');
}

function getApiData()
{
	$.getJSON('/social/messages', function(data) {
		$('#lastfm .tweet').html(data.lastfm);
		$('#twitter .tweet').html(data.twitter);
	});
	setTimeout(function(){getApiData()},'20000');
}

var messageTimestamp = 0;
function getMessage(){
	$.getJSON('/visitor/getMessage/timestamp/'+messageTimestamp,
		function(data){
			$('.visitor-message span').html(data.msg);
			messageTimestamp = data.timestamp;
			$('#get-lucky-audio').trigger('play');
		});
	setTimeout(function(){getMessage()},'8000');
}

function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1){
	  	c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1){
	  	c_value = null;
 	}else{
	  	c_start = c_value.indexOf("=", c_start) + 1;
	  	var c_end = c_value.indexOf(";", c_start);
	  	if (c_end == -1){
			c_end = c_value.length;
	   	}
		c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


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
}).done(function(){getCurrentUsers();});

getApiData();
//getMessage();

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

////////////////// RESET ALL MESSAGES OFF SCREEN //////////////////
var $messages = $('.message');
	$messages.each(function(){
		$(this).css({left: -$(this).outerWidth()});
	})
////////////////// LIST OF MESSAGES TO CYCLE THROUGH //////////////////
var tickers = new Array();
tickers[0] = 'time';
tickers[1] = 'twitter';
tickers[2] = 'blog';
tickers[3] = 'lastfm';
tickers[4] = 'blank'
tickers[5] = 'instagram';
tickers[6] = 'made';
tickers[7] = 'blank'

var image_tickers = new Array();
image_tickers[0] = 'instagram';
image_tickers[1] = 'lastfm';

////////////////// SWITCH A ROO THE MESSAGES //////////////////
function apiTicker(tick){

	if(currentTick > -1){
		console.log('turning off '+tickers[currentTick]);
		var $prev = $('.ticker #'+tickers[currentTick]);
		if(image_tickers.indexOf(tickers[currentTick]) != -1){
			$prev.fadeOut();
		}else{
			$prev.animate({
		      left: parseInt($prev.css('left'),10) == 0 ?
		        -$prev.outerWidth() :
		        0
		    },500,function() {
		    	$prev.hide();
		    });
		}
	}

	if(image_tickers.indexOf(tickers[tick]) != -1){
		$('.ticker #'+tickers[tick]).fadeIn();
		return true;
	}

	console.log('turning on '+tickers[tick]);
	var $lefty = $('.ticker #'+tickers[tick]);
	$lefty.show();
	$lefty.animate({
      left: parseInt($lefty.css('left'),10) == 0 ?
        -$lefty.outerWidth() :
        0
    });
}

////////////////// SOME BORING QUEUING STUFF /////////////////
var nextTick = 0;
var currentTick = -1;
setInterval(function(){
	//set new one
	apiTicker(nextTick);
	currentTick = nextTick;
	//dont scroll off the end
	nextTick++;
	if(tickers[nextTick])
	{}else{
		nextTick = 0;
	}
}, 5000);
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

var played = getCookie('get_lucky');
if( played!=null && played!="")
{}else{
	$('#get-lucky-audio').trigger('play');
	setCookie('get_lucky',true,2);
}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$('.buzz-message').click(function(){
  $('.buzz-message span').css("color",'red');
  $.ajax({url: '/visitor/playAudio', async: false}).done(
  function(data){
    console.log(data);
    $('.buzz-message span').css("color",'white');
  });
});

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$('.visitor-message .icon-pencil').click(function(){
	$('.visitor-message span').hide();
	$('.visitor-message input').show();
	$('.visitor-message input').keypress(function (e) {
	  if (e.which == 13) {
	    var val = $('.visitor-message input').val();
	    $.ajax({
	    	url: '/visitor/postMessage/message/'+encodeURI(val)
	    }).done(function(data){
	    	$('.visitor-message input').hide();
	    	$('.visitor-message span').show();
	    });
	  }
	});
});
