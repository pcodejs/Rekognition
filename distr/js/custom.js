/*=======================================================================================
| Template Name: AWS Amazon Rekognition - Deep Learning Powered Image Recognition Service
| Theme URL: https://codecanyon.net/user/berkinedesign
| Author: BerkineDesign
| Author URL: https://codecanyon.net/user/berkinedesign
| Version: 1.0
| File name: custom.js
| Date Created: 15.03.2020
| Website: www.berkinedesign.com
========================================================================================= */


/* -------------------------------------------------------------------- */
/*                            TABLE OF CONTENTS
/* -------------------------------------------------------------------- */
/*   01 - IMAGE UPLOAD BUTTON                                           */
/*   02 - SECOND IMAGE UPLOAD BUTTON FOR FACE COMPARISON                */
/*   03 - AJAX CALL TO PROCESS UPLOADED IMAGES                          */
/*   04 - AJAX CALL TO PROCESS DEMO IMAGES                              */
/*   05 - CALL PROCESSSAMPLEIMAGES() FUNCTION FOR DEMO IMAGES           */
/*   06 - FACIAL ANALYSIS PROCESS                                       */
/*   07 - CELEBRITY RECOGNITION PROCESS                                 */
/*   08 - FACE COMPARISON PROCESS                                       */
/*   09 - IMAGE MODERATION PROCESS                                      */
/*   10 - OBJECT DETECTION PROCESS                                      */
/*   11 - TEXT IN THE IMAGE PROCES                                      */
/*   12 - DOWNLOAD LABELS BUTTON                                        */
/*   13 - CUSTOM INPUT FIELD FOR UPLOAD BUTTON                          */
/*   14 - CUSTOM INPUT FIELD FOR SECOND UPLOAD BUTTON (FACE COMPARISON) */
/*   15 - DEMO IMAGES DISPLAY                                           */
/*   16 - SHOW BLURRED IMAGE (IMAGE MODERATION)                         */
/*   17 - DISPLAY CANVAS BOUNDING BOXES                                 */




/*===========================================================================
*
*  01 - IMAGE UPLOAD BUTTON
*
*============================================================================*/

$(document).ready(function() {

  "use strict";

  /* ------------------------------------------------------ */
  /*    CHECK FILE SIZE AND FILE TYPE & Call ProcessImage()
  /* ------------------------------------------------------ */

  $("#file").on('change', function(e) {

    $("#labels").html('');

    /* Adjust the file size accordingly (5MB for Demo) (Rekognition supports up to 15MB file size through S3 Bucket) */
    var fileMaxSize = 5242880;              
    var fileSize = this.files[0].size;
    var fileExtension = $('#file').val().split('.').pop().toLowerCase();

    if (fileSize !== 'undefined') {

      if (fileSize > fileMaxSize) {
      
          var message = "Maximum allowed file size is 5MB. Selected file size is " + (fileSize/Math.pow(1024,2)).toFixed(0) +"MB.";
          
          showStatusMessages(message, "error");
          
          $('#file').val('');
          
          return false;

      } else if($.inArray(fileExtension, ['png', 'jpeg', 'jpg']) == -1) {

          var message = "File with extention \"" + fileExtension + "\" is not allowed. Use only \"jpeg\" | \"png\".";
        
          showStatusMessages(message, "error");
        
          $('#file').val('');
        
          return false;

      } else {

          var formID = $('form').attr("id");

          download_data = [];

          switch(formID) {
            case 'facial-analysis': processImages(formID);
              break; 
            case 'celebrity-recognition': processImages(formID);
              break;
            case 'face-comparison': processImages(formID);
              break;
            case 'image-moderation': processImages(formID);
              break;
            case 'object-detection': processImages(formID);
              break;
            case 'text-image': processImages(formID);
              break;
            default: 
                  var message = 'Incorrect Form ID was Used';      
                  showStatusMessages(message, 'error');
          }          
        
      }

    }

  });

});


/* --------------------------------------------------- */
/*   DISPLAY FILE UPLOAD ERROR MESSAGES
/* --------------------------------------------------- */
function showStatusMessages(message,status){
  
    "use strict";

    if (status == "success") {
        
        $("#status-message").addClass("success").removeClass("error");
      
    } else if (status == "error") {
        
        $("#status-message").removeClass("success").addClass("error");
    }

    $("#status-message")
      .slideDown()
      .html(message)
      .delay(5000)
      .slideUp();
}



/*===========================================================================
*
*  02 - SECOND IMAGE UPLOAD BUTTON FOR FACE COMPARISON
*
*============================================================================*/

$(document).ready(function() {

  "use strict";

  /* ------------------------------------------------------ */
  /*    CHECK FILE SIZE AND FILE TYPE & Call ProcessImage()
  /* ------------------------------------------------------ */

  $("#file-comparison").on('change', function(e) {

    $("#labels").html('');

    /* Adjust the file size accordingly (5MB for Demo) (Rekognition supports up to 15MB file size through S3 Bucket) */
    var fileMaxSize = 5242880;              
    var fileSize = this.files[0].size;
    var fileExtension = $('#file-comparison').val().split('.').pop().toLowerCase();

    if (fileSize !== 'undefined') {

      if (fileSize > fileMaxSize) {
      
          var message = "Maximum allowed file size is 5MB. Selected file size is " + (fileSize/Math.pow(1024,2)).toFixed(0) +"MB.";
          
          showComparisonStatusMessages(message, "error");
          
          $('#file-comparison').val('');
          
          return false;

      } else if($.inArray(fileExtension, ['png', 'jpeg', 'jpg']) == -1) {

          var message = "File with extention \"" + fileExtension + "\" is not allowed. Use only \"jpeg\" | \"png\".";
        
          showComparisonStatusMessages(message, "error");
        
          $('#file-comparison').val('');
        
          return false;

      } else {

          var formID = $('form').attr("id");

          processImages(formID);
        
      }

    } 

  });

});


/* --------------------------------------------------- */
/*   DISPLAY SECOND FILE UPLOAD ERROR MESSAGES
/* --------------------------------------------------- */
function showComparisonStatusMessages(message,status){
  
    "use strict";

    if (status == "success") {
        
        $("#status-message-comparison").addClass("success").removeClass("error");
      
    } else if (status == "error") {
        
        $("#status-message-comparison").removeClass("success").addClass("error");
    }

    $("#status-message-comparison")
      .slideDown()
      .html(message)
      .delay(5000)
      .slideUp();
}



/*===========================================================================
*
*  03 - AJAX CALL TO PROCESS UPLOADED IMAGES
*
*============================================================================*/

function processImages(id) {

    "use strict";

    var currentForm = document.getElementById(id);
    var formData = new FormData(currentForm);

    var currentUrl = id + "-backend.php";
       
     $.ajax({
           type: "POST",
           url: currentUrl,
           data: formData,
           contentType: false,
           processData: false,
           cache: false,
           beforeSend: function() {
              $("#processing").removeClass('deactivated');
           },
           complete: function() {
              $("#processing").addClass('deactivated');
           },
           success: function(data)
           {

              $("#file-label").text('Upload an image...');  
              $("#file-label-comparison").text('Upload an image...');  

               switch(id) {
                  case 'facial-analysis': processFacialAnalysis(data);
                    break; 
                  case 'celebrity-recognition': processCelebrityRecognition(data);
                    break;
                  case 'face-comparison': processFaceComparison(data);
                    break;
                  case 'image-moderation': processImageModeration(data);
                    break;
                  case 'object-detection': processObjectDetection(data);
                    break;
                  case 'text-image': processTextImage(data);
                    break;
                }

            },
            error: function (data) {
                
                var message = data['message'];      
                var status = data['status'];
                showStatusMessages(message, status);

            }

         }).done(function(data) {
                
              $("#file").val('');
              $("#file-comparison").val('');

          })
}



/*===========================================================================
*
*  04 - AJAX CALL TO PROCESS DEMO IMAGES
*
*============================================================================*/

function processSampleImages(id) {

    "use strict";

    var currentForm = document.getElementById(id);
    var formData = new FormData();

    var base64image = $('#sample-image a.active img').attr('src');
    var base64imageComparison = $('#sample-image-comparison a.active img').attr('src');

    formData.append('demo-image', base64image);
    formData.append('demo-image-comparison', base64imageComparison);

    var currentUrl = id + "-backend.php";
       
     $.ajax({
           type: "POST",
           url: currentUrl,
           data: formData,
           contentType: false,
           processData: false,
           cache: false,
           beforeSend: function() {
              $("#processing").removeClass('deactivated');
           },
           complete: function() {
              $("#processing").addClass('deactivated');
           },
           success: function(data)
           {  

               switch(id) {
                  case 'facial-analysis': processFacialAnalysis(data);
                    break; 
                  case 'celebrity-recognition': processCelebrityRecognition(data);
                    break;
                  case 'face-comparison': processFaceComparison(data);
                    break;
                  case 'image-moderation': processImageModeration(data);
                    break;
                  case 'object-detection': processObjectDetection(data);
                    break;
                  case 'text-image': processTextImage(data);
                    break;
                }

            },
            error: function (data) {
                
                var message = data['message'];      
                var status = data['status'];
                showStatusMessages(message, status);

            }

        }).done(function(data) {
                
            $("#file").val('');
            $("#file-comparison").val('');

        })
}



/*===========================================================================
*
*  05 - CALL PROCESSSAMPLEIMAGES() FUNCTION FOR DEMO IMAGES
*
*============================================================================*/

$(document).ready(function(){

  "use strict";

  processSampleImages($('form').attr("id"));

  $('.img-thumb').on('click', function(event) {
    
      event.preventDefault();

      download_data = [];

      $("#labels").html('');

      processSampleImages($('form').attr("id"));
      
  });


  $('.img-thumb-comparison').on('click', function(event) {
    
      event.preventDefault();

      download_data = [];

      $("#labels").html('');

      processSampleImages($('form').attr("id"));
      
  });
    
});



/*===========================================================================
*
*  06 - FACIAL ANALYSIS PROCESS
*
*============================================================================*/

function processFacialAnalysis(data) {

    "use strict";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    var img = document.getElementById("image");

    var imgWidth = img.width;
    var imgHeight = img.height;


    if (data === undefined || data.length == 0) {
      
        $("<div class='label' />").html("<div class='left'>No human face was found in the image</div>").appendTo("#labels");

    } else {

        for( var i = 0; i < data.length; i++ ) {

            download_data.push( [] );

        }


        for (var i = 0; i < data.length; i++) {    

            var smile = (data[i].Smile.Value) ? 'Is smiling' : 'Not smiling';   
            var eyeglasses = (data[i].Eyeglasses.Value) ? 'Is wearing eyeglasses' : "Not wearing eyeglasses";
            var sunglasses = (data[i].Sunglasses.Value) ? 'Is wearing sunglasses' : "Not wearing sunglasses";
            var mustache = (data[i].Mustache.Value) ? 'Appears to have a mustache' : "Doesn't have a mustache";
            var beard = (data[i].Beard.Value) ? 'Appears to have a beard' : "Doesn't have a beard";
            var eyesopen = (data[i].EyesOpen.Value) ? 'Has open eyes' : "Has closed eyes";
            var mouthopen = (data[i].MouthOpen.Value) ? 'Has open mouth' : "Has closed mouth";
            var emotions = [];

            var x = data[i].BoundingBox.Left * imgWidth;
            var y = data[i].BoundingBox.Top * imgHeight;
            var width = data[i].BoundingBox.Width * imgWidth;
            var height = data[i].BoundingBox.Height * imgHeight;

            drawBoundingBox(x, y, width, height);

            for (var y = 0; y < 8; y++) {

                switch(data[i].Emotions[y].Type){
                    case 'SURPRIZED': if(data[i].Emotions[y].Type === 'SURPRIZED' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be suprized';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                      }
                        break;
                    case 'HAPPY': if(data[i].Emotions[y].Type === 'HAPPY' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be happy';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                  }
                        break;
                    case 'FEAR': if(data[i].Emotions[y].Type === 'FEAR' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be afraid';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                 }
                        break;
                    case 'CALM': if(data[i].Emotions[y].Type === 'CALM' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be calm';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                  }
                        break;
                    case 'SAD': if(data[i].Emotions[y].Type === 'SAD' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be sad';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                }
                        break;
                    case 'CONFUSED': if(data[i].Emotions[y].Type === 'CONFUSED' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be confused';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                     }
                        break;
                    case 'ANGRY': if(data[i].Emotions[y].Type === 'ANGRY' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be angry';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                  }
                        break;
                    case 'DISGUSTED': if(data[i].Emotions[y].Type === 'DISGUSTED' && data[i].Emotions[y].Confidence >= 80) {
                                           emotions['emotion'] = 'Appears to be disgusted';
                                           emotions['confidence'] = data[i].Emotions[y].Confidence;
                                      }
                        break;
                }
            }

            
            $("<div class='label' />").html("<div class='left'>" + data[i].Gender.Value + "</div>"+ "<div class='right'>" + data[i].Gender.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'> Age Range</div>"+ "<div class='right'>Between " + data[i].AgeRange.Low + "-" + data[i].AgeRange.High + " years old</div>").appendTo("#labels");
            if(typeof(emotions['emotion']) !== 'undefined' && emotions['emotion'] !== null) $("<div class='label' />").html("<div class='left'>" + emotions['emotion'] + "</div>"+ "<div class='right'>" + emotions['confidence'].toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + smile + "</div>"+ "<div class='right'>" + data[i].Smile.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + eyeglasses + "</div>"+ "<div class='right'>" + data[i].Eyeglasses.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + sunglasses + "</div>"+ "<div class='right'>" + data[i].Sunglasses.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + mustache + "</div>"+ "<div class='right'>" + data[i].Mustache.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + beard + "</div>"+ "<div class='right'>" + data[i].Beard.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + eyesopen + "</div>"+ "<div class='right'>" + data[i].EyesOpen.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>" + mouthopen + "</div>"+ "<div class='right'>" + data[i].MouthOpen.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            
            download_data[i].push(data[i].Gender.Value, data[i].Gender.Confidence.toFixed(2));
            download_data[i].push('Age Range', data[i].AgeRange.Low + '-' + data[i].AgeRange.High);
            if(typeof(emotions['emotion']) !== 'undefined' && emotions['emotion'] !== null)
                download_data[i].push(emotions['emotion'], emotions['confidence'].toFixed(2));
            download_data[i].push(smile, data[i].Smile.Confidence.toFixed(2));
            download_data[i].push(eyeglasses, data[i].Eyeglasses.Confidence.toFixed(2));
            download_data[i].push(sunglasses, data[i].Sunglasses.Confidence.toFixed(2));
            download_data[i].push(mustache, data[i].Mustache.Confidence.toFixed(2));
            download_data[i].push(beard, data[i].Beard.Confidence.toFixed(2));
            download_data[i].push(eyesopen, data[i].EyesOpen.Confidence.toFixed(2) );
            download_data[i].push(mouthopen, data[i].MouthOpen.Confidence.toFixed(2));
        }      

    }  

}



/*===========================================================================
*
*  07 - CELEBRITY RECOGNITION PROCESS
*
*============================================================================*/

function processCelebrityRecognition(data) {

    "use strict";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    var img = document.getElementById("image");

    var imgWidth = img.width;
    var imgHeight = img.height;


    if (data === undefined || data.length == 0) {
      
        $("<div class='label' />").html("<div class='left'>No celebrity match found</div>").appendTo("#labels");

    } else {

        for( var i = 0; i < data.length; i++ ) {

            download_data.push( [] );

        }


        for (var i = data.length - 1; i >= 0; i--) {    
            
            $("<div class='label' />").html("<div class='left'>" + data[i].Name + "</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'><a href=https://" + data[i].Urls + " target='_blank'>Learn More</a></div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>Match Confidence</div><div class='right'>" + data[i].MatchConfidence + "%</div>").appendTo("#labels");
            
            if (i !== 0) {
                $("<div class='label' />").html("<div class='left'></div>").appendTo("#labels");
            }

            download_data[i].push(data[i].Name);
            download_data[i].push(data[i].Urls);
            download_data[i].push('Match Confidence', data[i].MatchConfidence);
            
            var x = data[i].Face.BoundingBox.Left * imgWidth;;
            var y = data[i].Face.BoundingBox.Top * imgHeight;
            var width = data[i].Face.BoundingBox.Width * imgWidth;
            var height = data[i].Face.BoundingBox.Height * imgHeight;
                    
            drawBoundingBox(x, y, width, height);

        }

     }       
}



/*===========================================================================
*
*  08 - FACE COMPARISON PROCESS
*
*============================================================================*/

function processFaceComparison(data) {

    "use strict";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    var img = document.getElementById("image-comparison");

    var imgWidth = img.width;
    var imgHeight = img.height;


    if (data === undefined || data.length == 0) {
      
        $("<div class='label' />").html("<div class='left'>No match was found</div>").appendTo("#labels");

    } else {


        for (var i = data.length - 1; i >= 0; i--) {    
            
            $("<div class='label' />").html("<div class='left'>Face Match Found</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>Similarity</div><div class='right'>" + data[i].Similarity.toFixed(2) + "%</div>").appendTo("#labels");
            $("<div class='label' />").html("<div class='left'>Face Match Confidence</div><div class='right'>" + data[i].Face.Confidence.toFixed(2) + "%</div>").appendTo("#labels");
            
            
            var x = data[i].Face.BoundingBox.Left * imgWidth;;
            var y = data[i].Face.BoundingBox.Top * imgHeight;
            var width = data[i].Face.BoundingBox.Width * imgWidth;
            var height = data[i].Face.BoundingBox.Height * imgHeight;
                    
            drawBoundingBox(x, y, width, height);

        }

     } 

}



/*===========================================================================
*
*  09 - IMAGE MODERATION PROCESS
*
*============================================================================*/

function processImageModeration(data) {

    "use strict";

    if (data === undefined || data.length == 0) {
      
        $("<div class='label' />").html("<div class='left'>No adult content was found</div>").appendTo("#labels");

    } else {

        for (var i = 0; i < data.length; i++) {    
            
            $("<div class='label' />").html("<div class='left'>" + data[i].Name + "</div><div class='right'>" + data[i].Confidence.toFixed(2) + "%</div>").appendTo("#labels");

        }

     } 
}


/*===========================================================================
*
*  10 - OBJECT DETECTION PROCESS
*
*============================================================================*/

function processObjectDetection(data) {

    "use strict";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    var img = document.getElementById("image");

    var imgWidth = img.width;
    var imgHeight = img.height;

    if (data === undefined || data === null) {
      
        $("<div class='label' />").html("<div class='left'>No object was found</div>").appendTo("#labels");

    } else {

        for( var i = 0; i < data.length; i++ ) {

            download_data.push( [] );

        }


        for (var i = 0; i < data.length; i++) {    
            
            $("<div class='label' />").html("<div class='left'>" + data[i].Name + "</div><div class='right'>" + data[i].Confidence.toFixed(2) + "%</div>").appendTo("#labels");

            download_data[i].push(data[i].Name, data[i].Confidence.toFixed(2));

            if(data[i].Instances.length > 0) {

                for(var z = 0; z < data[i].Instances.length; z++) {

                    var x = data[i].Instances[z].BoundingBox.Left * imgWidth;;
                    var y = data[i].Instances[z].BoundingBox.Top * imgHeight;
                    var width = data[i].Instances[z].BoundingBox.Width * imgWidth;
                    var height = data[i].Instances[z].BoundingBox.Height * imgHeight;
                    
                    drawBoundingBox(x, y, width, height);

                }

            }
            

        }

     } 

}



/*===========================================================================
*
*  11 - TEXT IN THE IMAGE PROCESS
*
*============================================================================*/

function processTextImage(data) {

    "use strict";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    var img = document.getElementById("image");

    var imgWidth = img.width;
    var imgHeight = img.height;


    if (data === undefined || data.length == 0) {
      
        $("<div class='label' />").html("<div class='left'>No text was detected</div>").appendTo("#labels");

    } else {

        for( var i = 0; i < data.length; i++ ) {

            download_data.push( [] );

        }


        for (var i = 0; i < data.length; i++) {    
            
            if (data[i].Type === "LINE") {

                $("<div class='label' />").html("<div class='left'>" + data[i].DetectedText + "</div><div class='right'>" + data[i].Confidence.toFixed(2) + "%</div>").appendTo("#labels");
                
                download_data[i].push(data[i].DetectedText, data[i].Confidence.toFixed(2));
                
                var x = data[i].Geometry.BoundingBox.Left * imgWidth;;
                var y = data[i].Geometry.BoundingBox.Top * imgHeight;
                var width = data[i].Geometry.BoundingBox.Width * imgWidth;
                var height = data[i].Geometry.BoundingBox.Height * imgHeight;
                        
                drawBoundingBox(x, y, width, height);
            }            

        }

     } 
}



/*===========================================================================
*
*  12 - DOWNLOAD LABELS BUTTON
*
*============================================================================*/

var download_data = [];

function downloadRekognitionResults() {

    "use strict";

    var csv = 'Results, Confidence\n';

    download_data.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
    });
 
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = $.now() + '.csv';
    hiddenElement.click();
}



/*===========================================================================
*
*  13 - CUSTOM INPUT FIELD FOR UPLOAD BUTTON
*
*============================================================================*/

$(document).ready(function() {

  "use strict";

  $('#file').each( function() {

    var $input   = $( this ),
      $label   = $input.next( 'label' ),
      labelVal = $label.html();

    $input.on( 'change', function( e ) {

      var fileName = '';

      if( this.files && this.files.length > 1 ) {

        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
      
      } else if( e.target.value ) {

        fileName = e.target.value.split( '\\' ).pop();

      }

      if( fileName ) {

        $label.find( 'span' ).html( '1 File Selected' );

        updateImage(this);

      } else {
        
        $label.html( labelVal );

      }

    });

    // Firefox bug fix
    $input
    .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
    .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
  });

});


/* --------------------------------------------------- */
/*   DISPLAY IN THE MAIN IMAGE BOX
/* --------------------------------------------------- */
function updateImage(input) {

  "use strict";

  if (input.files && input.files[0]) {
     
      var reader = new FileReader();
      
      reader.onload = function (e) {

          $('#image').attr('src', e.target.result);

      }
      
      reader.readAsDataURL(input.files[0]);
  }

  /* Blur Image for Active Image for Image Moderation */
  $('.moderation').foggy({
        blurRadius: 20,          
        opacity: 0.7,            
        cssFilterSupport: true   
  });

  $('#image-blur').css('display', 'none');
  $('#image-view').css('display', 'block');

}
    


/*===========================================================================
*
*  14 - CUSTOM INPUT FIELD FOR SECOND UPLOAD BUTTON (FACE COMPARISON)
*
*============================================================================*/

$(document).ready(function() {

  "use strict";

  $('#file-comparison').each( function() {

    var $input   = $( this ),
      $label   = $input.next( 'label' ),
      labelVal = $label.html();

    $input.on( 'change', function( e ) {

      var fileName = '';

      if( this.files && this.files.length > 1 ) {

        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
      
      } else if( e.target.value ) {

        fileName = e.target.value.split( '\\' ).pop();

      }

      if( fileName ) {

        $label.find( 'span' ).html( '1 File Selected' );

        updateImageComparison(this);

      } else {
        
        $label.html( labelVal );

      }

    });

    // Firefox bug fix
    $input
    .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
    .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
  });

});


/* --------------------------------------------------------- */
/*   DISPLAY IN THE SECOND MAIN IMAGE BOX (FACE COMPARISON)
/* --------------------------------------------------------- */
function updateImageComparison(input) {

  "use strict";

  if (input.files && input.files[0]) {
     
      var reader = new FileReader();
      
      reader.onload = function (e) {

          $('#image-comparison').attr('src', e.target.result);

      }
      
      reader.readAsDataURL(input.files[0]);
  }

}



/*===========================================================================
*
*  15 - DEMO IMAGES DISPLAY
*
*============================================================================*/

$(document).ready(function () {

  "use strict";

  /* --------------------------------------------------- */
  /*   DISPLAY LARGE VERSION OF IMAGE THUMBS
  /* --------------------------------------------------- */
  $('.img-thumb img').click(function(e){

    e.preventDefault();

    $('#image').attr('src',$(this).attr('src'));

  });


  /* ---------------------------------------------------------------- */
  /*   DISPLAY LARGE VERSION OF SECOND IMAGE THUMBS (FACE COMPARISON)
  /* ---------------------------------------------------------------- */
  $('.img-thumb-comparison img').click(function(e){

    e.preventDefault();

    $('#image-comparison').attr('src',$(this).attr('src'));

  });


  /* --------------------------------------------------- */
  /*   OUTER BORDER FOR IMAGE THUMBS
  /* --------------------------------------------------- */
  $('#sample-image a img').click(function(e) {

      e.preventDefault();

      $('#sample-image a').removeClass('active');

      var $parent = $(this).parent();
      
      $parent.addClass('active');

      /* Blur Image for Active Image */
      $('.moderation').foggy({
        blurRadius: 20,          
        opacity: 0.7,            
        cssFilterSupport: true   
      });

      $('#image-blur').css('display', 'none');
      $('#image-view').css('display', 'block');
      
  });


  /* -------------------------------------------------------- */
  /*   OUTER BORDER FOR SECOND IMAGE THUMBS (FACE COMPARISON)
  /* -------------------------------------------------------- */
  $('#sample-image-comparison a img').click(function(e) {

      e.preventDefault();

      $('#sample-image-comparison a').removeClass('active');

      var $parent = $(this).parent();
      
      $parent.addClass('active');
      
  });

});



/*===========================================================================
*
*  16 - SHOW BLURRED IMAGE (IMAGE MODERATION)
*
*============================================================================*/

$(document).ready(function(){

    "use strict";

    $('.moderation').foggy({
       blurRadius: 20,          // In pixels.
       opacity: 0.7,            // Falls back to a filter for IE.
       cssFilterSupport: true   // Use "-webkit-filter" where available.
    });


    $('#image-view').on('click', function(e){

      e.preventDefault();
      
      $('.moderation').foggy(false);

      $('#image-view').css('display', 'none');
      $('#image-blur').css('display', 'block');

    });


    $('#image-blur').on('click', function(e){

      e.preventDefault();
      
      $('.moderation').foggy({
        blurRadius: 20,          
        opacity: 0.7,            
        cssFilterSupport: true   
      });

      $('#image-blur').css('display', 'none');
      $('#image-view').css('display', 'block');

    }); 

});



/*===========================================================================
*
*  17 - DISPLAY CANVAS BOUNDING BOXES
*
*============================================================================*/

var $canvas = document.getElementById('canvas');
var ctx = canvas.getContext("2d");

$(document).ready(function(){

    "use strict";

    if($('form').attr("id") == 'face-comparison') {
      
        $canvas.width = $("#image-wrapper-comparison").width();
        $canvas.height = $("#image-wrapper-comparison").height();

    } else {

        $canvas.width = $("#image-wrapper").width();
        $canvas.height = $("#image-wrapper").height();

    }
    
});


function drawBoundingBox(left, top, width, height) {

  "use strict";

  var x = left;
  var y = top;
  var w = width;
  var h = height;

  ctx.beginPath();
  ctx.lineWidth = "2";
  ctx.strokeStyle = "#00BFFF";
  ctx.rect(x, y, w, h);
  ctx.stroke();

}