<?php
use LINE\LINEBot\MessageBuilder\TextMessageBuilder as TextMessageBuilder;

function calculate($query){
	
	$query = urlencode($query);
	$result =  file_get_contents('http://api.mathjs.org/v4/?expr='. $query);
	$result = new TextMessageBuilder($result);
		
	return $result;
}

function Keyword($query)
{
	$messages[] = "Keyword";
	$messages[] = "1.Olahraga";
	$messages[] = "2.Masakan";
	$messages[] = "3.Tips";
	$messages[] = "4.About";
	return $messages;
}


?>
