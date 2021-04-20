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

class Search extends CI_Controller
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
        $data['title'] = $this->lang->line('Search');
        $data['getMobileMenu'] = $this->autoloadModel->getMobileMenu($this->config->item('headerMenu1'), TRUE).$this->autoloadModel->getMobileMenu($this->config->item('headerMenu2'), TRUE);
        $data['getMainMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu1'));
        $data['getSecondMenu'] = $this->autoloadModel->getMenu($this->config->item('headerMenu2'));
        $data['getFooterMenu1'] = $this->autoloadModel->getMenu($this->config->item('footerMenu1'));
        $data['getFooterMenu2'] = $this->autoloadModel->getMenu($this->config->item('footerMenu2'));
        $data['getFooterMenu3'] = $this->autoloadModel->getMenu($this->config->item('footerMenu3'));
        $content = $this->load->view($this->session->theme.'/search', $data, true);
        $this->load->model(array('searchModel'));
    }

    public function index()
    {
        $postSearch = $this->input->get('q', true);
        $postPageVideo = $this->input->get('pv', true);
        $postPageUser = $this->input->get('pu', true);
        $postPagePost = $this->input->get('pp', true);
        if(!empty($postSearch)) {
            $data = $this->searchModel->search($postSearch, $postPageVideo, $postPagePost, $postPageUser);
            $this->load->library('pagination');
            $data['paginationVideos'] = $this->pagination($postSearch, $data['nbVideos'], 'pg');
            $data['paginationPosts'] = $this->pagination($postSearch, $data['nbPosts'], 'pp');
            $data['paginationUsers'] = $this->pagination($postSearch, $data['nbUsers'], 'pu');
            $data['searchResult'] = $postSearch;
        } else {
            $data['nbVideos'] = 0;
            $data['nbPosts'] = 0;
            $data['nbUsers'] = 0;
        }
        $content = $this->load->view($this->session->theme.'/search', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    // Update search via Ajax
    public function pagination($postSearch, $total_rows, $query_string_segment)
    {
        $config["base_url"] = site_url('search?q='.$postSearch.'');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = $query_string_segment;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
