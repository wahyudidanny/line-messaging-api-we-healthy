<?php

require __DIR__ . '/vendor/autoload.php';


use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// load config
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// initiate app
$configs =  [
	'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

/* ROUTES */
$app->get('/', function ($request, $response) {
	return "Lanjutkan!";
});

$app->post('/', function ($request, $response)
{
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}

	// is this request comes from LINE?
	if($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)){
		return $response->withStatus(400, 'Invalid signature');
	}

	// init bot
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
	$data = json_decode($body, true);
	
	
	//*function on_follow()
	//{
		//return "Welcome {$this->profile->display_name}.\n Saya adalah bot, saya akan membantumu .";
	//}
	
	
	foreach ($data['events'] as $event)
	{
		$userMessage = $event['message']['text'];
		
		/*
		if(strtolower($userMessage) == 'saya mau olahraga')
		{
			
			if($this->session->get('mq_status') == 0 && $text == 'start'){
				$this->session->set('mq_status', 1);
				$this->session->set('mq_start_time', time());
				
				
				$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('baik saya set timer 5 detik');
				$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
				return $result->getHTTPStatus() . ' ' . $result->getRawBody();
				
				
				if(time() - $this->session->get('mq_start_time') > 5){
					$this->session->set('mq_status', 0);
					$messages[] = 'Time is over.';
					$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($messages);
					$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
					return $result->getHTTPStatus() . ' ' . $result->getRawBody();
				}
				
				
				
			}
			
			
		
		}
		*/
		if(strtolower($userMessage) == 'kasi tips olahraga')
		{
			$messages = $this->tips_olahraga();
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
			
	
		
		if(strtolower($userMessage) == 'halo')
		{
			$message = "Halo juga";
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
		
		if(strtolower($userMessage) == 'event')
		{
			$message = $event;
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
		
		
		if(strtolower($userMessage) == 'contoh text message')
		{
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('ini adalah contoh text message');
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
		
		if(strtolower($userMessage) == 'kirim gambar pushup')
		{
            $imageMessage = new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg","https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg");
			$result = $bot->replyMessage($event['replyToken'], $imageMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
		
		
		if(strtolower($userMessage) == 'kirim gambar')
		{
            $imageMessage = new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg","https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg");
			$result = $bot->replyMessage($event['replyToken'], $imageMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
		

		if(strtolower($userMessage) == "kirim sticker")
		{
			$stickerMessage = new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1,1);
			$result = $bot->replyMessage($event['replyToken'], $stickerMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();     
		}
				
		
		if(strtolower($userMessage) == "button template")
		{
			$buttonTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder(
				 "title",
				 "text",
				 "https://i0.wp.com/angryanimebitches.com/wp-content/uploads/2013/03/tamakomarket-overallreview-tamakoanddera.jpg",
				   [
						new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('Action Button','action'), //display , and paramter value in API
				   ]
			   );
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template', $buttonTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
		
		if(strtolower($userMessage) == "confirm template")
		{
			$confirmTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder(
			   "apakah gw ganteng?",
			   [
			   new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('Ya',"/ya"),
			   new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('Tidak','/tidak'),
			   ]
			   );
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template', $confirmTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
			}
		
		if(strtolower($userMessage) == "carousel template")
		{
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("title", "text","https://i0.wp.com/angryanimebitches.com/wp-content/uploads/2013/03/tamakomarket-overallreview-tamakoanddera.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('buka',"http://hilite.me/"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("title", "text","https://i0.wp.com/angryanimebitches.com/wp-content/uploads/2013/03/tamakomarket-overallreview-tamakoanddera.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"http://hilite.me/"),
			  ]),
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
	}
	
	
	
	

});

private function tips_olahraga(){
	$questions = array(
			'Sebelum olahraga sebaiknya kita melakukan pemanasan terlebih dahulu',
			'Perbanyak Konsumsi Air Putih',
			'Tidur dan Beristirahatlah yang Cukup',
			'Pilih Makanan Berwarna Cerah sebagai Antioksidan',
			'Kurangi Makanan Olahan dan Makanan dalam Kaleng'),
			'Kenali Makanan Pemicu, Kendalikan Asupan Gula dan Garam Anda',
			'Berhenti Makan ketika Anda Merasa Kenyang',
			'Katakan Tidak untuk Makanan Berminyak'
		);

	$index = rand(0, count($questions) - 1);

	return array($questions[$index]);
}



$app->run();
