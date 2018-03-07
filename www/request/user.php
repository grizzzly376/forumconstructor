<? include_once dirname(__FILE__)."/../core.php"; ?>
			<div id=usearch class=group>
				<span>Поиск пользователей</span>
				<form id=searchform action=user.php><table width=200px cellpadding=5><tr><td><input name='name' class=finput></td><td width=72px><input class=fbutton type=submit value=Поиск></td></tr></table></form>
			</div>
			<div class=group>
			<?
				if($_GET['name']!=""){
					$query = mysql_query("SELECT * FROM `users` WHERE `name` = '".$_GET['name']."'");
					$nr = mysql_num_rows($query);
					if($nr>0){
						$uid = mysql_result($query,0,'id');
					}
				}
				if($_GET['id']!="")$uid = $_GET['id'];
				if($uid=="") $uid = -1;
				$query = mysql_query("SELECT *, `users`.`id` AS `userid`, (SELECT count(*) FROM `posts` WHERE `author` = `userid`) AS 'msgs' FROM `users` LEFT JOIN `posts` ON `posts`.`author` = `users`.`id` WHERE `users`.`id` = ".$uid) or die(mysql_error());
				$found_users = mysql_num_rows($query);
				$result = mysql_fetch_object($query); 
			
				if($found_users>0){
					echo "Информация о пользователе <b>$result->name</b> (ID:<b>$result->userid</b>)</b><br><br>";
					echo "<table class=post  width=100% cellpadding=0 cellspacing=0>";
					echo "<tr><td  valign=top width=160px height=150px><img src=";
					
					if($result->avatar==0) echo "\"images/avatar.png\"";
					else echo "\"images/avatars/$uid.jpg?" . time() . "\"";
					
					echo " class='user-avatar' uid=$uid>";
					
					if($uid == $user->id){
						echo"<form enctype=\"multipart/form-data\" id=uavatar-form><input type=hidden value=upload_avatar name=action><input  name=\"userfile\" type=file id=uavatar class=ufile accept=\"image/jpeg,image/png,image/gif\"></form>";
					}
					
					$raw = $result->info;
					$raw = str_replace("\"","&quot;",$raw);
					
					echo "</td>";
					echo "<td valign=top width=250px align=left>";
					echo "Группа :<br><i".(($result->group==2)?" class='moder'":"").(($result->group==3)?" class='admin'":"").">".$GROUPS[$result->group]."</i><br><br>";
					echo "Сообщений на форуме : <br><span class=\"msgs-col\">$result->msgs</span><br><br>";
					echo "Дата регистрации : <br>$result->reg_date<br><br>";
					echo "Последний раз был в сети :<br><i>$result->active</i><br><br>";
					if($user->group>1)echo "IP :<br><i>$result->IP</i><br><br>";
							
								$query = mysql_query("SELECT *, (SELECT `name` FROM `users` WHERE `id` = `bans`.`moderator`) AS 'mname' FROM `bans` WHERE `target`=".$result->userid." HAVING UNIX_TIMESTAMP(`expires`) > ".time()) or die(mysql_error());
								$bannr = mysql_num_rows($query);
								while($banlist = mysql_fetch_object($query)){
									echo "<br><br><font color=red>Забанен модератором <a href=user.php?id=".$banlist->moderator.">".$banlist->mname."</a> до <i>".$banlist->expires."</i> Причина: <i>".$banlist->comment."</i></font>";
								}
				
			   
					echo "</td><td  valign=top><div class=msg raw=\"$raw\">";

					$result->info = str_replace("\n","<br>",strip_tags($result->info));
					$result->info = str_replace("[i]","<i>",$result->info);
					$result->info = str_replace("[/i]","</i>",$result->info);
					$result->info = str_replace("[b]","<b>",$result->info);
					$result->info = str_replace("[/b]","</b>",$result->info);
					$result->info = str_replace("[s]","<s>",$result->info);
					$result->info = str_replace("[/s]","</s>",$result->info);
					$result->info = str_replace("[u]","<u>",$result->info);
					$result->info = str_replace("[/u]","</u>",$result->info);
					$result->info = str_replace("[orange]","<font color=orange>",$result->info);
					$result->info = str_replace("[/orange]","</font>",$result->info);
					$result->info = str_replace("[red]","<font color=red>",$result->info);
					$result->info = str_replace("[/red]","</font>",$result->info);
					$result->info = str_replace("[green]","<font color=green>",$result->info);
					$result->info = str_replace("[/green]","</font>",$result->info);
					$result->info = str_replace("[blue]","<font color=blue>",$result->info);
					$result->info = str_replace("[/blue]","</font>",$result->info);
					$result->info = str_replace("[header]","<h2>",$result->info);
					$result->info = str_replace("[/header]","</h2>",$result->info);
					$result->info = str_replace("[/color]","</font>",$result->info);
					$result->info = str_replace("[quote]","<div class=quote>",$result->info);
					$result->info = str_replace("[/quote]","</div>",$result->info);
					$result->info = str_replace("[url=\"","<a target=\"_blank\" href=\"",$result->info);
					$result->info = str_replace("\"]","\">",$result->info);
					$result->info = str_replace("[/url]","</a>",$result->info);
					$result->info = str_replace("[spoiler]","<br><span class=\"spoiler-toggle\" state=1><table><tr><td><img src=\"ico/show.png\"> </td><td valign=top>  Спойлер</td></tr></table></span><div class=spoiler>",$result->info);
					$result->info = str_replace("[/spoiler]","</div>",$result->info);
					$result->info = str_replace("[img]","<img class='inserted-img' src=",$result->info);
					$result->info = str_replace("[/img]",">",$result->info);
					$result->info = str_replace("[center]","<center>",$result->info);
					$result->info = str_replace("[/center]","</center>",$result->info);
					$result->info = str_replace("[left]","<p align=left>",$result->info);
					$result->info = str_replace("[right]","<p align=right>",$result->info);
					$result->info = str_replace("[justify]","<p align=justify>",$result->info);
					$result->info = str_replace("[/left]","</p>",$result->info);
					$result->info = str_replace("[/right]","</p>",$result->info);
					$result->info = str_replace("[/justify]","</p>",$result->info);
					for($i=30;$i>0;$i--){
						$result->info = str_replace("smile".$i,"<img src=ico/smiles/$i.gif>",$result->info);
					}
						echo $result->info; 
					
					echo "</div></td></tr></table>";
				
					
						if(($user->group>0))
								echo "<a href=mail.php?action=new&reciever=$result->name><button class='fbutton' uid=$uid>Сообщение</button></a>";
						if(($user->group>1))
								echo "<button class='fbutton userban' uid=$uid>Бан</button>";
						if(($user->group>1))
								echo "<button class='fbutton userrename' uid=$uid>Переименовать</button>";
						if(($bannr>0)&&($user->group>1))
								echo "<button class='fbutton clearban' uid=$uid>Снять бан</button>";
						if(($user->group>2)||($user->id == $uid))
								echo "<button class='fbutton editinfo' uid=$uid>Редактировать</button>";
						if(($user->group>2))
								echo "<button class='fbutton makeuser' uid=$uid>Пользователь</button>";
						if(($user->group>2))
								echo "<button class='fbutton makemoder' uid=$uid>Модератор</button>";
						if(($user->group>2))
								echo "<button class='fbutton makeadmin' uid=$uid>Администратор</button>";
						if(($user->group>2)||($user->id == $uid))
								echo "<button class='fbutton newpass' uid=$uid>Новый пароль</button>";
					}else{
						
					echo "Пользователь не найден!";
					
					}
												
				?>
			</div>
