<?
	include_once dirname(__FILE__)."/../core.php";
?>			

			<table width=100%>
				<tr>
					<td>
				<a href=index.php style='text-decoration:none'><center><img id=logoimg src="<? if($SETTINGS->logo!="") echo $SETTINGS->logo; else echo "/images/logo.png"; ?>" height=100px></center>
				<div id=fname><? echo $SETTINGS->fname; ?></div>
				</a>
					</td>
					<td>
			<div id=authinfo>
				<img src='ico/clock.png' height=12px> Время на сервере <? echo date("d.m.Y H:i"); ?>
				<br>
				<?
				
				if($logon)
					echo "<img src='ico/person.png' height=12px> Вы авторизованы как <i><a href=\"user.php?id=".$user->id."\"><b>".$user->name."</b></a></i>, группа <i".(($user->group==2)?" class='moder'":"").(($user->group==3)?" class='admin'":"").">".$GROUPS[$user->group]."</i>.<br>";
				
					$query = mysql_query("SELECT UNIX_TIMESTAMP(`active`) AS 'last' FROM `users` WHERE UNIX_TIMESTAMP(`active`) >=".(time()-180)) or die(mysql_error());
					$nr = mysql_num_rows($query);
					echo "<img src='ico/persons.png' height=12px> Сейчас на форуме пользователей : $nr.";
					if($user->group==3) echo "<br><img src='ico/adminpanel.png' height=12px> Вам доступна <u><a href=admin.php>панель администрирования</a></u>.";
					if($banned) echo $baninfo;
					if($user->group>-1){
						$query = mysql_query("SELECT * FROM `mails` WHERE `reciever` = $user->id AND `read` = 0");
						$new_mails = mysql_num_rows($query);
							if($new_mails > 0) echo "<br><img src='ico/blackmail.png' height=12px> <span class=blink>У вас <b>$new_mails</b> непрочитанных сообщений на <a href=mail.php>почте</a>.</span>";
					}
					if($SETTINGS->info!="") echo "<br><br><hr><br>$SETTINGS->info";
				?>
			<script>
				<? if($logon) if($new_mails > 0) echo "document.title = 'Новые сообщения на почте'"; else echo "document.title = '$SETTINGS->fname'";?>
			</script>
			</div>
			</td>
			<td align=right>
			<?
				if($SETTINGS->adv == "true") echo"<div id=adv3><img src='/adv/ad3.png'></div>";
			?>
			</td></tr>
			</table>
			<hr>
			

			