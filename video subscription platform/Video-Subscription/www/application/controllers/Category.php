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

class Category extends CI_Controller
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
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $content = $this->load->view($this->session->theme.'/template', $data, true);
        $this->load->model(array('categoryModel'));
    }

    public function index($getUrl = '', $getOrder = '', $getPag = '')
    {
        $segment = ($this->uri->segment(1) === 'videos') ? $this->uri->segment(2, 0) : $this->uri->segment(3, 0);
        if($segment !== 'title' && $segment !== 'popular' && $segment !== 'rated' && $segment !== 'favorites') {
            $segment = '';
        }
        // Get videos
        if ($this->uri->segment(1) === 'videos') {
            $data = $this->categoryModel->getAllVideos($getOrder, $getPag);
            $data['title'] = $this->lang->line('All Videos').' - '.$this->config->item('sitename');
            $config["base_url"] = site_url('videos/'.$segment);
        } else {
            $data = $this->categoryModel->getBlocsVideo(urldecode($getUrl), $getOrder, $getPag);
            $data['title'] = $data['cat_title'].' '.$this->lang->line('Videos').' - '.$this->config->item('sitename');
            $config["base_url"] = site_url('category/'.$data['cat_url'].'/'.$segment);
        }
        // Get pagination
        $config['reuse_query_string'] = TRUE;
        $config['suffix'] = '/';
        $config['total_rows'] = $data['nbRows'];
        $config['per_page'] = $this->config->item('cat_pag');
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        // Get search result
        if(!empty($this->input->get('q'))) {
            if ($this->uri->segment(1) === 'videos') {
                $data = array_merge($data, $this->categoryModel->search($this->input->get('q', true), $getPag));
            } else {
                $data = array_merge($data, $this->categoryModel->search($this->input->get('q', true), $getPag, $data['id_category']));
            }
        }
        $content = $this->load->view($this->session->theme.'/category', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }
}
