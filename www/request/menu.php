<? include_once dirname(__FILE__)."/../core.php"; ?>
<div id=mwrap> 
					<!--<img class=background src=bg.png> -->
					<img class=background src=<? if($SETTINGS->bgimage!="") echo $SETTINGS->bgimage; else echo "/images/bg.png"; ?>>
					<table width=100% cellpadding=0 cellspacing=0><tr>
						<td valign=top align=left>
							<a href="index.php">Главная</a><a href="rules.php">Правила</a><?
							if(!$logon) echo "<a href=\"reg.php\">Регистрация</a>";
								else{
									if($new_mails  < 1)
										echo "<a href='mail.php'>Почта</a>";
									else
										echo "<a href='mail.php'>Почта <span class=newmails><img src=/ico/mail2.png height=14px></span></a>";	
									echo "<a href='logout.php' id='logout'>Выход</a>";
								}
							?>
						</td>
						<td></td>
						<?
							if(!$logon)
								echo "<td width=650px align=right><form class=login_form><nobr><span style='width:60px !important;display:inline-block'>Логин</span> <input class=lgn></nobr> <nobr><span style='width:60px !important;display:inline-block'>Пароль</span> <input class=password type=password></nobr> <input class=lgn_btn value='Вход' type=submit></form></td>"
						?>
					</tr></table>
				</div>