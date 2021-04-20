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

class Medias extends CI_Controller
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
        $this->load->model(array('mediasModel'));
        $this->load->helper(array('file'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('medias');
        // Process form for sending image
        if($this->input->post('submit') && !$this->config->item('demo')) {
            $config['upload_path']   = './uploads/images/default/';
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            $config['max_size']      = 5000;
            $config['max_width']     = 5048;
            $config['max_height']    = 5536;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('userImage')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $data['msg'] = alert($this->lang->line('The file was successfully sent'));
            }
        }
        if(null!==$this->input->get('img', true) && !$this->config->item('demo')) {
            $file = 'uploads/images/videos/'.$this->input->get('img', true);
            if(is_readable($file) && unlink($file)) {
                $deleteDbImg = $this->mediasModel->deleteDbImg($this->input->get('img', true));
                $data['msg'] = alert($this->lang->line('The file has been deleted'));
            } else {
                $data['msg'] = alert($this->lang->line('The file was not found or not readable and could not be deleted'), 'danger');
            }
        }
        if(null!==$this->input->get('swf', true) && !$this->config->item('demo')) {
            $file = 'uploads/files/videos/'.$this->input->get('swf', true);
            if(is_readable($file) && unlink($file)) {
                $deleteDbFile = $this->mediasModel->deleteDbFile($this->input->get('swf', true));
                $data['msg'] = alert($this->lang->line('The file has been deleted'));
            } else {
                $data['msg'] = alert($this->lang->line('The file was not found or not readable and could not be deleted'), 'danger');
            }
        }
        $data['getImages'] = '';
        $getImagesNames = get_filenames('uploads/images/videos/');
        $nbRows = count($getImagesNames);
        $limitResult = array_slice($getImagesNames, (int)$this->input->get('page', true), 12);
        foreach ($limitResult as $file) {
            $data['getImages'] .= '<div class="col-sm-4 col-lg-3 col-md-3">
        							  <div class="video-medias-box">
        							      <img src="'.site_url('uploads/images/videos/'.$file).'" class="img-thumbnail m-t-10 m-b-10" />
        							 	  <div class="video-action display">
        									 <a href="'.site_url('dashboard/medias/?img='.$file).'" class="btn btn-danger btn-xs"><i class="md md-close"></i></a>
        								  </div>
        							  </div>
        						   </div>';
        }
        // Displaying pagination
        $this->load->library('pagination');
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = site_url('dashboard/medias');
        $config['total_rows'] = $nbRows;
        $config['per_page'] = 12;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['getFiles'] = '';
        $getFilesNames = get_filenames('uploads/files/videos/');
        foreach ($getFilesNames as $file) {
            $video = $this->mediasModel->getSwfVideo($file);
            $data['getFiles'] .= '<tr class="text-center">
        							<td>'.$file.'</td>
        							<td>'.$video['title'].'</td>
        							<td>
        								<!--<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/medias/?edit='.$file).'"><i class="fa fa-pencil"></i></a>-->
        								<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/medias/?swf='.$file).'"> <i class="fa fa-trash-o"></i> </a>
        							</td>
        						</tr>';
        }
        $content = $this->load->view('dashboard/medias', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
