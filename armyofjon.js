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
          aoj.app.resizeVideoFullScreen();
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
    },

    resizeVideoFullScreen: function()
    {
      return;
      var maxWidth = $(window).width();
      $('#video').width(maxWidth);
    },

    takePicture: function()
    {
      var canvas = document.querySelector('#canvas');
      canvas.getContext('2d').drawImage(video, 0, 0);
      var data = canvas.toDataURL('image/png');

      console.log('data',data);

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
          $('#statusText').html('Failure! No Match');
        }
        else if (typeof data.images[0].candidates != 'undefined')
        {
          var confidence = Math.round(data.images[0].transaction.confidence * 100);
          var outputTxt = 'Match! Confidence=' + confidence + '%<br/>';

          outputTxt += '<img id="matchPic" src="/jonpics/' + data.images[0].transaction.subject + '.jpg"/>';

          $('#statusText').html(outputTxt);
          $('#matchPic').width(640);
        }


      }




    }


  }
}());


aoj.app.init();

$(window).resize(function(){
  aoj.app.resizeVideoFullScreen();
})

/**
(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 320,
      height = 0;
  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);
  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );
  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);
  function takepicture() {
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    var data = canvas.toDataURL('image/png');
    photo.setAttribute('src', data);
  }
  startbutton.addEventListener('click', function(ev){
    takepicture();
    ev.preventDefault();
  }, false);
})();

**/
