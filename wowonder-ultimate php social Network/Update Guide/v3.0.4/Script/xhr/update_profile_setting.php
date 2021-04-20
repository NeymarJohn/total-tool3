<?php 
if ($f == "update_profile_setting") {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0 && Wo_CheckSession($hash_id) === true) {
        $Userdata = Wo_UserData($_POST['user_id']);
        if (!empty($Userdata['user_id'])) {
            $pattern = '/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{1,100}' . '((:[0-9]{1,5})?\\/.*)?$/i';
            if (!empty($_POST['website'])) {
                if (!preg_match($pattern, $_POST['website'])) {
                    $errors[] = $error_icon . $wo['lang']['website_invalid_characters'];
                }
            }
            if (!empty($_POST['working_link'])) {
                if (!preg_match($pattern, $_POST['working_link'])) {
                    $errors[] = $error_icon . $wo['lang']['company_website_invalid'];
                }
            }
            if (!is_numeric($_POST['relationship']) || empty($_POST['relationship'])) {
                $_POST['relationship'] = 0;
                Wo_DeleteMyRelationShip();
            }
            if (isset($_POST['relationship_user']) && is_numeric($_POST['relationship_user']) && $_POST['relationship_user'] > 0) {
                if (is_numeric($_POST['relationship']) && $_POST['relationship'] > 0 && $_POST['relationship'] <= 4) {
                    $relationship_user = Wo_Secure($_POST['relationship_user']);
                    $user              = Wo_Secure($wo['user']['id']);
                    if (!Wo_IsRelationRequestExists($user, $relationship_user, $_POST['relationship'])) {
                        $registration_data = array(
                            'from_id' => $user,
                            'to_id' => $relationship_user,
                            'relationship' => Wo_Secure($_POST['relationship']),
                            'active' => 0
                        );
                        $registration_id   = Wo_RegisterRelationship($registration_data);
                        if ($registration_id) {
                            $relationship_user_data  = Wo_UserData($relationship_user);
                            $notification_data_array = array(
                                'recipient_id' => $relationship_user,
                                'type' => 'added_u_as',
                                'user_id' => $wo['user']['id'],
                                'text' => $wo['lang']['relationship_request'],
                                'url' => 'index.php?link1=timeline&u=' . $relationship_user_data['username'] . '&type=requests'
                            );
                            Wo_RegisterNotification($notification_data_array);
                        }
                    }
                }
            }
            if (empty($errors)) {
                $Update_data = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'website' => $_POST['website'],
                    'about' => $_POST['about'],
                    'working' => $_POST['working'],
                    'working_link' => $_POST['working_link'],
                    'address' => $_POST['address'],
                    'school' => $_POST['school'],
                    'relationship_id' => $_POST['relationship']
                );
                $Update_data['school_completed'] = 0;
                if (!empty($_POST['school']) && !empty($_POST['completed']) && $_POST['completed'] == 'on') {
                    $Update_data['school_completed'] = 1;
                }
                if (Wo_UpdateUserData($_POST['user_id'], $Update_data)) {
                    $field_data = array();
                    if (!empty($_POST['custom_fields'])) {
                        $fields = Wo_GetProfileFields('profile');
                        foreach ($fields as $key => $field) {
                            $name = $field['fid'];
                            if (isset($_POST[$name])) {
                                if (mb_strlen($_POST[$name]) > $field['length']) {
                                    $errors[] = $error_icon . $field['name'] . ' field max characters is ' . $field['length'];
                                }
                                $field_data[] = array(
                                    $name => $_POST[$name]
                                );
                            }
                        }
                    }
                    if (!empty($field_data)) {
                        $insert = Wo_UpdateUserCustomData($_POST['user_id'], $field_data);
                    }
                    if (empty($errors)) {
                        $data = array(
                            'status' => 200,
                            'first_name' => Wo_Secure($_POST['first_name']),
                            'last_name' => Wo_Secure($_POST['last_name']),
                            'message' => $success_icon . $wo['lang']['setting_updated']
                        );
                    }
                }
            }
        }
    }
    Wo_CleanCache();
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
