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

class Videos extends CI_Controller
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
        $this->load->model(array('videosModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('All Videos');
        // Deleting a video
        $idVideo = $this->input->get('del', true);
        if(isset($idVideo) && !$this->config->item('demo')) {
            $this->videosModel->delVideo($idVideo);
        }
        // Viewing videos
        $data['getVideos'] = $this->videosModel->getVideos();
        $content = $this->load->view('dashboard/videos', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function episodes()
    {
        $data['title'] = $this->lang->line('All episodes');
        // Deleting an episode
        $idEpisode = $this->input->get('del', true);
        if(isset($idEpisode) && !$this->config->item('demo')) {
            $this->videosModel->delEpisode($idEpisode);
        }
        // Viewing episodes
        $data['getVideos'] = $this->videosModel->getEpisodes();
        $content = $this->load->view('dashboard/episodes', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function add()
    {
        $data['title'] = $this->lang->line('New Video');
        // Processing the Add Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postDescription = $this->input->post('description', true);
        $postIdCategory = $this->input->post('category', true);
        $postKeywords = $this->input->post('keywords', true);
        $postType = $this->input->post('type', true);
        $postEmbed = $this->input->post('embed', true);
        $postSubscription = $this->input->post('subscription', true);
        $postStatus = $this->input->post('status', true);
        if(($postTitle) != '' && !$this->config->item('demo')) {
            if($postURL == '') {
                $postURL = url_title(convert_accented_characters($postTitle), $separator = '-', $lowercase = true);
            } else {
                $postURL = url_title(convert_accented_characters($postURL), $separator = '-', $lowercase = true);
            }
            if(!empty($postKeywords)) {
                $postKeywords = array_map("addQuote", $postKeywords);
                $postKeywords = implode(",", $postKeywords);
            }
            $data['msg'] = $this->videosModel->addVideo($postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus);
        }
        // Get categories
        $data['getCategories'] = $this->videosModel->getCategories();
        // Get keywords
        $data['getKeywords'] = $this->videosModel->getKeywords();
        $content = $this->load->view('dashboard/video_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idVideo = '')
    {
        $data['title'] = $this->lang->line('Videos');
        // Processing the Change Form
        $postTitle = $this->input->post('title', true);
        $postURL = $this->input->post('url', true);
        $postDescription = str_replace('"', '&#34;', $this->input->post('description', true));
        $postIdCategory = $this->input->post('category', true);
        $postKeywords = $this->input->post('keywords', true);
        $postType = $this->input->post('type', true);
        $postEmbed = $this->input->post('embed', true);
        $postSubscription = $this->input->post('subscription', true);
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
            $data['msg'] = $this->videosModel->editVideo($idVideo, $postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus);
        }
        // Process form for upload the image
        if(null !== $this->input->post('hiddenImage') && !$this->config->item('demo')) {
            $data = array_merge($data, $this->uploadImage($idVideo, 0));
        }
        // Process form for upload the video from input
        if($this->input->post('userInput') && !$this->config->item('demo')) {
            $updateFile = $this->videosModel->updateFile($idVideo, $this->input->post('userInput', true));
            if($updateFile) {
                $data['msg'] = alert($this->lang->line('The file was successfully update'));
            }
        }
        // Processing the form for sending the trailer from input
        if ($this->input->post('userTrailer') && !$this->config->item('demo')) {
            $updateTrailer = $this->videosModel->updateTrailer($idVideo, $this->input->post('userTrailer', true));
            if ($updateTrailer) {
                $data['msg'] = alert($this->lang->line('The file was successfully update'));
            }
        }
        // Get video data
        $data = array_merge($data, $this->videosModel->getVideo($idVideo));
        // Get categories
        $data['getCategories'] = $this->videosModel->getCategories($data['id_category']);
        // Get keywords
        $data['getKeywords'] = $this->videosModel->getKeywords($idVideo);
        // Process form for upload the video
        if(null !== $this->input->post('hiddenFile') && !$this->config->item('demo')) {
            if ($data['type_video'] === '4') {
                $data = array_merge($data, $this->uploadAmazonS3File($idVideo, 0));
            } else {
                $data = array_merge($data, $this->uploadFile($idVideo, 0));
            }
        }
        $data = array_merge($data, $this->uploadAmazonS3BrowserFile($idVideo, 0));

        $content = $this->load->view('dashboard/video_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function addepisode()
    {
        $data['title'] = $this->lang->line('New episode');
        $post['video'] = $this->input->post('video', true);
        $post['title'] = $this->input->post('title', true);
        $post['description'] = $this->input->post('description', true);
        $post['episode'] = $this->input->post('episode', true);
        $post['season'] = $this->input->post('season', true);
        $post['image'] = $this->input->post('image', true);
        $post['type'] = $this->input->post('type', true);
        $post['embed'] = $this->input->post('embed', true);
        $post['status'] = $this->input->post('status', true);
        if(!empty($post['title']) && !$this->config->item('demo')) {
            $data['msg'] = $this->videosModel->addEpisode($post);
        }
        $data['getVideosList'] = $this->videosModel->getVideosList();
        $content = $this->load->view('dashboard/episode_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function editepisode($idVideo = '')
    {
        $data['title'] = $this->lang->line('Videos');
        $post['video'] = $this->input->post('video', true);
        $post['title'] = $this->input->post('title', true);
        $post['description'] = str_replace('"', '&#34;', $this->input->post('description', true));
        $post['episode'] = $this->input->post('episode', true);
        $post['season'] = $this->input->post('season', true);
        $post['image'] = $this->input->post('image', true);
        $post['type'] = $this->input->post('type', true);
        $post['embed'] = $this->input->post('embed', true);
        $post['status'] = $this->input->post('status', true);
        if ($post['title'] != '' && !$this->config->item('demo')) {
            $data['msg'] = $this->videosModel->editEpisode($post, $idVideo);
        }
        // Processing the form for sending the image
        if(null !== $this->input->post('hiddenImage') && !$this->config->item('demo')) {
            $data = array_merge($data, $this->uploadImage($idVideo, 1));
        }
        // Process form for upload the video from input
        if($this->input->post('userInput') && !$this->config->item('demo')) {
            $updateFile = $this->videosModel->updateFile($idVideo, $this->input->post('userInput', true), 1);
            if($updateFile) {
                $data['msg'] = alert($this->lang->line('The file was successfully update'));
            }
        }
        $data = array_merge($data, $this->videosModel->getEpisode($idVideo));
        // Update parent video type
        $this->videosModel->updateVideoType($data['id_relation']);
        $data['getVideosList'] = $this->videosModel->getVideosList($data['id_relation']);
        // Process form for upload the video
        if(null !== $this->input->post('hiddenFile') && !$this->config->item('demo')) {
            if ($data['type'] === '4') {
                $data = array_merge($data, $this->uploadAmazonS3File($idVideo, 1));
            } else {
                $data = array_merge($data, $this->uploadFile($idVideo, 1));
            }
        }
        $data = array_merge($data, $this->uploadAmazonS3BrowserFile($idVideo, 0));
        
        $content = $this->load->view('dashboard/episode_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function uploadImage($id, $type) {
        $config['upload_path']   = './uploads/images/videos/';
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        $config['max_size']      = 5000;
        $config['max_width']     = 5048;
        $config['max_height']    = 5536;
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('userImage')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $data['msg'] = alert($this->lang->line('The file was successfully sent'));
            $this->videosModel->updateImage($id, site_url('uploads/images/videos/'.$this->upload->data('file_name')), $type);
        }
        return $data;
    }

    public function uploadFile($id, $type) {
        $config['upload_path']   = './uploads/files/videos/';
        $config['allowed_types'] = 'mp4|mpeg|mov|ogg|webm';
        $config['max_size']      = 500000;
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('userFile')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $data['msg'] = alert($this->lang->line('The file was successfully sent'));
            $this->videosModel->updateFile($id, site_url('uploads/files/videos/'.$this->upload->data('file_name')), $type);
        }
        return $data;
    }

     /**
     * Simple file upload to S3 bucket (browser -> server -> amazonS3)
     *
     * @access public
     */    
    public function uploadAmazonS3File($idVideo, $type)
    {
        $this->file_name = "";
        $this->upload_data = "";

        $this->load->library(array('upload', 'aws'));
        $this->load->helper(array('form', 'url', 'aws_helper', 'file'));

        if (isset($_FILES['userFile']))
        {
            $config['upload_path']   = 'videos/'; // Leave blank if want to upload at root of bucket
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docs|zip|mp4|mov|ogg|webm'; // 'jpg|jpeg|png|gif|pdf|doc|docs|zip'
            $config['remove_spaces'] = TRUE;
            $config['max_size']      = '15120'; // 15MB
            
            //S3 integration library config
            $config['acl'] = 'public-read';
            $config['make_unique_filename'] = true;
            
            $this->_upload_file($config, 'userFile');
            if (!empty($this->upload_data)) {
                // run insert model to write data to db
                if($this->videosModel->updateFile($idVideo, $this->upload_data['full_path'], $type)) {
                    $data['msg'] = alert($this->lang->line('The file was successfully sent'));
                } else {
                    $data['msg'] = alert($this->lang->line('Some problem occurred, please try again later!'));
                }
            } else {
                $data['msg'] = alert($this->custom_errors['userFile'], 'danger');
            }
        }
        return $data;
    }

    public function uploadAmazonS3BrowserFile($idVideo, $type)
    {
        $this->load->helper(array('form', 'url', 'aws', 'file'));
	    $this->load->library('aws');

        //S3 integration library config
        $config['acl'] = 'public-read';
        $data['s3_details'] = $this->aws->getS3Details($config['acl']);

        if (!empty($this->input->post('uploaded_files')) && !$this->config->item('demo')) //validation passed
        {
            $uploaded_files = ($this->input->post('uploaded_files') != "") ? $this->input->post('uploaded_files') : "";
            $uploaded_files = json_decode($uploaded_files);
            // In this case we use only one file, the lastest
            foreach ($uploaded_files as $file){
                $original_name = $file->original_name;
                $s3_name = $file->s3_name;
                $size = $file->size;
                $url = $file->url;
            }
            // run insert model to write data to db
            if($this->videosModel->updateFile($idVideo, $s3_name, $type)) {
                $data['msg'] = alert($this->lang->line('The file was successfully sent'));
            } else {
                $data['msg'] = alert($this->lang->line('Some problem occurred, please try again later!'));
            }
        }
        
        return $data;
    }

    /**
     * File upload to S3 Bucket
     *
     * @access private
     */    
    function _upload_file($config, $field_name)
    {
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload_s3($field_name)) {
            $this->custom_errors[$field_name] = $this->upload->display_errors();
        } else {
            $this->upload_data = $this->upload->data();
        }        
    }

    /**
     * Check file upload error occured or not, If occured then set the form field error.
     *
     * @access private
     */    
    function _check_file($field, $field_value)
    {
        if (isset($this->custom_errors[$field_value])) {
            $this->form_validation->set_message('_check_file', $this->custom_errors[$field_value]);
            unset($this->custom_errors[$field_value]);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Delete File from S3 Bucket
     *
     * @access public
     */    
    public function delete_file($file_id="")
    {
        if(!$file_id) {
            $this->session->set_flashdata('msg', 'Nothing to delete.');
            redirect('cis3integration');
        }

        if($this->cis3integration_model->delete_file($file_id)==true) {
                $this->session->set_flashdata('msg', 'File successfully deleted from S3 Bucket.');
                $this->load->library('user_agent');
                redirect($this->agent->referrer());// or whatever logic you need
        } else {
            $this->session->set_flashdata('msg', 'Unable to delete file.');
            redirect('cis3integration'); // or whatever logic you need
        }
    }
}
