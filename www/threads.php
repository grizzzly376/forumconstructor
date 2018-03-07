<?
	include 'top.php';
	$forum = $_GET['forum'];
?>
		
		<div id=main>
		<?
			include "request/threads.php";
		?>
		</div>
				<div id=bottom>
			<? include "request/online.php"; ?>
		</div>
	</body>
</html>