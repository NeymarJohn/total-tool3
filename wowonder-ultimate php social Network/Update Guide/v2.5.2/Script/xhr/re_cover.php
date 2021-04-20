<?php 
if ($f == 're_cover') {
   if (isset($_POST['pos'])) {
       if (($_POST['cover_image'] != $wo['userDefaultCover']) &&
       ($_POST['cover_image'] == $wo['user']['cover_org'] || Wo_IsAdmin()) &&
       (Wo_GetMedia($wo['user']['cover_full']) == $_POST['real_image']) || Wo_IsAdmin()) {
          $cover_image_extension = pathinfo($_POST['cover_image'])['extension'];
          $real_image_extension = pathinfo($_POST['real_image'])['extension'];
          $extension_allowed = explode(',', $wo['config']['allowedExtenstion']);
          if(in_array($cover_image_extension, $extension_allowed) && in_array($real_image_extension, $extension_allowed)){

             $from_top             = abs($_POST['pos']);
             $cover_image          = $_POST['cover_image'];
             $full_url_image       = Wo_GetMedia($_POST['cover_image']);
             $default_image        = explode('.', $_POST['cover_image']);
             $default_image        = $default_image[0] . '_full.' . $cover_image_extension;
             $get_default_image    = file_put_contents($default_image, file_get_contents($_POST['real_image']));
             $image_type           = $_POST['image_type'];
             $default_cover_width  = 1120;
             $default_cover_height = 276;
             require_once("assets/libraries/thumbncrop.inc.php");
             $tb = new ThumbAndCrop();
             $tb->openImg($default_image);
             $newHeight = $tb->getRightHeight($default_cover_width);
             $tb->creaThumb($default_cover_width, $newHeight);
             $tb->setThumbAsOriginal();
             $tb->cropThumb($default_cover_width, 366, 0, $from_top);
             $tb->saveThumb($cover_image);
             $tb->resetOriginal();
             $tb->closeImg();
             $upload_s3        = Wo_UploadToS3($cover_image);
             $update_user_data = Wo_UpdateUserData($wo['user']['user_id'], array(
                 'last_cover_mod' => time()
             ));
          }
       }
       if (empty($full_url_image)) {
           $full_url_image = Wo_GetMedia($wo['userDefaultCover']);
       }
       $data = array(
           'status' => 200,
           'url' => $full_url_image . '?timestamp=' . md5(time())
       );
   }
   Wo_CleanCache();
   header("Content-type: application/json");
   echo json_encode($data);
   exit();
}
