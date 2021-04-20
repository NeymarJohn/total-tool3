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

class Pages extends CI_Controller
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
        $this->load->model(array('pagesModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Pages');
        // Deleting a page
        $idPage = $this->input->get('del', true);
        if(isset($idPage) && !$this->config->item('demo')) {
            $this->pagesModel->delPage($idPage);
        }
        // Viewing pages
        $data['getPages'] = $this->pagesModel->getPages();
        $content = $this->load->view('dashboard/pages', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('Pages');
        // Processing the creative form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postContent = $this->input->post('content', true);
        $postSubPage = $this->input->post('subpage', true);
        $postCustomPage = $this->input->post('customPage', true);
        // Processing the left form
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->pagesModel->addPage($postTitle, $postURL, $postContent, $postSubPage, $postCustomPage);
        }
        $data['display_footer'] = '1';
        $content = $this->load->view('dashboard/page_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idPage = '')
    {
        $data['title'] = $this->lang->line('Pages');
        // Processing the Change Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postContent = $this->input->post('content', true);
        $postSubPage = $this->input->post('subpage', true);
        $postCustomPage = $this->input->post('customPage', true);
        // Process form
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->pagesModel->editPage($idPage, $postTitle, $postURL, $postContent, $postSubPage, $postCustomPage);
        }
        // Get page data
        $data = array_merge($data, $this->pagesModel->getPage($idPage));
        // Get list pages
        $data['getListPages'] = $this->pagesModel->getListPages($data['sub_page']);
        $content = $this->load->view('dashboard/page_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
