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

class User extends CI_Controller
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
        $this->load->model(array('userModel'));
    }

    public function index($getUrl = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('Profile of').': '.$data['username'].' - '.$this->config->item('sitename');
        // Comment form processing
        $postCom = $this->input->post('com_message', true);
        if(isset($this->session->id) && ($postCom) != '') {
            $this->userModel->addCom($data['id'], $postCom);
        }
        // Delete profile comment
        if($data['id'] === $this->session->userdata('id')) {
            if($this->input->get('del') && !$this->config->item('demo')){
                $data['msg'] = $this->userModel->deleteComProfile($this->input->get('del', true));
            }
        }
        // Get favorites
        $data = array_merge($data, $this->userModel->getFavsVideos($data['id'], 0, 16, TRUE));
        // Get playlist
        if ($data['playlist_profile'] !== 'no') {
            $data = array_merge($data, $this->userModel->getPlaylist($data['playlist_profile'], TRUE));
        }
        // Get profile comments
        $data['getComsProfile'] = $this->userModel->getComsProfile($data['id']);
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function favorites($getUrl = '', $getPag = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('Favorites of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Get favorites
        $data = array_merge($data, $this->userModel->getFavsVideos($data['id'], $getPag, $this->config->item('more_pag')));
        $this->load->library('pagination');
        $config["base_url"] = site_url('/user/favorites/'.$data['url'].'/');
        $config['total_rows'] = $data['nbRows'];
        $config['per_page'] = $this->config->item('more_pag');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function playlists($getUrl = '', $getPag = '')
    {
        if($this->session->userdata('id') && $this->input->post('playlistTitle')) {
            $data['msg'] = $this->userModel->createPlaylist($this->input->post('playlistTitle', true));
        }
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('Playlists of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Get playlists
        $data = array_merge($data, $this->userModel->getPlaylists($data['id'], $data['url']));
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function playlist($getUrl = '', $getId = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('All the favorites of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Delete playlist
        $postDelPlaylist = $this->input->get('del', true);
        if(isset($this->session->id) && (!empty($postDelPlaylist)) && !$this->config->item('demo')) {
            $this->userModel->delPlaylist($postDelPlaylist, $this->session->id, $data['url']);
        }
        // Get playlist
        $data = array_merge($data, $this->userModel->getPlaylist($getId));
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function notes($getUrl = '', $getPag = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('Notes of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Get user notes
        $data = array_merge($data, $this->userModel->getNotesVideos($data['id'], $getPag, $this->config->item('more_pag')));
        $this->load->library('pagination');
        $config["base_url"] = site_url('/user/notes/'.$data['url'].'/');
        $config['total_rows'] = $data['nbRows'];
        $config['per_page'] = $this->config->item('more_pag');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function comments($getUrl = '', $getPag = '')
    {
        // Deleting comment
        $postDelCom = $this->input->get('del', true);
        if(isset($this->session->id) && (!empty($postDelCom)) && !$this->config->item('demo')) {
            $this->userModel->delCom($postDelCom, $this->session->id);
        }
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('Comments of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Get user video comments
        $data = array_merge($data, $this->userModel->getComsVideos($data['id'], $getPag, $this->config->item('more_pag')));
        $this->load->library('pagination');
        $config["base_url"] = site_url('/user/comments/'.$data['url'].'/');
        $config['total_rows'] = $data['nbRows'];
        $config['per_page'] = $this->config->item('more_pag');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $content = $this->load->view($this->session->theme.'/user', $data, true);
        $this->load->view($this->session->theme.'/template', array('content' => $content));
    }

    public function settings($getUrl = '', $getPag = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('My Profile').' - '.$this->config->item('sitename');
        // Secure the page
        if($data['id'] !== $this->session->userdata('id')) {
            redirect('/user/'.$getUrl.'/');
        } else {
            // Processing the profile setting form
            $postUsername = $this->input->post('username', true);
            $postEmail = $this->input->post('email', true);
            $postEmail = filter_var($postEmail, FILTER_VALIDATE_EMAIL);
            $postLocation = $this->input->post('location', true);
            $postAbout = $this->input->post('about', true);
            $postFacebook = $this->input->post('facebook', true);
            $postTwitter = $this->input->post('twitter', true);
            $postGoogle = $this->input->post('google', true);
            $postLinkedin = $this->input->post('linkedin', true);
            $postAuthComs = $this->input->post('auth_coms', true);
            $postIdPlaylist = $this->input->post('id_playlist', true);
            // Verification of urls
            $postFacebook = checkUrl($postFacebook, 'www.facebook.com');
            $postTwitter = checkUrl($postTwitter, 'www.twitter.com');
            $postGoogle = checkUrl($postGoogle, 'plus.google.com');
            $postLinkedin = checkUrl($postLinkedin, 'www.linkedin.com');
            if($postUsername && !$this->config->item('demo') || $postUsername && $data['role'] === '0') {
                $newUsername = '';
                $data['msg'] = $this->userModel->updateProfile($data['email'], $data['passkey'], $postUsername, $postEmail, $postLocation, $postAbout, $postFacebook, $postTwitter, $postGoogle, $postLinkedin, $postAuthComs, $newUsername, $postIdPlaylist);
            }
            // Processing the form for sending the avatar image
            if(null !== $this->input->post('submit', true) && !$this->config->item('demo') || null !== $this->input->post('submit', true) && $data['role'] === '0') {
                $config['upload_path']   = './uploads/images/users/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = 1000;
                $config['max_width']     = 2048;
                $config['max_height']    = 1536;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('userImage')) {
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $data['msg'] = alert($this->lang->line('The file was successfully sent'));
                    $this->userModel->updateAvatarImage($data['id'], $this->upload->data('file_name'));
                    // Deleting the old image
                    if(!empty($data['image'])) {
                        $file = 'uploads/images/users/'.$data['image'];
                        if(is_readable($file)) {
                            unlink($file);
                        }
                    }
                }
            }
            // Processing the form for deleting the avatar image
            if(null !== $this->input->post('delete', true) && !$this->config->item('demo') || null !== $this->input->post('delete', true) && $data['role'] === '0') {
                if(!empty($data['image'])) {
                    $this->userModel->updateAvatarImage($data['id']);
                    $this->session->unset_userdata('name_image');
                    $file = 'uploads/images/users/'.$data['image'];
                    if(is_readable($file) && unlink($file)) {
                        $data['msg'] = alert($this->lang->line('The file has been deleted successfully'));
                    }
                } else {
                    $data['msg'] = alert($this->lang->line('No files to delete'), 'danger');
                }
            }
            // Processing the form for sending the profile image
            if(null !== $this->input->post('submitProfileImage') && !$this->config->item('demo') || null !== $this->input->post('submitProfileImage') && $data['role'] === '0') {
                $config['upload_path']   = './uploads/images/users/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = 1000;
                $config['max_width']     = 2048;
                $config['max_height']    = 1536;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('userProfileImage')) {
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $data['msg'] = alert($this->lang->line('The file was successfully sent'));
                    $this->userModel->updateProfileImage($data['id'], $this->upload->data('file_name'));
                    // Deleting the old image
                    if(!empty($data['profile_image'])) {
                        $file = 'uploads/images/users/'.$data['profile_image'];
                        if(is_readable($file)) {
                            unlink($file);
                        }
                    }
                }
            }
            // Processing the form for deleting the profile image
            if(null !== $this->input->post('deleteProfileImage', true) && !$this->config->item('demo') || null !== $this->input->post('deleteProfileImage', true) && $data['role'] === '0') {
                if(!empty($data['profile_image'])) {
                    $this->userModel->updateProfileImage($data['id']);
                    $this->session->unset_userdata('name_image');
                    $file = 'uploads/images/users/'.$data['profile_image'];
                    if(is_readable($file) && unlink($file)) {
                        $data['msg'] = alert($this->lang->line('The file has been deleted successfully'));
                    }
                } else {
                    $data['msg'] = alert($this->lang->line('No files to delete'), 'danger');
                }
            }
            // Get playlist list
            $data['getPlaylistsList'] = $this->userModel->getPlaylistsList($data['id']);
            if (!empty($newUsername) && ($newUsername !== $getUrl)) {
                // Redirect the user who change his username
                redirect('/user/settings/'.$newUsername.'/');
            } else {
                // Refresh user datas with new content
                $data = array_merge($data, $data = $this->userModel->getUserData($getUrl));
                $content = $this->load->view($this->session->theme.'/user', $data, true);
                $this->load->view($this->session->theme.'/template', array('content' => $content));
            }
        }
    }

    public function subscription($getUrl = '')
    {
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = 'Subscribe - '.$this->config->item('sitename');
        if($data['id'] !== $this->session->userdata('id')) {
            redirect('/user/'.$getUrl.'/');
        } else {
            if ($this->config->item('paymentMethod') === 'PayPalCheckout') {
                $this->session->unset_userdata('PayPalResult');
                $this->session->unset_userdata('shopping_cart');
                $postPaypalOffer = $this->input->get('paypal-offer');
                if ($postPaypalOffer) {
                    if (!$this->session->userdata('paypal_chekout')) {
                        $this->autoloadModel->setPaymentSession();
                    }
                    if ($this->session->userdata('paypal_chekout')) {
                        if ($postPaypalOffer === '1') {
                            $cart['items'][] = array(
                                'id' => '',
                                'name' => $this->config->item('paypal1Description'),
                                'qty' => '1',
                                'price' => $subtotal = $this->config->item('paypal1Price'),
                            );
                            $cart['badge'] = $this->config->item('paypal1Description');
                        } elseif ($postPaypalOffer === '2') {
                            $cart['items'][] = array(
                                'id' => '',
                                'name' => $this->config->item('paypal2Description'),
                                'qty' => '1',
                                'price' => $subtotal = $this->config->item('paypal2Price'),
                            );
                            $cart['badge'] = $this->config->item('paypal2Description');
                        } elseif ($postPaypalOffer === '3') {
                            $cart['items'][] = array(
                                'id' => '',
                                'name' => $this->config->item('paypal3Description'),
                                'qty' => '1',
                                'price' => $subtotal = $this->config->item('paypal3Price'),
                            );
                            $cart['badge'] = $this->config->item('paypal3Description');
                        }
                        $cart['shopping_cart'] = array(
                            'items' => $cart['items'],
                            'subtotal' => (int)$subtotal,
                            'shipping' => 0,
                            'handling' => 0,
                            'tax' => 0,
                        );
                        $cart['shopping_cart']['grand_total'] = number_format($cart['shopping_cart']['subtotal'] + $cart['shopping_cart']['shipping'] + $cart['shopping_cart']['handling'] + $cart['shopping_cart']['tax'], 2);
                        $cart['paypal_offer'] = $postPaypalOffer;
                        $cart['user_id'] = $data['id'];
                        $this->session->set_userdata('shopping_cart', $cart);
                        redirect('paypal/setexpresscheckout/', 'Location');
                    }
                }
                if ($this->input->get('p')) { // A payment has been succeful
                    $data['msg'] = alert($this->lang->line('Your subscription has been created'));
                }
            } elseif ($this->config->item('paymentMethod') === 'PayPalPro') {
                $this->session->unset_userdata('PayPalResult');
                $this->session->unset_userdata('shopping_cart');
                $postPaypalOffer = $this->input->post('paypal-offer');
                if ($postPaypalOffer) {
                    if (!$this->session->userdata('paypal_pro')) {
                        $this->autoloadModel->setPaymentSession();
                    }
                    if ($this->session->userdata('paypal_pro')) {
                        $cart['card_number'] = $this->input->post('card_number', true);
                        $cart['card_expiration'] = $this->input->post('card_expiration', true);
                        $cart['card_cvv'] = $this->input->post('card_cvv', true);
                        $paypalOffer = $this->input->post('paypal-offer', true);
                        if ($paypalOffer === 'paypalpro1') {
                            $cart['price'] = $this->config->item('paypal1Price');
                            $cart['badge'] = $this->config->item('paypal1Description');
                            $cart['type'] = $this->config->item('paypal1Type');
                            $cart['period'] = $this->config->item('paypal2Period');
                            $cart['billing_period'] = $this->config->item('paypal1BillingPeriod');
                        } elseif ($paypalOffer === 'paypalpro2') {
                            $cart['price'] = $this->config->item('paypal2Price');
                            $cart['badge'] = $this->config->item('paypal2Description');
                            $cart['type'] = $this->config->item('paypal2Type');
                            $cart['period'] = $this->config->item('paypal2Period');
                            $cart['billing_period'] = $this->config->item('paypal2BillingPeriod');
                        } elseif ($paypalOffer === 'paypalpro3') {
                            $cart['price'] = $this->config->item('paypal3Price');
                            $cart['badge'] = $this->config->item('paypal13Description');
                            $cart['type'] = $this->config->item('paypal3Type');
                            $cart['period'] = $this->config->item('paypal3Period');
                            $cart['billing_period'] = $this->config->item('paypal3BillingPeriod');
                        }
                        $cart['buyer_email'] = $data['email'];
                        $cart['user_id'] = $data['id'];
                        $this->session->set_userdata('shopping_cart', $cart);
                        if ($cart['type'] === '1') {
                            redirect('paypal/createrecurringpaymentsprofile/', 'Location');
                        } else {
                            redirect('paypal/dodirectpayment/', 'Location');
                        }
                    }
                }
                if ($this->input->get('p')) { // A payment has been succeful
                    $data['msg'] = alert($this->lang->line('Your subscription has been created'));
                }
            } else {
                $postStripeToken = $this->input->post('stripeToken', true);
                $postTypeSubscription = $this->input->post('typeSubscription', true);
                $postPaymentPeriod = $this->input->post('paymentPeriod', true);
                if($postStripeToken) {
                    $data['msg'] = $this->userModel->createSubscription($data['id'], $data['email'], $data['customer_id'], $postTypeSubscription, $postPaymentPeriod);
                    $this->userModel->updatePaymentStats($postTypeSubscription);
                }
            }
            if ($errorMsg = $this->session->userdata('errors')) {
                $data['msg'] = alert($errorMsg['Errors'][0]['L_LONGMESSAGE'], 'danger');
                $this->session->unset_userdata('errors');
            }
            // Viewing user's video comments
            $content = $this->load->view($this->session->theme.'/user', $data, true);
            $this->load->view($this->session->theme.'/template', array('content' => $content));
        }
    }

    public function pesapalIframe()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->load->library('pesapal');
        $data = $this->userModel->getUserData($this->session->url);
        $reference = $this->userModel->getPesapalReference();
        $data['msg'] = $this->pesapal->pesapalPayment($data, $this->session->url, $reference);
        return $this->load->view('responseAjax', $data);
    }

    public function pesapalSubscription()
    {
        if (!$this->session->userdata('id')) {
            exit('No direct script access allowed');
        }
        if ((int)$this->input->get('pesapal_merchant_reference') === (int)$this->userModel->getPesapalReference()) {
            $postTransactionId = $this->input->get('pesapal_transaction_tracking_id', true);
            $postType = $this->input->get('type', true);
            if ($postTransactionId && $postType) {
                $createPesapalSubscription = $this->userModel->createPesapalSubscription($this->session->userdata('id'), $postType, $postTransactionId, $postTransactionId);
                if($createPesapalSubscription){
                    $this->userModel->updatePaymentStats(0);
                    redirect('/user/' . $this->session->url . '/');
                } 
            }
        }
    }

    public function history($getUrl = '', $getPag = '')
    {
        // Get user profile
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['title'] = $this->lang->line('History of').' '.$data['username'].' - '.$this->config->item('sitename');
        // Secure the page
        if($data['id'] !== $this->session->userdata('id')) {
            redirect('/user/'.$getUrl.'/');
        } else {
            // Get user history
            $data = array_merge($data, $this->userModel->getHistory($data['id'], $data['url'], $getPag, $this->config->item('more_pag')));
            $this->load->library('pagination');
            $config["base_url"] = site_url('/user/history/'.$data['url'].'/');
            $config['total_rows'] = $data['nbRows'];
            $config['per_page'] = $this->config->item('more_pag');
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $content = $this->load->view($this->session->theme.'/user', $data, true);
            $this->load->view($this->session->theme.'/template', array('content' => $content));
        }
    }

    public function unsubscribe($getUrl = '', $getIdSubscription = '') {
        $data = $this->userModel->getUserData(urldecode($getUrl));
        $data['msg'] = $this->userModel->unsubscribeInvoice($data['id'], $getIdSubscription);
        $this->load->view('responseAjax', $data);
    }
}
