<?php

$apiAppId = 'fbe65900';
$apiAppKey = 'fe41439fabbbf4ed69a92f0f8ee7f36b';

// ajaxy stuff...
if (isset($_REQUEST['ajax']))
{
	// match a picture...
	if ($_REQUEST['ajax'] == 'match')
	{
		$imageData = explode(',',$_REQUEST['match']);

		$data = '{"image":"'.$imageData[1].'","gallery_name":"pics" ,"threshold":"0" }';

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

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
</head>

<body>
	<div class="container">
      	<div class="header">
        	<nav>
          		<ul class="nav nav-pills pull-right">
            		<li role="presentation" class="active"><a href="#">Home</a></li>
		            <li role="presentation"><a href="#">About</a></li>
		            <li role="presentation"><a href="#">Contact</a></li>
		            <li role="presentation"><a href="https://github.com/jonroig/armyofjon" target="_blank">GitHub</a></li>
          		</ul>
        	</nav>
        	<h3 class="text-muted">The Army of Jon</h3>
      	</div>

      	<div class="jumbotron">
	        <h1>Join the Army</h1>
	        <p class="lead">
	        	Millions of years of evolution have lead to this moment.
	        	<br/>Futuristic html5 technology will take control of your webcam.
	        	<br/>Our facial recognition algorithms will judge you.
	        </p>
	        <p><a class="btn btn-lg btn-success" href="#" role="button">Join the Army!</a></p>
      	</div>

      	<div class="row marketing">
	        <div class="col-lg-6">
	          	<h4>Uhhh... what?</h4>
	        	<p>
	        		All my life, people tell me that I look like someone they know...
		        <p>
		        </p>
		        	... and for all that time,
		        	I joked that I would one day organize all the people who look like me into an army.
		        </p>
		        <p>
		        	Until now, that was just hubris.
		        </p>
		        <p>
		        	For many millenia, mankind has lacked the ability to pull off such a feat. We had only
		        	rumors and innuendo of people who look like us. Hard facts and real connections were hard
		        	to come by.
		        </p>
		        <p>
		        	With recent advances in browser technology, we can now harness the power of html5 to send images
		        	captured directly from your webcam directly through outer space to our servers located high in the clouds.
		        	Our learning machines use sophisticated facial recognition algorithms to determine how closely your face
		        	matches that of the existing army.
		        </p>
		        <p>
		        	<b>If you look enough like the rest of us, you'll be invited to join the army.</b>
		        </p>

	          	<h4>Current Members</h4>
	          	<p>
	          		Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.
	          	</p>

	          	<h4>Technology Stack</h4>
	          	<ul>
	          		<li>
	          			<a href="https://github.com/jonroig/armyofjon" target="_blank">Fork us on GitHub!</a>
	          		</li>
	          		<li>
	          			Facial recognition powered by <a href="http://www.kairos.com/" target="_blank">Kairos</a>
	          		</li>
	          		<li>
	          			<a href="http://php.net/" target="_blank">PHP</a> backend
	          		</li>
	          		<li>
	          			<a href="http://jquery.com/" target="_blank">jQuery</a>
	          		</li>
	          		<li>
	          			Look 'n' feel from <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a>
	          		</li>
	          		<li>
	          			Thanks for the <a href="https://developer.mozilla.org/en-US/docs/Web/API/WebRTC_API/Taking_still_photos" target="_blank">WebRTC example</a>, Mozilla!
	          		</li>
	          	</ul>

	          	<h4>Contact Jon</h4>
	          	<ul>
	          		<li>
	          			Email: jon at jon roig dot com
	          		</li>
	          		<li>
	          			Twitter: <a href="https://twitter.com/runnr_az" target="_blank">@runnr_az</a>
	          		</li>
	          		<li>
	          			Instagram: <a href="http://instagram.com/jonroig" target="_blank">@jonroig</a>
	          		</li>
	          		<li>
	          			GitHub: <a href="https://github.com/jonroig" target="_blank">jonroig</a>
	          		</li>
	          		<li>
	          			LinkedIn: <a href="http://www.linkedin.com/in/jonroig/">jonroig</a>
	          		</li>
	          	</ul>
	        </div>
      	</div>

      	<footer class="footer">
       		<p>&copy; Smersh Light Industries 2014</p>
      	</footer>

	</div> <!-- /container -->
</body>

<!--



        <p>
        	Millions of years of evolution have led to this moment. We have the technology. We
        	can finally get together and make things happen. We can change things.
        </p>


	<div id="welcome">
		<div id="pressAllow">^^^^ Press Allow!</div>
		<h1>Army of Jon</h1>
	</div>
	<div id="container" style="display:none">
		<video autoplay id="video" width="640" height="480"></video>
	</div>
	<div id="picture" style="display: none">
		<canvas id="canvas" width="640" height="480"></canvas>
		<img src="http://placekitten.com/g/320/261" id="photo" alt="photo"/>
		<div id="statusText"></div>
	</div>
	-->

	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<script src="armyofjon.js"></script>
</html>
