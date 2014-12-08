/**
Core Army of Jon Javascript Code
... hopefully this is useful to someone.

 - jon (jon@jonroig.com)

**/
var aoj=aoj||{};

aoj.app=(function()
{
  var video        = document.querySelector('#video'),
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

      $('#webcamModalTryAgainButton').click(function(){
        aoj.app.hideWelcome();
      });

      // file input stuff for uploading images
      $('#fileInput').change(function(e){
        var uploadFile = $('#fileInput'). prop('files')[0];

        var reader = new FileReader();
        reader.onload = function(e) {
           aoj.app.beginImageUpload(reader.result);
        }

        reader.readAsDataURL(uploadFile);
      });

      // this keep the dialog box cool
      $(window).scroll(function(){$(window).resize()});
      $('#webcamModal').scroll(function(){$(window).resize()});

      return;
    },


    beginImageUpload: function(base64Image)
    {
      $('#uploadModal').modal('show');
      $('#uploadModalLabel').html('Uploading...');
      $('#uploadPhoto').attr('src', base64Image);
      $('#uploadStatusText').hide();

      // send the image and stuff to the server for analysis
      $.ajax({
        type:"POST",
        url:'?ajax=match',
        async:true,
        dataType:'json',
        data:{'match': base64Image},
        success: this.processLookup
      });
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
      $('#statusText').hide();
      $('#photo').hide();
      $('#webcamModalLabel').html('Join My Army');
      $('#webcamModalFooter').show();
      $('#webcamModalHeader').show();
      $('#sendAndScanButton').show().html('Send and scan');
      $('#sendAndScanButton').attr('disabled',false);
      $('#webcamModalTryAgainButton').hide();
      // do a little modal cleanup
      $(window).resize();
    },


    // take a picture from the webcam
    takePicture: function()
    {
      $('#sendAndScanButton').html('Transmitting...').attr('disabled',true);

      // convert the video to canvas then write it to an image
      var canvas = document.querySelector('#canvas');
      canvas.getContext('2d').drawImage(video, 0, 0);
      imageData = canvas.toDataURL('image/png');

      $('#photo').show();
      $('#photo').attr('src', imageData);
      $('#container').hide();
      $('#picture').show();
      $('#canvas').hide();

      $('#statusText').show().html('Matching...');

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
      if (typeof data.error != 'undefined')
      {
        $('#uploadStatusText').hide();
        $('#uploadModalMessage').html('Invalid file format :(');
        $('#uploadModalLabel').html('Upload failed');
      }

      else if (typeof data.Errors != 'undefined')
      {
        var errorMessage = '';
        for(var x = 0; x < data.Errors.length; x++)
        {
          errorMessage += data.Errors[x].Message + ".";
        }
        $('#statusText').html('Failure! ' + errorMessage);
        $('#uploadStatusText').html('Failure! ' + errorMessage);


        $('#sendAndScanButton').attr('disabled',false).hide();
        $('#webcamModalTryAgainButton').show();

      }

      // handle non-errors
      else if (typeof data.images != 'undefined')
      {
        // no match to any gallery item...
        if (data.images.length == 1 && data.images[0].transaction.gallery_name == '')
        {
          $('#statusText').html('Failure! No Match! <a href="javascript:aoj.app.hideWelcome();">Try Again</a>' );
          $('#uploadStatusText').html('Failure! No Match!');

          $('#sendAndScanButton').attr('disabled',false).hide();
          $('#webcamModalTryAgainButton').show();
        }

        // otherwise... success!
        else if (typeof data.images[0].candidates != 'undefined')
        {
          var confidence = Math.round(data.images[0].transaction.confidence * 100);
          var outputTxt = 'Match! Confidence=' + confidence + '%<br/>';
          $('#statusText').html(outputTxt);

          outputTxt = '<img id="matchPic" src="/pics/' + data.images[0].transaction.subject.replace('-','.') +'"/>';
          outputTxt += "<br/>Congratulations! You've been accepted! Your picture will be used as a sample for all future Army of Jon members.";
          $('#webcamModalImgArea').html(outputTxt);
          $('#uploadStatusText').show().html(outputTxt);

          $('#matchPic').width(640);
          $('#sendAndScanButton').hide();
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

