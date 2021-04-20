<?php 

if (!empty($_POST['recipient_id']) && is_numeric($_POST['recipient_id']) && $_POST['recipient_id'] > 0) {
	$json_success_data   = array();
	$user_id         = $wo['user']['id'];
	$user_login_data = $wo['user'];
	if (!empty($user_login_data)) {
		$recipient_id    = $_POST['recipient_id'];
        $user_login_data2 = Wo_UserData($recipient_id);
        if (!empty($user_login_data2)) {

        	$limit             = 20;
            $after_message_id  = 0;
            $before_message_id = 0;
            $message_id = 0;
            if (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0) {
                $limit = $_POST['limit'];
            }
            if (!empty($_POST['after_message_id'])) {
                $after_message_id = $_POST['after_message_id'];
            }
            if (!empty($_POST['before_message_id'])) {
                $before_message_id = $_POST['before_message_id'];
            }
            if (!empty($_POST['message_id'])) {
                $message_id = $_POST['message_id'];
            }
            $message_info = array(
                'user_id' => $user_id,
                'recipient_id' => $recipient_id,
                'before_message_id' => $before_message_id,
                'after_message_id' => $after_message_id,
                'message_id' => $message_id
            );
            $message_info = Wo_GetMessagesAPPN($message_info,$limit);
            $not_include_status = false;
            $not_include_array = array();
            if (!empty($_POST['not_include'])) {
                $not_include_array = @explode(',', $_POST['not_include']);
                $not_include_status = true;
            }
            $timezone = new DateTimeZone($user_login_data['timezone']);
            foreach ($message_info as $message) {
                if ($not_include_status == true) {
                    foreach ($not_include_array as $value) {
                        if (!empty($value)) {
                            $value = Wo_Secure($value);
                            unset($message[$value]);
                        }
                    }
                }
                if (empty($message['stickers'])) {
                    $message['stickers'] = '';
                }
                $message['time_text'] = Wo_Time_Elapsed_String($message['time']);
                $message_po  = 'left';
                if ($message['from_id'] == $user_id) {
                    $message_po  = 'right';
                }
                
                $message['position']  = $message_po;
                $message['type']      = Wo_GetFilePosition($message['media']);
                if (!empty($message['stickers']) && strpos($message['stickers'], '.gif') !== false) {
                    $message['type'] = 'gif';
                }
                if ($message['type_two'] == 'contact') {
                    $message['type']   = 'contact';
                }
                $message['type']     = $message_po . '_' . $message['type'];
                $message['product']     = null;
                if (!empty($message['product_id'])) {
                    $message['type']     = $message_po . '_product';
                    $message['product'] = Wo_GetProduct($message['product_id']);
                }
                $message['file_size'] = 0;
                if (!empty($message['media'])) {
                    $message['file_size'] = '0MB';
                    if (file_exists($message['file_size'])) {
                        $message['file_size'] = Wo_SizeFormat(filesize($message['media']));
                    }
                    $message['media']     = Wo_GetMedia($message['media']);
                }
                if (!empty($message['time'])) {
                    $time_today  = time() - 86400;
                    if ($message['time'] < $time_today) {
                        $message['time_text'] = date('m.d.y', $message['time']);
                    } else {
                        $time = new DateTime('now', $timezone);
                        $time->setTimestamp($message['time']);
                        $message['time_text'] = $time->format('H:i');
                    }
                }
                array_push($json_success_data, $message);
            }
            $send_messages_to_phones = Wo_MessagesPushNotifier();
            $typing = 0;
			$check_typing = Wo_IsTyping($recipient_id);
			if ($check_typing) {
			    $typing = 1;
			} 
            $response_data = array('api_status' => 200,
            	                   'messages' => $json_success_data,
            	                   'typing' => $typing);

        }
        else{
        	$error_code    = 5;
		    $error_message = 'recipient user not found';
        }
	}
	else{
		$error_code    = 4;
	    $error_message = 'user not found';
	}
}
else{
	$error_code    = 3;
    $error_message = 'recipient_id can not be empty';
}