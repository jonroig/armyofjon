/**
Core Army of Jon Javascript Code
... hopefully this is useful to someone.

 - jon (jon@jonroig.com)

**/
var aoj=aoj||{};

aoj.app=(function()
{
  var streaming = false,
      video        = document.querySelector('#video'),
      canvas       = document.querySelector('#canvas');

  var imageData = null;
  var webcamStarted = false;

  return {

    // basic setup for the show to come
    init: function()
    {
      // get that big green button going
      $('#scanMeButton').click(function(){
        aoj.app.startWebcam();

        $('#webcamModal').modal('show');
      });

      $('#video').click(function(){
        aoj.app.takePicture();
      });

      $('#sendAndScanButton').click(function(){
        aoj.app.takePicture();
      });

      // this keep the dialog box cool
      $(window).scroll(function(){$(window).resize()});
      $('#webcamModal').scroll(function(){$(window).resize()});

      return;
    },


    // start the webcam
    startWebcam: function()
    {
      // set up the picture function if we need to
      if (webcamStarted == true)
      {
        aoj.app.hideWelcome();
        return;
      }

      navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

      navigator.getMedia({
        video: true,
        audio: false
        },
        function(stream)
        {
          webcamStarted = true;
          aoj.app.hideWelcome();

          if (navigator.mozGetUserMedia)
          {
            video.mozSrcObject = stream;
          }
          else
          {
            var vendorURL = window.URL || window.webkitURL;
            video.src = vendorURL.createObjectURL(stream);
          }
          video.play();
        },
        function(err) {
          console.log("An error occured! " + err);
        }
      )
    },


    // hide everything and set the stage to take a picture
    hideWelcome: function()
    {
      $('#welcome').hide();
      $('#container').show();
      $('#statusText').html('');
      $('#photo').hide();
      $('#webcamModalLabel').html('Join My Army');
      $('#webcamModalFooter').show();
      $('#webcamModalHeader').show();

      // do a little modal cleanup
      $(window).resize();
    },


    // take a picture from the webcam
    takePicture: function()
    {
      // convert the video to canvas then write it to an image
      var canvas = document.querySelector('#canvas');
      canvas.getContext('2d').drawImage(video, 0, 0);
      imageData = canvas.toDataURL('image/png');

      $('#photo').show();
      $('#photo').attr('src', imageData);
      $('#container').hide();
      $('#picture').show();
      $('#canvas').hide();

      $('#statusText').html('Matching...');

      // send the image and stuff to the server for analysis
      $.ajax({
        type:"POST",
        url:'?ajax=match',
        async:true,
        dataType:'json',
        data:{'match':imageData},
        success: this.processLookup
      });
    },


    // ajax response... success? failure?
    processLookup: function(data)
    {
      // handle erorrs
      if (typeof data.Errors != 'undefined')
      {
        var errorMessage = '';
        for(var x = 0; x < data.Errors.length; x++)
        {
          errorMessage += data.Errors[x].Message + ".";
        }
        $('#statusText').html('Failure! ' + errorMessage);
      }

      // handle non-errors
      else if (typeof data.images != 'undefined')
      {
        // no match to any gallery item...
        if (data.images.length == 1 && data.images[0].transaction.gallery_name == '')
        {
          $('#statusText').html('Failure! No Match! <a href="javascript:aoj.app.hideWelcome();">Try Again</a>' );
        }

        // otherwise... success!
        else if (typeof data.images[0].candidates != 'undefined')
        {
          var confidence = Math.round(data.images[0].transaction.confidence * 100);
          var outputTxt = 'Match! Confidence=' + confidence + '%<br/>';

          outputTxt += '<img id="matchPic" src="/pics/' + data.images[0].transaction.subject.replace('-','.') +'"/>';

          $('#statusText').html(outputTxt);
          $('#matchPic').width(567);
          $(window).resize();
        }
      }
    },


    // just an easy hook to the imageData
    returnImageData: function()
    {
      return imageData;
    }



  }
}());

aoj.app.init();

