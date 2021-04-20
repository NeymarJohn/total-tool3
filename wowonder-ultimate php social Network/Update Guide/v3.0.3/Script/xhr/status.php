<?php 
if ($f == 'status') {
    if ($s == 'new') {
        $data  = array(
            'message' => $error_icon . $wo['lang']['please_check_details'],
            'status' => 500
        );
        $error = false;
        if (!isset($_FILES['statusMedia'])) {
            $error = true;
        } else {
            if (gettype($_FILES['statusMedia']) != 'array' || count($_FILES['statusMedia']) < 1 || count($_FILES['statusMedia']) > 20) {
                $error = true;
            }
            if (isset($_POST['title']) && strlen($_POST['title']) > 100) {
                $error = true;
            }
            if (isset($_POST['description']) && strlen($_POST['description']) > 300) {
                $error = true;
            }
            if (!Wo_IsValidMimeType($_FILES['statusMedia']['type'])) {
                $error = true;
            }
        }
        if (!$error) {
            $registration_data            = array();
            $registration_data['user_id'] = $wo['user']['id'];
            $registration_data['posted']  = time();
            $registration_data['expire']  = time()+(60*60*24);
            if (isset($_POST['title']) && strlen($_POST['title']) >= 2) {
                $registration_data['title'] = Wo_Secure($_POST['title']);
            }
            if (isset($_POST['description']) && strlen($_POST['description']) >= 10) {
                $registration_data['description'] = Wo_Secure($_POST['description']);
            }
            if (count($registration_data) > 0) {
                $last_id = Wo_InsertUserStory($registration_data);
                if ($last_id && is_numeric($last_id) && !empty($_FILES["statusMedia"])) {
                    $files   = Wo_MultipleArrayFiles($_FILES["statusMedia"]);
                    $sources = array();
                    $thumb   = '';
                    foreach ($files as $fileInfo) {
                        if ($fileInfo['size'] > 0) {
                            $fileInfo['file'] = $fileInfo['tmp_name'];
                            $media            = Wo_ShareFile($fileInfo);
                            $file_type        = explode('/', $fileInfo['type']);
                            if ($media['filename']) {
                                $sources[] = array(
                                    'story_id' => $last_id,
                                    'type' => $file_type[0],
                                    'filename' => $media['filename'],
                                    'expire' => time()+(60*60*24)
                                );
                            }
                            if (empty($thumb)) {
                                if (in_array(strtolower(pathinfo($media['filename'], PATHINFO_EXTENSION)), array(
                                    "gif",
                                    "jpg",
                                    "png",
                                    'jpeg'
                                ))) {
                                    $thumb             = $media['filename'];
                                    $explode2          = @end(explode('.', $thumb));
                                    $explode3          = @explode('.', $thumb);
                                    $last_file         = $explode3[0] . '_small.' . $explode2;
                                    $arrContextOptions = array(
                                        "ssl" => array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false
                                        )
                                    );
                                    $fileget           = file_get_contents(Wo_GetMedia($thumb), false, stream_context_create($arrContextOptions));
                                    if (!empty($fileget)) {
                                        $importImage = @file_put_contents($thumb, $fileget);
                                    }
                                    $crop_image = Wo_Resize_Crop_Image(400, 400, $thumb, $last_file, 60);
                                    $upload_s3  = Wo_UploadToS3($last_file);
                                    $thumb      = $last_file;
                                }
                            }
                        }
                    }
                    $img_types     = array(
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
                        'image/gif'
                    );
                    if (in_array(strtolower(pathinfo($media['filename'], PATHINFO_EXTENSION)), array(
                                    "m4v",
                                    "avi",
                                    "mpg",
                                    'mp4'
                                )) && !empty($_FILES["cover"]) && in_array($_FILES["cover"]["type"], $img_types)) {
                        $fileInfo = array(
                            'file' => $_FILES["cover"]["tmp_name"],
                            'name' => $_FILES['cover']['name'],
                            'size' => $_FILES["cover"]["size"],
                            'type' => $_FILES["cover"]["type"]
                        );
                        $media            = Wo_ShareFile($fileInfo);
                        $file_type        = explode('/', $fileInfo['type']);
                        if (empty($thumb)) {
                            if (in_array(strtolower(pathinfo($media['filename'], PATHINFO_EXTENSION)), array(
                                "gif",
                                "jpg",
                                "png",
                                'jpeg'
                            ))) {
                                $thumb             = $media['filename'];
                                $explode2          = @end(explode('.', $thumb));
                                $explode3          = @explode('.', $thumb);
                                $last_file         = $explode3[0] . '_small.' . $explode2;
                                $arrContextOptions = array(
                                    "ssl" => array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false
                                    )
                                );
                                $fileget           = file_get_contents(Wo_GetMedia($thumb), false, stream_context_create($arrContextOptions));
                                if (!empty($fileget)) {
                                    $importImage = @file_put_contents($thumb, $fileget);
                                }
                                $crop_image = Wo_Resize_Crop_Image(400, 400, $thumb, $last_file, 60);
                                $upload_s3  = Wo_UploadToS3($last_file);
                                $thumb      = $last_file;
                            }
                        }

                    }
                    if (count($sources) > 0) {
                        foreach ($sources as $registration_data) {
                            Wo_InsertUserStoryMedia($registration_data);
                        }
                        if (!empty($thumb)) {
                            $thumb        = Wo_Secure($thumb);
                            $mysqli_query = mysqli_query($sqlConnect, "UPDATE " . T_USER_STORY . " SET thumbnail = '$thumb' WHERE id = $last_id");
                        }
                        $data = array(
                            'message' => $success_icon . $wo['lang']['status_added'],
                            'status' => 200
                        );
                    }
                }
            }
        }
        if ($wo['loggedin'] == true) {
            Wo_CleanCache();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'p' && isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $data    = array(
            "status" => 404
        );
        $id      = Wo_Secure($_GET['id']);
        $html    = '';
        $stories = Wo_GetStroies(array(
            'user' => $id
        ));
        if (count($stories) > 0) {
            foreach ($stories as $wo['story']) {
                $html .= Wo_LoadPage('status/content');
            }
            $data = array(
                "status" => 200,
                "html" => $html
            );
        } else {
            $html = Wo_LoadPage('status/no-stories');
            $data = array(
                "status" => 404,
                "html" => $html
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'remove' && isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $data = array(
            "status" => 304
        );
        if (Wo_DeleteStatus($_GET['id'])) {
            $data['status'] = 200;
            Wo_CleanCache();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'lm' && isset($_GET['offset']) && is_numeric($_GET['offset']) && $_GET['offset'] > 0) {
        $data    = array(
            'status' => 404
        );
        $html    = '';
        $stories = Wo_GetAllStories($_GET['offset']);
        if ($stories && count($stories) > 0) {
            foreach ($stories as $wo['status']) {
                $html .= Wo_LoadPage('admin/status/status-list');
            }
            $data = array(
                'status' => 200,
                'html' => $html
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
