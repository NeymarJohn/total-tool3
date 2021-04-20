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

class Posts extends CI_Controller
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
        $this->load->model(array('postsModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Posts');
        // Deleting a post
        $idPost = $this->input->get('del', true);
        if(isset($idPost) && !$this->config->item('demo')) {
            $this->postsModel->delPost($idPost);
        }
        // Viewing posts
        $data['getPosts'] = $this->postsModel->getPosts();
        $content = $this->load->view('dashboard/posts', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('Posts');
        // Processing the Add Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postContent = $this->input->post('content', true);
        $postIdCategory = $this->input->post('category', true);
        $postKeywords = $this->input->post('keywords', true);
        $postStatus = $this->input->post('status', true);
        if(($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            $data['msg'] = $this->postsModel->addPost($postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $this->session->userdata('id'));
        }
        // Get categories
        $data['getCategories'] = $this->postsModel->getCategories();
        // Get keywords
        $data['getKeywords'] = $this->postsModel->getKeywords();
        $content = $this->load->view('dashboard/post_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idPost = '')
    {
        $data['title'] = $this->lang->line('Posts');
        // Processing the Change Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postContent = $this->input->post('content', true);
        $postIdCategory = $this->input->post('category', true);
        $postKeywords = $this->input->post('keywords', true);
        $postStatus = $this->input->post('status', true);
        if(isset($postTitle) && ($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            if(!empty($postKeywords)) {
                $postKeywords = array_map("addQuote", $postKeywords);
                $postKeywords = implode(",", $postKeywords);
            }
            $data['msg'] = $this->postsModel->editPost($idPost, $postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $this->session->userdata('id'));
        }
        // Processing the form for sending the image
        if(null !== $this->input->post('hiddenImage', true) && !$this->config->item('demo')) {
            $config['upload_path']   = './uploads/images/posts/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            $config['max_size']      = 5000;
            $config['max_width']     = 5048;
            $config['max_height']    = 5536;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('userImage')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $data['msg'] = alert($this->lang->line('The file was successfully sent'));
                $this->postsModel->updateImage($idPost, site_url('uploads/images/posts/'.$this->upload->data('file_name')));
            }
        }
        // Recovering post data
        $data = array_merge($data, $this->postsModel->getPost($idPost));
        // Get categories
        $data['getCategories'] = $this->postsModel->getCategories($data['id_category']);
        // Get keywords
        $data['getKeywords'] = $this->postsModel->getKeywords($idPost);
        $content = $this->load->view('dashboard/post_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
