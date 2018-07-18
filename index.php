<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- style -->
      <!-- <script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script> -->
      <script type="text/javascript" src="assets/js/jquery.js"></script>
    	<script src="assets/js/bootstrap.min.js"></script>
    	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- style -->
    <title>upload photo frame</title>
  </head>

  <body>
    <br>
    <div class="container">
    <!-- <div class="alert alert-info alert-dismissible fade show" role="alert">
      <strong>Holy guacamole!</strong> You should check in on some of those fields below.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div> -->

    <!-- <div class="container"> -->
      <!-- form-->
      <form id="image-form" enctype="multipart/form-data">
        <div class="form-group">
          <label>Photo</label>
          <input onchange="prevImg(this);" class="form-control" type="file" name="imagefile" id="imageFile">
        </div>
        <div class="form-group">
          <label>Frame</label>
          <?php
            include 'koneksi.php';
            $s= 'SELECT * FROM parameter where param1="frame" ORDER BY nama ASC';
            $e=mysqli_query($conn,$s);
           ?>
          <select onchange="prevFrame();"  class="form-control" name="frame" id="frame">
            <option value="">-Select Frame-</option>
            <?php
            while ($r=mysqli_fetch_assoc($e)) {
              echo '<option value="'.$r['nama'].'">'.$r['param2'].'</option>';
              // echo '<option value="'.$r['id_param'].'">'.$r['param2'].'</option>';
            }
           ?>
          </select>
        </div>
        <div class="form-group">
          <img width="250" id="imagePreview"  src="uploads/no_preview.png" alt="" />
          <!-- <img width="250" id="imagePreview" style="background:url(uploads/photo01.png)" src="uploads/no_preview.png" alt="" /> -->
        </div>
        <input type="hidden" id="tempImagePreview">
        <button  class="btn btn-primary" type="submit" name="submit" id="image-submit">Save</button>
      </form>
      <!-- form-->

    </div>
  </body>

  <script>

    $(document).ready(function(){
      // code here
    });

    function resetForm() {
      $('#imagePreview').val('');
      $('#imageFile').val('');
      $('#frame').val('');
    }

    function prevFrame() {
      if ($('#imageFile').val()=='') {
        alert('select image first');
        $('#frame').val('');
      } else {
        var selectedFrame = $('#frame').val();
        var selectedImage = $('#tempImagePreview').val();
        $('#imagePreview').attr({
          src:'frame/'+selectedFrame,
          style:'background:url('+selectedImage+')'
        });
      }
    }

    function prevImg(input) {
      $('#frame').val('');

      if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
          // set image source -> temporary
            $('#tempImagePreview').val(e.target.result);

          // set image source -> preview image
            $('#imagePreview').attr('src', e.target.result);
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
    }

    $('form#image-form').submit(function(){
      var formData = new FormData($(this)[0]);
      $.ajax({
        url: 'upload.php',
        // url: './api/myapi.php',
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
