<?
	date_default_timezone_set('Europe/Minsk');
	if($_GET['action']=="login"){
		$login = $_GET['login'];
		$password = $_GET['password'];
		setcookie("password", $password);
		setcookie("login", $login);
		$FIRSTLOGIN = true;
	}else{
		if($_GET['action']!="reg")
		if($_GET['action']!="logout"){
		header("Content-Type: text/html; charset=utf-8");
	}}

	include "core.php";
	
	
	/*$query = mysql_query("SELECT * FROM `users`");
	$result = array();
	while($responce = mysql_fetch_array($query, MYSQL_ASSOC))
			array_push($result, $responce);
	echo json_encode($result);*/
	

	
	if($_GET['action']=="reg"){
		$login = $_GET['login'];
		$password = $_GET['password'];
		$mail = $_GET['mail'];
		$info = $_GET['info'];
		
		if(!preg_match('/^[a-zA-Zа-яА-Я0-9_]{2,20}$/u', $login)) exit("Недопустимый логин");
		if(mysql_num_rows(mysql_query("SELECT * FROM `users`WHERE `name` = '$login'"))>0) exit("Логин уже занят");
		if(strlen($password)<=6) exit("Слишком короткий пароль");
		if(filter_var($mail,FILTER_VALIDATE_EMAIL) === false) exit("Введен некорректный e-mail");
		
		$salt = md5(time());
		$hash = md5($salt + $_GET['password']);
		mysql_query("INSERT INTO `users` (`id`, `group`, `name`, `email`, `passhash`, `salt`, `info`, `color`, `active`, `reg_date`) VALUES (NULL, '1', '$login', '$mail', '$hash', '$salt', '$info', 'white', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
		setcookie("password", $password);
		setcookie("login", $login);
		mysql_query("INSERT INTO `stat` (`id`, `day`, `amount`, `reg`) VALUES (NULL, CURRENT_DATE(), '1', '1') ON DUPLICATE KEY UPDATE `reg` = `reg` +1");
		echo "true";
	}
	
	if($_GET['action']=="logout"){
		setcookie("password", "");
		setcookie("login", "");		
		echo "true";
	}
	

	
	if($_GET['action']=="sendpost"){
		if($user->group>0){
			$uid = $user->id;
			$thread = $_GET['thread'];
			$msg = $_GET['message'];
			$query = mysql_query("SELECT `closed` FROM `threads` WHERE `id`='$thread' LIMIT 1");
			$closed = mysql_fetch_object($query)->closed;
			if(($closed==0)||($user->group>1)){
				$query = mysql_query("INSERT INTO `posts` (`id`, `author`, `text`, `date`, `edited`, `thread`) VALUES (NULL, '$uid', '$msg', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000', '$thread')") or die(mysql_error());
				echo "true";
			}
		}
		
	}
	
	if($_GET['action']=="newthread"){
		if($user->group>0){
			$uid = $user->id;
			$tname = strip_tags($_GET['tname']);
			$msg = strip_tags($_GET['message']);
			$forum = $_GET['forum'];
			$query = mysql_query("INSERT INTO `threads` (`id`, `name`, `author`, `created`, `forum`, `closed`, `visits`) VALUES (NULL, '$tname', '$uid', CURRENT_TIMESTAMP, '$forum', '0', '0')") or die(mysql_error());
			
			$thread_id = mysql_insert_id();
			
			$query = mysql_query("INSERT INTO `posts` (`id`, `author`, `text`, `date`, `edited`, `thread`) VALUES (NULL, '$uid', '$msg', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000', '$thread_id')") or die(mysql_error());			
			
			$qname = $_GET['qtext'];
			$opt = $_GET['opt'];		
			if(count($opt)){
				for($i=0;$i<count($opt);$i++)
						$query = mysql_query("INSERT INTO `questions` (`id`, `tid`, `text`) VALUES (NULL, '$thread_id', '".strip_tags($opt[$i])."')") or die(mysql_error());
				mysql_query("UPDATE `threads` SET `question` = '".strip_tags($qname)."' WHERE `id` = $thread_id") or die(mysql_error());	
			}
			
			echo "page.php?thread=".$thread_id;
		}
	}
	if($_GET['action']=="voteq"){
		if($user->group>0){
			$uid = $user->id;
			$qid = $_GET['qid'];
			
			$tid = mysql_result(mysql_query("SELECT * FROM `questions` WHERE `id` = '$qid'"),0,'tid');
			
			$query = mysql_query("SELECT * FROM `answers` LEFT JOIN `questions` ON `answers`.`qid` = `questions`.`id` WHERE (`tid` = '$tid') AND (`uid` = '$uid')");
			if(mysql_num_rows($query)<1){
				mysql_query("INSERT INTO `answers` (`id` , `uid`, `qid`) VALUES ( NULL, '$uid' , '$qid')") or die(mysql_error());	
				echo "true";
			}
		}
	}	
	
	if($_GET['action']=="deletemsg"){
			if($user->group>1){
				$uid = $user->id;
				$pid = $_GET['postid'];
				$query = mysql_query("DELETE FROM `posts` WHERE `id` = $pid") or die(mysql_error());
				echo "true";
			}
	}
	
	if($_GET['action']=="deletethread"){
			if($user->group>1){
				$thread = $_GET['thread'];
				$query = mysql_query("DELETE FROM `posts` WHERE `thread` = $thread") or die(mysql_error());
				$query = mysql_query("DELETE FROM `threads` WHERE `id` = $thread") or die(mysql_error());
				$query = mysql_query("DELETE FROM `answers` WHERE `qid` IN (SELECT `id` FROM `questions` WHERE `tid`='$thread')") or die(mysql_error());
				$query = mysql_query("DELETE FROM `questions` WHERE `tid` = '$thread'") or die(mysql_error());
				echo "true";
			}
	}
	
	if($_GET['action']=="editmsg"){	
		$text = $_GET['text'];
		$pid = $_GET['postid'];
		$query = mysql_query("SELECT * FROM `posts` LEFT JOIN `users` ON `posts`.`author` = `users`.`id` WHERE `posts`.`id`='$pid'");
		$result = mysql_fetch_object($query);
		if(($user->group>1)||($result->author==$user->id)){
			mysql_query("UPDATE `posts` SET `text` = '$text', `edited` = NOW() WHERE `posts`.`id` = $pid") or die(mysql_error());
			echo "true";
		}
	}

	if($_GET['action']=="editinfo"){	
		$text = $_GET['text'];
		$uid = $_GET['user'];
		if(($user->group>1)||($uid==$user->id)){
			mysql_query("UPDATE `users` SET `info` = '$text' WHERE `users`.`id` = $uid") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="changegroup"){
		if($user->group>2){
			$uid = $_GET['user'];
			$group = $_GET['group'];
			mysql_query("UPDATE `users` SET `group` = '$group' WHERE `users`.`id` = $uid") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="changecolor"){
		$uid = $_GET['user'];
		if(($user->group>2)||($uid==$user->id)){
			$color = mysql_real_escape_string($_GET['color']);
			mysql_query("UPDATE `users` SET `color` = '$color' WHERE `users`.`id` = $uid") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="toggleimp"){
		$tid = $_GET['thread'];
		$val = $_GET['value'];
		if($user->group>1){
			$val = mysql_real_escape_string($val);
			mysql_query("UPDATE `threads` SET `important` = '$val' WHERE `threads`.`id` = '$tid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="toggleclose"){
		$tid = $_GET['thread'];
		$val = $_GET['value'];
		if($user->group>1){
			$val = mysql_real_escape_string($val);
			mysql_query("UPDATE `threads` SET `closed` = '$val' WHERE `threads`.`id` = '$tid'") or die(mysql_error());
			echo "true";
		}
	}	
	if($_GET['action']=="renamethread"){
		$tid = $_GET['thread'];
		$newname = $_GET['newname'];
		if($user->group>1){
			mysql_query("UPDATE `threads` SET `name` = '$newname' WHERE `threads`.`id` = '$tid'") or die(mysql_error());
			echo "true";
		}
	}	
	if($_GET['action']=="tmove"){
		$tid = $_GET['thread'];
		$fid = $_GET['forum'];
		if($user->group>1){
			$query = mysql_query("SELECT * FROM `forums` WHERE `id`='$fid'");
			if(mysql_num_rows($query)>0){
				mysql_query("UPDATE `threads` SET `forum` = '$fid' WHERE `threads`.`id` = '$tid'") or die(mysql_error());
				echo "true";
			}
		}
	}	
	if($_GET['action']=="newpass"){
		$uid = $_GET['user'];
		$oldpass = $_GET['oldpass'];
		$newpass = $_GET['newpass'];
		$query= mysql_query("SELECT * FROM `users` WHERE `id`='$uid' LIMIT 1") or die(mysql_error());
		$result = mysql_fetch_object($query);
		$hash = md5($result->salt + $result->oldpass);
		if(($user->group>2)||($hash == $result->passhash)){
	
				$newsalt = md5(time());
				$newhash = md5($newsalt + $newpass);
			
				mysql_query("UPDATE `users` SET `salt` = '$newsalt' WHERE `id` = '$uid'") or die(mysql_error());
				mysql_query("UPDATE `users` SET `passhash` = '$newhash' WHERE `id` = '$uid'") or die(mysql_error());
				
				if($user->id==$uid){
					setcookie("password", $newpass);
				}
				echo "true";

		}
	}
	
	if($_GET['action']=="newsection"){
		if($user->group >2){
			$name= $_GET['name'];
			mysql_query("INSERT INTO `sections` (`id` ,`name` ,`info`) VALUES ( NULL , '$name', '')") or die(mysql_error());
			echo "true";
		}
	}
	
	if($_GET['action']=="deletesection"){
		if($user->group >2){
			$sid= $_GET['sid'];
			$nr = mysql_num_rows(mysql_query("SELECT * FROM `forums` WHERE `section` = '$sid'"));
			if($nr<1){
				mysql_query("DELETE FROM `sections` WHERE `id` = '$sid'") or die(mysql_error());
				echo "true";
			}else
				echo "Раздел содержит форумы";
		}
	}
	if($_GET['action']=="newforum"){
		if($user->group >2){
			$name= $_GET['name'];
			$section = $_GET['section'];
			$info = $_GET['info'];
			mysql_query("INSERT INTO `forums` (`id` , `section`, `name` ,`info`) VALUES ( NULL, '$section' , '$name', '$info')") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="deleteforum"){
		if($user->group >2){
			$fid= $_GET['fid'];
			$nr = mysql_num_rows(mysql_query("SELECT * FROM `threads` WHERE `forum` = '$fid'"));
			if($nr<1){
				mysql_query("DELETE FROM `forums` WHERE `id` = '$fid'") or die(mysql_error());
				echo "true";
			}else
				echo "Форум содержит темы";
		}
	}
	if($_GET['action']=="renameforum"){
		if($user->group >2){
			$fid= $_GET['fid'];
			$name = $_GET['name'];
			mysql_query("UPDATE `forums` SET `name` = '$name' WHERE `id` = '".strip_tags($fid)."'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="moveforum"){
		if($user->group >2){
			$fid= $_GET['fid'];
			$sid = $_GET['sid'];
			mysql_query("UPDATE `forums` SET `section` = '$sid' WHERE `id` = '$fid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="renamesection"){
		if($user->group >2){
			$sid= $_GET['sid'];
			$name = $_GET['name'];
			mysql_query("UPDATE `sections` SET `name` = '$name' WHERE `id` = '$sid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="updatefinfo"){
		if($user->group >2){
			$fid= $_GET['fid'];
			$info = $_GET['info'];
			mysql_query("UPDATE `forums` SET `info` = '$info' WHERE `id` = '$fid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="updatevar"){
		if($user->group >2){
			$key= $_GET['key'];
			$value = $_GET['value'];
			mysql_query("UPDATE `variables` SET `value` = '$value' WHERE `key` = '$key'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="banuser"){
		if($user->group >1){
			$uid= $_GET['user'];
			$expires = $_GET['exp'];
			$comment = $_GET['comment'];
			mysql_query("INSERT INTO `bans` (`id`, `target`, `moderator`, `expires`, `comment`) VALUES (NULL, '$uid', '".$user->id."', FROM_UNIXTIME(".(time()+(60*60*$expires))."), '$comment')") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="renameuser"){
		if($user->group >2){
			$uid= $_GET['user'];
			$name = $_GET['name'];
			mysql_query("UPDATE `users` SET `name` = '$name' WHERE `id` = '$uid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="clearban"){
		if($user->group >1){
			$uid= $_GET['user'];
			mysql_query("UPDATE `bans` SET `expires` = ".time()." WHERE `target` = '$uid'") or die(mysql_error());
			echo "true";
		}
	}
	if($_GET['action']=="newmail"){
		if($user->group >0){
			$rname = $_GET['rcvr'];
			$title = $_GET['title'];
			$text = $_GET['text'];
			$query = mysql_query("SELECT * FROM `users` WHERE `name`= '".$rname."'");
			if(mysql_num_rows($query)>0){
				$rid = mysql_result($query, 0, 'id');
				mysql_query("INSERT INTO `mails` (`id`, `sender`, `reciever`, `text`, `title`, `read`, `fav`, `date`) VALUES (NULL, '$user->id', '$rid', '$text', '$title', '0', '0', CURRENT_TIMESTAMP)") or die(mysql_error());
				echo "true";
			}
			
		}
	}
	if($_GET['action']=="togglefav"){
		$mid = $_GET['mid'];
		$val = $_GET['value'];
		$uid = mysql_result(mysql_query("SELECT * FROM `mails` WHERE `id`=$mid"),0,'reciever');
		if($user->id == $uid){
			mysql_query("UPDATE `mails` SET `fav` = '$val' WHERE `id` = '$mid'") or die(mysql_error());
			echo "true";
		}
	}
  
  function resize($image){
	  list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
	   $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
	  $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
      $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
      $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
    } else {
      echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
      return false;
    }
	$w = 0;
	$h = 0;
	if($w_i>=$h_i) {
		$s = $h_i;
	}else{
		$s = $w_i;			
	}
	$img_s1 = imagecreatetruecolor($s, $s); // Создаём дескриптор для выходного изображения
	imagecopy($img_s1, $img_i, 0, 0, 0, 0, $s, $s); // Переносим часть изображения из исходного в выходное
	$img_s2 = imagecreatetruecolor(150, 150); // Создаём дескриптор для выходного изображения
	imagecopyresized ( $img_s2 , $img_s1 , 0 , 0 , 0 , 0 , 150 , 150 , $s , $s );
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_s2, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
  }
  
	if($_POST['action']=="upload_avatar"){
		if($user->group >0){
			$uploaddir = 'images/avatars/';
			// это папка, в которую будет загружаться картинка
			$apend=$user->id.'.jpg'; 
			// это имя, которое будет присвоенно изображению 
			$uploadfile = "$uploaddir$apend"; 
			//в переменную $uploadfile будет входить папка и имя изображения

			// В данной строке самое важное - проверяем загружается ли изображение (а может вредоносный код?)
			// И проходит ли изображение по весу. В нашем случае до 512 Кб
			//if(($_FILES['userfile']['type'] == 'image/gif' || $_FILES['userfile']['type'] == 'image/jpeg' || $_FILES['userfile']['type'] == 'image/png') && ($_FILES['userfile']['size'] != 0 and $_FILES['userfile']['size']<=(512*1024))) 
			if($_FILES['userfile']['size']<=(2048*1024))
			{ 
			// Указываем максимальный вес загружаемого файла. Сейчас до 512 Кб 
			  if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
			   { 
			   //Здесь идет процесс загрузки изображения 
			   $size = getimagesize($uploadfile); 
			   // с помощью этой функции мы можем получить размер пикселей изображения 
				 resize($uploadfile);
				 mysql_query("UPDATE `users` SET `avatar` = true WHERE `id` = '$user->id'") or die(mysql_error());
				 echo "true";
			   } else {
			   echo "Файл не загружен, вернитеcь и попробуйте еще раз";
			   } 
			} else { 
			echo "Размер файла не должен превышать 512Кб";
			} 
		}		
	}
	
	if($_POST['action']=="createpage"){
		if($user->group >2){
			$innertext = $_POST['pagetext'];
			$pagename = $_POST['pagename'];
			$flagtop = $_POST['flagtop']*1;
			
			// открываем файл, если файл не существует,
			//делается попытка создать его
			$fp = fopen($pagename, "w");
			
			// строка, которую будем записывать
			if($flagtop) $text = "<? include 'top.php'; ?>";
			if($flagtop) $text.= "<div id=main>";
			
			$text.= $innertext;
			
			if($flagtop) $text.= "</div>";
			if($flagtop) $text.= "</body></html>";
			 
			$text = stripslashes ($text);
			 
			mysql_query("INSERT INTO `pages` (`id`, `source` , `changed`) VALUES (NULL, '$pagename', CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE `changed` = CURRENT_TIMESTAMP") or die(mysql_error());
			 
			// записываем в файл текст
			fwrite($fp, $text);
			 
			// закрываем
			fclose($fp);
			echo "true";
		}
	}
	if($_POST['action']=="loadpagehtml"){
		if($user->group >2){
			$pid = $_POST['pid'];
			if($pid!=""){
				$file_handle = fopen(mysql_result(mysql_query("SELECT * FROM `pages` WHERE `id` = '$pid'"),0,'source'), "r");
				while (!feof($file_handle)) {
				   $line = fgets($file_handle);
				   echo $line;
				}
				fclose($file_handle);
			}
		}
	}
	if($_POST['action']=="loadfpriority"){
		if($user->group >2){
			$fid = $_POST['fid'];
			if($fid!=""){
				echo mysql_result(mysql_query("SELECT * FROM `forums` WHERE `id`=$fid"),0 ,'priority');
			}
		}
	}
	if($_POST['action']=="changefpriority"){
		if($user->group >2){
			$fid = $_POST['fid'];
			$priority = $_POST['priority'];
			if($fid!=""){
				mysql_query("UPDATE `forums` SET `priority` = $priority WHERE `id`=$fid") or die(mysql_error());
			}
		}
	}
	if($_POST['action']=="loadspriority"){
		if($user->group >2){
			$sid = $_POST['sid'];
			if($sid!=""){
				echo mysql_result(mysql_query("SELECT * FROM `sections` WHERE `id`=$sid"),0 ,'priority');
			}
		}
	}
	if($_POST['action']=="changespriority"){
		if($user->group >2){
			$sid = $_POST['sid'];
			$priority = $_POST['priority'];
			if($sid!=""){
				mysql_query("UPDATE `sections` SET `priority` = $priority WHERE `id`=$sid") or die(mysql_error());
			}
		}
	}
	
	if($_GET['action']=="deletepage"){
		if($user->group >2){
			$pid = $_GET['pid'];
			unlink(mysql_result(mysql_query("SELECT * FROM `pages` WHERE `id` = $pid"), 0, 'source'));
			mysql_query("DELETE FROM `pages` WHERE `id`=$pid");
			echo "true";
		}
	}
	
	mysql_close($connect);
?>