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

  return {
    init: function()
    {
      return;
      // set up the picture function
      $('#video').click(function(){
        aoj.app.takePicture();
      })

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
    },


    // take a picture from the webcam
    takePicture: function()
    {
      var canvas = document.querySelector('#canvas');
      canvas.getContext('2d').drawImage(video, 0, 0);
      imageData = canvas.toDataURL('image/png');

      $('#photo').show();
      $('#photo').attr('src', imageData);
      $('#container').hide();
      $('#picture').show();
      $('#canvas').hide();

      $('#statusText').html('Matching...');

      $.ajax({
        type:"POST",
        url:'?ajax=match',
        async:true,
        dataType:'json',
        data:{'match':imageData},
        success: this.processLookup
      });
    },

    returnImageData: function()
    {
      return imageData;
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

          outputTxt += '<img id="matchPic" src="/pics/' + data.images[0].transaction.subject + '.jpg"/>';

          $('#statusText').html(outputTxt);
          $('#matchPic').width(640);
        }
      }
    }


  }
}());

aoj.app.init();

