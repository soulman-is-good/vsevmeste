// Создание заказа

	// amount - сумма заказа
	INSERT INTO `kkb`(`order_id`, `order_date`, `amount`) VALUES ("'.$order_id.'", "'.time().'", "'.$amount.'"); // пример создания заказа
	$id = mysql_insert_id(); // ID заказа у тебя в базе
	$addr = '/application/extensions/paysys/paysys/'; // адрес к папке paysys
	$path1 = $addr.'config.txt'; // Открой этот файл и замени все адреса на свои
	$currency_id = "398"; // Код тенге
	require_once($addr."kkb.utils.php"); // В этом файле найди все константы DOCROOT(я использовал в кохане) и замени их на абсолютный адрес
	$sign = process_request($id,$currency_id,$amount,$path1); // должен вернуть base64, который нужно вставить в форму


// Форма для оплаты

	<form name="SendOrder" method="post" action="https://3dsecure.kkb.kz/jsp/process/logon.jsp">
		<input type="hidden" name="Signed_Order_B64" value="<?= $sign ?>">
		<input type="hidden" name="Language" value="rus" />
		<input type="hidden" name="BackLink" value="{Ссылка для возврата на сайт}" />
	    <input type="hidden" name="PostLink" value="{Ссылка обработчика сайта}" />
		<input type="text" name="email" value="" />
		<input type="submit" value="Оплатить" class="big_button" />
	</form>

// Обработка заказа

	function checkKkbOrder($result, $path1, $kkb) { // Проверка существования заказа
		if($order['complete'] == 1){return false;}
		$req = process_check($result['PAYMENT_REFERENCE'], $result['PAYMENT_APPROVAL_CODE'], $result['ORDER_ORDER_ID'], 398, $result['ORDER_AMOUNT'], $path1);
		$xml = simplexml_load_string(file_get_contents('https://3dsecure.kkb.kz/jsp/remote/checkOrdern.jsp?'.urlencode($req)));
		$response = $xml->bank->response->attributes();
		if($response->payment == 'true')
			return true;
		else{
			return false;
		}
	}
	function kkb() {
		$addr = '/application/extensions/paysys/paysys/';
		require_once($addr."kkb.utils.php");
		$path1 = $addr.'config.txt';
		$result = 0;
		$result = process_response(stripslashes($_POST["response"]),$path1); 
		$res = mysql_query('SELECT * FROM `kkb` WHERE `id`="'.intval($result['ORDER_ORDER_ID']).'"');
		if($kkb = mysql_fetch_array($res)){
			if(!checkKkbOrder($result, $path1, $kkb)){
				echo 1;
				exit;
			}
			mysql_query('UPDATE `kkb` SET `complete`="1",`response`="'.mysql_real_escape_string(print_r($result, true)).'" WHERE `id`="'.$kkb['id'].'"'); // можешь result раскидать по разным полям. но я не стал заморачиваться. а можешь на него и вовсе забить. просто укажи что заказ оплачен.
			mysql_query('UPDATE `orders` SET `status`="2" WHERE `id`="'.$kkb['order_id'].'"'); // действия при успешной оплате
		}
	}



// Карта для тестирования в файле "test card.txt"
// Для перехода с тестового в рабочий режим нужно изменить все адреса с "https://3dsecure.kkb.kz" на "https://epay.kkb.kz".

