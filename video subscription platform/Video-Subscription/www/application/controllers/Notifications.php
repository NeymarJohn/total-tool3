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

class Notifications extends CI_Controller
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
        $this->load->model(array('NotificationsModel'));
    }

    public function index()
    {
        // Type (0:newsletter, 1:report video, 2:member, 3:sale, 4:subscriber, 5:comment)
        $data['title'] = $this->lang->line('Notifications');
        $totalNotifications = $this->NotificationsModel->getTotalNotifications();
        $data['getAllNotifications'] = $this->NotificationsModel->getAllNotifications($this->input->get('page'));
        $data['getPagination'] = $this->createPagination($totalNotifications, 20);
        if ($data['getAllNotifications'] && !$this->config->item('demo')) {
            if ($this->NotificationsModel->updateStatus() > 0) {
                $data['msg'] = alert($this->lang->line('Update Completed'));
            }
        }
        $content = $this->load->view('dashboard/notifications', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function createPagination($totalRows, $perPage)
    {
        $this->load->library('pagination');
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = site_url('dashboard/notifications/');
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
