
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
var userCount = 0;
function getCurrentUsers()
{
	$.ajax({
	  url: "/visitor/current/interval/20/count/"+userCount,
	  context: document.body
	}).done(function(data) {
	  $('#current-users').html(data);
	  var userCount = data;
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

var played = getCookie('get_lucky');
if( played!=null && played!="")
{}else{
	$('#get-lucky-audio').trigger('play');
	setCookie('get_lucky',true,2);
}

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
