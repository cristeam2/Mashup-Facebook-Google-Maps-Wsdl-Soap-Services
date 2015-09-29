/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$(function(){

var app_id='1484115481899871';
var scopes = 'email, public_profile, user_friends,user_location,user_hometown';

var btn_login= '<a href="#" id="login" class="btn btn-primary">Iniciar sesion</a>';


var div_session = "<div id='facebook-session'>"+
					  "<strong></strong>"+
					  "<img>"+
					  "<a href='#' id='logout' class='btn btn-danger'>Cerrar sesion</a>"+
					  "</div>";

	window.fbAsyncInit = function() {

	  	FB.init({
	    	appId      : app_id,
	    	status     : true,
	    	cookie     : true, 
	    	xfbml      : true, 
	    	version    : 'v2.1'
	  	});


	  	FB.getLoginStatus(function(response) {
	    	statusChangeCallback(response, function() {});
	  	});
  	};

  	var statusChangeCallback = function(response, callback) {

   		
    	if (response.status === 'connected') {
      		getFacebookData();
    	} else {
     		callback(false);
    	}
  	}


  	var checkLoginState = function(callback) {
    	FB.getLoginStatus(function(response) {
      		callback(response);
    	});
  	}

  	var getFacebookData =  function() {
  		FB.api('/me', function(response) {

	  		$('#login').after(div_session);
	  		$('#login').remove();
	  		$('#facebook-session strong').text("Bienvenido: "+ response.name);
	  		$('#facebook-session img').attr('src','http://graph.facebook.com/'+response.id+'/picture?type=large');
	  	console.log(response);

      //console.log(response.hometown.name);
      //console.log(response.location.name);
	  	});
  	
  	}

  	var facebookLogin = function() {
  		checkLoginState(function(data) {
  			if (data.status !== 'connected') {
  				FB.login(function(response) {
  					if (response.status === 'connected')
  						getFacebookData();
  				}, {scope: scopes});
  			}
  		})
  	}

  	var facebookLogout = function() {
  		checkLoginState(function(data) {
  			if (data.status === 'connected') {
				FB.logout(function(response) {
					$('#facebook-session').before(btn_login);
					$('#facebook-session').remove();
				})
			}
  		})

  	}

  	$(document).on('click', '#publicaFijo', function(e) {
		FB.login(function(){  
      
  // Note: The call will only work if you accept the permission request
  		FB.api('/me/feed', 'post', {

        message: 'message'
        });
			},{scope: 'publish_actions'});
  	})


    $(document).on('click', '#publicaPreguntando', function(e) {
  FB.ui({
  method: 'feed',
  link: 'https://developers.facebook.com/',
  caption: 'An example caption',
}, function(response){});
})

document.getElementById('email').onclick = function() {
  FB.api('/me', { locale: 'en_US', fields: 'name, email,friends,location,locale,hometown' },
  function(response) {
    console.log(response);
  }
);
};
  
  // Note: The call will only work if you accept the permission request

  	$(document).on('click', '#login', function(e) {
  		e.preventDefault();

  		facebookLogin();

  	})

  	$(document).on('click', '#logout', function(e) {
  		e.preventDefault();

  		if (confirm("¿Está seguro?"))
  			facebookLogout();
  		else 
  			return false;
  	})

})



