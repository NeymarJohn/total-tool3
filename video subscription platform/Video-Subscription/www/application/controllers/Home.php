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

class Home extends CI_Controller
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
        $data['title'] = $this->config->item('sitename').' - '.$this->config->item('description');
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $data['hideMenu'] = TRUE;
        $content = $this->load->view($this->session->theme.'/template', $data, true);
        $this->load->model(array('homeModel'));
    }

    public function index($getOrder = '', $getPag = '')
    {
        // Displaying all the videos
        $data['getPopularVideos'] = $this->homeModel->getBlocsVideo('popular');
        $data['getNewsVideos'] = $this->homeModel->getBlocsVideo('news');
        $data['getRatedVideos'] = $this->homeModel->getBlocsVideo('rated');
        $data['getTitleVideo'] = $this->homeModel->getBlocsVideo('');
        $data['getCategories1Title'] = $this->homeModel->getCategoriesTitle($this->config->item('homeCategory1'));
        $data['getCategories1'] = $this->homeModel->getCategories($this->config->item('homeCategory1'));
        $data['getCategories2Title'] = $this->homeModel->getCategoriesTitle($this->config->item('homeCategory2'));
        $data['getCategories2'] = $this->homeModel->getCategories($this->config->item('homeCategory2'));
        $data['getCategories3Title'] = $this->homeModel->getCategoriesTitle($this->config->item('homeCategory3'));
        $data['getCategories3'] = $this->homeModel->getCategories($this->config->item('homeCategory3'));
        $data['getCategories4Title'] = $this->homeModel->getCategoriesTitle($this->config->item('homeCategory4'));
        $data['getCategories4'] = $this->homeModel->getCategories($this->config->item('homeCategory4'));
        $content = $this->load->view($this->session->theme.'/home', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }
}
