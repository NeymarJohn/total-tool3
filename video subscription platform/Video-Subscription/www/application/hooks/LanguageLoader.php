<?php
class LanguageLoader
{
    public function initialize()
    {
        $ci =& get_instance();
        $ci->load->helper('language');
        $site_lang = $ci->session->userdata('site_lang');
        if ($site_lang) {
            $ci->lang->load('front', $ci->session->userdata('site_lang'));
        } else {
            $ci->lang->load('front', 'english');
        }
    }
}
