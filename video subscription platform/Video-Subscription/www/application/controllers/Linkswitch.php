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

class Linkswitch extends CI_Controller
{
    public function switchLang($language = "")
    {
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        redirect(base_url());
    }
    
    public function switchTheme($theme = "")
    {
        $theme = ($theme != "") ? $theme : "default";
        $this->session->set_userdata('theme', $theme);
        $this->load->library('user_agent');
        redirect($this->agent->referrer());
    }

    public function switchPanel()
    {
        $this->session->set_userdata('demo_panel', true);
        $this->load->library('user_agent');
        redirect($this->agent->referrer());
    }
}
