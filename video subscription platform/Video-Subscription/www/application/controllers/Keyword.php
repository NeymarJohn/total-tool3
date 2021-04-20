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

class Keyword extends CI_Controller
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
        $this->load->model(array('keywordModel'));
    }

    public function index($getUrl = '', $getOrder = '', $getPag = '')
    {
        // Get videos of this keyword
        $data = $this->keywordModel->getBlocsVideo(urldecode($getUrl), $getOrder, $getPag);
        $data['title'] = $this->lang->line('Keyword').' - '.$this->config->item('sitename');
        // Get search result
        if(!empty($this->input->get('q'))) {
            $data = array_merge($data, $this->keywordModel->search($this->input->get('q', true), $data['id_keyword'], $getPag));
        }
        // Get pagination
        $this->load->library('pagination');
        $segment = $this->uri->segment(3, 0);
        if($segment !== 'title' && $segment !== 'popular' && $segment !== 'rated' && $segment !== 'favorites') {
            $segment = '';
        }
        $config['reuse_query_string'] = TRUE;
        $config['suffix'] = '/';
        $config['base_url'] = site_url('keyword/'.$getUrl.'/'.$segment);
        $config['total_rows'] = $data['nbRows'];
        $config['per_page'] = $this->config->item('key_pag');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $content = $this->load->view($this->session->theme.'/keyword', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }
}
