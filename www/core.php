<?
	include "settings.php";
	if(!$FIRSTLOGIN){
	$password = $_COOKIE['password'];
	$login = $_COOKIE['login'];
	}
	foreach($_GET as $key => $value) {
		
		if(($key!="pagetext")&&($_GET['action']!="renameforum")&&($key!="opt")){
			$value = trim($value);
			$value = htmlspecialchars($value);
			$value = mysql_real_escape_string($value);
			$_GET[$key] = $value;
		}else if($key=="opt"){
			foreach($value as $k=>$v){
				$v = trim($v);
				$v = htmlspecialchars($v);
				$v = mysql_real_escape_string($v);				
			}
			
		}
	}
	foreach($_POST as $key => $value) {
		
		if($key!="pagetext"){
			$value = trim($value);
			$value = htmlspecialchars($value);
			$value = mysql_real_escape_string($value);
			$_POST[$key] = $value;
		}
	}
function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
	
	$GROUPS[0] = "забаненные пользователи";
	$GROUPS[1] = "пользователи";
	$GROUPS[2] = "модераторы";
	$GROUPS[3] = "администраторы";
	
	$COLORS[1] = "";
	$COLORS[2] = "orange";
	$COLORS[3] = "red";
	
	$connect = mysql_connect($MYSQL_ADDR, $MYSQL_NAME, $MYSQL_PASSWORD) or die(mysql_error());
	mysql_select_db($DBNAME) or die(mysql_error());
	
	mysql_set_charset("utf8");
	
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'tpp'");
	$SETTINGS->tpp = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'ppp'");
	$SETTINGS->ppp = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'mpp'");
	$SETTINGS->mpp = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'fname'");
	$SETTINGS->fname = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'styles'");
	$SETTINGS->styles = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'script_top'");
	$SETTINGS->st = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'adv'");
	$SETTINGS->adv = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'bgimage'");
	$SETTINGS->bgimage = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'logo'");
	$SETTINGS->logo = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'styles_file'");
	$SETTINGS->styles_file = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'scripts_file'");
	$SETTINGS->scripts_file = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `variables` WHERE `key` = 'info'");
	$SETTINGS->info = mysql_result($query, 0, 'value');
	$query = mysql_query("SELECT * FROM `users` WHERE `name` = '$login' LIMIT 1") or die(mysql_error());
	$user = mysql_fetch_object($query);
	
	$hash = md5($user->salt+$password);
	
	$logon = false;
	
	if($hash == $user->passhash){
		$logon = true;
		mysql_query("UPDATE `users` SET `active` = NOW() WHERE `id` = ".$user->id);
		mysql_query("UPDATE `users` SET `IP` = '".get_ip()."' WHERE `id` = ".$user->id);
		if($FIRSTLOGIN) echo "true";
	}else{
		$user = NULL;
		if($FIRSTLOGIN) echo "Неверное имя пользователя или пароль";
	}
	
	if(($user->group<3)&&($forbidden)) {
		header('HTTP/1.1 403 Forbidden');
		header("Location: error.php?code=403");
		exit();
	}
	
	mysql_query("INSERT INTO `stat` (`id`, `day`, `amount`, `reg`) VALUES (NULL, CURRENT_DATE(), '1', '0') ON DUPLICATE KEY UPDATE `amount` = `amount` +1");
	
	if($logon){
			$query = mysql_query("SELECT *, (SELECT `name` FROM `users` WHERE `id` = `bans`.`moderator`) AS 'mname' FROM `bans` WHERE `target`=".$user->id." HAVING UNIX_TIMESTAMP(`expires`) > ".time()." ORDER BY `expires` DESC");
			$hasban = mysql_num_rows($query);
			if($hasban>0){
				$banned = true;
				$banlist = mysql_fetch_object($query);
				$baninfo = "<br><font color=red>Забанен модератором <a href=user.php?id=".$banlist->moderator.">".$banlist->mname."</a> до <i>".$banlist->expires."</i> Причина: <i>".$banlist->comment."</i></font>";
				if($user->group*1 == 1) $user->group = 0;
			}
	}
?>