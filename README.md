Build An Army of You!
=========

Demo sites: [armyofjon.com](http://armyofjon.com) and [armyofnicolascage.com](http://armyofnicolascage.com)

Connect with the people in the world who look like you!

... a dubious experiment using javascript / html5 getUserMedia paired with facial recognition.

To build your own army site:
* Get an API account at  [Kairos.com](http://kairos.com)
* Change the API key details in index.php
* Put your sample pictures into the pics directory
* Prime the recognition engine and enroll your sample images from the command line. You can enroll as many or as few samples as you like. Images must be of only a single subject
* Enjoy!


Example image loading script. The subject_id is the image's file name with the "." replaced with a "-".
```
curl -v -H "app_id:APP_ID" -H "app_key:APP_KEY" -X POST "http://api.kairos.com/enroll" -d '{"url": "http://armyofjon.com/pics/jon1.jpg","subject_id":"jon1-jpg","gallery_name":"pics","multiple_faces":"false"}'
```
