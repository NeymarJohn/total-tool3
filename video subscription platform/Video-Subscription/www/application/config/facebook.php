<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'login/';
$config['facebook_logout_redirect_url'] = 'login/logout';
$config['facebook_permissions']         = array('email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;
