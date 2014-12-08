
<?
/***
Very simple PHP wrapper for the Kairos Face Recognition API
... by Jon Roig (jon at jon roig dot com / @runnr_az)
***/


class kairosInterface
{
	private $apiAppId;
	private $apiAppKey;
	private $apiGalleryId;

	public function __construct($apiAppId, $apiAppKey, $apiGalleryId)
	{
		$this->apiAppId = $apiAppId;
		$this->apiAppKey = $apiAppKey;
		$this->apiGalleryId = $apiGalleryId;
	}

	public function recognize($imageData)
	{
		$data = '{"image":"'.$imageData.'","gallery_name":"'.$this->apiGalleryId.'" ,"threshold":".5" }';
		$response = $this->curlHttp('http://api.kairos.com/recognize', $data);

		return $response;
	}

	public function enroll($imageURL, $imageSubjectId)
	{
		$data = '{"url": "'.$imageURL.'","subject_id":"'.$imageSubjectId.'","gallery_name":"'.$this->apiGalleryId.'","multiple_faces":"false"}';
		$response = $this->curlHttp('http://api.kairos.com/enroll', $data);

		return $response;
	}

	private function curlHttp($url, $data)
	{
		$ch = curl_init();
		$headers = array("app_id:".$this->apiAppId, "app_key:".$this->apiAppKey);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
}