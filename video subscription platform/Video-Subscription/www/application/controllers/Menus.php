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

class Menus extends CI_Controller
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
        $this->load->model(array('menusModel'));
    }

    public function index() {
        $data['title'] = $this->lang->line('Menus');
        // Create new menu
        if ($this->input->post('title', true) && !$this->config->item('demo')) {
            $insertId = $this->menusModel->addMenu($this->input->post('title', true));
            redirect(site_url('dashboard/menus/edit/'.$insertId.'/'));
        }
        // Edit the menu
        if ($this->input->post('menu', true) && !$this->config->item('demo')) {
            redirect(site_url('dashboard/menus/edit/'.$this->input->post('menu', true).'/'));
        }
        $data['idMenu'] = $startMenuId = $this->config->item('headerMenu1');
        $data['getMenus'] = $this->menusModel->getMenus($startMenuId);
        $getMenu = $this->menusModel->getMenu($startMenuId);
        $data['getTileMenu'] = $getMenu['title'];
        $data['getMenu'] = $getMenu['getMenu'];
        $data['getDefaultPages'] = $this->menusModel->getDefaultPages($startMenuId);
        $data['getCategories'] = $this->menusModel->getCategories($startMenuId);
        $data['getPages'] = $this->menusModel->getPages($startMenuId);
        $data['getPostCategories'] = $this->menusModel->getPostCategories($startMenuId);
        $content = $this->load->view('dashboard/menus', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idMenu = '')
    {
        $data['title'] = $this->lang->line('Menus');
        // Create new menu
        if ($this->input->post('title', true) && !$this->config->item('demo')) {
            $insertId = $this->menusModel->addMenu($this->input->post('title', true));
            redirect(site_url('dashboard/menus/edit/'.$insertId.'/'));
        }
        // Edit the menu
        if ($this->input->post('menu', true) && !$this->config->item('demo')) {
            redirect(site_url('dashboard/menus/edit/'.$this->input->post('menu', true).'/'));
        }
        $data['idMenu'] = $idMenu;
        $data['getMenus'] = $this->menusModel->getMenus($idMenu);
        $getMenu = $this->menusModel->getMenu($idMenu);
        $data['getTileMenu'] = $getMenu['title'];
        $data['getMenu'] = $getMenu['getMenu'];
        $data['getDefaultPages'] = $this->menusModel->getDefaultPages($idMenu);
        $data['getCategories'] = $this->menusModel->getCategories($idMenu);
        $data['getPages'] = $this->menusModel->getPages($idMenu);
        $data['getPostCategories'] = $this->menusModel->getPostCategories($idMenu);
        $content = $this->load->view('dashboard/menus', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function delete($idMenu = '') {
        if(!$this->config->item('demo')) {
            $this->menusModel->deleteMenu($idMenu);
            redirect(site_url('dashboard/menus/'));
        } else {
            redirect(site_url('dashboard/menus/'));
        }
    }

    public function update()
    {
        if(!$this->config->item('demo')) {
            $json = json_decode($this->input->post('list', true));
            foreach ($json as $object) {
                if (!is_null($object->id)) {
                    if($object->children) {
                        foreach ($object->children as $subObject) {
                            $array[] = $subObject->id;
                        }
                        $array[] = $object->id;
                        $array2[] = implode('|', $array);
                        unset($array);
                    } else {
                        $array2[] = $object->id;
                    }
                }
            }
            $serialized = implode(',', $array2);
            if(!is_null($this->input->post('menu', true))) {
                $this->menusModel->updateMenu($serialized, $this->input->post('menu', true));
            }
        }
    }
}
