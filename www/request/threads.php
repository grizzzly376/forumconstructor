		<?
			include_once dirname(__FILE__)."/../core.php";
			$forum = $_GET['forum'];
			$query = mysql_query("SELECT *, `sections`.`name` AS 'sname', `forums`.`name` AS 'fname' FROM `forums` LEFT JOIN `sections` ON `forums`.`section` = `sections`.`id` WHERE `forums`.`id` = '$forum' LIMIT 1") or die(mysql_error());
			$result = mysql_fetch_object($query);
			echo "<a href=index.php>$SETTINGS->fname</a> > ".$result->sname." > <i>".$result->fname."</i><br><br><hr><br>";
		?>
			<?
				if($user->group>0) echo "<a href=newthread.php?forum=$forum><button class=fbutton>Новая тема</button></a><br><br>";
			?>
			
				<?
					$query = mysql_query("SELECT * FROM `threads` WHERE `forum` = $forum");
					$nr = mysql_num_rows($query);
					if($nr>0) $notempty = true;
						$tpage = $_GET['page'];
						
						$pages = intval($nr / $SETTINGS->tpp);
						if($nr % $SETTINGS->tpp !=0) $pages++;
						
						if($tpage==0) $tpage=1;
						$begin = ($tpage-1)*$SETTINGS->tpp;
					if($notempty){
						echo "<div class=pages>";
						echo "Переход на страницу : ";
						for($i=1;$i<$pages+1;$i++){
							if($i==$tpage) echo "<b>";
							echo "<a href=threads.php?forum=$forum&page=$i>$i</a>";
							if($i==$tpage) echo "</b>";
						}
						echo "</div>";
					}
				?>
			
			<br>

			<table id=topics width=100% cellpadding=0 cellspacing=0>
				
				<?
					
					$query = mysql_query("(SELECT *, `threads`.`id` AS `tid`, `threads`.`name` AS 'tname', `threads`.`created` AS 'created', `threads`.`visits` AS 'visits', `users`.`name` AS 'username',`users`.`id` AS 'userid', (SELECT `date` FROM `posts` WHERE `thread`=`tid` ORDER BY `id` DESC LIMIT 1) AS 'lastd' FROM `threads` LEFT JOIN `users` ON `threads`.`author` = `users`.`id` WHERE `threads`.`forum` = '$forum' ORDER BY `important` DESC , `lastd` DESC)  LIMIT $begin, 20") or die(mysql_error());
					if(mysql_num_rows($query)>0)
							echo "<tr><th>Тема</th><th width=\"100px\" colspan=2>Тема создана</th><th colspan=2>Последнее сообщение</th><th width=\"30px\">Ответы</th><th width=\"30px\">Смотрели</th></tr>";
					else
							echo "Пока не создано ни одной темы";
					while($result = mysql_fetch_object($query)){
						$query2 = mysql_query("SELECT `posts`.`id` AS 'postid', `users`.`id` AS 'userid', `users`.`name` AS 'username', `posts`.`date` AS 'date' FROM `posts` LEFT JOIN `users` ON `posts`.`author` = `users`.`id` WHERE `posts`.`thread` = '$result->tid' ORDER BY `posts`.`id` DESC") or die(mysql_error());
						$result2 = mysql_fetch_object($query2);
						$result2_nr = mysql_num_rows($query2);
						$ipages = intval($result2_nr / $SETTINGS->ppp);
						if($result2_nr % $SETTINGS->ppp !=0) $ipages++;
						echo "<tr align=center><td align=left>";
						if($user->group>1) echo "<button class='sbutton deletethread' thread=".$result->tid."><img src=/ico/delete.png title=Удалить></button>";
						if($user->group>1) echo "<button class='sbutton toggleimp' thread=".$result->tid." val=".(($result->important+1)%2)."><img src=/ico/important.png title=Важная></button>";
						if(($user->group>1)&&($result->closed==0)) echo "<button class='sbutton closer' thread=".$result->tid."><img src=/ico/close.png title=Закрыть></button>";
						if(($user->group>1)&&($result->closed==1)) echo "<button class='sbutton opener' thread=".$result->tid."><img src=/ico/open.png title=Открыть></button>";
						if($user->group>1) echo "<button class='sbutton renamet' thread=".$result->tid."><img src=/ico/rename.png title=Переименовать></button>";
						if($user->group>1) echo "<button class='sbutton tmove' thread=".$result->tid."><img src=/ico/move.png title=Переестить></button>";
						if($result->question!="") echo "<button class='sbutton'><img src=/ico/q.png></button>";
						if($result->closed==1) echo "<button class='sbutton closed'><img src=/ico/close.png></button>";
						if($result->important==1) echo "<button class='sbutton important'><img src=/ico/important.png title=Важная></button>";
						echo "<a href=page.php?thread=".$result->tid.">".$result->tname."</a> <i>Страницы:</i> <span class=pages><a href=page.php?thread=".$result->tid."&id=1>1</a>";
						if($ipages>1) echo "... <a href=page.php?thread=".$result->tid."&id=$ipages>$ipages</a>";
						echo "</span></td><td  width='75px'>".$result->created."</td><td width='80px'><b><a href=user.php?id=".$result->userid."><i>".$result->username."</i></a></b></td><td width='75px'>".$result2->date."</td><td width='80px'><b><a href=user.php?id=".$result2->userid."><i>".$result2->username."</i></a></b></td><td>".$result2_nr."</td><td>".$result->visits."</td></tr>";	
					}
					if(mysql_num_rows($query)>0)
						echo "<tr><th>Тема</th><th colspan=2>Тема создана</th><th colspan=2>Последнее сообщение</th><th>Ответы</th><th>Смотрели</th></tr>";
				?>
				
				
				
			</table>
			<br>

			<div class=pages>
				<?
				if($notempty){
					echo "Переход на страницу :";				
					for($i=1;$i<$pages+1;$i++){
						if($i==$tpage) echo "<b>";
						echo "<a href=threads.php?forum=$forum&page=$i>$i</a>";
						if($i==$tpage) echo "</b>";
					}
				}
				?>
			</div><br>	
			<?
				if(($user->group>0)&&($notempty)) echo "<a href=newthread.php?forum=$forum><button class=fbutton>Новая тема</button></a><br><br>";
			?>
			<div id=search class=group>
				<span>Поиск сообщений</span>
				<form id=searchform action=search.php><table width=100% cellpadding=5><tr><td><input name=content id=fsearch></td><td width=72px><input class=fbutton type=submit value=Поиск></td></tr></table></form>
			</div>	