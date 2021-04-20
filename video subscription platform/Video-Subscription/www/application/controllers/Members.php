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

class Members extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if($this->config->item('cache_activation') === 1) {
            $this->output->cache($this->config->item('cache_expire'));
        }
        if($this->config->item('cache_activation') === 2) {
            $this->output->delete_cache();
        }
        $this->lang->load('front', $this->session->site_lang);
        $this->autoloadModel->setThemeSession();
        $data['title'] = 'Members - '.$this->config->item('sitename');
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $content = $this->load->view($this->session->theme.'/template', $data, true);
        $this->load->model(array('membersModel'));
    }

    public function index()
    {
        // Displaying all the videos with pagination
        $data['getMembersNotes'] = $this->membersModel->getMembersNotes();
        $data['getMembersFavs'] = $this->membersModel->getMembersFavs();
        $data['getMembersComs'] = $this->membersModel->getMembersComs();
        $content = $this->load->view($this->session->theme.'/members', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }
}
