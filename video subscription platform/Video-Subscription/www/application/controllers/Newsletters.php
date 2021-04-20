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

class Newsletters extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!isset($this->session->admin)) {
            redirect('/login/');
        }
        $this->lang->load('front', $this->session->site_lang);
        $data = $this->autoloadModel->getNotifications();
        $content = $this->load->view('dashboard/template', $data, true);
        $this->load->model(array('newslettersModel'));
    }

    public function index($getEmail = '')
    {
        $data['title'] = $this->lang->line('Newsletter Subscription');
        // Remove an email from newsletter
        $idEmail = $this->input->get('del', true);
        if(isset($idEmail) && !$this->config->item('demo')) {
            $this->newslettersModel->delEmail($idEmail);
        }
        $data['getNewsletters'] = $this->newslettersModel->getNewsletters();
        $content = $this->load->view('dashboard/newsletters', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($getId = '')
    {
        // Proccess form
        $post['email'] = $this->input->post('email', true);
        $post['status'] = $this->input->post('status', true);
        if(isset($post['email']) && !$this->config->item('demo')) {
            if ($this->newslettersModel->editNewsletter($getId, $post)) {
                $data['msg'] = alert($this->lang->line('Saved changes'));
            }
        }
        // Get datas
        $data['title'] = $this->lang->line('Newsletter Subscription');
        $data['getNewsletter'] = $this->newslettersModel->getNewsletter($getId);
        $content = $this->load->view('dashboard/newsletter_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
