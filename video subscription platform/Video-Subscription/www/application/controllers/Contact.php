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

class Contact extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->config->item('cache_activation') === 1) {
            $this->output->cache($this->config->item('cache_expire'));
        }
        if($this->config->item('cache_activation') === 2) {
            $this->output->delete_cache();
        }
        $this->autoloadModel->setThemeSession();
        $this->lang->load('front', $this->session->site_lang);
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $content = $this->load->view($this->session->theme.'/template', $data, true);
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Contact Us').' - '.$this->config->item('sitename');
        $postData['name'] = $this->input->post('name', true);
        $postData['email'] = $this->input->post('email', true);
        $postData['issue'] = $this->input->post('issue', true);
        $postData['subject'] = $this->input->post('subject', true);
        $postData['secureUserCode'] = $this->input->post('secureUserCode', true);
        $postData['secureCode'] = $this->input->post('secureCode', true);
        $postData['message'] = $this->lang->line('Name:').' '.$postData['name'].'<br>'.
                               $this->lang->line('Email:').' '.$postData['email'].'<br><br>'.
                               $this->lang->line('Issue:').' '.$postData['issue'][0].'<br><br>'.
                               $this->lang->line('Subject:').' '.$postData['subject'].'<br><br>'.
                               $this->lang->line('Message:').' <br><br>'.$this->input->post('message', true);
        if($this->input->post('submit')) {
            if($postData['secureUserCode'] === $postData['secureCode']) {
                $data['msg'] = $this->sendEmail($postData);
            } else {
                $data['msg'] = alert($this->lang->line('Incorrect security code'), 'danger');
            }
        }
        $data['secureCode'] = random(6);
        $content = $this->load->view($this->session->theme.'/contact', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function sendEmail($postData)
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
        $this->email->to($this->config->item('emailsite'));
        $this->email->subject($postData['issue'][0]);
        $this->email->message($postData['message']);
        if($this->email->send()) {
            return alert($this->lang->line('Your message has been sent successfully'));
        } else {
            return alert($this->lang->line('The email could not be sent. Please contact support'), 'danger');
        }
    }
}
