<?php
  // header('Content-Type: application/json; charset=UTF-8');

  // include 'koneksi.php';
  include 'lib.php';
  $dir='uploads/';

  // echo json_encode(['status'=>"OK"]);
  // pr($_FILES);
  if(isset($_FILES["imagefile"]["type"])) { //check if user send a file
      $validextensions = array('jpeg', 'jpg','png'); //define our valid extensions
      $temporary = explode('.', $_FILES['imagefile']['name']); //exploade name of file to name and extension
      $file_extension = end($temporary); //save it's extension

      if ($_FILES['imagefile']['size'] < 5000000 && in_array($file_extension, $validextensions)) { //check size of file that smaller than 5MB and also format of that that in my valid extensions
          if ($_FILES['imagefile']['error'] > 0) { //if we have error send to jQuery Ajax
              echo 'File Error: ' . mysqli_error($conn);
              // echo 'File Error: ' . mysqli_error($db);
              $output['status'] = 'build_error';
          } else {
              $giliapps_id = 'giliapps'.uniqid(); //define a dynamic var to use for name of new image like post id or time and more
              // $giliapps_id = 'giliapps' . $some_dynamic_var; //define a dynamic var to use for name of new image like post id or time and more
              $sourcePath = $_FILES['imagefile']['tmp_name']; //get source path of image
              list($width, $height) = getimagesize($_FILES['imagefile']['tmp_name']); //get width and height of image
              $ratio = $height / $width; //compute aspect ratio to prevent deformation
              $new_width = 1600; //define my new width
              $new_height = $new_width * $ratio; //compte new height with keeping aspect ratio
              $targetPath = $dir. $giliapps_id . "." . $file_extension; //define target path on server to save image
              vd($targetPath);
              $upfile = move_uploaded_file($sourcePath, $targetPath); //send file from client to server
              $new_image = imagecreatetruecolor($new_width, $new_height); //define blank image with new width and height

              if ($file_extension == 'png') { //check if that image is a PNG
                  $old_image = imagecreatefrompng($targetPath); //add our new PNG image on server to a var
              } else {
                  $old_image = imagecreatefromjpeg($targetPath); //add our new JPG image on server to a var
              }
              $imgCopyResampled=imagecopyresampled($new_image,$old_image,0,0,0,0,$new_width, $new_height, $width, $height); //insert exist image on server to our new blank image, that is CROP + RESIZE
              $targetPath2 = $dir. $giliapps_id . "-final.jpg"; //define a new path on server to save new image

              if ($file_extension == 'png') { //check if new cropped and resized image is a PNG or no
                  imagejpeg($new_image, $targetPath2, 100); //save new JPG image on server, that is CONVERT
                  unlink($targetPath); //remove unused PNG image
              } else {
                  imagejpeg($new_image, $targetPath2, 100); //save new JPG image on server
              }
              $o1 = imagecreatefrompng("overlay1.png"); //define my first watermark PNG image
              $o2 = imagecreatefrompng("overlay2.png"); //define my second watermark PNG image
              imagecopyresampled($new_image, $o1, 615, $new_height - 80, 0, 0, 985, 80, 985, 80); //add first watermark to right bottom of new image, that is ADD WATERMARK
              imagejpeg($new_image, $targetPath2, 100); //create another JPG file
              imagecopyresampled($new_image, $o2, 0, 0, 0, 0, 390, 105, 390, 105); //add second watermark to left top of new image
              imagejpeg($new_image, $targetPath2, 100); //create final JPG file
          }
      }
  }
?>
