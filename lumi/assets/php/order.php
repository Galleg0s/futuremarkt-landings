<?
	require_once('form_throttle.php');
	if (isset($_POST['utm_source']))
		$source = ' '.$_POST["utm_source"];
	else 
		$source = '';
	if (isset($_POST['utm']))
		$utm = urldecode($_POST["utm"]);
	else 
		$utm = '';
	$message = '
			<html>
				<head>
					<title>'.$subject.'</title>
				</head>
				<body>
					<p>Заявка с сайта <a href="http://sony-xperia-store.ru/">sony-xperia-store.ru</a></p>
					<p>IP: '.$_SERVER['REMOTE_ADDR'].'</p>';
	foreach ($_POST as $key => $value) {
		if ($key != 'key')
			$message .='<p>'.$key.' - '.urldecode($value).'</p>';
	}
	$message .='</body>
			</html>';
	$headers  = "Content-type: text/html; charset=utf-8 \r\n";
	$headers .= "From: sony-xperia-store.ru\r\n";
	$name = urldecode($_POST["name"]);
	$tel = urldecode($_POST["phone"]);
	$tel = str_replace('+', '', $tel);
	$tel = str_replace(' ', '', $tel);
	$tel = str_replace('(', '', $tel);
	$tel = str_replace(')', '', $tel);
	$tel = str_replace('-', '', $tel);
	if (formthrottle_too_many_submissions($tel) && (strtolower($_POST["name"])!='test' && mb_strtolower(urldecode($_POST["name"]), 'UTF-8')!='тест'))
	{
		$to = 'leadsdima@gmail.com';
		$subject = 'Sony Xperia Повторные'.$source;
		mail($to, $subject, $message, $headers);
	}
	else
	{
		$to = 'leadsdima@yandex.ru';
		$subject = 'Sony Xperia'.$source;
		mail($to, $subject, $message, $headers);
		$authKey = $_POST['key'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$st = array("id" => "56dac2be712297a32d746a8c");
		$mainProduct = urldecode($_POST["mainProduct"]);
		$mainProductPrice = urldecode($_POST["mainProductPrice"]);
		$content = array("name" => $name, "phone" => $tel, "site" => "sony-xperia-store.ru", "offer" => "Sony Xperia", "mainProductPrice" => $mainProductPrice, "mainProduct" => $mainProduct, "ip" => $ip, "status" => $st, "utm" => $utm, "owner" => "Маркетологи");
		if ($tel != ''){
			$myCurl = curl_init();
			curl_setopt_array($myCurl, array(
				CURLOPT_URL => 'http://194.58.119.157:9080/api/entity/Lead',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_HTTPHEADER => array("X-Access-Token: ".$authKey),
				CURLOPT_POSTFIELDS => http_build_query($content)
			));
			$response = curl_exec($myCurl);
			curl_close($myCurl);
		}
	}
?>