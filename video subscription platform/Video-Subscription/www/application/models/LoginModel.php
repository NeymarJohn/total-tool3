<?php
class LoginModel extends CI_Model
{
    public function socialConnect($userData, $userID)
    {
        $sql = "SELECT id, url, username, image, role, subscriber, status, passkey FROM 2d_users WHERE id = ?";
        $query = $this->db->query($sql, array($userID));
        if($row = $query->row()) {
            if($row->status) {
                $this->session->set_userdata('id', $row->id);
                $this->session->set_userdata('username', $row->username);
                $this->session->set_userdata('url', $row->url);
                $this->session->set_userdata('userData',$userData);
                if($row->image) {
                    $this->session->set_userdata('name_image', $row->image);
                }
                if($row->subscriber == 1) {
                   $this->session->set_userdata('subscriber', true);
                }
                if($row->role == 1) {
                    $this->session->set_userdata('admin', $row->role);
                    redirect(site_url('dashboard/'));
                } else {
                    redirect(site_url());
                }
            } else {
                return alert('', 'warning');
            }
        } else {
            return alert('Error.', 'danger');
        }
    }

    public function checkConnect($email, $password, $rememberme)
    {
        $sql = "SELECT id, url, username, image, role, subscriber, status, passkey FROM 2d_users WHERE email = ? AND password = ?";
        $query = $this->db->query($sql, array($email, $password));
        if($row = $query->row()) {
            if($row->status) {
                $this->session->set_userdata('id', $row->id);
                $this->session->set_userdata('username', $row->username);
                $this->session->set_userdata('url', $row->url);
                if($row->image) {
                    $this->session->set_userdata('name_image', $row->image);
                }
                if($rememberme == true) {
                    $cookie = array(
                      'name'     => 'remember_me',
                      'value'    => "$email",
                      'expire'   => '99999999',
                      'httponly' => true
                                     );
                    $this->input->set_cookie($cookie);
                } else {
                    $cookie = array(
                      'name'     => 'remember_me',
                      'value'    => ''
                            );
                    $this->input->set_cookie($cookie);
                }
                if($row->subscriber == 1) {
                   $this->session->set_userdata('subscriber', true);
                }
                if($row->role == 1) {
                    $this->session->set_userdata('admin', $row->role);
                    redirect(site_url('dashboard/'));
                } else {
                    redirect(site_url());
                }
            } else {
                return alert($this->lang->line('Your account is awaiting validation. Please check your mailbox').' <a href="/login/?send='.$email.'&key='.$row->passkey.'">'.$this->lang->line('or click here').'</a> '.$this->lang->line('to receive it again'), 'warning');
            }
        } else {
            return alert($this->lang->line('Email or password incorrect'), 'danger');
        }
    }

    public function getLocation($ip){
        $ip_data = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip));
        if($ip_data && $ip_data->geoplugin_countryName !== null) {
            $result['countryCode'] = $ip_data->geoplugin_countryCode;
            $result['countryName'] = $ip_data->geoplugin_countryName;
            $result['city'] = $ip_data->geoplugin_city;
        }
        return (isset($result)) ? $result : FALSE;
    }

    public function addUser($email, $username, $password, $passkey, $countryCode, $countryName, $city)
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ?";
        $query = $this->db->query($sql, array($email));
        if($row = $query->row()) {
            $msg = alert($this->lang->line('This email address is already used'), 'danger');
            $isCreated = FALSE;
        } else {
            $sql = "SELECT id FROM 2d_users WHERE username = ?";
            $query = $this->db->query($sql, array($username));
            if($row = $query->row()) {
                $msg = alert($this->lang->line('This username is already used'), 'danger');
                $isCreated = FALSE;
            } else {
                $sql = 'INSERT INTO 2d_users (url, username, email, password, passkey, role, status, date_created, date_modified, ip, country_code, country_name, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $this->db->query($sql, array(url_title(convert_accented_characters($username), $separator = '-', $lowercase = true), ucfirst($username), strtolower($email), $password, $passkey, 0, ($this->config->item('confirmation_inscription') ? 0 : 1), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $this->input->ip_address(), $countryCode, $countryName, $city));
                $this->sendConfirmation($email, $passkey);
                $msg = alert($this->lang->line('Your account has been created'), 'success');
                $isCreated = TRUE;
            }
        }
        return array(
            'msg' => $msg,
            'isCreated' => $isCreated
        );
    }

    public function addLocationStats($countryCode, $countryName, $date)
    {
        $sql = "SELECT id, value FROM 2d_stats_location WHERE country_code = ? AND Year(date_created) = Year('$date')";
        $query = $this->db->query($sql, array($countryCode));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_stats_location SET value = ? WHERE id = ?";
            $this->db->query($sql, array($row->value+1, $row->id));
            $id = $row->id;
        } else {
            $sql = 'INSERT INTO 2d_stats_location (country_code, country_name, value, date_created) VALUES (?, ?, ?, ?)';
            $this->db->query($sql, array($countryCode, $countryName, 1, date("Y-m-d")));
            $id = $this->db->insert_id();
        }
        return $id ? $id : FALSE;
    }

    public function addNotification()
    {
        $sql = "INSERT INTO 2d_notifications (type, new, date_created, date_modified) VALUES (?, ?, ?, ?)";
        $query = $this->db->query($sql, array(2, TRUE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        if($this->db->insert_id()) {
            $id = $this->db->insert_id();
        }
        return (isset($id)) ? $id : FALSE;
    }

    // Facebook & Google
    public function checkSocialLogin($data = array())
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ?";
        $query = $this->db->query($sql, array($data['email']));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_users SET oauth_provider = ?, oauth_uid = ? WHERE id = ?";
            $this->db->query($sql, array($data['oauth_provider'], $data['oauth_uid'], $row->id));
            $userID = $row->id;
        } else {
            // Get total results & generate username
            $sql = "SELECT id FROM 2d_users";
            $query = $this->db->query($sql);
            $username = substr($data['first_name'], 0, 4).substr($data['last_name'], 0, 4).$query->num_rows();
            // Insert new member
            $sql = 'INSERT INTO 2d_users (url, username, email, passkey, role, status, date_created, date_modified, oauth_provider, oauth_uid, ip, country_code, country_name, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $this->db->query($sql, array(url_title(convert_accented_characters($username), $separator = '-', $lowercase = true), ucfirst($username), strtolower($data['email']), random(20), 0, 1, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $data['oauth_provider'], $data['oauth_uid'], $this->input->ip_address(), $data['countryCode'], $data['countryName'], $data['city']));
            $userID = $this->db->insert_id();
            // TODO: envoyer confirmation
            // $this->sendConfirmation($email, $passkey);
            // Update location statistiques
            $this->addLocationStats($data['countryCode'], $data['countryName'], date("Y-m-d"));
            $this->addNotification();
        }
        return $userID ? $userID : FALSE;
    }

    public function checkTwitterLogin($data = array())
    {
        $sql = "SELECT id FROM 2d_users WHERE username = ?";
        $query = $this->db->query($sql, array($data['username']));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_users SET oauth_provider = ?, oauth_uid = ? WHERE id = ?";
            $this->db->query($sql, array($data['oauth_provider'], $data['oauth_uid'], $row->id));
            $userID = $row->id;
        } else {
            // Get total results & generate username
            $sql = "SELECT id FROM 2d_users";
            $query = $this->db->query($sql);
            $username = $data['username'].$query->num_rows();
            // Insert new member
            $sql = 'INSERT INTO 2d_users (url, username, passkey, role, status, date_created, date_modified, oauth_provider, oauth_uid, ip, country_code, country_name, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $this->db->query($sql, array(url_title(convert_accented_characters($username), $separator = '-', $lowercase = true), $username, random(20), 0, 1, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $data['oauth_provider'], $data['oauth_uid'], $this->input->ip_address(), $data['countryCode'], $data['countryName'], $data['city']));
            $userID = $this->db->insert_id();
            // TODO: envoyer confirmation
            // $this->sendConfirmation($email, $passkey);
            // Update location statistiques
            $this->addLocationStats($data['countryCode'], $data['countryName'], date("Y-m-d"));
        }
        return $userID ? $userID : FALSE;
    }

    public function sendConfirmation($email, $passkey)
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
                 'linkButton' => site_url('login/confirm/?mail='.$email.'&key='.$passkey),
                 'labelButton' => $this->config->item('buttonMailConfirmation')
                 );
        $body = $this->load->view('email-templates/action.php', $data, TRUE);
        $this->email->message($body);
        if($this->email->send()) {
            return alert($this->lang->line('Please check your mailbox to activate your account'));
        } else {
            return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
        }
    }

    public function checkRecovery($email)
    {
        $sql = "SELECT passkey FROM 2d_users WHERE email = ?";
        $query = $this->db->query($sql, array($email));
        if($row = $query->row()) {
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
            $this->email->subject($this->config->item('titleMailRecovery'));
            $data = array(
                     'message'  => $this->config->item('mailRecovery'),
                     'linkButton' => site_url('login/changepass/?mail='.$email.'&key='.$row->passkey),
                     'labelButton' => $this->config->item('buttonMailRecovery')
                     );
            $body = $this->load->view('email-templates/action.php', $data, TRUE);
            $this->email->message($body);
            if($this->email->send()) {
                return alert($this->lang->line('Your account recovery information has been sent by email'));
            } else {
                return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
            }
        } else {
            return alert($this->lang->line('Your email address is not registered'), 'danger');
        }
    }

    public function changePassword($email, $passkey, $password)
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ? AND passkey = ?";
        $query = $this->db->query($sql, array($email, $passkey));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_users SET passkey = ?, password = ? WHERE id = ?";
            $this->db->query($sql, array(random(20), $password, $row->id));
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
            $this->email->subject($this->config->item('titleMailPasswordChanged'));
            $data = array(
                     'headTitle'  => $this->config->item('titleMailPasswordChanged'),
                     'message'  => $this->config->item('mailPasswordChanged')
                     );
            $body = $this->load->view('email-templates/alert.php', $data, TRUE);
            $this->email->message($body);
            if($this->email->send()) {
                return alert($this->lang->line('Your password has been changed'));
            } else {
                return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
            }
        } else {
            return alert($this->lang->line('The operation did not work. The link is probably no longer valid. Thank you for making a new request'), 'danger');
        }
    }

    public function checkPasskey($email, $passkey)
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ? AND passkey = ?";
        $query = $this->db->query($sql, array($email, $passkey));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_users SET status = ?, passkey = ? WHERE id = ?";
            $this->db->query($sql, array(1, random(20), $row->id));
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
            $this->email->subject($this->config->item('titleMailWelcome'));
            $data = array(
                     'headTitle'  => $this->config->item('titleMailWelcome'),
                     'message'  => $this->config->item('mailWelcome')
                     );
            $body = $this->load->view('email-templates/alert.php', $data, TRUE);
            $this->email->message($body);
            if($this->email->send()) {
                return alert($this->lang->line('Your account has been validated'));
            } else {
                return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
            }
        } else {
            return alert($this->lang->line('Your account could not be validated'), 'danger');
        }
    }

    public function changeMail($email, $passkey, $oldmail)
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ? AND passkey = ?";
        $query = $this->db->query($sql, array($oldmail, $passkey));
        if($row = $query->row()) {
            $sql = "UPDATE 2d_users SET status = ?, passkey = ?, email = ? WHERE id = ?";
            $this->db->query($sql, array(1, random(20), $email, $row->id));
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
            $this->email->subject($this->config->item('titleMailWelcome'));
            $data = array(
                     'headTitle'  => $this->config->item('titleMailWelcome'),
                     'message'  => $this->config->item('mailWelcome')
                     );
            $body = $this->load->view('email-templates/alert.php', $data, TRUE);
            $this->email->message($body);
            if($this->email->send()) {
                return alert($this->lang->line('Your Email has been changed'));
            } else {
                return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
            }
        } else {
            return alert($this->lang->line('Your email cannot be changed'), 'danger');
        }
    }
}
