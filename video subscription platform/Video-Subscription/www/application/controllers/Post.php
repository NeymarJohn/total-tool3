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

class Post extends CI_Controller
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
        $content = $this->load->view($this->session->theme.'/template', $data, true);
        $this->load->model(array('postModel'));
    }

    public function index($getUrl = '', $getPag = '')
    {
        // Get post data
        $data = $this->postModel->getPost(urldecode($getUrl));
        $data['title'] = $data['title_post'].' - '.$data['category'].' - '.$this->config->item('description');
        $data['ogDescription'] = $data['content'];
        $data['ogImage'] = $data['image'];
        $content = $this->load->view($this->session->theme.'/template', $data, true);
        // Comment form processing
        $postCom = $this->input->post('com_message', true);
        $postRelated = $this->input->post('related', true);
        if(isset($this->session->id) && ($postCom) != '') {
            $this->postModel->addCom($data['id'], $postCom, $postRelated);
            $this->postModel->addNotification(5);
        }
        // Get last posts (widget)
        $data['getLastPosts'] = $this->postModel->getLastPosts();
        // Get comments
        $data['getBestComs'] = $this->postModel->getComs($data['id'], $getPag, true);
        $data = array_merge($data, $this->postModel->getComs($data['id'], $getPag));
        $data['getPagination'] = $this->createPagination(site_url('post/'.$data['url'].'/'), $data['nbRows'], $this->config->item('coms_pag'));
        // Get keywords
        $data['getKeywords'] = $this->postModel->getKeywords($data['ids_keywords']);
        $content = $this->load->view($this->session->theme.'/post', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function allPosts($getPag = '')
    {
        $data = $this->postModel->getAllPosts($getPag);
        $data['title'] = $this->lang->line('All posts').' - '.$this->config->item('sitename');
        $data['getPagination'] = $this->createPagination(site_url('posts/'), $data['nbRows'], $this->config->item('blog_pag'));
        // Get last posts (widget)
        $data['getLastPosts'] = $this->postModel->getLastPosts();
        $content = $this->load->view($this->session->theme.'/post_category', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function category($getUrl = '', $getPag = '')
    {
        $data = $this->postModel->getCategoryPosts(urldecode($getUrl), $getPag);
        $data['title'] = $data['cat_title'].' '.$this->lang->line('Videos').' - '.$this->config->item('sitename');
        $data['getPagination'] = $this->createPagination(site_url('post/category/'.$data['cat_url'].'/'), $data['nbRows'], $this->config->item('blog_pag'));
        // Get last posts (widget)
        $data['getLastPosts'] = $this->postModel->getLastPosts();
        // TODO: creer fonction pour retourner le nombre de commentaires pour chaque post
        $content = $this->load->view($this->session->theme.'/post_category', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function keyword($getUrl = '', $getPag = '')
    {
        $data = $this->postModel->getKeywordPosts(urldecode($getUrl), $getPag);
        $data['title'] = $data['key_title'].' '.$this->lang->line('Videos').' - '.$this->config->item('sitename');
        $data['getPagination'] = $this->createPagination(site_url('post/keyword/'.$data['key_url'].'/'), $data['nbRows'], $this->config->item('blog_pag'));
        // Get last posts (widget)
        $data['getLastPosts'] = $this->postModel->getLastPosts();
        $content = $this->load->view($this->session->theme.'/post_keyword', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function createPagination($baseUrl, $totalRows, $perPage)
    {
        $config['suffix'] = '/';
        $this->load->library('pagination');
        $config["base_url"] = $baseUrl;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    // Ajax call
    public function likesComs($idCom, $likeType)
    {
        if(isset($this->session->id)) {
            $this->postModel->likesComs($idCom, $likeType);
        }
    }
}
