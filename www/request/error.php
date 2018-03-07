<?
include_once dirname(__FILE__)."/../core.php";		
			if(!isset($_GET['code']))
				$status = $_SERVER['REDIRECT_STATUS'];
			else
				$status = $_GET['code'];
			$codes = array(
				   400 => array('400', 'Синтаксическая ошибка в запросе'),
				   403 => array('403', 'Отклонено сервером'),
				   404 => array('404', 'Страница не найдена'),
				   405 => array('405', 'Метод не доступен'),
				   408 => array('408', 'Время ожидания истекло'),
				   500 => array('500', 'Внутренняя ошибка'),
				   502 => array('502', 'Плохой шлюз'),
				   504 => array('504', 'Истекло время ожидания шлюза'),
			);
			$title = $codes[$status][0];
			$message = $codes[$status][1];
			if ($title == false || strlen($status) != 3) {
				   $message = 'Код ошибки HTTP не правильный.';
			}
			echo "<center class=p404>$title<br>$message</center>"
?>