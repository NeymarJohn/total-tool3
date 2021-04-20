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

class Categories extends CI_Controller
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
        $this->load->model(array('categoriesModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Categories');
        // Removing a Category
        $idCategory = $this->input->get('del', true);
        if(isset($idCategory) && !$this->config->item('demo')) {
            $data['msg'] = $this->categoriesModel->delCategorie($idCategory);
        }
        // View categories
        $data['getCategories'] = $this->categoriesModel->getCategories();
        $content = $this->load->view('dashboard/categories', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('Categories');
        if($this->input->get('cat', true)) {
            $data['msg'] = alert('Please, you must add a category ! Then add your first video.');
        }
        // Processing the Add Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postParentCat = $this->input->post('parent_cat', true);
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->categoriesModel->addCategorie($postTitle, $postURL, $postParentCat);
        }
        $data['getListCats'] = $this->categoriesModel->getListCats();
        $content = $this->load->view('dashboard/categorie_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idCategory = '')
    {
        $data['title'] = $this->lang->line('Categories');
        // Processing the Change Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true); 
        $postParentCat = $this->input->post('parent_cat', true);
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->categoriesModel->editCategorie($idCategory, $postTitle, $postURL, $postParentCat);
        }
        $data = $this->categoriesModel->getCategorie($idCategory);
        $data['getListCats'] = $this->categoriesModel->getListCats($data['id_relation'], $idCategory);
        $content = $this->load->view('dashboard/categorie_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
        // Processing the form for sending the image
        if(null !== $this->input->post('hiddenFile', true) && !$this->config->item('demo')) {
            $config['upload_path']   = './uploads/images/categories/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            $config['max_size']      = 5000;
            $config['max_width']     = 5048;
            $config['max_height']    = 5536;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('image')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $data['msg'] = alert('The file was successfully sent');
                $this->categoriesModel->updateImage($idCategory, site_url('uploads/images/categories/'.$this->upload->data('file_name')));
            }
        }
    }
}
