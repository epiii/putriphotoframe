<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
  </head>

  <body>
    <form id="image-form" enctype="multipart/form-data">
      <input type="file" name="imagefile" id="imagefile">
      <button type="submit" name="submit" id="image-submit">Save</button>
    </form>
  </body>
  <script>
    $('form#image-form').submit(function(){
      var formData = new FormData($(this)[0]);
      $.ajax({
        url: './api/myapi.php',
        data: formData,
        type: 'POST',
        dataType: 'JSON',
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#preloader').fadeIn('fast'); //show a loader for waiting
        },
        complete: function() {
          $('#preloader').fadeOut('fast'); //hide that loader
        },
        success: function (result) {
          if(result['status'] == 'OK') {
            alert("Your image was saved. Thank you 🙏🙏"); //you can use frameworks to beautify notifications
            document.getElementById("image-form").reset(); //reset form to add another image
          } else {
            alert("Sorry! Your image was not saved. Please try again 😔");
          }
        },
        error: function (xhr, status, error) {
          alert("Server Error! Your image was not saved. Please try again 😤");
        }
      });
      return false;
    });
  </script>
</html>
