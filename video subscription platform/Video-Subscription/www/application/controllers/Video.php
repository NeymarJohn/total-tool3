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

class Video extends CI_Controller
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
        $this->load->model(array('videoModel'));
    }

    public function index($getUrl = '', $getPag = '')
    {
        // Get video data
        $data = $this->videoModel->getVideo(urldecode($getUrl));
        $data['title'] = $data['title_video'].' - '.$data['category'].' - '.$this->config->item('description');
        $data['ogDescription'] = $data['description'];
        $data['ogImage'] = $data['image'];
        // Get users who have the video in favorite
        $data['getUsersFav'] = $this->videoModel->getUsersFav($data['id']);
        // Report video (not working notification)
        if($this->input->get('report')) {
            $data['msg'] = $this->videoModel->reportVideo($data['id']);
        }
        // Create new playlist
        if($this->session->userdata('id') && $this->input->post('playlistTitle')) {
            $data['msg'] = $this->videoModel->createPlaylist($this->input->post('playlistTitle', true));
        }
        // Add / remove site to favorites
        $postFav = $this->input->get('fav', true);
        if($postFav != '' && isset($this->session->id)) {
            if($postFav === 'add') {
                $addFav = $this->videoModel->addFav($data['id']);
                if ($addFav) {
                    $this->videoModel->updateStats(3);
                }
            }
            if($postFav === 'del') {
                $this->videoModel->delFav($data['id']);
            }
        }
        // Get average score
        $data = array_merge($data, $this->videoModel->getNote($data['id']));
        // Get keywords
        $data['getKeywords'] = $this->videoModel->getKeywords($data['ids_keywords']);
        // Get user data (video as favorites)
        if(isset($this->session->id)) {
            $data['getFav'] = $this->videoModel->getFav($data['id']);
        } else {
            $data['getFav'] = 0;
        }
        // Playlist form processing
        if($this->session->id && $this->input->post('submitPlaylist')) {
            $this->videoModel->updatePlaylists($data['id'], $this->input->post('playlists', true));
        }
        // Get user playlists
        if(isset($this->session->id)) {
            $data['getPlaylists'] = $this->videoModel->getPlaylists($data['id']);
        }
        // Comment form processing
        $postCom = $this->input->post('com_message', true);
        $postRelated = $this->input->post('related', true);
        if(isset($this->session->id) && ($postCom) != '') {
            $addCom = $this->videoModel->addCom($data['id'], $postCom, $postRelated);
            if($addCom) {
                $this->videoModel->updateStats(4);
                $this->videoModel->addNotification(5);
            }
        }
        // Get comments
        $data['getBestComs'] = $this->videoModel->getComs($data['id'], $getPag, true);
        $data = array_merge($data, $this->videoModel->getComs($data['id'], $getPag));
        $data['getPagination'] = $this->createPagination(site_url('video/'.$data['url'].'/'), $data['nbRows'], $this->config->item('coms_pag'));
        // Get episodes
        $data['getSaison'] = $this->videoModel->getSaison($data['id']);
        $data['nbSaison'] = $this->videoModel->getNbSaison($data['id']);
        if($data['nbSaison']) {
            $data['getEpisodes'] = '';
            $this->session->numVideo = 0;
            foreach ($data['nbSaison'] as $row) {
                $data['getEpisodes'] .= $this->videoModel->getEpisodes($data['id'], $row->season);
            }
            $data['getVideoPlaylist'] = $this->videoModel->getVideoPlaylist($data, TRUE);
        } else {
            $data['getVideoPlaylist'] = $this->videoModel->getVideoPlaylist($data);
        }
        $content = $this->load->view($this->session->theme.'/video', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function createPagination($baseUrl, $totalRows, $perPage)
    {
        $this->load->library('pagination');
        $config["base_url"] = $baseUrl;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    // Update video statistics (nb of views) via Ajax
    public function updateVideoStat($idVideo)
    {
        $this->videoModel->updateVideoStat($idVideo);
    }

    // Update note via Ajax
    public function updateNote($idVideo, $score)
    {
        if(isset($this->session->id)) {
            $this->videoModel->updateNote($idVideo, $score);
            $this->videoModel->updateStats(2);
        }
    }

    // Update likes in comments via Ajax
    public function likesComs($idCom, $likeType)
    {
        if(isset($this->session->id)) {
            $this->videoModel->likesComs($idCom, $likeType);
        }
    }
}
