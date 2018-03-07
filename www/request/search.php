<? include_once dirname(__FILE__)."/../core.php"; ?>
			<div id=search class=group>
				<span>Поиск сообщений</span>
				<form id=searchform action=search.php><table width=100% cellpadding=5><tr><td><input name=content value='<? echo $_GET['content']; ?>' id=fsearch></td><td  width=72px><input class=fbutton type=submit value=Поиск></td></tr></table></form>
			</div>		
		<?

			
				$page = $_GET['page'];
				$content = $_GET['content'];
				
				$query = mysql_query("SELECT * FROM `posts` LEFT JOIN `users` ON `posts`.`author` = `users`.`id` LEFT JOIN `threads` ON `posts`.`thread` = `threads`.`id` WHERE (`posts`.`text` LIKE '%$content%') OR (`users`.`name` LIKE '%$content%') OR (`threads`.`name` LIKE '%$content%')") or die(mysql_error());
				
				$nr = mysql_num_rows($query);
				
			echo "Найдено <b>$nr</b> сообщений<br><br>";
				
				$pages = intval($nr / $SETTINGS->ppp);
				if($nr % $SETTINGS->ppp !=0) $pages++;
				
				if($page==0) $page=1;
				$begin = ($page-1)*$SETTINGS->ppp;
				
			echo "<div class=pages>";
			echo "Переход на страницу : ";				
				for($i=1;$i<$pages+1;$i++){
					if($i==$page) echo "<b>";
					echo "<a href=search.php?page=$i&content=$content>$i</a>";
					if($i==$page) echo "</b>";
				}
				
			?>
			</div>
			
			<?
			
			
			
			$query = mysql_query("SELECT *, UNIX_TIMESTAMP(`users`.`active`) AS 'last', `users`.`name` AS 'name', `users`.`id` AS `userid`, `posts`.`id` AS `postid`, (SELECT count(*) FROM `posts` WHERE `author` = `userid`) AS 'msgs' FROM `posts` LEFT JOIN `users` ON `posts`.`author` = `users`.`id`  LEFT JOIN `threads` ON `posts`.`thread` = `threads`.`id` WHERE `text` LIKE '%".$content."%' OR `users`.`name` LIKE '%".$content."%'  OR (`threads`.`name` LIKE '%$content%') ORDER BY `posts`.`id` DESC LIMIT $begin, ".$SETTINGS->ppp) or die(mysql_error());
			while($result = mysql_fetch_object($query)){
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
					
					
					echo "<div class=group>";
					$query2 = mysql_query("SELECT *, `forums`.`name` as 'fname', `threads`.`name` AS 'tname', `forums`.`id` AS 'fid', `threads`.`id` AS 'tid' FROM `threads` LEFT JOIN `forums` ON `threads`.`forum` = `forums`.`id` WHERE `threads`.`id` = '$result->thread' LIMIT 1") or die(mysql_error());
					$result2 = mysql_fetch_object($query2);
					echo "<a href=threads.php?forum=".$result2->fid.">".$result2->fname."</a> > <a href=page.php?thread=$result2->tid><i>".$result2->tname."</i></a><br><br><hr><br>";
					
					echo "<table class=post  width=100% cellpadding=0 cellspacing=0>
						<tr><td valign=top width=160px height=150px><a href=user.php?id=".$result->userid.">";
						if($result->avatar==0)
						echo "<img src=\"images/avatar.png\" class=user-avatar>";
						else echo "<img src=\"images/avatars/$result->userid.jpg\" class=user-avatar>";
						echo "</a></td><td rowspan=3  valign=top><div class=msg>";
					echo $result->text;
					echo "</div></td></tr>
						<tr><td height=20px><i ".(($result->group==2)?" class='moder'":"").(($result->group==3)?" class='admin'":"")."><b><a href=user.php?id=".$result->userid.">".$result->name."</a></b></i></td></tr>
						<tr><td valign=top>Сообщений : <span class=\"msgs-col\">".$result->msgs."</span>";
					if(time()-180<$result->last) echo "<br><span class=online>Онлайн</span>";
					echo "</td></tr>
						<tr><td align=right colspan=2>Отправлено ".$result->date;
					if($result->edited!= "0000-00-00 00:00:00") echo "<br>Отредактировано ".$result->edited;
					echo " <hr>";
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
					echo "<a href=search.php?page=$i&content=$content>$i</a>";
					if($i==$page) echo "</b>";
				}
				
			?>
			</div>			
