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

class Maintenance extends CI_Controller
{
    public function index()
    {
        $this->autoloadModel->setThemeSession();
        $data['title'] = $this->config->item('sitename').' - '.$this->lang->line('Maintenance');
        $data['message'] = $this->config->item('maintenance_message');
        $content = $this->load->view($this->session->theme.'/maintenance', $data, true);
        $this->load->view('landing', array('content' => $content));
    }
}
