<?php
class UserModel extends CI_Model
{
    function getUserData($url)
    {
        $sql = "SELECT id, url, username, email, passkey, role, subscriber, badge, image, profile_image, facebook, twitter, google, linkedin, location, about, nb_notes, nb_favs, nb_coms, auth_coms, playlist_profile, customer_id, date_created FROM 2d_users WHERE url = ?";
        $query = $this->db->query($sql, array($url));
        if($result = $query->row()) {
            return array(
             'id'               => $result->id,
             'url'              => $result->url,
             'username'         => $result->username,
             'email'            => $result->email,
             'passkey'          => $result->passkey,
             'role'             => $result->role,
             'subscriber'       => $result->subscriber,
             'badge'            => $result->badge,
             'image'            => $result->image,
             'profile_image'    => $result->profile_image,
             'facebook'         => $result->facebook,
             'twitter'          => $result->twitter,
             'google'           => $result->google,
             'linkedin'         => $result->linkedin,
             'location'         => $result->location,
             'about'            => $result->about,
             'nb_notes'         => $result->nb_notes,
             'nb_favs'          => $result->nb_favs,
             'nb_coms'          => $result->nb_coms,
             'auth_coms'        => $result->auth_coms,
             'playlist_profile' => $result->playlist_profile,
             'customer_id'      => $result->customer_id,
             'date_created'     => $result->date_created
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function updatePaymentStats($type)
    {
        $sql = "SELECT attribut, value FROM 2d_stats WHERE date_created = ? AND attribut = ?";
        $query = $this->db->query($sql, array(date('Y-m-d'), $type));
        if($result = $query->row()) {
            $newStat = $result->value+1;
            $sql = "UPDATE 2d_stats SET value = ? WHERE date_created = ? AND attribut = ?";
            $this->db->query($sql, array($newStat, date('Y-m-d'), $type));
        } else {
            $sql = "INSERT INTO 2d_stats (attribut, value, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array($type, 1, date('Y-m-d')));
        }
    }

    public function getFavsVideos($userId, $getPag = 0, $limit = 16, $slider = FALSE)
    {
        $sql = "SELECT ga.id AS id FROM 2d_favorites fa, 2d_videos ga WHERE ((fa.id_user = ?) AND (fa.id_video = ga.id) AND (ga.status != 0))";
        $query = $this->db->query($sql, array($userId));
        $nbRows = $query->num_rows();
        $sql = "SELECT ga.title AS title, ga.image AS image, ga.url AS url FROM 2d_favorites fa, 2d_videos ga WHERE ((fa.id_user = ?) AND (fa.id_video = ga.id) AND (ga.status != 0)) LIMIT ?, ?";
        $query = $this->db->query($sql, array($userId, (int)$getPag, (int)$limit));
        $getFavsVideos = '';
        foreach ($query->result() as $row) {
            $getFavsVideos .= '<div class="'.(($slider === TRUE) ? 'item' : 'col-sm-12 col-md-4').'">
                                  <a href="'.site_url('video/'.$row->url.'/').'" class="image-popup">
							    	  <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img img-responsive">
                                      <span class="play-button-small"></span>
                                  </a>
                               </div>';
        }
        return array(
         'getFavsVideos' => $getFavsVideos,
         'nbRows' => $nbRows
         );
    }

    public function getPlaylistsList($userId) {
        $sql = "SELECT playlist_profile FROM 2d_users WHERE (id = ?)";
        $query = $this->db->query($sql, array($userId));
        if($result = $query->row()) {
            $playlistProfile = $result->playlist_profile;
        }
        $sql = "SELECT id, title FROM 2d_playlists WHERE (id_user = ?)";
        $query = $this->db->query($sql, array($userId));
        $getPlaylistsList = '<option value="no">'.$this->lang->line('No').'</option>';
        foreach ($query->result() as $row) {
            $select = ($playlistProfile === $row->id) ? 'selected' : '';
            $getPlaylistsList .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getPlaylistsList;
    }

    public function createPlaylist($title) {
        $sql = 'INSERT INTO 2d_playlists (title, id_user, date_created) VALUES (?, ?, ?)';
        $this->db->query($sql, array(strip_tags($title), $this->session->id, date("Y-m-d H:i:s")));
    }

    public function getPlaylists($userId, $userUrl)
    {
        $sql = "SELECT id, title, ids_videos FROM 2d_playlists WHERE (id_user = ?)";
        $query = $this->db->query($sql, array($userId));
        $getPlaylists = '';
        foreach ($query->result() as $row) {
            $idsVideos = explode(',', $row->ids_videos);
            $idsVideos = array_filter($idsVideos);
            $nbVideos = (count($idsVideos) <= 1) ? count($idsVideos).' '.$this->lang->line('video') : count($idsVideos).' '.$this->lang->line('videos');
            if (count($idsVideos) >= 1) {
                $sql = 'SELECT image FROM 2d_videos WHERE id = ?';
                $query = $this->db->query($sql, array($idsVideos[0]));
                $image = ($result = $query->row()) ? $result->image : '';
            } else {
                $image = '';
            }
            $getPlaylists .= '<div class="col-sm-12 col-md-4">
                                  <a href="'.site_url('user/playlist/'.$userUrl.'/'.$row->id.'/').'" class="image-popup">
                                      <img src="'.(empty($image) ?site_url('assets/images/default-video.jpg') : $image).'" class="thumb-img img-responsive" alt="'.$row->title.'">
                                      <span class="background-playlists"><span class="background-text">'.$nbVideos.'</span></span>
                                  </a>
                                  <p class="m-t-10"><a href="'.site_url('user/playlist/'.$userUrl.'/'.$row->id.'/').'">'.$row->title.'</a></p>
                              </div>';
        }
        return array(
            'getPlaylists' => $getPlaylists
         );
    }

    public function getPlaylist($playlistId, $slider = FALSE)
    {
        $sql = "SELECT id, title, ids_videos FROM 2d_playlists WHERE id = ?";
        $query = $this->db->query($sql, array($playlistId));
        if($result1 = $query->row()) {
            $idsVideos = explode(',', $result1->ids_videos);
            $getPlaylist = '';
            foreach ($idsVideos as $idVideo) {
                $sql = 'SELECT title, url, image FROM 2d_videos WHERE id = ? AND status != 0';
                $query = $this->db->query($sql, array($idVideo));
                if($result = $query->row()) {
                    $getPlaylist .= '<div class="'.(($slider === TRUE) ? 'item' : 'col-sm-12 col-md-4').'">
                                        <a href="'.site_url('video/'.$result->url.'/').'" class="image-popup">
                                            <img src="'.(empty($result->image) ? site_url('assets/images/default-video.jpg') : $result->image).'" class="thumb-img img-responsive m-t-5 m-r-5" alt="'.$result->title.'">
                                            <span class="play-button-small"></span>
                                        </a>
                                    </div>';
                }
            }
            return array(
                'getPlaylistTitle' => $result1->title,
                'getPlaylistId' => $result1->id,
                'getPlaylist' => $getPlaylist
             );
        } else {
            return array(
                'getPlaylistTitle' => NULL,
                'getPlaylistId' => NULL,
                'getPlaylist' => NULL
             );
        }

    }

    public function delPlaylist($idPlaylist, $idUser, $userUrl)
    {
        $sql = 'SELECT id FROM 2d_playlists WHERE id = ? AND id_user = ?';
        $query = $this->db->query($sql, array($idPlaylist, $idUser));
        if($result = $query->row()) {
            $sql = 'DELETE FROM 2d_playlists WHERE id = ?';
            $this->db->query($sql, array($result->id));
            redirect('/user/playlists/'.$userUrl.'/');
        }
    }

    public function getNotesVideos($userId, $getPag = 0, $limit = 10)
    {

        $sql = "SELECT ga.id AS id FROM 2d_videos ga, 2d_notes no WHERE ((no.id_user = ?) AND (no.id_video = ga.id) AND (ga.status != 0))";
        $query = $this->db->query($sql, array($userId));
        $nbRows = $query->num_rows();
        $sql = "SELECT ga.title AS title, ga.url AS url, no.note AS note FROM 2d_videos ga, 2d_notes no WHERE ((no.id_user = ?) AND (no.id_video = ga.id) AND (ga.status != 0)) ORDER BY note DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array($userId, (int)$getPag, (int)$limit));
        $getNotesVideos = '';
        foreach ($query->result() as $row) {
            $getNotesVideos .= '<tr>
								<td><a href="'.site_url('video/'.$row->url.'/').'">'.$row->title.'</a></td>
								<td>'.rating($row->note, $class = '').'</td>
							</tr>';
        }
        return array(
         'getNotesVideos' => $getNotesVideos,
         'nbRows' => $nbRows
         );
    }

    public function getComsVideos($userId, $getPag = 0, $limit = 5)
    {

        $sql = "SELECT us.id AS id FROM 2d_users us, 2d_comments co, 2d_videos ga WHERE ((co.id_user = ?) AND (co.id_user = us.id) AND (co.id_video = ga.id) AND (ga.status != 0))";
        $query = $this->db->query($sql, array($userId));
        $nbRows = $query->num_rows();
        $sql = "SELECT us.username AS username, us.image AS image, us.url AS userUrl, co.id AS id, co.comment AS comment, co.date_created AS date_created, ga.title AS title, ga.url AS videoUrl FROM 2d_users us, 2d_comments co, 2d_videos ga WHERE ((co.id_user = ?) AND (co.id_user = us.id) AND (co.id_video = ga.id) AND (ga.status != 0)) ORDER BY date_created DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array($userId, (int)$getPag, (int)$limit));
        $getComsVideos = '';
        foreach ($query->result() as $row) {
            $time = timespan(strtotime($row->date_created), time(), 1);
            $getComsVideos .= '<div class="comment">
								<img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" alt="'.$row->username.'" class="comment-avatar">
								<div class="comment-body">
									<div class="comment-text">
										<div class="comment-header">
											<a href="'.site_url('user/'.$row->userUrl.'/').'" title="'.$row->username.'">'.$row->username.'</a>
                                            <span>'.$this->lang->line('on').'</span>
                                            <a href="'.site_url('video/'.$row->videoUrl.'/').'" title="'.$row->title.'">'.$row->title.'</a>
                                            <span>'.$this->lang->line('about').' '.$time.'</span>
										</div>
										'.$row->comment.'
									</div>
									'.(($userId === $this->session->id) ? '<a href="'.current_url().'/?del='.$row->id.'"><i class="fa fa-remove text-danger fl-right"></i></a>' : '').'
								</div>
							</div>';
        }
        return array(
         'getComsVideos' => $getComsVideos,
         'nbRows' => $nbRows
         );
    }

    public function addCom($userId, $postCom)
    {
        $sql = 'INSERT INTO 2d_profiles_comments (comment, id_user_page, id_user_member, date_created, ip) VALUES (?, ?, ?, ?, ?)';
        $this->db->query($sql, array(strip_tags($postCom), $userId, $this->session->id, date("Y-m-d H:i:s"), $this->input->ip_address()));
    }

    public function delCom($idCom, $idUser)
    {
        $sql = 'SELECT id FROM 2d_comments WHERE id = ? AND id_user = ?';
        $query = $this->db->query($sql, array($idCom, $idUser));
        if($result = $query->row()) {
            $sql = 'DELETE FROM 2d_comments WHERE id = ?';
            $this->db->query($sql, array($result->id));
            $this->updateComs($idUser);
        }
    }

    public function updateComs($idUser)
    {
        $sql = 'SELECT id FROM 2d_comments WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_coms = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
    }

    public function updateProfile($email, $passkey, $postUsername, $postEmail, $postLocation, $postAbout, $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, &$newUsername, $postIdPlaylist)
    {
        $sql = "SELECT id FROM 2d_users WHERE username = ?";
        $query = $this->db->query($sql, array($postUsername));
        if($result = $query->row()) {
            $requestNewUsername = FALSE;
            if($result->id === $this->session->id) {
                $sql = "SELECT id FROM 2d_users WHERE email = ?";
                $query = $this->db->query($sql, array($postEmail));
                if($result = $query->row()) {
                    if($result->id === $this->session->id) {
                        $sql = "UPDATE 2d_users SET location = ?, about = ?, facebook = ?, twitter = ?, google = ?, linkedin = ?, auth_coms = ?, playlist_profile = ? WHERE id = ?";
                        $this->db->query($sql, array(strip_tags(ucfirst($postLocation)), strip_tags($postAbout), $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, $postIdPlaylist, $this->session->id));
                        return alert($this->lang->line('Saved changes'));
                    } else {
                        return alert($this->lang->line('This email is not available'), 'danger');
                    }
                } else {
                    $sql = "UPDATE 2d_users SET location = ?, about = ?, facebook = ?, twitter = ?, google = ?, linkedin = ?, auth_coms = ?, playlist_profile = ? WHERE id = ?";
                    $this->db->query($sql, array(strip_tags(ucfirst($postLocation)), strip_tags($postAbout), $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, $postIdPlaylist, $this->session->id));
                    $sendMail = $this->sendConfirmation(strip_tags(strtolower($postEmail)), $passkey, $email);
                    if($sendMail) {
                        return alert($this->lang->line('Please check your mailbox to verify your email'));
                    } else {
                        return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
                    }
                }
            } else {
                return alert($this->lang->line('This username is not available'), 'danger');
            }
        } else {
            $postUrl = $newUsername = url_title(convert_accented_characters($postUsername), $separator = '-', $lowercase = true);
            $sql = "SELECT id FROM 2d_users WHERE email = ?";
            $query = $this->db->query($sql, array($postEmail));
            if($result = $query->row()) {
                if($result->id === $this->session->id) {
                    $sql = "UPDATE 2d_users SET username = ?, url = ?, location = ?, about = ?, facebook = ?, twitter = ?, google = ?, linkedin = ?, auth_coms = ?, playlist_profile = ? WHERE id = ?";
                    $this->db->query($sql, array(strip_tags(ucfirst($postUsername)), strip_tags($postUrl), strip_tags(ucfirst($postLocation)), strip_tags($postAbout), $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, $postIdPlaylist, $this->session->id));
                    return alert($this->lang->line('Saved changes'));
                } else {
                    return alert($this->lang->line('This email is not available'), 'danger');
                }
            } else {
                $sql = "UPDATE 2d_users SET username = ?, url = ?, location = ?, about = ?, facebook = ?, twitter = ?, google = ?, linkedin = ?, auth_coms = ?, playlist_profile = ? WHERE id = ?";
                $this->db->query($sql, array(strip_tags(ucfirst($postUsername)), strip_tags($postUrl), strip_tags(ucfirst($postLocation)), strip_tags($postAbout), $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, $postIdPlaylist, $this->session->id));
                $sendMail = $this->sendConfirmation(strip_tags(strtolower($postEmail)), $passkey, $email);
                if($sendMail) {
                    return alert($this->lang->line('Please check your mailbox to verify your email'));
                } else {
                    return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
                }
            }
        }
    }

    public function sendConfirmation($email, $passkey, $newmail)
    {
        $this->load->library('email');
        $config = array(
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('emailsite'), $this->config->item('author'));
        $this->email->to($email);
        $this->email->subject($this->config->item('titleMailConfirmation'));
        $data = array(
                 'message'  => $this->config->item('mailConfirmation'),
                 'linkButton' => site_url('login/changemail/?mail='.$email.'&key='.$passkey.'&oldmail='.$newmail),
                 'labelButton' => $this->lang->line('Confirm Email Address')
                 );
        $body = $this->load->view('email-templates/action.php', $data, TRUE);
        $this->email->message($body);
        if($this->email->send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateAvatarImage($iduser, $imgUser = '')
    {
        $sql = "UPDATE 2d_users SET image = ? WHERE id = ?";
        $this->db->query($sql, array($imgUser, $iduser));
        $this->session->set_userdata('name_image', $imgUser);
    }

    public function updateProfileImage($iduser, $imgUser = '')
    {
        $sql = "UPDATE 2d_users SET profile_image = ? WHERE id = ?";
        $this->db->query($sql, array($imgUser, $iduser));
    }

    public function getComsProfile($userId)
    {
        $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.image AS image, us.url AS url FROM 2d_profiles_comments co, 2d_users us WHERE ((co.id_user_page = ?) AND (co.id_user_member = us.id)) ORDER BY date_created DESC";
        $query = $this->db->query($sql, array($userId));
        $getComsProfile = '';
        foreach ($query->result() as $row) {
            $time = timespan(strtotime($row->date_created), time(), 1);
            $getComsProfile .= '<div class="comment comment-box big">
        							<img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" alt="" class="comment-avatar">
        							<div class="comment-body">
        								<div class="comment-text">
        									<div class="comment-header">
        										<a href="'.site_url('user/'.$row->url.'/').'" title="">'.$row->username.'</a><span>'.$this->lang->line('about').' '.$time.'</span>
        									</div>
        									'.$row->comment.'
                                            '.($userId === $this->session->userdata('id') ? '<a href="'.current_url().'/?del='.$row->id.'"><i class="fa fa-remove text-danger fl-right"></i></a>' : '').'
        								</div>
        							</div>
        						</div>';
        }
        return $getComsProfile;
    }

    public function deleteComProfile($idCom)
    {
        $sql = 'DELETE FROM 2d_profiles_comments WHERE id = ?';
        $this->db->query($sql, array((int)$idCom));
        return alert($this->lang->line('Comment deleted'), 'success');
    }

    public function getHistory($userId, $userUrl, $getPag = 0, $limit = 5) {
        $sql = "SELECT id FROM 2d_payments WHERE (id_user = ?)";
        $query = $this->db->query($sql, array($userId));
        $nbRows = $query->num_rows();
        $getHistory = '';
        $sql = "SELECT id, type, subscription_id, price, currency, status, date_created, date_end, trial_start, trial_end FROM 2d_payments WHERE id_user = ? ORDER BY date_created DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array($userId, (int)$getPag, (int)$limit));
        foreach ($query->result() as $row) {
            // Update each payment for this user (debug)
            // if($row->type = 1) {
            //     $row->status = $this->updateStripeStatus($row->subscription_id);
            // }
            $type = ($row->type === '1') ? $this->lang->line('Subscription') : $this->lang->line('Payment');
            $date_end = ($row->type === '1') ? '<p><b>'.$this->lang->line('Date end').' : </b><br>'.date("d/m/Y", strtotime($row->date_end)).'</p>' : '';
            $trial_start = ($row->type === '1') ? '<p><b>'.$this->lang->line('Trial start').' : </b><br>'.date("d/m/Y", strtotime($row->trial_start)).'</p>' : '';
            $trial_end = ($row->type === '1') ? '<p><b>'.$this->lang->line('Trial end').' : </b><br>'.date("d/m/Y", strtotime($row->trial_end)).'</p>' : '';
            $status = ($row->status === 'active' || $row->status === 'trialing') ? '<span>'.$row->status.'</span> <a href="" id="unsubscribeInvoice" data-user="'.$userUrl.'" data-subscription="'.$row->subscription_id.'" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('This action is irreversible').'">('.$this->lang->line('cancel subscription').')</a>' : $row->status;
            $btnClass = ($row->status === 'canceled') ? 'btn-danger' : 'btn-success';
            $getHistory .= '<tr class="text-center">
                                <td>'.$type.'</td>
                                <td>'.$row->subscription_id.'</td>
            					<td>'.$row->price.'</td>
                                <td><button class="btn btn-xs '.$btnClass.'" data-toggle="modal" data-target="#modal-'.$row->id.'">'.$row->status.'</button></td>
                                <td>'.date("d/m/Y", strtotime($row->date_created)).'</td>
            				</tr>
                            <!-- Modal -->
                            <div id="modal-'.$row->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">'.$this->lang->line('Invoice').' : '.$row->subscription_id.'</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <p><b>'.$this->lang->line('Type').' : </b><br>'.$type.'</p>
                                                    <p><b>'.$this->lang->line('Price').' : </b><br>'.$row->price.' '.$row->currency.'</p>
                                                    <p><b>'.$this->lang->line('Date created').' : </b><br>'.date("d/m/Y", strtotime($row->date_created)).'</p>
                                                    '.$trial_start.'
                                                </div>
                                                <div class="col-xs-6">
                                                    <p><b>'.$this->lang->line('Reference').' : </b><br>'.$row->subscription_id.'</p>
                                                    <p><b>'.$this->lang->line('Status').' : </b><br>'.$status.'</p>
                                                    '.$date_end.'
                                                    '.$trial_end.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">'.$this->lang->line('Close').'</button>
                                        </div>
                                        <div class="loader">
                                            <div class="sk-circle">
                                                <div class="sk-circle1 sk-child"></div>
                                                <div class="sk-circle2 sk-child"></div>
                                                <div class="sk-circle3 sk-child"></div>
                                                <div class="sk-circle4 sk-child"></div>
                                                <div class="sk-circle5 sk-child"></div>
                                                <div class="sk-circle6 sk-child"></div>
                                                <div class="sk-circle7 sk-child"></div>
                                                <div class="sk-circle8 sk-child"></div>
                                                <div class="sk-circle9 sk-child"></div>
                                                <div class="sk-circle10 sk-child"></div>
                                                <div class="sk-circle11 sk-child"></div>
                                                <div class="sk-circle12 sk-child"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End modal -->';
        }
        return array(
            'getHistory' => $getHistory,
            'nbRows' => $nbRows
        );
    }

    public function sendInvoice($email, $idSubscription, $price, $description)
    {
        $this->load->library('email');
        $config = array(
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('emailsite'), $this->config->item('author'));
        $this->email->to($email);
        $this->email->subject($this->config->item('titleMailConfirmation'));
        $data = array(
                 'message'  => $this->config->item('mailConfirmation'),
                 'idSubscription'  => $idSubscription,
                 'price'  => $price,
                 'description'  => $description
                 );
        $body = $this->load->view('email-templates/billing.php', $data, TRUE);
        $this->email->message($body);
        return $this->email->send();
    }

    public function addNotification($type = 3)
    {
        $sql = "INSERT INTO 2d_notifications (type, new, date_created, date_modified) VALUES (?, ?, ?, ?)";
        $query = $this->db->query($sql, array($type, TRUE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        if($this->db->insert_id()) {
            $id = $this->db->insert_id();
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function createSubscription($userId, $userEmail, $customerId, $typeSubscription, $paymentPeriod) {
        require_once('./application/vendor/stripe/init.php');
        \Stripe\Stripe::setApiKey(html_escape($this->config->item('secretkey')));
        try {
            if(empty($customerId)) {
                $customer = \Stripe\Customer::create(array(
                    'email' => $_POST['stripeEmail'],
                    "source"  => $_POST['stripeToken']
                ));
                $customerId = $customer->id;
            }
            if($typeSubscription === '1') {
                $subsription = \Stripe\Subscription::create(array(
                    "customer" => $customerId,
                    "plan" => $this->config->item('plan'),
                    "trial_period_days" => $this->config->item('planTrial')
                ));
                $type = 1;
                $description = $badge = $this->config->item('planDescription');
                $currency = $this->config->item('planCurrency');
            } elseif($typeSubscription === '2') {
                $subsription = \Stripe\Subscription::create(array(
                    "customer" => $customerId,
                    "plan" => $this->config->item('plan2'),
                    "trial_period_days" => $this->config->item('plan2Trial')
                ));
                $type = 1;
                $description = $badge = $this->config->item('plan2Description');
                $currency = $this->config->item('plan2Currency');
            } elseif($typeSubscription === '3') {
                $subsription = \Stripe\Subscription::create(array(
                    "customer" => $customerId,
                    "plan" => $this->config->item('plan3'),
                    "trial_period_days" => $this->config->item('plan3Trial')
                ));
                $type = 1;
                $description = $badge = $this->config->item('plan3Description');
                $currency = $this->config->item('plan3Currency');
            } else {
                $subsription = \Stripe\Charge::create(array(
                    'customer' => $customerId,
                    'amount'   => str_replace(".", "", $this->config->item('payPrice')),
                    'currency' => $this->config->item('payCurrency')
                ));
                $type = 0;
                $description = $badge = $this->config->item('payDescription');
                $currency = $this->config->item('payCurrency');
            }
        } catch(Exception $e) {
            error_log("unable to create the payment for:".$_POST['stripeEmail'].", error:" . $e->getMessage());
            return alert($this->lang->line('An error has occurred, payment has not been send'), 'danger');
            exit;
        }
        // Set customer id and active subscription
        $sql = "UPDATE 2d_users SET customer_id = ?, subscriber = ?, badge = ? WHERE id = ?";
        $this->db->query($sql, array($customerId, 1, $badge, $userId));
        // Create subscription
        $formatedPeriod = '+'.$paymentPeriod.' day';
        $date_end = ($type === 1) ? date('Y-m-d H:i:s', $subsription->current_period_end) : date("Y-m-d H:i:s", strtotime($formatedPeriod, time()));
        $sql = 'INSERT INTO 2d_payments (id_user, price, currency, status, type, subscription_id, date_created, date_modified, date_end, ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->db->query($sql, array($userId, $this->config->item('planPrice'), $currency, $subsription->status, $type, $subsription->id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $date_end, $this->input->ip_address()));
        // Send Invoice
        $this->sendInvoice($userEmail, $this->db->insert_id(), $this->config->item('planPrice'), $description);
        $this->addNotification(3);
        if ($type === 1) {
            $this->addNotification(4);
        }
        return alert($this->lang->line('Your subscription has been created'));
    }

    public function updateStripeStatus($subscriptionId) {
        require_once('./application/vendor/stripe/init.php');
        \Stripe\Stripe::setApiKey(html_escape($this->config->item('secretkey')));
        try {
            $customer = \Stripe\Subscription::retrieve($subscriptionId);
            $status = $customer->status;
        } catch(Exception $e) {
            error_log("unable to check status for:, error:" . $e->getMessage());
            return alert($this->lang->line('An error has occurred, status has not been update'), 'danger');
            exit;
        }
        // Update payment status
        $sql = "UPDATE 2d_payments SET status = ?, date_modified = ?, trial_start = ?, trial_end = ? WHERE subscription_id = ?";
        $this->db->query($sql, array($status, date("Y-m-d H:i:s"), date('Y-m-d H:i:s', $customer->trial_start), date('Y-m-d H:i:s', $customer->trial_end), $subscriptionId));
        return $status;
    }

    public function unsubscribeInvoice($getUrl, $idSubscription) {
        require_once('./application/vendor/stripe/init.php');
        \Stripe\Stripe::setApiKey(html_escape($this->config->item('secretkey')));
        try {
            $sub = \Stripe\Subscription::retrieve($idSubscription);
            $sub->cancel();
            $status = $sub->status;
        } catch(Exception $e) {
            error_log("unable to check status for:, error:" . $e->getMessage());
            return json_encode("No such subscriptions");
            exit;
        }
        $sql = "UPDATE 2d_payments SET status = ?, date_modified = ? WHERE subscription_id = ?";
        $this->db->query($sql, array($status, date("Y-m-d H:i:s"), $idSubscription));
        return json_encode($status);
    }

    public function getPesapalReference()
    {
        $sql = 'SELECT MAX(id) AS id FROM 2d_payments';
        $query = $this->db->query($sql);
        if ($result = $query->row()) {
            $nextId = (int)$result->id + 1;
        }
        return $nextId;
    }

    public function createPesapalSubscription($userId ,$type, $postTransactionId)
    {
        // Set customer id and active subscription
        $sql = "UPDATE 2d_users SET subscriber = ? WHERE id = ?";
        $this->db->query($sql, array(1, $userId));
        if ($type === '2') {
            $type = 2;
            $description = $this->config->item('plan2Description');
            $currency = $this->config->item('plan2Currency');
            $price = $this->config->item('planPrice');
        } elseif ($type === '3') {
            $type = 2;
            $description = $this->config->item('plan3Description');
            $currency = $this->config->item('plan3Currency');
            $price = $this->config->item('planPrice');
        } else {
            $type = 2;
            $description = $this->config->item('payDescription');
            $currency = $this->config->item('payCurrency');
            $price = $this->config->item('planPrice');
        }
        // Create subscription
        $sql = 'INSERT INTO 2d_payments (id_user, price, currency, status, type, subscription_id, date_created, date_modified, date_end, ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $this->db->query($sql, array($userId, $price, $currency, 'succeeded', $type, $postTransactionId, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $this->input->ip_address()));
        return TRUE;
    }
}
