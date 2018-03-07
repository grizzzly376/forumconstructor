$(document).ready(function(){
	$("#mwrap .login_form").appendTo($("#mwrap table tr td").eq(0))
	$(".login_form *").each(function(){
		$(this).after("<br>")
	})
})