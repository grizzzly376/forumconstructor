<?
	include_once 'top.php';
?>
		
		<div id=main>
			<? include_once 'request/index.php'; ?>
		</div>
		
		<div id=bottom>
			<? include "request/online.php"; ?>
		</div>
		
		<?
			
		if($SETTINGS->adv == "true") echo"<center>
		<table id=ad>
		<tr align=center><td>
			<div id=ad1>
				<img src=/adv/ad1.png>
			</div>
		</td>
		<td width=100px> </td>
		<td>
			<div id=ad2>
				<img src=/adv/ad2.png>
			</div>
		</td></tr></table>
		</center>";
		?>
	</body>
</html>