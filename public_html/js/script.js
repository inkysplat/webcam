
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

// GET CURRENT USERS ON PAGE - LONG POLL
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

//GET LATEST TWEETS/LASTFM
function getApiData()
{
	$.getJSON('/social/messages', function(data) {
		$('#lastfm .tweet a').html(data.lastfm.msg);
		$('#lastfm img').attr('src',data.lastfm.img);
		$('#twitter .tweet a').html(data.twitter.msg);
		$('#blog .tweet a').html(data.blog.msg);
		$('#blog .tweet a').attr('href',data.blog.url);
		$('#instagram img').attr('src',data.instagram.msg);
		$('#instagram .tweet a').html(data.instagram.caption);
	});
	setTimeout(function(){getApiData()},'20000');
}

//////////// NOT USED (WAS USED FOR USER COMMENT/MESSAGE - AJN 18/06/13)
/**var messageTimestamp = 0;
function getMessage(){
	$.getJSON('/visitor/getMessage/timestamp/'+messageTimestamp,
		function(data){
			$('.visitor-message span').html(data.msg);
			messageTimestamp = data.timestamp;
			$('#get-lucky-audio').trigger('play');
		});
	setTimeout(function(){getMessage()},'8000');
}***/

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


if(localClient)
{
	var streamUrl = 'http://192.168.0.250:8443/?action=stream';
}else{
	if(staticStream)
	{
		var streamUrl = '/stream.php?static=true';
	}else{
		var streamUrl = '/stream.php';
	}
}
$('#camara-canvas').css({'background-image':"url("+streamUrl+")"});
$('.ticker').fadeIn();

$.ajax({
  url: "/visitor/add",
  context: document.body
}).done(function(){getCurrentUsers();});

getApiData();
//getMessage();NOT USED

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
tickers[0] = 'counter';
tickers[1] = 'time';
tickers[2] = 'twitter';
tickers[3] = 'blog';
tickers[4] = 'lastfm';
tickers[5] = 'blank'
tickers[6] = 'instagram';
tickers[7] = 'made';
tickers[8] = 'blank'

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
$('.buzz-message').click(function(){
  $('.icon-bullhorn').css({'color':'red'});
  $('#police-siren-audio').trigger('play');
  $.ajax({url: '/visitor/playAudio'}).done(
  function(data){
    console.log(data);
    $('.icon-bullhorn').css({'color':'white'});
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

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$(document).ready(function() {
	$(".fancybox").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});