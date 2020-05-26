$(function(){
	$('#login_btn').click(function (){
		var data = $("#form1").serialize();
		$.ajax({
			url:'https://september-rain.com/auth/',
			type:"POST",
			data:data,
			dataType:"json",
			timespan:1000
		}).done(function(data,status,jqXHR) {
			var username = $("#username").val();
			var password = $("#password").val();
			var data = {
				"username": username,
				"password": password
			};
			$.cookie("username", username, { expires: 7 , path: '/', domain: 'september-rain.com'});
			$.cookie("password", password, { expires: 7 , path: '/', domain: 'september-rain.com'});
		  userid = $.cookie("username");
		  passwd = $.cookie("password");
		  // alert("username["  + userid +  "] password[" + passwd + "]");
			window.location.href = '/';
		}).fail(function(jqXHR, status, errorThrown ) {
		}).always(function(){
		});
	});
});


