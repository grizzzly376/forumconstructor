<?
	include 'top.php';
	
	                $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
                    $user = json_decode($s, true);
                    //$user['network'] - соц. сеть, через которую авторизовался пользователь
                    //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
                    //$user['first_name'] - имя пользователя
                    //$user['last_name'] - фамилия пользователя
					
                
?>
		
		<div id=main>
			<center>
			<div class='group reg'>
				<script src="//ulogin.ru/js/ulogin.js"></script>
				<div id="uLogin" data-ulogin="display=panel;theme=flat;fields=first_name,last_name;providers=vkontakte,odnoklassniki,facebook,yandex;hidden=twitter,google,steam,youtube,instagram,openid,lastfm;redirect_uri=login.php;mobilebuttons=0;"></div>
				<?
					echo "Ответ сервера :".$user['network']." ИД ".$user['identity'];
				?>
			</div>
			</center>
		</div>
	</body>
</html>