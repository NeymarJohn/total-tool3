<?php 
if ($f == 'update_user_avatar_picture') {
    $images = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');
    if (isset($_FILES['avatar']['name'])) {
        if (Wo_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $_POST['user_id']) === true) {
            $img  = Wo_UserData($_POST['user_id']);
            $data = array(
                'status' => 200,
                'img' => $img['avatar'] . '?cache=' . rand(11, 22),
                'img_or' => $img['avatar_org'],
                'avatar_full' => Wo_GetMedia($img['avatar_full']) . '?cache=' . rand(11, 22),
                'avatar_full_or' => $img['avatar_full'],
                'big_text' => $wo['lang']['looks_good'],
                'small_text' => $wo['lang']['looks_good_des']
            );
        }
    }
    // else if(isset($_POST['selected_image']) && !empty($_POST['selected_image']) && in_array($_POST['selected_image'], $images)){
    //     $num = Wo_Secure($_POST['selected_image']);
    //     $filename = @Wo_ImportImageFromUrl($wo['config']['site_url'].'/upload/photos/'.$num.'.jpg');
    //     $explode2  = @end(explode('.', $filename));
    //     $explode3  = @explode('.', $filename);
    //     $last_file = $explode3[0] . '_full.' . $explode2;
    //     $compress  = Wo_CompressImage($filename, $last_file, 50);
    //     $upload_s3      = Wo_UploadToS3($last_file);
    //     Wo_Resize_Crop_Image($wo['profile_picture_width_crop'], $wo['profile_picture_height_crop'], $filename, $filename, $wo['profile_picture_image_quality']);
    //     $upload_s3 = Wo_UploadToS3($filename);
    //     $new = $filename;
        
    //     $update_data = Wo_UpdateUserData($wo['user']['user_id'], array('avatar' => $new,
    //                                                                        'startup_image' => 1));
    //     $data['status'] = 200;
    //     $data['img'] = '';
    // }
    Wo_CleanCache();
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
