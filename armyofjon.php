<?
// main php code for army of jon

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
			// if it's a match, we want to save it as a png
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