<?php 
if ($f == 'live') {
    if ($s == 'create') {
    	if (empty($_POST['stream_name'])) {
    		$data['message'] = $error_icon . $wo['lang']['please_check_details'];
    	}
    	else{
    		$post_id = $db->insert(T_POSTS,array('user_id' => $wo['user']['id'],
		    	                                 'postText' => '',
                                                 'postType' => 'live',
                                                 'postPrivacy' => 0,
                                                 'stream_name' => Wo_Secure($_POST['stream_name']),
                                                 'time' => time()));
    		$db->where('id',$post_id)->update(T_POSTS,array('post_id' => $post_id));
            Wo_RunInBackground(array('status' => 200,
                                     'post_id' => $post_id));
            Wo_notifyUsersLive($post_id);
            $data['status'] = 200;
            $data['post_id'] = $post_id;

    	}
    	header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'check_comments') {
    	if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
    		$post_id = Wo_Secure($_POST['post_id']);
    		$post_data = $db->where('id',$post_id)->getOne(T_POSTS);
    		if (!empty($post_data)) {
                if ($post_data->live_ended == 0) {
                    //if ($_POST['page'] == 'story') {
                        $user_comment = $db->where('post_id',$post_id)->where('user_id',$wo['user']['id'])->getOne(T_COMMENTS);
                        if (!empty($user_comment)) {
                            $db->where('id',$user_comment->id,'>');
                        }
                    //}
                    if (!empty($_POST['ids'])) {
                        $ids = array();
                        foreach ($_POST['ids'] as $key => $one_id) {
                            $ids[] = Wo_Secure($one_id);
                        }
                        $db->where('id',$ids,'NOT IN')->where('id',end($ids),'>');
                    }
                    //if ($_POST['page'] == 'story') {
                        $db->where('user_id',$wo['user']['id'],'!=');
                    //}
    				$comments = $db->where('post_id',$post_id)->where('text','','!=')->get(T_COMMENTS);
    				$html = '';
    				foreach ($comments as $key => $value) {
    					if (!empty($value->text)) {
    						$wo['comment'] = Wo_GetPostComment($value->id);
    						$html .= Wo_LoadPage('story/includes/live_comment');
    						$count = $count + 1;
    						if ($count == 4) {
    	                      break;
    	                    }
    					}
    				}


                    $count = 0;
                    $word = $wo['lang']['offline'];
                    if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10)) {
                        //$db->where('post_id',$post_id)->where('time',time()-6,'<')->update(T_LIVE_SUB,array('is_watching' => 0));
                        $word = $wo['lang']['live'];
                        $count = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->getValue(T_LIVE_SUB,'COUNT(*)');

                        if ($wo['user']['id'] == $post_data->user_id) {
                            $joined_users = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->where('is_watching',0)->get(T_LIVE_SUB);
                            $joined_ids = array();
                            if (!empty($joined_users)) {
                                foreach ($joined_users as $key => $value) {
                                    $joined_ids[] = $value->user_id;
                                    $wo['comment'] = array('id' => '',
                                                           'text' => 'joined live video');
                                    $user_data = Wo_UserData($value->user_id);
                                    if (!empty($user_data)) {
                                        $wo['comment']['publisher'] = $user_data;
                                        $html .= Wo_LoadPage('story/includes/live_comment');
                                    }
                                }
                                if (!empty($joined_ids)) {
                                    $db->where('post_id',$post_id)->where('user_id',$joined_ids,'IN')->update(T_LIVE_SUB,array('is_watching' => 1));
                                }
                            }

                            $left_users = $db->where('post_id',$post_id)->where('time',time()-6,'<')->where('is_watching',1)->get(T_LIVE_SUB);
                            $left_ids = array();
                            if (!empty($left_users)) {
                                foreach ($left_users as $key => $value) {
                                    $left_ids[] = $value->user_id;
                                    $wo['comment'] = array('id' => '',
                                                           'text' => 'left live video');
                                    $user_data = Wo_UserData($value->user_id);
                                    if (!empty($user_data)) {
                                        $wo['comment']['publisher'] = $user_data;
                                        $html .= Wo_LoadPage('story/includes/live_comment');
                                    }
                                }
                                if (!empty($left_ids)) {
                                    $db->where('post_id',$post_id)->where('user_id',$left_ids,'IN')->delete(T_LIVE_SUB);
                                }
                            }
                        }
                    }
                    $still_live = 'offline';
                    if (!empty($post_data) && $post_data->live_time >= (time() - 10)){
                        $still_live = 'live';
                    }
                    
                    Wo_RunInBackground(array(
                        'status' => 200,
                        'html' => $html,
                        'count' => $count,
                        'word' => $word,
                        'still_live' => $still_live
                    ));
                    
                    if ($wo['user']['id'] == $post_data->user_id) {
                        if ($_POST['page'] == 'live') {
                            $time = time();
                            $db->where('id',$post_id)->update(T_POSTS,array('live_time' => $time));
                            $db->where('parent_id',$post_id)->update(T_POSTS,array('live_time' => $time));
                        }
                    }
                    else{
                        if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10) && $_POST['page'] == 'story') {
                            $is_watching = $db->where('user_id',$wo['user']['id'])->where('post_id',$post_id)->getValue(T_LIVE_SUB,'COUNT(*)');
                            if ($is_watching > 0) {
                                $db->where('user_id',$wo['user']['id'])->where('post_id',$post_id)->update(T_LIVE_SUB,array('time' => time()));
                            }
                            else{
                                $db->insert(T_LIVE_SUB,array('user_id' => $wo['user']['id'],
                                                             'post_id' => $post_id,
                                                             'time' => time(),
                                                             'is_watching' => 0));
                            }
                        }
                    }
                }
                else{
                    $data['message'] = $error_icon . $wo['lang']['please_check_details'];
                }
                
    		}
    		else{
    			$data['message'] = $error_icon . $wo['lang']['please_check_details'];
    		}
    	}
    	else{
    		$data['message'] = $error_icon . $wo['lang']['please_check_details'];
    	}
    	header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete') {
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $db->where('post_id',Wo_Secure($_POST['post_id']))->where('user_id',$wo['user']['id'])->update(T_POSTS,array('live_ended' => 1));
            if ($wo['config']['live_video_save'] == 0) {
                $db->where('post_id',Wo_Secure($_POST['post_id']))->where('user_id',$wo['user']['id'])->delete(T_POSTS);
                $db->where('parent_id',Wo_Secure($_POST['post_id']))->delete(T_POSTS);
            }
        }
    }
}
