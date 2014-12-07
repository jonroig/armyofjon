<?php

$apiAppId = 'fbe65900';
$apiAppKey = 'fe41439fabbbf4ed69a92f0f8ee7f36b';

// ajaxy stuff...
if (isset($_REQUEST['ajax']))
{
	// match a picture...
	/*
	curl -v -H "app_id:fbe65900" -H "app_key:fe41439fabbbf4ed69a92f0f8ee7f36b"
	-X POST "http://api.kairos.com/recognize"
	-d '{"url":"http://armyofjon.com/jonpics/jon7.jpg","gallery_name":"jonpics" ,"threshold":"0" }'
	**/
	if ($_REQUEST['ajax'] == 'match')
	{
		$imageData=explode(',',$_REQUEST['match']);

		$data = '{"image":"'.$imageData[1].'","gallery_name":"jonpics" ,"threshold":"0" }';

		//$data = '{"image":"http://armyofjon.com/jonpics/jon7.jpg","gallery_name":"jonpics" ,"threshold":"0" }';
		$headers = array("app_id:fbe65900", "app_key:fe41439fabbbf4ed69a92f0f8ee7f36b");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, 'http://api.kairos.com/recognize');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        echo $response;
		curl_close($ch);
	}

die();
}


?>
<!doctype html>
<html>
<head>
	<title>armyofjon.com</title>
	<meta name="description" content="Army of Jon" />
	<link rel="stylesheet" href="armyofjon.css" />
</head>

<body>
	<div id="welcome">
		^^^^ Press Allow!
		<h1>Army of Jon</h1>
	</div>
	<div id="container" style="display:none">
		<video autoplay id="video" width="640" height="480"></video>
	</div><div id="picture" style="display: none">
		<canvas id="canvas" width="640" height="480"></canvas>
		<img src="http://placekitten.com/g/320/261" id="photo" alt="photo"/>
		<div id="statusText"></div>
	</div></body>

	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="armyofjon.js"></script>
</html>
