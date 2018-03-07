		<?
			include_once dirname(__FILE__)."/../core.php";
			$thread = $_GET['thread'];
			$page = $_GET['id'];
			
			echo "<span id=threadid thread=$thread></span>";
			
			mysql_query("UPDATE `threads` SET `visits` = `visits` + 1 WHERE `id` = '$thread'") or die(mysql_error());
			
			$query = mysql_query("SELECT *, `forums`.`name` as 'fname', `threads`.`name` AS 'tname', `forums`.`id` AS 'fid' FROM `threads` LEFT JOIN `forums` ON `threads`.`forum` = `forums`.`id` WHERE `threads`.`id` = '$thread' LIMIT 1") or die(mysql_error());
			$result = mysql_fetch_object($query);
			$query = mysql_query("SELECT *, `sections`.`name` AS 'sname', `forums`.`name` AS 'fname' FROM `forums` LEFT JOIN `sections` ON `forums`.`section` = `sections`.`id` WHERE `forums`.`id` = '".$result->fid."' LIMIT 1") or die(mysql_error());
			$result2 = mysql_fetch_object($query);
			
			if($result->closed==1){
				echo "<button class='sbutton closed'><img src=/ico/close.png></button>";
				$closed = true;
			}
			if($result->question!="") echo "<button class='sbutton'><img src=/ico/q.png></button>";
			if($result->important==1) echo "<button class='sbutton important'><img src=/ico/important.png title=Важная></button>";
			echo "<a href=index.php>$SETTINGS->fname</a> > ".$result2->sname." > <a href=threads.php?forum=".$result->fid.">".$result->fname."</a> > <i>".$result->tname."</i><br><br><hr><br>";

		?>
			

			<div class=pages>
				Переход на страницу : 
			<?
				$query = mysql_query("SELECT * FROM `posts` WHERE `thread` = $thread");
				$nr = mysql_num_rows($query);
				
				$pages = intval($nr / $SETTINGS->ppp);
				if($nr % $SETTINGS->ppp !=0) $pages++;
				
				if($page==0) $page=$pages;
				$begin = ($page-1)*$SETTINGS->ppp;
				
				
				for($i=1;$i<$pages+1;$i++){
					if($i==$page) echo "<b>";
					echo "<a href=page.php?thread=$thread&id=$i>$i</a>";
					if($i==$page) echo "</b>";
				}
				
				echo "</div>";
				// Q
				if(($result->question!="")&&($user->group>0)){
					
					
					$query = mysql_query("SELECT * FROM `answers` LEFT JOIN `questions` ON `answers`.`qid` = `questions`.`id` WHERE (`tid` = '$thread') AND (`uid` = '$user->id') ORDER BY `questions`.`id`");
					if(mysql_num_rows($query)<1){
						echo "<div class='group qdiv emptyq'><span>Опрос</span><form id=qform><br><b>".$result->question."</b><br>";
						$query = mysql_query("SELECT * FROM `questions` WHERE `tid` = '$thread' ORDER BY `id`");
						echo "<table cellpadding=0 cellspacing=0 class=answt>";
						while($result = mysql_fetch_object($query)){
							echo "<tr><td><input type=radio name=qi value=".$result->id."></td><td class=biggertext>$result->text</td></tr>";
						}
						echo "</table>";
						echo "<center><input type=submit class=fbutton value=Голосовать></center>";
					}else{
						echo "<div class='group qdiv'><span>Опрос</span><form id=qform><br><b>".$result->question."</b><br>";
						$asum = mysql_num_rows(mysql_query("SELECT * FROM `answers` LEFT JOIN `questions` ON `answers`.`qid` = `questions`.`id` WHERE (`tid` = '$thread')"));
						$query = mysql_query("SELECT * , COUNT(`answers`.`uid`) AS 'sum' FROM `questions` LEFT OUTER JOIN `answers` ON `answers`.`qid` = `questions`.`id` WHERE (`tid` = '$thread') GROUP BY `questions`.`id`");
						echo "<table cellpadding=0 cellspacing=0 class=answt>";
						while($result = mysql_fetch_object($query)){
							echo "<tr><td valign=top><div class=qamount>$result->sum</div></td><td valign=top><div class=chart all=$asum this=$result->sum></div></td><td  class=biggertext>$result->text</td></tr>";
						}						
						echo "</table>";
						echo "<br>Всего проголосовало : <b>$asum</b>";
					}
					
					echo "</form></div>";
				}
				
			?>
			
			
			<?
			
			
			
			$query = mysql_query("SELECT *, UNIX_TIMESTAMP(`users`.`active`) AS 'last', `users`.`id` AS `userid`, `posts`.`id` AS `postid`, (SELECT count(*) FROM `posts` WHERE `author` = `userid`) AS 'msgs' FROM `posts` LEFT JOIN `users` ON `posts`.`author` = `users`.`id` WHERE `posts`.`thread` = ".$thread." ORDER BY `posts`.`id` ASC LIMIT $begin, ".$SETTINGS->ppp) or die(mysql_error());
			$rn = 0;
			while($result = mysql_fetch_object($query)){
					$rn++;
					$rawtext  = $result->text;
					$rawtext = str_replace("\"","&quot;",$rawtext);
					$result->text = str_replace("\n","<br>",strip_tags($result->text));
					$result->text = str_replace("[i]","<i>",$result->text);
					$result->text = str_replace("[/i]","</i>",$result->text);
					$result->text = str_replace("[b]","<b>",$result->text);
					$result->text = str_replace("[/b]","</b>",$result->text);
					$result->text = str_replace("[s]","<s>",$result->text);
					$result->text = str_replace("[/s]","</s>",$result->text);
					$result->text = str_replace("[u]","<u>",$result->text);
					$result->text = str_replace("[/u]","</u>",$result->text);
					$result->text = str_replace("[orange]","<font color=orange>",$result->text);
					$result->text = str_replace("[/orange]","</font>",$result->text);
					$result->text = str_replace("[red]","<font color=red>",$result->text);
					$result->text = str_replace("[/red]","</font>",$result->text);
					$result->text = str_replace("[green]","<font color=green>",$result->text);
					$result->text = str_replace("[/green]","</font>",$result->text);
					$result->text = str_replace("[blue]","<font color=blue>",$result->text);
					$result->text = str_replace("[/blue]","</font>",$result->text);
					$result->text = str_replace("[header]","<h2>",$result->text);
					$result->text = str_replace("[/header]","</h2>",$result->text);
					$result->text = str_replace("[/color]","</font>",$result->text);
					$result->text = str_replace("[quote]","<div class=quote>",$result->text);
					$result->text = str_replace("[/quote]","</div>",$result->text);
					$result->text = str_replace("[url=\"","<a target=\"_blank\" href=\"",$result->text);
					$result->text = str_replace("\"]","\">",$result->text);
					$result->text = str_replace("[/url]","</a>",$result->text);
					$result->text = str_replace("[spoiler]","<br><span class=\"spoiler-toggle\" state=1><table><tr><td><img src=\"ico/show.png\"> </td><td valign=top>  Спойлер</td></tr></table></span><div class=spoiler>",$result->text);
					$result->text = str_replace("[/spoiler]","</div>",$result->text);
					$result->text = str_replace("[img]","<img class='inserted-img' src=",$result->text);
					$result->text = str_replace("[/img]",">",$result->text);
					$result->text = str_replace("[center]","<center>",$result->text);
					$result->text = str_replace("[/center]","</center>",$result->text);
					$result->text = str_replace("[left]","<p align=left>",$result->text);
					$result->text = str_replace("[right]","<p align=right>",$result->text);
					$result->text = str_replace("[justify]","<p align=justify>",$result->text);
					$result->text = str_replace("[/left]","</p>",$result->text);
					$result->text = str_replace("[/right]","</p>",$result->text);
					$result->text = str_replace("[/justify]","</p>",$result->text);
					for($i=30;$i>0;$i--){
						$result->text = str_replace("smile".$i,"<img src=ico/smiles/$i.gif>",$result->text);
					}
					
					
					echo "<div class=group>
						<table class=post  width=100% cellpadding=0 cellspacing=0>
						<tr><td width=160px height=150px><a href=user.php?id=".$result->userid.">";
						if($result->avatar==0)
						echo "<img src=\"images/avatar.png\" class=user-avatar>";
						else echo "<img src=\"images/avatars/$result->userid.jpg\" class=user-avatar>";
						
						echo "</a></td><td rowspan=3  valign=top><div class=msgnum>#".(($page-1)*$SETTINGS->ppp+$rn)."</div><div class=msg raw=\"$rawtext\">";
					echo $result->text;
					echo "</div></td></tr>
						<tr><td height=20px><i ".(($result->group==2)?" class='moder'":"").(($result->group==3)?" class='admin'":"")."><b><a href=user.php?id=".$result->userid.">".$result->name."</a></b></i></td></tr>
						<tr><td valign=top>Сообщений : <span class=msgs-col>".$result->msgs."</span>";
					if(time()-180<$result->last) echo "<br><span class=online>Онлайн</span>";
					if($user->group>1) echo "<br>IP: <i>$result->IP</i>";
					echo "</td></tr>
						<tr><td align=right colspan=2>Отправлено ".$result->date;
					if($result->edited!= "0000-00-00 00:00:00") echo "<br>Отредактировано ".$result->edited;
					echo " <hr>";
					if(($user->group>1)||($user->id==$result->userid))	echo "<button class='sbutton edit' postid=".$result->postid."><img src=/ico/rename.png title='Редактировать'></button>";
					if($user->group>1)echo "<button class='sbutton deletemsg' postid=".$result->postid."><img src=/ico/delete.png title='Удалить'></button>";
					if($user->group>0)echo "<button class='sbutton makequote' postid=".$result->postid."><img src=/ico/quote.png title='Цитировать'></button>";
					echo "</td></tr>
						</table>
						</div>";
				
			}
				if($closed) echo "<button class='sbutton closed'><img src=/ico/close.png></button> Тема закрыта<br><br>";
			?>
			<div class=pages>
				Переход на страницу : <?
				for($i=1;$i<$pages+1;$i++){
					if($i==$page) echo "<b>";
					echo "<a href=page.php?thread=$thread&id=$i>$i</a>";
					if($i==$page) echo "</b>";
				}
				
			?>
			</div>			
			<?
			
			if((($user->group>0)&&(!$closed))||($user->group>1))
				echo "<div id=sendpost class=group>
						<div id=tools>
							<center>
							<div class=ico alt=i><i>i</i></div>
							<div class=ico alt=u><u>u</u></div>
							<div class=ico alt=b><b>b</b></div>
							<div class=ico alt=s><s>s</s></div>
							<div class=ico alt=header><h3>H</h3></div>
							<div class=ico alt='red'><div class=color style='background:red'></div></div>
							<div class=ico alt='green'><div class=color style='background:green'></div></div>
							<div class=ico alt='blue'><div class=color style='background:blue'></div></div>
							<div class=ico alt='orange'><div class=color style='background:orange'></div></div>
							<div class=ico alt='center'><img src=ico/center.png height=20px width=20px></div>
							<div class=ico alt='left'><img src=ico/left.png height=20px width=20px></div>
							<div class=ico alt='right'><img src=ico/right.png height=20px width=20px></div>
							<div class=ico alt='justify'><img src=ico/justify.png height=20px width=20px></div>
							<div class='ico insertimage'><img src=ico/pic.png height=20px width=20px></div>
							<div class=ico alt='spoiler'><img src=ico/hide.png height=20px width=20px></div>
							<div class='ico link'><img src=ico/link.png height=20px width=20px></div>
							<div class='ico smiles'><img src=ico/smiles/16.gif></div>
							</center>
						</div><br><br>
						<center>
							<form>
								<div><textarea id=msgedit></textarea></div><br>
								<input type=submit class=fbutton value=Отправить>
							</form>
						</center>
				</div>"
			?>