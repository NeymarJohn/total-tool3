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

class Page extends CI_Controller
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
        $this->autoloadModel->setThemeSession();
        $this->lang->load('front', $this->session->site_lang);
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $this->load->model(array('pageModel'));
        $content = $this->load->view($this->session->theme.'/template', $data, true);
    }

    public function index($getUrl = '')
    {
        $data = $this->pageModel->getPage(urldecode($getUrl));
        $data['title'] = $data['title_page'].' - '.$this->config->item('sitename');
        if($data['sub_page']) {
            $data = array_merge($data, $this->pageModel->getSubPage($data['sub_page']));
        }
        $content = $this->load->view($this->session->theme.'/page', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }
}
