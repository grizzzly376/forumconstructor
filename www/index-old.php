<?
	include 'top.php';
?>
		
		<div id=main>
			<?
					/*$query = mysql_query("SELECT * FROM `users`");
					$result = array();
					while($responce = mysql_fetch_array($query, MYSQL_ASSOC))
							array_push($result, $responce);
					echo json_encode($result);*/
					
						$query = mysql_query("SELECT * FROM `sections` ORDER BY `priority` ASC") or die(mysql_error());
						$result = array();
						while($responce = mysql_fetch_object($query)){
							array_push($result, $responce);		
						}

						foreach($result AS $key => $value){
							$value->forums = array();
							$query = mysql_query("SELECT * FROM `forums` WHERE `section` = ".$value->id." ORDER BY `priority` ASC") or die(mysql_error());
							while($responce = mysql_fetch_object($query)){
								$query2 = mysql_query("SELECT count(*) AS 'count' FROM `threads` WHERE `forum` = '".$responce->id."'");
								$responce2 = mysql_fetch_object($query2);
								$query3 = mysql_query("SELECT count(*) AS 'count' FROM `posts` LEFT JOIN `threads` on `posts`.`thread` = `threads`.`id` WHERE `threads`.`forum` = '".$responce->id."'");
								if($query3)$responce3 = mysql_fetch_object($query3);
								$query4 = mysql_query("SELECT *, `users`.`name` as 'uname', `users`.`id` AS 'uid', `threads`.`name` AS 'tname', `threads`.`id` AS 'tid' FROM `posts` LEFT JOIN `users` ON `users`.`id` = `posts`.`author` LEFT JOIN `threads` ON `threads`.`id` = `posts`.`thread` LEFT JOIN `forums` ON `forums`.`id` = `threads`.`forum` WHERE `threads`.`forum` = '".$responce->id."' ORDER BY `posts`.`id` DESC");
								if($query4)$responce4 = mysql_fetch_object($query4);
								$forum = (object)array();
								$forum->name = $responce->name;
								$forum->info = $responce->info;
								$forum->lastuname = $responce4->uname;
								$forum->lastuid = $responce4->uid;
								$forum->lasttname = $responce4->tname;
								$forum->lasttid = $responce4->tid;
								$forum->date = $responce4->date;
								$forum->id = $responce->id;
								if($responce2->count)
									$forum->threads = $responce2->count;
								else
									$forum->threads = 0;
								if($responce3->count)
									$forum->posts = $responce3->count;
								else
									$forum->posts = 0;
								$value->forums[] = $forum;
							}
						}

						foreach($result AS $key => $value){
							echo "<div class=group>\n";
							echo "<span>".$value->name."</span>";
							foreach($value->forums AS $fkey => $fvalue){
								echo "<div class=thread><span><a class=tname href=threads.php?forum=".$fvalue->id.">".$fvalue->name."</a></span><br><span class=finfo>".$fvalue->info."</span><hr>\n";
									echo "<table width=100%><tr><td>";
									
								if($fvalue->lasttid!=""){
									echo "Последнее сообщение в теме <i><a href=page.php?thread=".$fvalue->lasttid.">".$fvalue->lasttname."</a></i> ".$fvalue->date." от пользователя <b>";
									echo "<a href=user.php?id=".$fvalue->lastuid."><i>".$fvalue->lastuname."</i></a></b>";
								}else{
									echo "Сообщений пока нет";
									
								}	
									echo"</td><td align=center width=120px>Тем<br><i>".$fvalue->threads."</i></td><td align=center width=120px>Сообщений<br><i>".$fvalue->posts."</i></td></tr></table>";
								echo "</div>";
							}
							echo "</div>\n";
						}
						
								
			?>
			<div id=search class=group>
				<span>Поиск сообщений</span>
				<form id=searchform action=search.php><table width=100% cellpadding=5><tr><td><input name=content id=fsearch></td><td width=72px><input class=fbutton type=submit value=Поиск></td></tr></table></form>
			</div>
		</div>
		
		<div id=bottom>
			<div class=group>
				<span>Сейчас на форуме:</span>
				<?
					$query = mysql_query("SELECT *, UNIX_TIMESTAMP(`active`) AS 'last' FROM `users` WHERE UNIX_TIMESTAMP(`active`) >=".(time()-180)) or die(mysql_error());
					$result = array();
					while($responce = mysql_fetch_object($query)){
						array_push($result, $responce);		
					}
					foreach($result AS $key=>$value){
						echo "<i ".(($value->group==2)?" class='moder'":"").(($value->group==3)?" class='admin'":"")."><b><a href=user.php?id=".$value->id.">".$value->name."</a></b></i> ";
					}
				?>
			</div>
		</div>
		
		<?
		if($SETTINGS->adv == "true") echo"<center>
		<table id=ad>
		<tr align=center><td>
			<div id=ad1>
				<img src=/adv/ad1.png>
			</div>
		</td>
		<td width=100px> </td>
		<td>
			<div id=ad2>
				<img src=/adv/ad2.png>
			</div>
		</td></tr></table>
		</center>";
		
		mysql_close($connect);
		?>
	</body>
</html>