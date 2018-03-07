		<?
			include_once dirname(__FILE__)."/../core.php";

		
		?>
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