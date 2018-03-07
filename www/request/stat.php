<?
include_once dirname(__FILE__)."/../core.php";
?>
		<span id=adminpanel>
			<?
				$q_sections = mysql_query("SELECT * FROM `sections`");
				$q_forums = mysql_query("SELECT * FROM `forums`");
			?>
			<center>Статистика</center>
			
			<a href="admin.php"><button class=fbutton>Общие</button></a> <a href="custom.php"><button class=fbutton>Скрипты и дизайн</button></a> <a href="pagemanager.php"><button class=fbutton>Страницы</button></a> <a href="stat.php"><button class=fbutton>Статистика</button></a>
			
			<div class=group>
				<span>Статистика</span>
				<div class=group>
					<span>Общая</span>
					<br>
					<?
					$query = mysql_query("SELECT * FROM `users` WHERE 1");
					echo "Всего пользователей зарегистрировано : <h4>".mysql_num_rows($query)."</h4><br>";
					$query = mysql_query("SELECT * FROM `threads` WHERE 1");
					echo "Всего тем : <h4>".mysql_num_rows($query)."</h4><br>";
					$query = mysql_query("SELECT * FROM `posts` WHERE 1");
					echo "Всего сообщений : <h4>".mysql_num_rows($query)."</h4><br>";
					$query4 = mysql_query("SELECT * FROM `bans` WHERE UNIX_TIMESTAMP(`expires`) > ".(time()));
					echo "Активно банов : <h4>".mysql_num_rows($query4)."</h4><br>";
					?>
				</div>
				<div class=group>
					<span>Календарь</span>
					<br>
					Активность
					<div id='chart_div'></div>
					<hr><br><br>
					Новые пользователи
					<div id='reg_div'></div>
				</div>
				<div class=group>
					<span>Посещения</span>
					<?
						$query = mysql_query("SELECT UNIX_TIMESTAMP(`active`) AS 'last' FROM `users` WHERE UNIX_TIMESTAMP(`active`) >=".(time()-60*60*24)) or die(mysql_error());
						$query2 = mysql_query("SELECT * FROM `posts` WHERE UNIX_TIMESTAMP(`date`) > ".(time() - 60*60*24));
						$query3 = mysql_query("SELECT * FROM `threads` WHERE UNIX_TIMESTAMP(`created`) > ".(time() - 60*60*24));
						$nr = mysql_num_rows($query);
						$posts = mysql_num_rows($query2);
						$threads = mysql_num_rows($query3);
						echo "Уникальных пользователей за 24 часа : <h4>".$nr."</h4><br>";
						$query = mysql_query("SELECT * FROM `stat` WHERE `day` = CURRENT_DATE()") or die(mysql_error());
						$result = mysql_fetch_object($query);
						echo "Активность пользователей сегодня : <h4>".$result->amount."</h4><br>";
						echo "Зарегистрировано пользователей сегодня : <h4>".$result->reg."</h4><br>";
						echo "Создано тем за 24 часа : <h4>".$threads."</h4><br>";
						$query3 = mysql_query("SELECT * FROM `threads` WHERE UNIX_TIMESTAMP(`created`) > ".(time() - 60*60*24*30));
						echo "Создано тем за месяц : <h4>".mysql_num_rows($query3)."</h4><br>";
						echo "Добавлено сообщений за 24 часа : <h4>".$posts."</h4><br>";
						$query2 = mysql_query("SELECT * FROM `posts` WHERE UNIX_TIMESTAMP(`date`) > ".(time() - 60*60*24));
						echo "Добавлено сообщений за месяц : <h4>".mysql_num_rows($query2)."</h4><br>";
					?>
				</div>
				<script>
				      google.charts.load('current', {'packages':['calendar']});
      google.charts.setOnLoadCallback(drawChart);
	  google.charts.setOnLoadCallback(drawReg);
      function drawChart() {
        var data = new google.visualization.DataTable();
       data.addColumn({ type: 'date', id: 'Date' });
       data.addColumn({ type: 'number', id: 'Активность' });

        data.addRows([
		<?
			$query = mysql_query("SELECT *, UNIX_TIMESTAMP(`day`)*1000 AS 'date' FROM `stat`");
			$nr = mysql_num_rows($query);
			for($i=0;$i<$nr;$i++){
				$result = mysql_fetch_object($query);
				echo "[new Date(".$result->date."), ".$result->amount."]";
				if ($i+1!=$nr) echo ",";
			}
		?>

        ]);
        var chart = new google.visualization.Calendar(document.getElementById('chart_div'));
        chart.draw(data, {
			calendar: {
				cellColor: {
					stroke: '#226677',
					strokeOpacity: 0.5,
					strokeWidth: 1,
				},
				 yearLabel: {
					color: '#333'
				}
			},
			colorAxis:{
				minValue: 0,  
				colors: ['#ffffff', '#226677']
			}
		})
      }
     function drawReg() {
        var data = new google.visualization.DataTable();
       data.addColumn({ type: 'date', id: 'Date' });
       data.addColumn({ type: 'number', id: 'Новые пользователи' });

        data.addRows([
		<?
			$query = mysql_query("SELECT *, UNIX_TIMESTAMP(`day`)*1000 AS 'date' FROM `stat`");
			$nr = mysql_num_rows($query);
			for($i=0;$i<$nr;$i++){
				$result = mysql_fetch_object($query);
				echo "[new Date(".$result->date."), ".$result->reg."]";
				if ($i+1!=$nr) echo ",";
			}
		?>

        ]);
        var chart = new google.visualization.Calendar(document.getElementById('reg_div'));
        chart.draw(data, {
			calendar: {
				cellColor: {
					stroke: '#227766',
					strokeOpacity: 0.5,
					strokeWidth: 1,
				},
				 yearLabel: {
					color: '#333'
				}

			},
			colorAxis:{
				minValue: 0,  
				colors: ['#ffffff', '#227766']
			}
		})
      }

				</script>
			</div>
		</span>