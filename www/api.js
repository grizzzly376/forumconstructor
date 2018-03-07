

function html_to_bb(oldHTML){
				oldHTML = oldHTML.split("<i>").join("[i]");
				oldHTML = oldHTML.split("</i>").join("[/i]");
				oldHTML = oldHTML.split("<b>").join("[b]");
				oldHTML = oldHTML.split("<s>").join("[s]");
				oldHTML = oldHTML.split("<u>").join("[u]");
				oldHTML = oldHTML.split("</s>").join("[/s]");
				oldHTML = oldHTML.split("</b>").join("[/b]");
				oldHTML = oldHTML.split("</u>").join("[/u]");
				oldHTML = oldHTML.split("<font color=\"red\">").join("[red]");
				oldHTML = oldHTML.split("<font color=\"blue\">").join("[blue]");
				oldHTML = oldHTML.split("<font color=\"green\">").join("[green]");
				oldHTML = oldHTML.split("<font color=\"orange\">").join("[orange]");
				oldHTML = oldHTML.split("</font>").join("[/color]");
				oldHTML = oldHTML.split("<h2>").join("[header]");
				oldHTML = oldHTML.split("</h2>").join("[/header]");
				oldHTML = oldHTML.split("<br>").join("\r\n");
				oldHTML = oldHTML.split("<div class=\"quote\">").join("[quote]");
				oldHTML = oldHTML.split("</div>").join("[/quote]");
				oldHTML = oldHTML.split("<img src=\"ico/smiles/").join("smile");
				oldHTML = oldHTML.split(".gif\">").join("");
				oldHTML = oldHTML.split("<a target=\"_blank\" href=\"").join("[url=\"");
				oldHTML = oldHTML.split("\">").join("\"]");
				oldHTML = oldHTML.split("</a>").join("[/url]");
				return oldHTML;
}

	 function insert(start, end, id) {
	  element = document.getElementById(id);
	  if(element == null) return;
	  if (document.selection) {
	   element.focus();
	   sel = document.selection.createRange();
	   sel.text = start + sel.text + end;
	  } else if (element.selectionStart || element.selectionStart == '0') {
	   element.focus();
	   var startPos = element.selectionStart;
	   var endPos = element.selectionEnd;
	   element.value = element.value.substring(0, startPos) + start + element.value.substring(startPos, endPos) + end + element.value.substring(endPos, element.value.length);
	  } else {
	   element.value += start + end;
	  }
	 }


$("document").ready(function(){
	reload();
	reload_login();
})

function reload_login(){
	$(".login_form").off().on("submit",function(){
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"login", "login":$(this).find(".lgn").val(), "password":$(this).find(".password").val() })
	  .done(function(data) {
			if(data=="true"){
				req_page("");
			}else{
				alert(data);
				$("#wait").css({"display":"none"});
			}
	  })
	  return false;
	})
	
}
function reload(){	
	 console.log("reload()")
	$(".chart").each(function(){
		$(this).find("div").remove()
		var width = $(this).attr("this")*1/$(this).attr("all")*1;
		$(this).append("<div>"+Math.floor(width*100)+"%</div>")
		$(this).find("div").css({"width":width*100+"%"})
	})
	
	$(".spoiler-toggle").off().on("click",function(){
		if($(this).attr("state")*1==1){
			$(this).next(".spoiler").css({"height":"auto","padding-bottom":"15px"});
			$(this).find("img").attr({"src":"ico/hide.png"})
			$(this).attr({"state":"2"})
		}else{
			$(this).next(".spoiler").css({"height":"0","padding-bottom":"0"});
			$(this).find("img").attr({"src":"ico/show.png"})
			$(this).attr({"state":"1"})					
		}
		
	})
	
/*	setInterval(function(){
		$(".blink").css({"color":"red"})
		setTimeout(function(){
			$(".blink").css({"color":"inherit"})
		},1000)
	},2000)
*/
	
	$(".createq").off().on("click",function(){
		$(".q-span").css({"display":"inline"})
		$(this).css({"text-decoration":"none"}).html("Создание опроса")
	})
	
	
	$("#reg_ok").off().on("click",function(){
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"reg", "login":$("#login").val(), "password":$("#password").val() , "mail": $("#mail-val").val(), "info" : $("#editinfo").val()})
	  .done(function(data) {
			if(data=="true"){
				req_page("index.php");
			}else{
				$("#wait").css({"display":"none"});
				$("#reg_error").html(data);
			}
	  })
	})		
	$("#sendpost form").off().on("submit",function(){
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"sendpost", "thread": $("#threadid").attr("thread")*1, "message":$("#msgedit").val()})
	  .done(function(data) {
			if(data=="true"){
				req_page("");
			}
	  })
	  return false;
	})	
	$("#ntopic").off().on("submit",function(){
	  $("#wait").css({"display":"inline"})
	  var opt = [];
	  for(var i=0;i<$(".qul input").size();i++){
		if($(".qul input").eq(i).val()!="")opt.push($(".qul input").eq(i).val());
	  }
	  $.get( "api.php", {"action":"newthread", "forum": $("#fid").val(), "message":$("#msgedit").val(), "tname":$("#tname").val(),"qtext":$("#qname").val(),"opt[]":opt})
	  .done(function(data) {
				if(data[0] == "p"){
					req_page(data);
				}else{
					alert(data);
					$("#wait").css({"display":"none"});
				}
	  })
	  return false;
	})	
	$(".deletemsg").off().on("click",function(){
	var anchor = $(this);
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"deletemsg", "postid": $(this).attr("postid")})
	  .done(function(data) {
				if(data == "true"){
					anchor.closest("div").fadeOut();
				}
			$("#wait").css({"display":"none"})
	  })
	  return false;
	})	
	$(".deletethread").off().on("click",function(){
	var anchor = $(this);
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"deletethread", "thread": $(this).attr("thread")})
	  .done(function(data) {
				if(data == "true"){
					anchor.closest("tr").fadeOut();
				}else{
					alert(data)
				}
			$("#wait").css({"display":"none"})
	  })
	  return false;
	})
	
	function worksmiles(edit){
		$("#"+edit).closest(".group").find(".smiles").on("click",function(){
			$(this).closest("#tools").find(".smiles-panel").remove();
			$(this).closest("#tools").find(".link-panel").remove();
			$(this).closest("#tools").find(".img-panel").remove();
			var stext = "<div class='smiles-panel'><div class='ico closesmiles'>x</div>";
			for(var i=1;i<30;i++)
					stext+="<div class='ico smile' alt="+i+"><img src=ico/smiles/"+i+".gif></div>";
			stext += "</div>";
			$(this).before(stext)	

			$(".smiles-panel").draggable();
			
			$(".closesmiles").off().on("click",function(){
				$(this).closest("#tools").find(".smiles-panel").remove();
			})
				
			 $('#tools .smile').off().on("click", function() {
				  var button_id = attribs = $(this).attr("alt");
				  button_id = button_id.replace(/\[.*\]/, '');
				  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
				  var start = 'smile'+button_id+attribs;
				  var end='';
				  insert(start, end, edit);
				  return false;
			  });
		})		
		
	}
	worksmiles('editinfo');
	if($("#msgedit").size()>0)
		worksmiles('msgedit');	
	
	function worklinks(edit){
		$("#"+edit).closest(".group").find(".link").on("click",function(){
			$(this).closest("#tools").find(".smiles-panel").remove();
			$(this).closest("#tools").find(".link-panel").remove();
			$(this).closest("#tools").find(".img-panel").remove();
			var stext = "<div class='link-panel'><div class='ico closelinks'>x</div><br>";
			stext += "<table><tr><td>Ссылка </td><td><input value='http://'></td></tr>"
			stext += "<tr><td>Описание </td><td><input></td></tr></table>"
			stext += "<tr><td></td><td><br><input class=linkok type=submit value='OK'></td></tr></table>"
			stext += "</div>";
			$(this).before(stext)	
			
			$(".link-panel").draggable();
			
			$(".closelinks").off().on("click",function(){
				$(this).closest("#tools").find(".link-panel").remove();
			})
				
			 $('#tools .linkok').off().on("click", function() {
				  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
				  var start = '[url=\"'+$(this).closest(".link-panel").find("input").eq(0).val()+'\"]'+$(this).closest(".link-panel").find("input").eq(1).val()+'[/url]';
				  var end='';
				  insert(start, end, edit);
				  $(this).closest("#tools").find(".link-panel").remove();
				  return false;
			  });
		})		
		
	}
	
	worklinks('editinfo');
	if($("#msgedit").size()>0) worklinks('msgedit');
	
	function workimages(edit){
		console.log("workimages")
		
		$("#"+edit).closest(".group").find(".insertimage").on("click",function(){
			console.log("workimages opened")
			$(this).closest("#tools").find(".smiles-panel").remove();
			$(this).closest("#tools").find(".link-panel").remove();
			$(this).closest("#tools").find(".img-panel").remove();
			var stext = "<div class='img-panel'><div class='ico closelinks'>x</div><br>";
			stext += "<table><tr><td>Ссылка </td><td><input value='http://'></td></tr></table>"
			stext += "<tr><td></td><td><br><input class=imgok type=submit value='OK'></td></tr></table>"
			stext += "</div>";
			$(this).before(stext)	
			
			$(".img-panel").draggable();
			
			$(".closelinks").off().on("click",function(){
				$(this).closest("#tools").find(".img-panel").remove();
			})
				
			 $('#tools .imgok').on("click", function() {
				  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
				  var start = '[img]'+$(this).closest(".img-panel").find("input").eq(0).val()+'[/img]';
				  var end='';
				  insert(start, end, edit);
				  $(this).closest("#tools").find(".img-panel").remove();
				  return false;
			  });
		})		
		
	}
	workimages('editinfo');
	if($("#msgedit").size()>0)workimages('msgedit');
	
	 $('#tools .ico').on("click", function() {
		  var button_id = attribs = $(this).attr("alt");
		  if(button_id){
			button_id = button_id.replace(/\[.*\]/, '');
			  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
			  var start = '['+button_id+attribs+']';
			  var end = '[/'+button_id+']';
			  insert(start, end, 'msgedit');
			  insert(start, end, 'editinfo');
			  return false;
			}
	});
 

	 
	 $(".edit").off().on("click",function(){
		$(this).closest(".group").find("td").eq(1).prepend("<div id=tools><center><div class=ico alt=i><i>i</i></div><div class=ico alt=u><u>u</u></div><div class=ico alt=b><b>b</b></div><div class=ico alt=s><s>s</s></div><div class=ico alt=header><h3>H</h3></div><div class=ico alt='red'><div class=color style='background:red'></div></div><div class=ico alt='green'><div class=color style='background:green'></div></div><div class=ico alt='blue'><div class=color style='background:blue'></div></div><div class=ico alt='orange'><div class=color style='background:orange'></div></div><div class=ico alt='center'><img src=ico/center.png height=20px width=20px></div><div class=ico alt='left'><img src=ico/left.png height=20px width=20px></div><div class=ico alt='right'><img src=ico/right.png height=20px width=20px></div><div class=ico alt='justify'><img src=ico/justify.png height=20px width=20px></div><div class='ico insertimage'><img src=ico/pic.png height=20px width=20px></div><div class=ico alt='spoiler'><img src=ico/hide.png height=20px width=20px></div><div class='ico link'><img src=ico/link.png height=20px width=20px></div><div class='ico smiles'><img src=ico/smiles/16.gif></div></center></div>")

		
		$(this).closest(".post").find('#tools .ico').on("click", function() {
			  var button_id = attribs = $(this).attr("alt");
			  if(button_id){
				  button_id = button_id.replace(/\[.*\]/, '');
				  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
				  var start = '['+button_id+attribs+']';
				  var end = '[/'+button_id+']';
				  insert(start, end, 'editor');
				  return false;
			  }
		});
		
		$(this).closest(".group").find(".msg").replaceWith(function(index, oldHTML){
				oldHTML = html_to_bb(oldHTML);
				//return $("<textarea class=editarea id=editor>").html(oldHTML);
				return $("<textarea class=editarea id=editor>").html($(this).closest(".group").find(".msg").attr("raw"));
		});
		
		$(this).addClass("save").find("img").attr({"src":"/ico/save.png"})
		$(".save").off().on("click",function(){
			 $("#wait").css({"display":"inline"})
			  $.get( "api.php", {"action":"editmsg", "postid": $(this).attr("postid"), "text": $(this).closest(".group").find(".editarea").val()})
			  .done(function(data) {
						if(data == "true"){
							req_page("");
						}
	  })
			
		})
				
		worksmiles('editor');
		worklinks('editor');
		workimages('editor');
		 
	 })
	 
	 $(".editinfo").off().on("click",function(){
		
		 $(this).closest(".group").find(".msg").replaceWith(function(index, oldHTML){
				return $("<textarea id=msgedit class=editarea>").html($(this).closest(".group").find(".msg").attr("raw"));
		});
		$("textarea").before("<div id=tools><center><div class=ico alt=i><i>i</i></div><div class=ico alt=u><u>u</u></div><div class=ico alt=b><b>b</b></div><div class=ico alt=s><s>s</s></div><div class=ico alt=header><h3>H</h3></div><div class=ico alt='red'><div class=color style='background:red'></div></div><div class=ico alt='green'><div class=color style='background:green'></div></div><div class=ico alt='blue'><div class=color style='background:blue'></div></div><div class=ico alt='orange'><div class=color style='background:orange'></div></div><div class=ico alt='center'><img src=ico/center.png height=20px width=20px></div><div class=ico alt='left'><img src=ico/left.png height=20px width=20px></div><div class=ico alt='right'><img src=ico/right.png height=20px width=20px></div><div class=ico alt='justify'><img src=ico/justify.png height=20px width=20px></div><div class='ico insertimage'><img src=ico/pic.png height=20px width=20px></div><div class=ico alt='spoiler'><img src=ico/hide.png height=20px width=20px></div><div class='ico link'><img src=ico/link.png height=20px width=20px></div><div class='ico smiles'><img src=ico/smiles/16.gif></div></center></div>");
		
		worksmiles('msgedit');
		worklinks('msgedit');
		workimages('msgedit');
		
	 $('#tools .ico').off().on("click", function() {
		  var button_id = attribs = $(this).attr("alt");
		  button_id = button_id.replace(/\[.*\]/, '');
		  if (/\[.*\]/.test(attribs)) { attribs = attribs.replace(/.*\[(.*)\]/, ' $1'); } else attribs = '';
		  var start = '['+button_id+attribs+']';
		  var end = '[/'+button_id+']';
		  insert(start, end, 'msgedit');
		  return false;
	});		
		$(this).html("Сохранить").addClass("save")
		$(".save").off().on("click",function(){
			 $("#wait").css({"display":"inline"})
			  $.get( "api.php", {"action":"editinfo", "user": $(this).attr("uid"), "text": $(this).closest(".group").find(".editarea").val()})
			  .done(function(data) {
						if(data == "true"){
							req_page("");
						}
			})
		})
	 })
	 
	 $("#qform").off().on("submit",function(){
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"voteq", "qid":$(this).find("input[name=qi]:checked").val()})
		  .done(function(data) {
					if(data=="true")
						req_page("");
					else{
						alert("Не удалость проголосовать "+data)
						$("#wait").css({"display":"none"})
					}
		  })		 
		 return false;
	 })
	 
	 $(".makeuser").off().on("click",function(){
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"changegroup", "user": $(this).attr("uid"), "group":1})
		  .done(function(data) {
					if(data=="true")
						req_page("");
					else{
						alert("Не получилось изменить права пользователя")
					}
		  })
	})	
	 $(".makemoder").off().on("click",function(){
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"changegroup", "user": $(this).attr("uid"), "group":2})
		  .done(function(data) {
					if(data=="true")
						req_page("");
					else
						alert("Не получилось изменить права пользователя")
		  })
	})	
	 $(".makeadmin").off().on("click",function(){
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"changegroup", "user": $(this).attr("uid"), "group":3})
		  .done(function(data) {
					if(data=="true")
						req_page("");
					else
						alert("Не получилось изменить права пользователя")
		  })
	})	
	 $(".profile").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  var color = prompt("Введите новый цвет","white");
		  $.get( "api.php", {"action":"changecolor", "user": $(this).attr("uid"), "color":color})
		  .done(function(data) {
					if(data=="true")
						req_page("");
					else
						alert("Не получилось изменить цвет аватара пользователя")
		  })
	})	
	 $(".closer").off().on("click",function(){
			anchor = $(this)
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"toggleclose", "thread": $(this).attr("thread"), "value":1})
		  .done(function(data) {
					if(data=="true")
						anchor.closest("td").find("a").eq(0).before("<button class='sbutton closed'><img src=/ico/close.png></button>");
					$("#wait").css({"display":"none"})
		  })
	})
	 $(".opener").off().on("click",function(){
			anchor = $(this)
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"toggleclose", "thread": $(this).attr("thread"), "value":0})
		  .done(function(data) {
					if(data=="true")
						anchor.closest("td").find(".closed").fadeOut();	
					$("#wait").css({"display":"none"})
		  })
	})
	 $(".toggleimp").off().on("click",function(){
			anchor = $(this)
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"toggleimp", "thread": $(this).attr("thread"), "value":$(this).attr("val")})
		  .done(function(data) {
					if(data=="true"){
						if(anchor.attr("val")*1==0)anchor.closest("td").find(".important").fadeOut();
						if(anchor.attr("val")*1==1)anchor.closest("td").find("a").eq(0).before("<button class='sbutton important'><img src=/ico/important.png title=Важная></button>");
					}
					$("#wait").css({"display":"none"})
		  })
	})
	 $(".renamet").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  var newname = prompt("Введите новое название",$(this).closest("td").find("a").eq(0).html());
		  $.get( "api.php", {"action":"renamethread", "thread": $(this).attr("thread"), "newname":newname})
		  .done(function(data) {
					if(data=="true")
						anchor.closest("td").find("a").eq(0).html(newname)
					$("#wait").css({"display":"none"})
		  })
	})	
	 $(".tmove").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  var newt = prompt("Введите ID форума",1);
		  $.get( "api.php", {"action":"tmove", "thread": $(this).attr("thread"), "forum":newt})
		  .done(function(data) {
					if(data=="true")
						req_page('threads.php?forum='+newt);
					$("#wait").css({"display":"none"})
		  })
	})
	 $(".newpass").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  var oldp = prompt("Введите старый пароль","");
		  var newp = prompt("Введите новый пароль","");
		  $.get( "api.php", {"action":"newpass", "user": $(this).attr("uid"), "newpass":newp, "oldpass":oldp})
		  .done(function(data) {
					if(data=="true")
						alert('Пароль успешно изменен')
					else alert('Не удалось изменить пароль')
					$("#wait").css({"display":"none"})
		  })
	})		
	 $("#new_s_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"newsection", "name": $("#new_s_name").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Раздел добавлен успешно")
					else	
						alert("Ошибка создания раздела")
					req_page("");
		  })
	})
	 $("#delete_s_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"deletesection", "sid": $("#delete_s_id").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Раздел удалён успешно")
					else	
						alert(data)
					req_page("");
		  })
	})
	 $("#new_f_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"newforum", "section": $("#new_f_section").val(),"name": $("#new_f_name").val(),"info": $("#new_f_info").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Форум добавлен успешно")
					else	
						alert(data)
					req_page("");
		  })
	})
	 $("#delete_f_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"deleteforum", "fid": $("#delete_f_id").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Форум удалён успешно")
					else	
						alert(data)
					req_page("");
		  })
	})
	 $("#rename_f_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"renameforum", "name": $("#rename_f_name").val(), "fid": $("#rename_f_id").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Форум переименован успешно")
					else	
						alert("Ошибка переименования форума")
					req_page("");
		  })
	})
	 $("#rename_s_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"renamesection", "name": $("#rename_s_name").val(), "sid": $("#rename_s_id").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Раздел переименован успешно")
					else	
						alert("Ошибка переименования раздела"+ data)
					req_page("");
		  })
	})
	 $("#change_fi_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatefinfo", "info": $("#change_fi_info").val(), "fid": $("#change_fi_id").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Информация о форуме изменена успешно")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_ppp_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_ppp_val").val(), "key": "ppp"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_tpp_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_tpp_val").val(), "key": "tpp"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_mpp_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_mpp_val").val(), "key": "mpp"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_fname_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_fname_val").val(), "key": "fname"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_adv_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_adv_val").val(), "key": "adv"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_styles_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_styles_val").val(), "key": "styles"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_logo_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_logo_val").val(), "key": "logo"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_scripts_file_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_scripts_file_val").val(), "key": "scripts_file"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_styles_file_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_styles_file_val").val(), "key": "styles_file"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_bgimage_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_bgimage_val").val(), "key": "bgimage"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_script_top_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_script_top_val").val(), "key": "script_top"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#updtate_info_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"updatevar", "value": $("#updtate_info_val").val(), "key": "info"})
		  .done(function(data) {
					if(data=="true")
						alert("Настройки сохранены")
					else	
						alert("Ошибка "+data)
					req_page("");
		  })
	})
	 $("#move_f_submit").off().on("click",function(){
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"moveforum", "fid": $("#move_f_id").val(), "sid": $("#move_f_section").val()})
		  .done(function(data) {
					if(data=="true")
						alert("Форум перемещён успешно")
					else	
						alert("Ошибка")
					req_page("");
		  })
	})
	 $(".userban").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  var hours = prompt("Введите колличество часов бана", 1);
		  var comment = prompt("Введите причину бана","");
		  $.get( "api.php", {"action":"banuser", "user": $(this).attr("uid"), "comment":comment, "exp": hours})
		  .done(function(data) {
					if(data=="true")
						alert('Пользователь забанен')
					else alert('Ошибка')
					req_page("");
		  })
	})
	$(".userrename").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  var nname = prompt("Введите новый логин", "");
		  $.get( "api.php", {"action":"renameuser", "user": $(this).attr("uid"), "name": nname})
		  .done(function(data) {
					if(data=="true")
						alert('Логин изменен')
					else alert('Ошибка')
					req_page("");
		  })
	})	
	 $(".clearban").off().on("click",function(){
		anchor = $(this)
		  $("#wait").css({"display":"inline"});
		  $.get( "api.php", {"action":"clearban", "user": $(this).attr("uid")})
		  .done(function(data) {
					if(data=="true")
						alert('Пользователь разабанен')
					else alert('Ошибка '+ data)
					req_page("");
		  })
	})		
	
	$(".makequote").off().on("click",function(){
		var qtext = $(this).closest(".group").find(".msg").html();
			qtext = html_to_bb(qtext);
			insert("[quote]"+$(this).closest(".group").find(".msg").attr("raw"), "[/quote]", 'msgedit');
	})
	$("#nmail").off().on("submit",function(){
	  $("#wait").css({"display":"inline"})
	  $.get( "api.php", {"action":"newmail", "rcvr":$("#mrec").val(), "title":$("#mtitle").val() ,"text":$("#mtext").val()})
	  .done(function(data) {
			if(data=="true"){
				req_page('mail.php?action=inbox');
			}else{
				alert("Ошибка "+data);
				$("#wait").css({"display":"none"});
			}
	  })
	  return false;
	})		
	$("#new_page_submit").off().on("click",function(){
	  $("#wait").css({"display":"inline"})
	  $.post( "api.php", {"action":"createpage", "pagetext":$("#new_page_code").val(), "pagename":$("#new_page_name").val() ,"flagtop":1})
	  .done(function(data) {
			if(data=="true"){
				req_page("");
			}else{
				alert("Ошибка "+data);
				$("#wait").css({"display":"none"});
			}
	  })
	  return false;
	})		
	 $(".makefav").off().on("click",function(){
		  anchor = $(this)
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"togglefav", "mid": $(this).attr("mailid"), "value":1})
		  .done(function(data) {
					if(data=="true")
						anchor.closest("td").find(".closed").fadeOut();	
					$("#wait").css({"display":"none"})
		  })
	})
	 $(".makenotfav").off().on("click",function(){
		  anchor = $(this)
		  $("#wait").css({"display":"inline"})
		  $.get( "api.php", {"action":"togglefav", "mid": $(this).attr("mailid"), "value":0})
		  .done(function(data) {
					if(data=="true")
						anchor.closest("td").find(".closed").fadeOut();	
					$("#wait").css({"display":"none"})
		  })
	})	
	$("#uavatar").off().on("change",function(){
		$("#wait").css({"display":"inline"})
		var formData = new FormData($('#uavatar-form')[0]);
		$.ajax({
		  type: "POST",
		  processData: false,
		  contentType: false,
		  url: "api.php",
		  data:  formData 
      })
      .done(function( data ) {
           if(data=="true"){
			req_page("");
		   }
		   else{alert(data);
		   $("#wait").css({"display":"none"})	}    	   
      });
		
	})
	$("#change_f_priority_id").off().on("change",function(){
		if($(this).val()!="empty"){
			$("#wait").css({"display":"inline"})
			$.post( "api.php", {"action":"loadfpriority", "fid":$(this).val()*1})
			  .done(function(data) {
				$("#change_f_priority_val").val(data);
				$("#wait").css({"display":"none"})
			  })
		}
	})
	$("#change_f_priority_submit").off().on("click", function(){
		if($(this).val()!="empty"){
			$("#wait").css({"display":"inline"})
			$.post( "api.php", {"action":"changefpriority", "fid":$("#change_f_priority_id").val()*1 , "priority": $("#change_f_priority_val").val()*1})
			  .done(function(data) {
				$("#wait").css({"display":"none"})
			  })
		}
	})
	$("#change_s_priority_id").off().on("change",function(){
		if($(this).val()!="empty"){
			$("#wait").css({"display":"inline"})
			$.post( "api.php", {"action":"loadspriority", "sid":$(this).val()*1})
			  .done(function(data) {
				$("#change_s_priority_val").val(data);
				$("#wait").css({"display":"none"})
			  })
		}
	})
	$("#change_s_priority_submit").off().on("click", function(){
		if($(this).val()!="empty"){
			$("#wait").css({"display":"inline"})
			$.post( "api.php", {"action":"changespriority", "sid":$("#change_s_priority_id").val()*1 , "priority": $("#change_s_priority_val").val()*1})
			  .done(function(data) {
				$("#wait").css({"display":"none"})
			  })
		}
	})
	
	
	$("#change_page_id").off().on("change",function(){
		var anchor = $(this)
		$("#wait").css({"display":"inline"})
		$(".temporary").remove();
		if($(this).val()!="empty")
		  $.post( "api.php", {"action":"loadpagehtml", "pid":$(this).val()*1})
		  .done(function(data) {
				
				anchor.after("<p class=temporary><br><br><textarea id=change_page_text style='width:90%;height:400px;'>"+data+"</textarea><br><br><input type=submit id=change_page_submit value='Изменить'  class=fbutton></p>")
				$("#wait").css({"display":"none"});
				
				$("#change_page_submit").off().on("click",function(){
					  $("#wait").css({"display":"inline"})
					  $.post( "api.php", {"action":"createpage", "pagetext":$("#change_page_text").val(), "pagename":$("#change_page_id option:selected").text() ,"flagtop":0})
					  .done(function(data) {
							if(data=="true"){
								req_page("");
							}else{
								alert("Ошибка "+data);
								$("#wait").css({"display":"none"});
							}
					  })					
					
				})
		  })	
		else	$("#wait").css({"display":"none"});	  
	})
	
	$("#delete_page_submit").off().on("click",function(){
		var anchor = $(this)
		$("#wait").css({"display":"inline"})

		  $.get( "api.php", {"action":"deletepage", "pid": $("#delete_page_id option:selected").val()})
		  .done(function(data) {
					if(data!="true")
						alert(data);	
					req_page("");
					$("#wait").css({"display":"none"})
		  })
	
		$("#wait").css({"display":"none"});	  
	})
}
