<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Coffee Theme
*
* PHP version >= 7.0.0
*
* @category  PHP
* @package   VideoTube - PHP Script
* @author    Nicolas Grimonpont <support@coffeetheme.com>
* @copyright 2010-2019 Nicolas Grimonpont
* @license   Standard License
* @link      http://coffeetheme.com/
*/

class Newsletter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('NewsletterModel'));
    }

    public function index($getEmail = '')
    {
        $getEmail = urldecode($getEmail);
        if ($this->input->post('token')) {
            if (filter_var($getEmail, FILTER_VALIDATE_EMAIL)) {
                $idNewsletter = $this->NewsletterModel->checkNewsletter($getEmail);
                if(!$idNewsletter) {
                    $idUser = $this->NewsletterModel->checkUsers($getEmail);
                    if(!$idUser) {
                        $idNewUser = $this->NewsletterModel->addEmail($getEmail, FALSE);
                    } else {
                        $idNewUser = $this->NewsletterModel->addEmail($getEmail, TRUE);
                    }
                    if($idNewUser) {
                        $mailchimpUser = $this->addUserTomailchimp($getEmail, $this->config->item('mailchimpList'));
                        $this->NewsletterModel->addNotification();
                        $data['msg'] = '<div class="text-danger p-t-10">'.$this->lang->line('Your email has been added').'</div>';
                    }
                } else {
                    $data['msg'] = '<div class="text-danger p-t-10">'.$this->lang->line('Mail already in newsletter list').'</div>';
                }
            }
            else {
                $data['msg'] = '<div class="text-danger p-t-10">'.$this->lang->line('Incorrect email format').'</div>';
            }
        }
        $content = $this->load->view('responseAjax', $data);
    }

    public function addUserTomailchimp($getEmail, $list_id)
    {
        $this->load->library('MailChimp');
        $result = $this->mailchimp->post("lists/$list_id/members", [
				'email_address' => $getEmail,
				'status'        => 'subscribed',
			]);
            return ($result) ? $result : FALSE;
    }

    public function sendNewsletter()
    {
        $usersList = $this->NewsletterModel->getUsersList();
        $this->load->library('email');
        $config = array(
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        foreach ($usersList as $row) {
            $this->email->clear();
            $this->email->from($this->config->item('emailsite'), $this->config->item('author'));
            $this->email->to($row->email);
            $this->email->subject($this->config->item('titleMailConfirmation'));
            $data = array(
                     'message'  => $this->config->item('mailConfirmation'),
                     'linkButton' => site_url('login/confirm/?mail=&key='),
                     'labelButton' => $this->lang->line('Confirm Email Address')
                     );
            $body = $this->load->view('email-templates/action.php', $data, TRUE);
            $this->email->message($body);
            if($this->email->send()) {
                return alert($this->lang->line('Please check your mailbox to activate your account'));
            } else {
                return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
            }
        }
    }
}
