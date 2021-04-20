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

class Comments extends CI_Controller
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
        $this->load->model(array('commentsModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Comments');
        $data['title2'] = $this->lang->line('All');
        $data['filterView'] = TRUE;
        // Counting result
        $data['totalComments'] = $this->commentsModel->getTotalComs();
        $data['totalPending'] = $this->commentsModel->getFiltersComs(2);
        $data['totalSpam'] = $this->commentsModel->getFiltersComs(3);
        // Removing a comments
        $idComment = $this->input->get('del', true);
        $idUser = $this->input->get('id', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($idUser) && !$this->config->item('demo')) {
            $this->commentsModel->delComment($idComment, $idUser, $typeComment);
        }
        // Change status
        $idComment = $this->input->get('id', true);
        $newStatus = $this->input->get('status', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($newStatus) && !$this->config->item('demo')) {
            $this->commentsModel->ChangeStatus($idComment, $newStatus, $typeComment);
        }
        // Show comments
        $getComments = $this->commentsModel->getComments();
        $data['getComments'] = $this->commentsModel->getPostComments($getComments);
        $content = $this->load->view('dashboard/comments', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function pending()
    {
        $data['title'] = $this->lang->line('Comments');
        $data['title2'] = $this->lang->line('Pending');
        // Counting result
        $data['totalComments'] = $this->commentsModel->getTotalComs();
        $data['totalPending'] = $this->commentsModel->getFiltersComs(2);
        $data['totalSpam'] = $this->commentsModel->getFiltersComs(3);
        // Removing a comments
        $idComment = $this->input->get('del', true);
        $idUser = $this->input->get('id', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($idUser) && !$this->config->item('demo')) {
            $this->commentsModel->delComment($idComment, $idUser, $typeComment);
        }
        // Change status
        $idComment = $this->input->get('id', true);
        $newStatus = $this->input->get('status', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($newStatus) && !$this->config->item('demo')) {
            $this->commentsModel->ChangeStatus($idComment, $newStatus, $typeComment);
        }
        // Show comments
        $data['getComments'] = $this->commentsModel->getComments(1);
        $content = $this->load->view('dashboard/comments', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function spam()
    {
        $data['title'] = $this->lang->line('Comments');
        $data['title2'] = $this->lang->line('Spam');
        // Counting result
        $data['totalComments'] = $this->commentsModel->getTotalComs();
        $data['totalPending'] = $this->commentsModel->getFiltersComs(2);
        $data['totalSpam'] = $this->commentsModel->getFiltersComs(3);
        // Removing a comments
        $idComment = $this->input->get('del', true);
        $idUser = $this->input->get('id', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($idUser) && !$this->config->item('demo')) {
            $this->commentsModel->delComment($idComment, $idUser, $typeComment);
        }
        // Change status
        $idComment = $this->input->get('id', true);
        $newStatus = $this->input->get('status', true);
        $typeComment = $this->input->get('type', true);
        if(isset($idComment) && isset($newStatus) && !$this->config->item('demo')) {
            $this->commentsModel->ChangeStatus($idComment, $newStatus, $typeComment);
        }
        // Show comments
        $data['getComments'] = $this->commentsModel->getComments(2);
        $content = $this->load->view('dashboard/comments', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('Comments');
        // Processing the Add Form
        $postAuthor = $this->input->post('author', true);
        $postComment = $this->input->post('comment', true);
        $postVideo = $this->input->post('video', true);
        $postStatus = $this->input->post('status', true);
        if(isset($postAuthor) && isset($postComment) && isset($postVideo) && !$this->config->item('demo')) {
            $data['msg'] = $this->commentsModel->addComment($postAuthor, $postComment, $postVideo, $postStatus);
        }
        // Get the list of users for the relations with the comments
        $data['getUsers'] = $this->commentsModel->getUsers();
        // Get the list of videos for the relations with the comments
        $data['getVideos'] = $this->commentsModel->getVideos();
        $content = $this->load->view('dashboard/comment_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idComment = '')
    {
        $data['title'] = $this->lang->line('Comments');
        // Processing the Change Form
        $postAuthor = $this->input->post('author', true);
        $postComment = $this->input->post('comment', true);
        $postVideo = $this->input->post('video', true);
        $postStatus = $this->input->post('status', true);
        $typeComment = $this->input->get('type', true);
        if(isset($postAuthor) && isset($postComment) && isset($postVideo) && !$this->config->item('demo')) {
            $data['msg'] = $this->commentsModel->editComment($idComment, $postAuthor, $postComment, $postVideo, $postStatus, $typeComment);
        }
        // Get comment data to display
        $typeComment = $this->input->get('type', true);
        $data = array_merge($data, $this->commentsModel->getComment($idComment, $typeComment));
        // Ban a user
        $banUser = $this->input->get('ban', true);
        if(isset($banUser) && !$this->config->item('demo')) {
            $data['msg'] = $this->commentsModel->banUser($data['id_user']);
        }
        // Get list of users for the relations with the comments
        $data['getUsers'] = $this->commentsModel->getUsers($data['id_user']);
        // Get list of videos or posts for the relationship with the comment
        if ($typeComment === '1') {
            $data['getVideos'] = $this->commentsModel->getPosts($data['id_type']);
        } else {
            $data['getVideos'] = $this->commentsModel->getVideos($data['id_type']);
        }
        $content = $this->load->view('dashboard/comment_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
