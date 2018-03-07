<? include_once dirname(__FILE__)."/../core.php";
$forbidden = true;
 ?>
		<span id=adminpanel>
			<?
				$q_sections = mysql_query("SELECT * FROM `sections`");
				$q_forums = mysql_query("SELECT * FROM `forums`");
			?>
			<center>Скрипты и дизайн</center>
			
			<a href="admin.php"><button class=fbutton>Общие</button></a> <a href="custom.php"><button class=fbutton>Скрипты и дизайн</button></a> <a href="pagemanager.php"><button class=fbutton>Страницы</button></a> <a href="stat.php"><button class=fbutton>Статистика</button></a>
			
			<div class=group>
				<span>Настройки</span>
				<div class=group>
					<span>Информация</span>
					Картинка фона <br><input id=updtate_bgimage_val value='<? echo $SETTINGS->bgimage; ?>'>	<button id=updtate_bgimage_submit class=fbutton>Ок</button><br>
					Логотип <br><input id=updtate_logo_val value='<? echo $SETTINGS->logo; ?>'>	<button id=updtate_logo_submit class=fbutton>Ок</button><br>
					<p class=info>Оставьте эти поля пустыми, если хотите восстановить значения по умолчанию.</p><br>
				</div>
				<div class=group>
					<span>Исходный код</span>
					Внешний файл стилей <br><input id=updtate_styles_file_val value='<? echo $SETTINGS->styles_file; ?>'>	<button id=updtate_styles_file_submit class=fbutton>Ок</button><br>
					<p class=info>Укажите ссылку на файл или адресс в корневом каталоге.</p><br>
					Стили <br><textarea style='width:90%;height:400px;' id=updtate_styles_val><? echo $SETTINGS->styles; ?></textarea> <button id=updtate_styles_submit class=fbutton>Ок</button><br>
					Внешний файл скриптов <br><input id=updtate_scripts_file_val value='<? echo $SETTINGS->scripts_file; ?>'>	<button id=updtate_scripts_file_submit class=fbutton>Ок</button><br>
					<p class=info>Укажите ссылку на файл или адресс в корневом каталоге.</p><br>
					Скрипты <br><textarea style='width:90%;height:400px;' id=updtate_script_top_val><? echo $SETTINGS->st; ?></textarea> <button id=updtate_script_top_submit class=fbutton>Ок</button><br>
				</div>
			</div>
		</span>