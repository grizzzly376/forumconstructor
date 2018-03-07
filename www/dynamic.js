function req_page(href){
		var links = [/threads.php*/, /index.php*/, /page.php*/, /admin.php*/, /custom.php*/, /error.php*/, /mail.php*/, /newthread.php*/ , /pagemanager.php*/,
		/reg.php*/, /search.php*/, /user.php*/]
		for(var i=0;i<links.length;i++)
		if(links[i].test(href)){
			$("#wait").css({"display":"inline"})
			  $.get("request/"+href)
			  .done(function(data) {
					window.history.pushState("object or string", "Title", href); 
					$("#main").html(data);
					req_page("top.php");
					req_page("menu.php");
					dynamic_links();
					reload();
					$("#wait").css({"display":"none"})
			  })
			  return false;
						  
		}
		if(href==""){
			 req_page((window.location.pathname+window.location.search).slice(1));
			 req_page("top.php");
			 req_page("menu.php");
		}
		if(/top.php*/.test(href)){
			  $.get("request/"+href)
			  .done(function(data) {
					if(data!=$("#top").html())$("#top").html(data);
					dynamic_links();
					reload_login();
			  })	
			return false;			  
		}
		if(/online.php*/.test(href)){
			  $.get("request/"+href)
			  .done(function(data) {
					if(data!=$("#bottom").html())$("#bottom").html(data);
					dynamic_links();
			  })	
			return false;			  
		}
		if(/menu.php*/.test(href)){
			  $.get("request/"+href)
			  .done(function(data) {
					if(data!=$("#menu").html())$("#menu").html(data);
					dynamic_links();
					reload_login();
			  })	
			return false;			  
		}
		if(/logout.php*/.test(href)){
			  $("#wait").css({"display":"inline"})
			  $.get( "api.php", {"action":"logout"})
			  .done(function(data) {
					if(data=="true"){
						req_page("");
					}
					else
						alert(data)
			  })
			  dynamic_links();
			  reload();
			  $("#wait").css({"display":"none"})
			return false;			  
		}
		return true;
}

function dynamic_links(){
	
	$("a").off().on("click",function(){

		var href = $(this).attr("href")
		return req_page(href);
		
	})
	
	window.onpopstate = function(event) {
		req_page(window.location.pathname+window.location.search)
	}
	
	
}


$(document).ready(function(){
	
	dynamic_links();
	
	setInterval(function(){req_page("top.php");req_page("online.php");},5000)
	
})