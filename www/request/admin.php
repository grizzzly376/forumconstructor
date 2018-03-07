<? include_once dirname(__FILE__)."/../core.php"; ?>
		<span id=adminpanel>
			<?
				$q_sections = mysql_query("SELECT * FROM `sections`");
				$q_forums = mysql_query("SELECT * FROM `forums`");
			?>
			<center>Панель администрирования</center>
			
			<a href="admin.php"><button class=fbutton>Общие</button></a> <a href="custom.php"><button class=fbutton>Скрипты и дизайн</button></a> <a href="pagemanager.php"><button class=fbutton>Страницы</button></a> <a href="stat.php"><button class=fbutton>Статистика</button></a>
			
			<div class=group >
				<span>Разделы и форумы</span>	
				<div class=group>
				<span>Создание</span>
					Новый раздел <br><input id=new_s_name>	<button class=fbutton id=new_s_submit>Ок</button><br>
					Новый форум<br> в разделе <select id=new_f_section><?
					$q_sections = mysql_query("SELECT * FROM `sections`");
					while($result = mysql_fetch_object($q_sections)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> Название: <input id=new_f_name>	Описание	: <input id=new_f_info> <button class=fbutton id=new_f_submit>Ок</button><br>
				</div>
				<div class=group>
				<span>Переименование</span>
				Переименовать форум <br><select id=rename_f_id><?
					$q_forums = mysql_query("SELECT * FROM `forums`");
					while($result = mysql_fetch_object($q_forums)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <input id=rename_f_name>	<button class=fbutton id=rename_f_submit>Ок</button><br>
				Переименовать раздел <br><select id=rename_s_id><?
					$q_sections = mysql_query("SELECT * FROM `sections`");
					while($result = mysql_fetch_object($q_sections)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <input id=rename_s_name> <button class=fbutton id=rename_s_submit>Ок</button><br>
				</div>
				<div class=group>
				<span>Разделы</span>
				Изменить приоритет <br><select id=change_s_priority_id><?
					$q_sections = mysql_query("SELECT * FROM `sections`");
					while($result = mysql_fetch_object($q_sections)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <input id=change_s_priority_val value="<? echo mysql_result($q_sections,0,'priority') or die(mysql_error());?>"> <button class=fbutton id=change_s_priority_submit>Ок</button><br>
				</div>
				<div class=group>
				<span>Форумы</span>
				Изменить информацию о форуме <br><select id=change_fi_id><?
					$q_forums = mysql_query("SELECT * FROM `forums`");
					while($result = mysql_fetch_object($q_forums)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <input id=change_fi_info>	<button class=fbutton id=change_fi_submit>Ок</button><br>
				Переместить форум <br><select id=move_f_id><?
					$q_forums = mysql_query("SELECT * FROM `forums`");
					while($result = mysql_fetch_object($q_forums)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> в раздел <select id=move_f_section><?
					$q_sections = mysql_query("SELECT * FROM `sections`");
					while($result = mysql_fetch_object($q_sections)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select>	<button class=fbutton id=move_f_submit>Ок</button><br>
				Изменить приоритет <br><select id=change_f_priority_id><?
					$q_forums = mysql_query("SELECT * FROM `forums`");
					while($result = mysql_fetch_object($q_forums)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <input id=change_f_priority_val value="<? echo mysql_result($q_forums,0,'priority') or die(mysql_error());?>"> <button class=fbutton id=change_f_priority_submit>Ок</button><br>
				</div>
				<div class=group>
				<span>Удаление</span>
				Удалить форум <br><select id=delete_f_id><?
					$q_forums = mysql_query("SELECT * FROM `forums`");
					while($result = mysql_fetch_object($q_forums)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <button class=fbutton id=delete_f_submit>Ок</button><br>
				<p class=info>Удалять можно только пустые форумы</p><br>
				Удалить раздел <br><select id=delete_s_id><?
					$q_sections = mysql_query("SELECT * FROM `sections`");
					while($result = mysql_fetch_object($q_sections)){
						echo "<option value=".$result->id.">".$result->name;
					}
				?></select> <button class=fbutton id=delete_s_submit>Ок</button><br>
				<p class=info>Удалять можно только пустые разделы</p><br>
				</div>
			</div>
			<div class=group>
				<span>Настройки</span>
				<div class=group>
					<span>Разбиение</span>
					Тем на странице <br><input id=updtate_tpp_val value=<? echo $SETTINGS->tpp; ?>>	<button id=updtate_tpp_submit class=fbutton>Ок</button><br>
					Сообщений на странице <br><input id=updtate_ppp_val value=<? echo $SETTINGS->ppp; ?>> <button class=fbutton id=updtate_ppp_submit>Ок</button><br>
					Письм на странице <br><input id=updtate_mpp_val value=<? echo $SETTINGS->mpp; ?>> <button class=fbutton id=updtate_mpp_submit>Ок</button><br>
				</div>
				<div class=group>
					<span>Информация</span>
					Название <br><input id=updtate_fname_val value='<? echo $SETTINGS->fname; ?>'>	<button id=updtate_fname_submit class=fbutton>Ок</button><br>
					Объявление <br><textarea id=updtate_info_val><? echo $SETTINGS->info; ?></textarea>	<button id=updtate_info_submit class=fbutton>Ок</button><br>
					Реклама <br><select id=updtate_adv_val><option value=true <? echo ($SETTINGS->adv=="true")?"selected":"";?>>Включена <option value=false <? echo ($SETTINGS->adv=="false")?"selected":"";?>>Отключена </select>	<button id=updtate_adv_submit class=fbutton>Ок</button><br>
				</div>
			</div>
		</span>