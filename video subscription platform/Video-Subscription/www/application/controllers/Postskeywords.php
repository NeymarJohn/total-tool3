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

class Postskeywords extends CI_Controller
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
        $this->load->model(array('postskeywordsModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('keywords');
        // Delete a keyword
        $idKeyword = $this->input->get('del', true);
        if(isset($idKeyword) && !$this->config->item('demo')) {
            $data['msg'] = $this->postskeywordsModel->delKeyword($idKeyword);
        }
        // View keywords
        $data['getKeywords'] = $this->postskeywordsModel->getKeywords();
        $content = $this->load->view('dashboard/keywords', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('keywords');
        // Processing the Add Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postDescription = $this->input->post('description', true);
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->postskeywordsModel->addKeyword($postTitle, $postURL);
        }
        $content = $this->load->view('dashboard/keyword_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idKeyword = '')
    {
        $data['title'] = $this->lang->line('keywords');
        // Processing the Change Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postDescription = $this->input->post('description', true);
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->postskeywordsModel->editKeyword($idKeyword, $postTitle, $postURL, $postDescription);
        }
        // Get keyword data
        $data = $this->postskeywordsModel->getKeyword($idKeyword);
        $content = $this->load->view('dashboard/keyword_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
        // Processing the form for sending the image
        if(null !== $this->input->post('hiddenFile', true) && !$this->config->item('demo')) {
            $config['upload_path']   = './uploads/images/keywords/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            $config['max_size']      = 5000;
            $config['max_width']     = 5048;
            $config['max_height']    = 5536;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('image')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $data['msg'] = alert($this->lang->line('The file was successfully sent'));
                $this->postskeywordsModel->updateImage($idKeyword, site_url('uploads/images/keywords/'.$this->upload->data('file_name')));
            }
        }
    }
}
