<?php 
if ($f == 'products') {
    if ($s == 'create' && Wo_CheckSession($hash_id) === true) {
        if ($wo['config']['who_upload'] == 'pro' && $wo['user']['is_pro'] == 0 && !Wo_IsAdmin() && !empty($_FILES['postPhotos'])) {
            $errors[] = $error_icon . $wo['lang']['free_plan_upload_pro'];
        }
        if (empty($_POST['name']) || empty($_POST['category']) || empty($_POST['description'])) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else if (empty($_POST['price'])) {
            $errors[] = $error_icon . $wo['lang']['please_choose_price'];
        } else if (!is_numeric($_POST['price'])) {
            $errors[] = $error_icon . $wo['lang']['please_choose_c_price'];
        } else if ($_POST['price'] == '0.00') {
            $errors[] = $error_icon . $wo['lang']['please_choose_price'];
        } else if (empty($_FILES['postPhotos']['name'])) {
            $errors[] = $error_icon . $wo['lang']['please_upload_image'];
        }
        if (isset($_FILES['postPhotos']['name'])) {
            $allowed = array(
                'gif',
                'png',
                'jpg',
                'jpeg'
            );
            for ($i = 0; $i < count($_FILES['postPhotos']['name']); $i++) {
                $new_string = pathinfo($_FILES['postPhotos']['name'][$i]);
                if (!in_array(strtolower($new_string['extension']), $allowed)) {
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }
            }
        }
        $type = 0;
        if (!empty($_POST['type'])) {
            $type = 1;
        }
        $currency = 0;
        if (isset($_POST['currency'])) {
            if (in_array($_POST['currency'], array_keys($wo['currencies']))) {
                $currency = Wo_Secure($_POST['currency']);
            }
        }
        if (empty($errors)) {
            $sub_category = '';
            if (!empty($_POST['product_sub_category']) && !empty($wo['products_sub_categories'][$_POST['category']])) {
                foreach ($wo['products_sub_categories'][$_POST['category']] as $key => $value) {
                    if ($value['id'] == $_POST['product_sub_category']) {
                        $sub_category = $value['id'];
                    }
                }
            }
            $lat = '';
            $lng = '';
            if (!empty($_POST['lat-product'])) {
                if (is_numeric($_POST['lat-product'])) {
                    $lat = $_POST['lat-product'];
                }
            }
            if (!empty($_POST['lng-product'])) {
                if (is_numeric($_POST['lng-product'])) {
                    $lng = $_POST['lng-product'];
                }
            }
            $page_id = 0;
            if (!empty($_POST['page_id']) && is_numeric($_POST['page_id']) && $_POST['page_id'] > 0 && Wo_IsPageOnwer(Wo_Secure($_POST['page_id']))) {
                $page_id = Wo_Secure($_POST['page_id']);
            }
            $price              = Wo_Secure($_POST['price']);
            $product_data_array = array(
                'user_id' => $wo['user']['user_id'],
                'name' => Wo_Secure($_POST['name']),
                'category' => Wo_Secure($_POST['category']),
                'sub_category' => $sub_category,
                'description' => Wo_Secure($_POST['description']),
                'time' => Wo_Secure(time()),
                'price' => $price,
                'type' => $type,
                'location' => Wo_Secure($_POST['location']),
                'currency' => $currency,
                'active' => 1,
                'lat' => Wo_Secure($lat),
                'lng' => Wo_Secure($lng),
                'page_id' => $page_id
            );
            $fields = Wo_GetCustomFields('product'); 
            if (!empty($fields)) {
                foreach ($fields as $key => $field) {
                    if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                        $errors[] = $error_icon . $wo['lang']['please_check_details'];
                        header("Content-type: application/json");
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                        exit();
                    }
                    elseif (!empty($_POST['fid_'.$field['id']])) {
                        $product_data_array['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                    }
                }
            }
            $product_data       = Wo_RegisterProduct($product_data_array);
            $product_id         = 0;
            if (!$product_data) {
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
                header("Content-type: application/json");
                echo json_encode(array(
                    'errors' => $errors
                ));
                exit();
            }
            $product_id = $product_data;
            $post_data  = array(
                'user_id' => Wo_Secure($wo['user']['user_id']),
                'product_id' => Wo_Secure($product_id),
                'postPrivacy' => Wo_Secure(0),
                'time' => time(),
                'page_id' => $page_id
            );
            $id         = Wo_RegisterPost($post_data);
            if (count($_FILES['postPhotos']['name']) > 0 && !empty($id) && $id > 0) {
                for ($i = 0; $i < count($_FILES['postPhotos']['name']); $i++) {
                    $fileInfo = array(
                        'file' => $_FILES["postPhotos"]["tmp_name"][$i],
                        'name' => $_FILES['postPhotos']['name'][$i],
                        'size' => $_FILES["postPhotos"]["size"][$i],
                        'type' => $_FILES["postPhotos"]["type"][$i],
                        'types' => 'jpg,png,jpeg,gif'
                    );
                    $file     = Wo_ShareFile($fileInfo, 1);
                    if (!empty($file)) {
                        $media_album = Wo_RegisterProductMedia($product_id, $file['filename']);
                    }
                }
            }
            $data = array(
                'status' => 200,
                'href' => Wo_SeoLink('index.php?link1=post&id=' . $id)
            );
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'edit' && Wo_CheckSession($hash_id) === true) {
        if (empty($_POST['name']) || empty($_POST['category']) || empty($_POST['description'])) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else if (empty($_POST['price'])) {
            $errors[] = $error_icon . $wo['lang']['please_choose_price'];
        } else if (!is_numeric($_POST['price'])) {
            $errors[] = $error_icon . $wo['lang']['please_choose_c_price'];
        } else if ($_POST['price'] == '0.00') {
            $errors[] = $error_icon . $wo['lang']['please_choose_price'];
        }
        if (isset($_FILES['postPhotos']['name'])) {
            $allowed = array(
                'gif',
                'png',
                'jpg',
                'jpeg'
            );
            for ($i = 0; $i < count($_FILES['postPhotos']['name']); $i++) {
                $new_string = pathinfo($_FILES['postPhotos']['name'][$i]);
                if (!in_array(strtolower($new_string['extension']), $allowed)) {
                    $errors[] = $error_icon . $wo['lang']['please_check_details'];
                }
            }
        }
        $type = 0;
        if (!empty($_POST['type'])) {
            $type = 1;
        }
        $currency = 0;
        if (isset($_POST['currency'])) {
            if (in_array($_POST['currency'], array_keys($wo['currencies']))) {
                $currency = Wo_Secure($_POST['currency']);
            }
        }
        if (empty($errors)) {
            $sub_category = '';
            if (!empty($_POST['product_sub_category']) && !empty($wo['products_sub_categories'][$_POST['category']])) {
                foreach ($wo['products_sub_categories'][$_POST['category']] as $key => $value) {
                    if ($value['id'] == $_POST['product_sub_category']) {
                        $sub_category = $value['id'];
                    }
                }
            }
            $price              = Wo_Secure($_POST['price']);
            $product_data_array = array(
                'name' => $_POST['name'],
                'category' => $_POST['category'],
                'sub_category' => $sub_category,
                'description' => $_POST['description'],
                'price' => $price,
                'location' => Wo_Secure($_POST['location']),
                'type' => $type,
                'currency' => $currency
            );

            $fields = Wo_GetCustomFields('product'); 
            if (!empty($fields)) {
                foreach ($fields as $key => $field) {
                    if ($field['required'] == 'on' && empty($_POST['fid_'.$field['id']])) {
                        $errors[] = $error_icon . $wo['lang']['please_check_details'];
                        header("Content-type: application/json");
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                        exit();
                    }
                    elseif (!empty($_POST['fid_'.$field['id']])) {
                        $product_data_array['fid_'.$field['id']] = Wo_Secure($_POST['fid_'.$field['id']]);
                    }
                }
            }

            $product_data       = Wo_UpdateProductData($_POST['product_id'], $product_data_array);
            $product_id         = $_POST['product_id'];
            if (!$product_data) {
                $errors[] = $error_icon . $wo['lang']['please_check_details'];
                header("Content-type: application/json");
                echo json_encode(array(
                    'errors' => $errors
                ));
                exit();
            }
            $id = Wo_GetPostIDFromProdcutID($product_id);
            if (isset($_FILES['postPhotos']['name'])) {
                if (count($_FILES['postPhotos']['name']) > 0 && !empty($id) && $id > 0) {
                    for ($i = 0; $i < count($_FILES['postPhotos']['name']); $i++) {
                        $fileInfo = array(
                            'file' => $_FILES["postPhotos"]["tmp_name"][$i],
                            'name' => $_FILES['postPhotos']['name'][$i],
                            'size' => $_FILES["postPhotos"]["size"][$i],
                            'type' => $_FILES["postPhotos"]["type"][$i],
                            'types' => 'jpg,png,jpeg,gif'
                        );
                        $file     = Wo_ShareFile($fileInfo, 1);
                        if (!empty($file)) {
                            $media_album = Wo_RegisterProductMedia($product_id, $file['filename']);
                        }
                    }
                }
            }
            if (!empty($_POST['deleted_images_ids'])) {
                $images_array = explode(',', $_POST['deleted_images_ids']);
                if (!empty($images_array)) {
                    $product_data = Wo_GetProduct($product_id);
                    foreach ($images_array as $key => $value) {
                        $image = Wo_ProductImageData(array('id' => $value));
                        if (!empty($image)) {
                            $explode2    = @end(explode('.', $image['image']));
                            $explode3    = @explode('.', $image['image']);
                            $small_image = $explode3[0] . '_small.' . $explode2;

                            $product = Wo_GetProduct($image['product_id']);
                            if (!empty($product) && $wo['user']['id'] == $product['user_id']) {
                                $deleted = Wo_DeleteProductImage($value);
                                if ($deleted) {
                                    if (($wo['config']['amazone_s3'] == 1 || $wo['config']['ftp_upload'] == 1 || $wo['config']['spaces'] == 1 || $wo['config']['cloud_upload'] == 1)) {
                                        Wo_DeleteFromToS3($image['image']);
                                        Wo_DeleteFromToS3($small_image);
                                    }
                                    @unlink($image['image']);
                                    @unlink($small_image);
                                }
                            }
                        }
                    }
                }
            }
            $data = array(
                'status' => 200,
                'href' => Wo_SeoLink('index.php?link1=post&id=' . $id)
            );
        }
        header("Content-type: application/json");
        if (isset($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();
    }
    // if ($s == 'delete_image_by_id') {
    //     $data['status'] = 400;
    //     if (!empty($_POST['image_id']) && is_numeric($_POST['image_id']) && $_POST['image_id'] > 0) {
    //         $image = Wo_ProductImageData(array('id' => $_POST['image_id']));
    //         if (!empty($image)) {
    //             $product = Wo_GetProduct($image['product_id']);
    //             if (!empty($product) && $wo['user']['id'] == $product['user_id']) {
    //                 $deleted = Wo_DeleteProductImage($_POST['image_id']);
    //                 if ($deleted) {
    //                     @unlink($image['image']);
    //                     $data['status'] = 200;
    //                 }
    //             }
    //         }
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
}
