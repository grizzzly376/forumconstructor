<?
	include_once "core.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title><? echo $SETTINGS->fname; ?></title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="jquery.min.js"></script>
		<script src="jquery-ui.min.js"></script>
		<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
		<script src=api.js  charset="utf-8"></script>
	

		<script src=dynamic.js></script>
		<link rel="shortcut icon" href="<? if($SETTINGS->logo!="") echo $SETTINGS->logo; else echo "/images/logo.png"; ?>" type="image/x-icon">
		<?
		 if($SETTINGS->styles_file!="") echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$SETTINGS->styles_file\">";
		 if($SETTINGS->scripts_file!="") echo "<script src=\"$SETTINGS->scripts_file\"  charset=\"utf-8\"></script>"; ?>
		<style>
			<? echo $SETTINGS->styles;?>
		</style>
		<script>
			<? echo $SETTINGS->st;?>
		</script>
	</head>
	<body>
		<!-- meow -->
		<div id=wait><center><img src=wait.png><br>Загрузка</center></div>
		<div id=top>
			<?
				include "request/top.php";
			?>
			<hr>
		</div>
			
			<div id=menu>
				<? include "request/menu.php"; ?>
			</div>
			