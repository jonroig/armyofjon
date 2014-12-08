<?php
/***
Army of Jon by Jon Roig (jon roig at jon roig dot com / @runnr_az)
Hit me up if you find this at all useful.
***/


// kairos settings
// this is my key for this project so be nice with it...
// grab your own from karos.com
$apiAppId = 'fbe65900';
$apiAppKey = 'fe41439fabbbf4ed69a92f0f8ee7f36b';
$apiGalleryId = 'pics';
$baseImageURL = 'http://armyofjon.com/pics';

include('kairosInterface.php');

// ajaxy stuff... keeping it simple
if (isset($_REQUEST['ajax']))
{
	// match a picture...
	if ($_REQUEST['ajax'] == 'match')
	{
		// split out the actual image data from the descriptor
		$imageData = explode(',',$_REQUEST['match']);

		// do a quick image validation test
		$imgContainer = @imagecreatefromstring(base64_decode($imageData[1]));
		if ($imgContainer == false)
		{
			// not actually a valid image file :(
			die('{"error":"invalid image"}');
		}

		// fire up the kairos recognize api...
		$kairosObj = new kairosInterface($apiAppId, $apiAppKey, $apiGalleryId);
		$response = $kairosObj->recognize($imageData[1]);

		// is it a succcess?
		$jsonResponse = json_decode($response);
		if (isset($jsonResponse->images) && isset($jsonResponse->images[0]->candidates) && isset($jsonResponse->images[0]->transaction->subject) && $jsonResponse->images[0]->transaction->confidence != 1)
		{
			// if it's a match, we want to save it...
			$uniqueId = uniqid();
			imagepng($imgContainer, "pics/".$uniqueId.'.png');

			// enroll the new pic in the gallery
			$newImageURL = $baseImageURL.'/'.$uniqueId.'.png';
			$newImageSubjectId = $uniqueId.'-png';

			$enrollResponse = $kairosObj->enroll($newImageURL, $newImageSubjectId);
		}

		// just pass the results directly back to the client since they're already json
		echo $response;
	}

	die();
}
?>
<!doctype html>
<html>
	<head>
		<title>Army Of Jon</title>
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
	            		<li role="presentation" class="active"><a href="#">Join</a></li>
			            <li role="presentation"><a href="#about">About</a></li>
			            <li role="presentation"><a href="#contact">Contact</a></li>
			            <li role="presentation"><a href="https://github.com/jonroig/armyofjon" target="_blank">GitHub</a></li>
	          		</ul>
	        	</nav>
	        	<h3 class="text-muted">The Army of Jon</h3>
	      	</div>

	      	<div class="jumbotron">
		        <h1>Join My Army</h1>
		        <p class="lead">
		        	Futuristic html5 technology takes control of your webcam.
		        	<br/>Facial recognition algorithms scan and judge you.
		        	<br/>It only takes seconds.
		        </p>
		        <p><a class="btn btn-lg btn-success" id="scanMeButton" href="#" role="button">Scan Me!</a></p>
	      	</div>

	      	<div class="row marketing">
		        <div class="col-lg-6">
		        	<a name="about"></a>
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
			        	Millions of years of evolution have led to this moment.
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

		          	<h4>Can I Upload a Picture?</h4>
		          	<p>
		          		Yup! Upload here:
		          		<input type="file" id="fileInput" style="padding: 20px"/>
		          	</p>
		          	<p>
		          		Note: All pics must be of a single person.
		          	</p>
		          	<h4>So... Why Did You Do This?</h4>
		          	<p>
		          		Honestly... I have no idea. Why are you even reading this?
		          	</p>
		          	<p>
		          		As a fulltime professional web developer, I need to keep up with the latest and greatest stuff to
		          		ensure my continued relevance. There's always new things, new technologies that you're expected
		          		to know.
		          		You can't just read about these things in a book... or, at least I can't. I needed a project.
		          		 In the
		          		spirit of <a href="http://www.justyo.co/" target="_blank">Yo</a>,
		          		<a href="http://xoxco.com/x/tacos/" target="_blank">TacoText</a> and
		          		<a href="http://www.textethan.com/" target="_blank">Ethan</a>, I needed something quick and pointless.
		          		I like the idea of combining readily existing
		          		technologies, some which, like the facial recognition system, are very complicated, and stringing them together
		          		to do something kind of dumb.
		          	</p>
		          	<p>
		          		To that end, this is kind of an art project. We'll see how the corpus of pictures which compose my army changes
		          		over time.
		          	</p>

		          	<h4>Can I Have My Own Army?</h4>
		          	<p>
		          		Sure! Just grab the code over at <a href="https://github.com/jonroig/armyofjon" target="_blank">GitHub</a>.
		          		If you end up using it somewhere, hit me up and let me see it.
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

		          	<a name="contact"></a><h4>Contact Jon</h4>
		          	<ul>
		          		<li>
		          			Email: jon at jon roig dot com
		          		</li>
		          		<li>
		          			Twitter: <a href="https://twitter.com/runnr_az" target="_blank">@runnr_az</a>
		          		</li>
		          		<li>
		          			Facebook: <a href="https://www.facebook.com/jonroig" target="_blank">jonroig</a>
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

		<!-- modal! -->
		<div class="modal fade" id="webcamModal" tabindex="-1" role="dialog" aria-labelledby="webcamModalLabel" aria-hidden="true">
	  		<div class="modal-dialog modal-webcam">
	    		<div class="modal-content">
	      			<div class="modal-header" id="webcamModalHeader" style="display: none">
	        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        			<h4 class="modal-title" id="webcamModalLabel">Accessing Webcam...</h4>
	      			</div>
	      			<div class="modal-body">
	    				<div id="welcome">
							^^^^ Press Allow! ^^^^
						</div>
						<div id="container" style="display:none">
							<video autoplay id="video" width="640" ></video>
						</div>
						<div id="picture" style="display: none">
							<canvas id="canvas" width="640" height="480"></canvas>
							<img src="http://placekitten.com/g/320/261" id="photo" alt="photo"/>
							<div id="statusText"></div>
						</div>
	      			</div>

	      			<div class="modal-footer" id="webcamModalFooter" style="display: none">
	        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        			<button type="button" class="btn btn-primary" id="sendAndScanButton">Send and scan</button>
	        			<button type="button" class="btn btn-primary" style="display: none" id="tryAgainButton">Try Again</button>
	      			</div>
	    		</div>
	  		</div>
		</div>


		<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="webcamModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-webcam">
	    		<div class="modal-content">
	      			<div class="modal-header" id="uploadModalHeader">
	        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        			<h4 class="modal-title" id="uploadModalLabel">Uploading...</h4>
	      			</div>
	      			<div class="modal-body">
	      				<div id="uploadModalMessage"></div>
	    				<img src="http://placekitten.com/g/320/261" id="uploadPhoto" alt="photo"/>
	      			</div>
	      			<div id="uploadStatusText"></div>

	      			<div class="modal-footer" id="uploadModalFooter">
	        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      			</div>
	    		</div>
	  		</div>
	  	</div>
	</body>

	<!-- javascript -->
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="armyofjon.js"></script>
</html>
