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

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->autoloadModel->setThemeSession();
        $this->input->cookie('remember_me', true);
        $this->load->model(array('loginModel'));
        if (!empty($this->config->item('facebook_app_id') && !empty($this->config->item('facebook_app_secret')))) {
            $this->load->library('facebook');
        }
    }

    public function index()
    {
        $data = $this->google();
        if (!empty($this->config->item('facebook_app_id') && !empty($this->config->item('facebook_app_secret')))) {
            $data = array_merge($data, $this->facebook());
        }
        if (!empty($this->config->item('consumerKey') && !empty($this->config->item('consumerSecret')))) {
            $data = array_merge($data, $this->twitter());
        }
        $email = $this->input->post('email', true);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $password = $this->input->post('password', true);
        $rememberme = $this->input->post('rememberme', true);
        if(isset($email) && isset($password)) {
            $data['msg'] = $this->loginModel->checkConnect($email, $password, $rememberme);
        }
        $send = $this->input->get('send', true);
        $key = $this->input->get('key', true);
        if(isset($send) && isset($key)) {
            $data['msg'] = $this->loginModel->sendConfirmation($send, $key);
        }
        $data['title'] = $this->lang->line('Login').' - '.$this->config->item('sitename');
        $data['rememberMe'] = $this->input->cookie('remember_me', true);
        $content = $this->load->view($this->session->theme.'/login', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function google()
    {
        // Include the google api php libraries
        require_once('./application/libraries/google-api-php-client/Google_Client.php');
        require_once('./application/libraries/google-api-php-client/contrib/Google_Oauth2Service.php');
        // Google Project API Credentials
        $clientId = $this->config->item('clientId');
        $clientSecret = $this->config->item('clientSecret');
        $redirectUrl = base_url() . 'login/';
        // Google Client Configuration
        $gClient = new Google_Client();
        $gClient->setApplicationName('Client Login');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);
        $google_oauthV2 = new Google_Oauth2Service($gClient);
        if (!isset($_GET['state'])) {
            if (isset($_REQUEST['code'])) {
                $gClient->authenticate();
                $this->session->set_userdata('googleToken', $gClient->getAccessToken());
                redirect($redirectUrl);
            }
            $token = $this->session->userdata('googleToken');
            if (!empty($token)) {
                $gClient->setAccessToken($token);
            }
        }
        if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'google';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['first_name'] = $userProfile['given_name'];
            $userData['last_name'] = $userProfile['family_name'];
            $userData['email'] = $userProfile['email'];
            $userData['gender'] = $userProfile['gender'];
            $userData['locale'] = $userProfile['locale'];
            $userData['profile_url'] = $userProfile['link'];
            $userData['picture_url'] = $userProfile['picture'];
            // Get location
            $result = $this->loginModel->getLocation($this->input->ip_address());
            $userData['countryCode'] = ($result !== NULL) ? $result['countryCode'] : 'unknown';
            $userData['countryName'] = ($result !== NULL) ? $result['countryName'] : 'unknown';
            $userData['city'] = ($result !== NULL) ? $result['city'] : 'unknown';
            // Insert or update user data
            $userID = $this->loginModel->checkSocialLogin($userData);
            if(!empty($userID)){
                $this->loginModel->socialConnect($userData, $userID);
            } else {
               $data['userData'] = array();
            }
        } else {
            $data['gAuthUrl'] = $gClient->createAuthUrl();
        }
        return $data;
    }

    public function facebook()
    {
        $userData = array();
		// Check if user is logged in
		if($this->facebook->is_authenticated()){
			// Get user facebook profile details
			$userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture');
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['first_name'] = $userProfile['first_name'];
            $userData['last_name'] = $userProfile['last_name'];
            $userData['email'] = $userProfile['email'];
            $userData['gender'] = $userProfile['gender'];
            $userData['locale'] = $userProfile['locale'];
            $userData['profile_url'] = 'https://www.facebook.com/'.$userProfile['id'];
            $userData['picture_url'] = $userProfile['picture']['data']['url'];
            // Get location
            $result = $this->loginModel->getLocation($this->input->ip_address());
            $userData['countryCode'] = ($result !== NULL) ? $result['countryCode'] : 'unknown';
            $userData['countryName'] = ($result !== NULL) ? $result['countryName'] : 'unknown';
            $userData['city'] = ($result !== NULL) ? $result['city'] : 'unknown';
            // Insert or update user data
            $userID = $this->loginModel->checkSocialLogin($userData);
			// Check user data insert or update status
            if(!empty($userID)){
                $this->loginModel->socialConnect($userData, $userID);
            } else {
               $data['userData'] = array();
            }
			// Get logout URL
			$data['logoutUrl'] = $this->facebook->logout_url();
		}else{
            $fbuser = '';
			// Get login URL
            $data['fAuthUrl'] =  $this->facebook->login_url();
        }
		return $data;
    }

    public function twitter(){
		$userData = array();
		//Include the twitter oauth php libraries
        require_once('./application/libraries/twitter-oauth-php-codexworld/twitteroauth.php');
		//Twitter API Configuration
		$consumerKey = $this->config->item('consumerKey');
		$consumerSecret = $this->config->item('consumerSecret');
		$oauthCallback = base_url().'login/';
		//Get existing token and token secret from session
		$sessToken = $this->session->userdata('token');
		$sessTokenSecret = $this->session->userdata('token_secret');
		//Get status and user info from session
		$sessStatus = $this->session->userdata('status');
		$sessUserData = $this->session->userdata('userData');
		if(isset($sessStatus) && $sessStatus == 'verified'){
			//Connect and get latest tweets
			$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessUserData['accessToken']['oauth_token'], $sessUserData['accessToken']['oauth_token_secret']);
			$data['tweets'] = $connection->get('statuses/user_timeline', array('screen_name' => $sessUserData['username'], 'count' => 5));
			//User info from session
			$userData = $sessUserData;
		}elseif(isset($_REQUEST['oauth_token']) && $sessToken == $_REQUEST['oauth_token']){
			//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
			$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessToken, $sessTokenSecret); //print_r($connection);die;
			$accessToken = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			if($connection->http_code == '200'){
				//Get user profile info
				$userInfo = $connection->get('account/verify_credentials');
				//Preparing data for database insertion
				$name = explode(" ",$userInfo->name);
				$first_name = isset($name[0])?$name[0]:'';
				$last_name = isset($name[1])?$name[1]:'';
				$userData = array(
					'oauth_provider' => 'twitter',
					'oauth_uid' => $userInfo->id,
					'username' => $userInfo->screen_name,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'locale' => $userInfo->lang,
					'profile_url' => 'https://twitter.com/'.$userInfo->screen_name,
					'picture_url' => $userInfo->profile_image_url
				);
                // Get location
                $result = $this->loginModel->getLocation($this->input->ip_address());
                $userData['countryCode'] = ($result !== NULL) ? $result['countryCode'] : 'unknown';
                $userData['countryName'] = ($result !== NULL) ? $result['countryName'] : 'unknown';
                $userData['city'] = ($result !== NULL) ? $result['city'] : 'unknown';
                // Insert or update user data
                $userID = $this->loginModel->checkTwitterLogin($userData);
    			// Check user data insert or update status
                if(!empty($userID)){
                    $this->loginModel->socialConnect($userData, $userID);
                } else {
                   $data['userData'] = array();
                }
				//Store status and user profile info into session
				$userData['accessToken'] = $accessToken;
				$this->session->set_userdata('status','verified');
				$this->session->set_userdata('userData',$userData);
				//Get latest tweets
				$data['tweets'] = $connection->get('statuses/user_timeline', array('screen_name' => $userInfo->screen_name, 'count' => 5));
			}else{
				$data['error_msg'] = $this->lang->line('Some problem occurred, please try again later!');
			}
		}else{
			//unset token and token secret from session
			$this->session->unset_userdata('token');
			$this->session->unset_userdata('token_secret');
			//Fresh authentication
			$connection = new TwitterOAuth($consumerKey, $consumerSecret);
			$requestToken = $connection->getRequestToken($oauthCallback);
			//Received token info from twitter
			$this->session->set_userdata('token',$requestToken['oauth_token']);
			$this->session->set_userdata('token_secret',$requestToken['oauth_token_secret']);
			//Any value other than 200 is failure, so continue only if http code is 200
			if($connection->http_code == '200'){
				//redirect user to twitter
				$twitterUrl = $connection->getAuthorizeURL($requestToken['oauth_token']);
				$data['tAuthUrl'] = $twitterUrl;
			}else{
				$data['tAuthUrl'] = base_url().'login';
				$data['error_msg'] = $this->lang->line('Error connecting to twitter! try again later!');
			}
        }
        return $data;
    }

    public function register()
    {
        $data['title'] = $this->lang->line('Registration').' - '.$this->config->item('sitename');
        $email = $this->input->post('email', true);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $conditions = $this->input->post('conditions', true);
        if(isset($email) && isset($username) && isset($password)) {
            if (strlen($username) >= 6) {
                if (strlen($password) >= 6) {
                    if(isset($conditions)) {
                        $result = $this->loginModel->getLocation($this->input->ip_address());
                        $countryCode = ($result !== FALSE) ? $result['countryCode'] : 'unknown';
                        $countryName = ($result !== FALSE) ? $result['countryName'] : 'unknown';
                        $city = ($result !== FALSE) ? $result['city'] : 'unknown';
                        $addUser = $this->loginModel->addUser($email, $username, $password, random(20), $countryCode, $countryName, $city);
                        if ($addUser['isCreated']) {
                            $this->loginModel->addLocationStats($countryCode, $countryName, date("Y-m-d"));
                            $this->loginModel->addNotification();
                        }
                        $data['msg'] = $addUser['msg'];
                    } else {
                        $data['msg'] = alert($this->lang->line('You must accept the terms of use'), 'danger');
                    }
                } else {
                    $data['msg'] = alert($this->lang->line('Password must be at least 6 characters long'), 'danger');
                }
            } else {
                $data['msg'] = alert($this->lang->line('User must be at least 6 characters long'), 'danger');
            }
        }
        $content = $this->load->view($this->session->theme.'/register', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function recovery()
    {
        $data['title'] = $this->lang->line('Login').' - '.$this->config->item('sitename');
        $data['msg'] = 'Password recovery - '.$this->config->item('sitename');
        $email = $this->input->post('email', true);
        if(isset($email)) {
            $data['msg'] = $this->loginModel->checkRecovery($email);
        } else {
            $data['msg'] = $this->lang->line('msgEnterYourEmail');
        }
        $content = $this->load->view($this->session->theme.'/recovery', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function changepass()
    {
        $email = $this->input->get('mail', true);
        $passkey = $this->input->get('key', true);
        $password = $this->input->post('password', true);
        $confirm = $this->input->post('confirm', true);
        if($password === $confirm) {
            if(isset($email) && isset($passkey) && isset($password)) {
                $data['msg'] = $this->loginModel->changePassword($email, $passkey, $password);
            }
        } else {
            $data['msg'] = alert($this->lang->line('Please enter the same password for both fields'), 'danger');
        }
        $data['email'] = $email;
        $data['passkey'] = $passkey;
        $content = $this->load->view($this->session->theme.'/newpassword', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function confirm()
    {
        $email = $this->input->get('mail', true);
        $passkey = $this->input->get('key', true);
        if(isset($email) && isset($passkey)) {
            $data['msg'] = $this->loginModel->checkPasskey($email, $passkey);
        } else {
            $data['msg'] = '';
        }
        $content = $this->load->view($this->session->theme.'/login', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function changemail()
    {
        $email = $this->input->get('mail', true);
        $passkey = $this->input->get('key', true);
        $oldmail = $this->input->get('oldmail', true);
        if(isset($email) && isset($passkey) && isset($oldmail)) {
            $data['msg'] = $this->loginModel->changeMail($email, $passkey, $oldmail);
        } else {
            $data['msg'] = '';
        }
        $content = $this->load->view($this->session->theme.'/login', $data, true);
        $this->load->view('landing', array('content' => $content));
    }

    public function logout()
    {
        $this->session->unset_userdata('token');
        $this->session->unset_userdata('userData');
        $this->session->sess_destroy();
        if (!empty($this->config->item('facebook_app_id') && !empty($this->config->item('facebook_app_secret')))) {
            $this->facebook->destroy_session();
        }
        $this->load->library('user_agent');
        redirect($this->agent->referrer(), 'refresh');
    }
}
