<? include_once dirname(__FILE__)."/../core.php"; ?>
			<div class=group>
				<span>Почта</span>
				<table width=100%>
					<tr>
						<td width=160px valign=top>
							<div class=group>
								<?								
									$action = $_GET['action'];
									if($action=="") $action="inbox";
									
									if($action=="new") echo "<b>"; echo "<a href=mail.php?action=new>Отправить</a><br>"; if($action=="new") echo "</b>";
									if($action=="inbox") echo "<b>"; echo "<a href=mail.php?action=inbox>Входящие ".(($new_mails>0)?("(".$new_mails.")"):"")."</a><br>"; if($action=="inbox") echo "</b>";
									if($action=="outbox") echo "<b>"; echo "<a href=mail.php?action=outbox>Исходящие</a><br>"; if($action=="outbox") echo "</b>";
									if($action=="fav") echo "<b>"; echo "<a href=mail.php?action=fav>Избранное</a><br>"; if($action=="fav") echo "</b>";
								?>
							</div>
						</td>
						<td>
							<div class=group>
								
								<?
	
									if($action=="inbox"){
										
										$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`sender`) AS 'sname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `reciever` = '".$user->id."'") or die(mysql_error());
										
										$nr = mysql_num_rows($query);
										
										$mpage = $_GET['page'];
										
										$pages = intval($nr / $SETTINGS->mpp);
										if($nr % $SETTINGS->mpp !=0) $pages++;
										
										if($mpage==0) $mpage=1;
										$begin = ($mpage-1)*$SETTINGS->mpp;
										
										if($nr > 0){
											echo "Переход на страницу : ";
											for($i=1;$i<$pages+1;$i++){
												if($i==$mpage) echo "<b>";
												echo "<a href=mail.php?page=$i>$i</a> ";
												if($i==$mpage) echo "</b>";
											}
											
											$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`sender`) AS 'sname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `reciever` = '".$user->id."' ORDER BY `mails`.`id` DESC LIMIT $begin, $SETTINGS->mpp") or die(mysql_error());
											echo "<br><br><table width=100% id=mail cellspacing=0>";
											echo "	<tr><th></th><th>Тема</th><th width=100px>Дата</th><th width=100px>Отправитель</th></tr>";
											
											while($result = mysql_fetch_object($query)){
												echo "<tr align=center ".(($result->read == 0)?"class=newmail":"")." ".(($result->fav == 1)?"class=fav":"")."><td align=left>";
												echo "<button class='sbutton ".(($result->fav == 0)?"makefav":"makenotfav")."' mailid='".$result->mid."'><img src=/ico/favourite.png title='В избранное'></button></td>";
												echo "<td align=left><a href=mail.php?action=read&id=$result->mid>$result->title</a></td><td>$result->date</td><td><b><a href=user.php?id=$result->sender>$result->sname</a></b></td></tr>";
											}

											echo "	<tr><th width=30px></th><th>Тема</th><th>Дата</th><th>Отправитель</th></tr>";
											echo "</table>";
										}else{
											echo "Входящих сообщений нет";
											
										}
									}
								if($action=="outbox"){
										
										$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`reciever`) AS 'rname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `sender` = '".$user->id."'") or die(mysql_error());
										
										$nr = mysql_num_rows($query);
										
										$mpage = $_GET['page'];
										
										$pages = intval($nr / $SETTINGS->mpp);
										if($nr % $SETTINGS->mpp !=0) $pages++;
										
										if($mpage==0) $mpage=1;
										$begin = ($mpage-1)*$SETTINGS->mpp;
										
										if($nr>0){
											echo "Переход на страницу : ";
											for($i=1;$i<$pages+1;$i++){
												if($i==$mpage) echo "<b>";
												echo "<a href=mail.php?page=$i>$i</a> ";
												if($i==$mpage) echo "</b>";
											}
											
											$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`reciever`) AS 'rname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `sender` = '".$user->id."' ORDER BY `mails`.`id` DESC LIMIT $begin, $SETTINGS->mpp") or die(mysql_error());
										
											echo "<br><br><table width=100% id=omail cellspacing=0>";
											echo "	<tr><th>Тема</th><th width=100px>Дата</th><th width=100px>Получатель</th></tr>";
											
											while($result = mysql_fetch_object($query)){
												echo "	<tr align=center ".(($result->read == 0)?"class=newmail":"")."><td align=left><a href=mail.php?action=read&id=$result->mid>$result->title</a></td><td>$result->date</td><td><b><a href=user.php?id=$result->sender>$result->rname</a></b></td></tr>";
											}

											echo "	<tr><th>Тема</th><th>Дата</th><th>Получатель</th></tr>";
											echo "</table>";
										}else{
											echo "Исходящих сообщений нет";
										}
									}
									if($action=="fav"){
										
										$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`sender`) AS 'sname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `reciever` = '".$user->id."' AND `fav` = 1") or die(mysql_error());
										
										$nr = mysql_num_rows($query);
										
										$mpage = $_GET['page'];
										
										$pages = intval($nr / $SETTINGS->mpp);
										if($nr % $SETTINGS->mpp !=0) $pages++;
										
										if($mpage==0) $mpage=1;
										$begin = ($mpage-1)*$SETTINGS->mpp;
										
										if($nr > 0){
											echo "Переход на страницу : ";
											for($i=1;$i<$pages+1;$i++){
												if($i==$mpage) echo "<b>";
												echo "<a href=mail.php?page=$i>$i</a>";
												if($i==$mpage) echo "</b>";
											}
											
											$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`sender`) AS 'sname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `reciever` = '".$user->id."' AND `fav` = 1 ORDER BY `mails`.`id` DESC LIMIT $begin, $SETTINGS->mpp") or die(mysql_error());
											echo "<br><br><table width=100% id=mail cellspacing=0>";
											echo "	<tr><th></th><th>Тема</th><th width=100px>Дата</th><th width=100px>Отправитель</th></tr>";
											
											while($result = mysql_fetch_object($query)){
												echo "<tr align=center ".(($result->read == 0)?"class=newmail":"")." ".(($result->fav == 1)?"class=fav":"")."><td align=left>";
												echo "<button class='sbutton ".(($result->fav == 0)?"makefav":"makenotfav")."' mailid='".$result->mid."'><img src=/ico/favourite.png title='В избранное'></button></td>";
												echo "<td align=left><a href=mail.php?action=read&id=$result->mid>$result->title</a></td><td>$result->date</td><td><b><a href=user.php?id=$result->sender>$result->sname</a></b></td></tr>";
											}

											echo "	<tr><th width=30px></th><th>Тема</th><th>Дата</th><th>Отправитель</th></tr>";
											echo "</table>";
										}else{
											echo "Избранных сообщений нет";
											
										}
									}
									if($action=="read"){
										$mailid = $_GET['id'];
										$query = mysql_query("SELECT *, `mails`.`id` AS 'mid', (SELECT `name` FROM `users` WHERE `id` = `mails`.`reciever`) AS 'rname', (SELECT `name` FROM `users` WHERE `id` = `mails`.`sender`) AS 'sname' FROM `mails` LEFT JOIN `users` ON `users`.`id` = `mails`.`reciever` WHERE `mails`.`id` = '".$mailid."'") or die(mysql_error());
										$result = mysql_fetch_object($query);
										if(($user->group>2)||($user->id==$result->sender)||($user->id==$result->reciever)){
											if($user->id==$result->reciever){
												mysql_query("UPDATE `mails` SET `read` = 1 WHERE `id`= ".$mailid);
											}
											echo "Тема : <i>$result->title</i><br>";
											echo "Отправитель : <a href=user.php?id=$result->sender><b><i>$result->sname</i></b></a><br>";
											echo "Получатель : <a href=user.php?id=$result->reciever><b><i>$result->rname</i></b></a><br>";
											echo "<br><hr><br>$result->text";
											echo "<br><br><hr>";
											if($user->id==$result->reciever)
													echo "<br><a href=\"mail.php?action=new&title=$result->title&reciever=$result->sname\"><button class=fbutton>Ответить</button></a>";
										}
									}
									if($action=="new"){
										if($_GET['title']!="") $_GET['title']="Re: ".$_GET['title'];
										echo "
												<form id=nmail>
												Кому : <input class=ninput id=mrec value='".$_GET['reciever']."'><br>
												Тема : <input class=ninput id=mtitle value='".$_GET['title']."'><br>
													<form>
													Сообщение :<br>
														<div><textarea class=ninput id=mtext></textarea></div><br>
														<input type=submit class=fbutton value=Отправить>
													</form>
												</form>
												";
									}
								?>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>			
