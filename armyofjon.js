var aoj=aoj||{};

aoj.app=(function()
{
  var streaming = false,
      video        = document.querySelector('#video'),
      canvas       = document.querySelector('#canvas');


  return {
    init: function()
    {
      console.log('init!');

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
          console.log('stream!');
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


    hideWelcome: function()
    {
      $('#welcome').hide();
      $('#container').show();
      $('#statusText').html('');
      $('#photo').hide();
    },


    takePicture: function()
    {
      var canvas = document.querySelector('#canvas');
      canvas.getContext('2d').drawImage(video, 0, 0);
      var data = canvas.toDataURL('image/png');

      console.log('data',data);
      $('#photo').show();
      $('#photo').attr('src', data);
      $('#container').hide();
      $('#picture').show();
      $('#canvas').hide();

      $('#statusText').html('Matching...');

      $.ajax({
        type:"POST",
        url:'?ajax=match',
        async:true,
        dataType:'json',
        data:{'match':data},
        success: this.processLookup
      });
    },


    processLookup: function(data)
    {
      console.log('processLookup=',data);

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

