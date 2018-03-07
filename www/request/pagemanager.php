<? include_once dirname(__FILE__)."/../core.php"; ?>
<span id=adminpanel>
			<?
				$q_sections = mysql_query("SELECT * FROM `sections`");
				$q_forums = mysql_query("SELECT * FROM `forums`");
			?>
			<center>Создание страниц</center>
			
			<a href="admin.php"><button class=fbutton>Общие</button></a> <a href="custom.php"><button class=fbutton>Скрипты и дизайн</button></a> <a href="pagemanager.php"><button class=fbutton>Страницы</button></a> <a href="stat.php"><button class=fbutton>Статистика</button></a>
	
			<div class=group>
				<span>Работа со страницами</span>
				<div class=group>
				<span>Создать новую страницу</span>
				Имя файла <br><input id=new_page_name><br>
				<p class=info>Укажите имя файла с расширением, к примеру <i>mypage.php</i><br>
				Страницы по умолчанию создаются с шапкой форума.
				</p><br>
				Код страницы <textarea id=new_page_code style='width:90%;height:400px;'></textarea><br>
				<button class=fbutton id=new_page_submit>Ок</button><br>
				<p class=info>Вы можете использовать здесь PHP, MySQL и HTML.</p><br>
				</div>
				<div class=group>
					<span>Изменить страницу</span>
					Выбрать страницу <br><select id=change_page_id>
					<option value="empty" selected> 
					<?
						$q_forums = mysql_query("SELECT * FROM `pages`");
						while($result = mysql_fetch_object($q_forums)){
							echo "<option value=".$result->id.">".$result->source;
						}
					?></select>
					<p class=info>Вы можете использовать здесь PHP, MySQL и HTML.</p><br>
				</div>
				<div class=group>
					<span>Удалить страницу</span>
					Выбрать страницу <br><select id=delete_page_id>
					<?
						$q_forums = mysql_query("SELECT * FROM `pages`");
						while($result = mysql_fetch_object($q_forums)){
							echo "<option value=".$result->id.">".$result->source;
						}
					?></select> <button class=fbutton id=delete_page_submit>Ок</button>
					<p class=info>Удалённую страницу нельзя восстановить.</p><br>
				</div>
			</div>


		</span>