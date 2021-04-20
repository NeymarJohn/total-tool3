<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

class EnvatoAPI
{
    private $api_url = 'http://marketplace.envato.com/api/edge/'; // Default URL
    private $api_set; // This will hold the chosen API set like "user-items-by-site"
    private $username; // The username of the author only needed to access the private sets
    private $api_key; // The api key of the author only needed to access the private setsw

    /**
    * set_api_url()
    *
    * Set the API URL
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function set_api_url($url)
    {
        $this->api_url = $url;
    }


    /**
    * get_api_url()
    *
    * Return the API URL
    *
    * @access   public
    * @return   string
    */
    public function get_api_url()
    {
        return $this->api_url;
    }

    /**
    * set_username()
    *
    * Set the Username
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function set_username($username)
    {
        $this->username = $username;
    }


    /**
    * set_api_key()
    *
    * Set the API key
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function set_api_key($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
    * set_api_set()
    *
    * Set the API set
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function set_api_set($api_set)
    {
        $this->api_set = $api_set;
    }


    /**
    * request()
    *
    * @access   public
    * @param    void
    * @return   array
    */
    public function request() {
        if(!empty($this->username) && !empty($this->api_key)) {
            // Build the private url
            $this->api_url .= $this->username . '/'.$this->api_key.'/'.$this->api_set . '.json'; // Sample: http://marketplace.envato.com/api/edge/JohnDoe/ahdio270410ayap20hkdooxaadht5s/popular:codecanyon.json
        } else {
            // Build the public url
            $this->api_url .=  $this->api_set . '.json'; // Sample: http://marketplace.envato.com/api/edge/popular:codecanyon.json
        }
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.66 Safari/537.36");
        $ch_data = curl_exec($ch);
        curl_close($ch);
        if(!empty($ch_data)) {
            return json_decode($ch_data, true);
        } else {
            return('We are unable to retrieve any information from the API.');
        }
    }

    public function checkConnection($items)
    {
        foreach ($items as $item) {
            foreach ($item['ids'] as $value) {
                $json = json_decode(file_get_contents('https://www.coffeetheme.com?edd_action=activate_license&item_id='.$value.'&license='.$item['number'].'&url='.site_url()));
                if ($json->success === true) {
                    $this->ci =& get_instance();
                    $this->ci->session->set_userdata($item['session'], 'default');
                    break;
                }
            }
        }
    }
}
