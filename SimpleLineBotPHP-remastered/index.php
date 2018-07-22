<?php

require __DIR__ . '/vendor/autoload.php';

use \LINE\LINEBot\SignatureValidator as SignatureValidator;
foreach (glob("handler/*.php") as $handler){include $handler;}
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

	
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
	$data = json_decode($body, true);
	

	foreach ($data['events'] as $event)
	{
		
		$userMessage = $event['message']['text'];

		if(strtolower($userMessage) == "keyword")
		{
			$messages[] = "Hello \n";
			$messages[] = "Ada beberapa keyword yang bisa kamu pakai \n";
			$messages[] = "1.Olahraga \n";
			$messages[] = "2.Tips Kesehatan\n";
			$messages[] = "3.Resep Makanan \n";
			$messages[] = "Untuk yang lain bakal menyusul ya";
			
			
		  	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(implode(" ",$messages));
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
		
		
		
		
		if(strtolower($userMessage) == "tips kesehatan")
		{
		 $questions = array(
						"Sebelum olahraga sebaiknya kita melakukan pemanasan terlebih dahulu",
						"Perbanyak Konsumsi Air Putih",
						"Tidur dan Beristirahatlah yang Cukup",
						"Pilih Makanan Berwarna Cerah sebagai Antioksidan",
						"Kurangi Makanan Olahan dan Makanan dalam Kaleng",
						"Kenali Makanan Pemicu, Kendalikan Asupan Gula dan Garam Anda",
						"Katakan Tidak untuk Makanan Berminyak"
						);
				$index = rand(0, count($questions) - 1);
				$getval = array($questions[$index]);
				
				
				$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(implode(" ",$getval));
				$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
				return $result->getHTTPStatus() . ' ' . $result->getRawBody();

		}
		
		
		
		if(strtolower($userMessage) == "olahraga medium")
		{
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Mountain Climbers", "30 Repetisi x 3 Set","https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/4b668875-0d03-4c26-8126-81daa38d8fad.gif",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/4b668875-0d03-4c26-8126-81daa38d8fad.gif"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Sprawls","30 Repetisi x 3 Set","https://img.aws.livestrongcdn.com/ls-article-image-640/cme/photography.prod.demandstudios.com/940ca2f5-2b16-4810-b940-eca178e2dd72.gif",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://img.aws.livestrongcdn.com/ls-article-image-640/cme/photography.prod.demandstudios.com/940ca2f5-2b16-4810-b940-eca178e2dd72.gif"),
			  ]),
				
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Jumping Squats","30 Repetisi x 3 Set","https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/5a1e902f-42e7-4b38-b3c5-af3cb2cbbf0c.gif",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/5a1e902f-42e7-4b38-b3c5-af3cb2cbbf0c.gif"),
			  ]),

			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
		
		
		if(strtolower($userMessage) == "olahraga sulit")
		{
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Pistol Squat", "5 Repetisi x 3 Set","https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/b0bf80e6-92bf-4a8c-8573-5876a22e9c46.gif",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://img.aws.livestrongcdn.com/ls-article-image-673/cme/photography.prod.demandstudios.com/b0bf80e6-92bf-4a8c-8573-5876a22e9c46.gif"),
			  ]),
				
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Jackknifes","30 Repetisi x 3 Set","http://904fitness.com/wp-content/uploads/2015/04/JACKKNIFE.gif",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"http://904fitness.com/wp-content/uploads/2015/04/JACKKNIFE.gif"),
			  ]),
				
			//  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Burpees","30 Repetisi x 3 Set","https://thumbs.gfycat.com/FondAntiqueCuckoo-size_restricted.gif",[
			//  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://thumbs.gfycat.com/FondAntiqueCuckoo-size_restricted.gif"),
			 // ]),
			
			
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}


		if(strtolower($userMessage) == "olahraga gampang")
		{
			$carouselTemplateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Pushup", "30 Repetisi x 3 Set","https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://i.ytimg.com/vi/_l3ySVKYVJ8/maxresdefault.jpg"),
			  ]),
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Plank","60 Repetisi x 3 Set","https://qph.fs.quoracdn.net/main-qimg-48f9ed13dc64074e201f20b1324ed957-c",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://qph.fs.quoracdn.net/main-qimg-48f9ed13dc64074e201f20b1324ed957-c"),
			  ]),
				
			  new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Squats","30 Repetisi x 3 Set","https://cdn-ami-drupal.heartyhosting.com/sites/muscleandfitness.com/files/body-weight-squat-swiss-ball-exercise_landscape.jpg",[
			  new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"https://cdn-ami-drupal.heartyhosting.com/sites/muscleandfitness.com/files/body-weight-squat-swiss-ball-exercise_landscape.jpg"),
			  ]),
			
			  //new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Situp","60 Repetisi x 3 Set","http://78.media.tumblr.com/586bae1d9492345315b7a066c69287cb/tumblr_inline_mx42q8LFnF1rdu2za.jpg",[
			  //new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"http://78.media.tumblr.com/586bae1d9492345315b7a066c69287cb/tumblr_inline_mx42q8LFnF1rdu2za.jpg"),
			 // ]),
				
			// new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Jumping Jack","60 Repetisi x 3 Set","http://www.weightloss-pill.net/wp-content/uploads/2018/03/jumping-jacks.jpg",[
			// new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Buka',"http://www.weightloss-pill.net/wp-content/uploads/2018/03/jumping-jacks.jpg"),
			//  ]),
				
			  ]);
			$templateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('nama template',$carouselTemplateBuilder);
			$result = $bot->replyMessage($event['replyToken'], $templateMessage);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		}
	}
		
});

$app->run();
