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

class Tools extends CI_Controller
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
        $this->load->model(array('toolsModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('Maintenance');
        if(null !== $this->input->post('deleteAccounts') && !$this->config->item('demo')) {
            $this->toolsModel->deleteAccounts();
        }
        if(null !== $this->input->post('deleteStats') && !$this->config->item('demo')) {
            $this->toolsModel->deleteStats();
        }
        $nbTotalTask = $this->toolsModel->getTotalTaskActivity();
        $data['taskActivity'] = $this->toolsModel->getTaskActivity($this->input->get('page', true));
        $data['getPagination'] = $this->createPagination(site_url('dashboard/tools/'), $nbTotalTask, 10);
        $content = $this->load->view('dashboard/tools', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function createPagination($baseUrl, $totalRows, $perPage)
    {
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $this->load->library('pagination');
        $config["base_url"] = $baseUrl;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
