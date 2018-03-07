<?
			include_once dirname(__FILE__)."/../core.php";
			$forum = $_GET['forum'];
			
			$query = mysql_query("SELECT *, `sections`.`name` AS 'sname', `forums`.`name` AS 'fname' FROM `forums` LEFT JOIN `sections` ON `forums`.`section` = `sections`.`id` WHERE `forums`.`id` = '$forum' LIMIT 1") or die(mysql_error());
			$result = mysql_fetch_object($query);
			echo "<a href=index.php>$SETTINGS->fname</a> > ".$result->sname." > <i><a href=threads.php?forum=$forum>".$result->fname."</a></i><br><br><hr><br>";
			
			
			
			
			
			if($logon==true)
				echo "<div class=group>
						<center>
						<form id=ntopic>
						Создание новой темы<br><br>
						<input type=hidden value=$forum id=fid>
						<input id=tname value=Название>
						<div id=tools>
							<div class=ico alt=i><i>i</i></div>
							<div class=ico alt=u><u>u</u></div>
							<div class=ico alt=b><b>b</b></div>
							<div class=ico alt=s><s>s</s></div>
							<div class=ico alt=header><h3>H</h3></div>
							<div class=ico alt='red'><div class=color style='background:red'></div></div>
							<div class=ico alt='green'><div class=color style='background:green'></div></div>
							<div class=ico alt='blue'><div class=color style='background:blue'></div></div>
							<div class=ico alt='orange'><div class=color style='background:orange'></div></div>
							<div class=ico alt='center'><img src=ico/center.png height=20px width=20px></div>
							<div class=ico alt='left'><img src=ico/left.png height=20px width=20px></div>
							<div class=ico alt='right'><img src=ico/right.png height=20px width=20px></div>
							<div class=ico alt='justify'><img src=ico/justify.png height=20px width=20px></div>
							<div class='ico insertimage'><img src=ico/pic.png height=20px width=20px></div>
							<div class=ico alt='spoiler'><img src=ico/hide.png height=20px width=20px></div>
							<div class='ico link'><img src=ico/link.png height=20px width=20px></div>
							<div class='ico smiles'><img src=ico/smiles/16.gif></div>
						</div><br><br>
							<form>
								<div><textarea id=msgedit></textarea></div><br>
						
						<span class=createq>Создать опрос</span><br><br>
						<span class=q-span><input id=qname value=Название>
								<ul class=qul>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
									<li><input class=qv></li>
								</ul>
						</span>
								<input type=submit class=fbutton value=Создать>
							</form>
						</center>
					</div>";
			?>
