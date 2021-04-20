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

class Settings extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!isset($this->session->admin)) {
            redirect('/login/');
        }
        $data = $this->autoloadModel->getNotifications();
        $content = $this->load->view('dashboard/template', $data, true);
        $this->load->model(array('settingsModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('General settings');
        $postTerms = $this->input->post('termsOfUse', true);
        $this->load->helper('file');
        if(array_key_exists('sitename', $_POST) && !$this->config->item('demo')) {
            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["sitename"] = \''.convertTexte($_POST['sitename']).'\';'."\n";
            $file .= '$config["logo"] = \''.convertTexte($_POST['logo']).'\';'."\n";
            $file .= '$config["theme"] = \''.$_POST['theme'].'\';'."\n";
            $file .= '$config["emailsite"] = "'.$_POST['emailsite'].'";'."\n";
            $file .= '$config["freeWebsite"] = '.($_POST['free-website']?'TRUE':'FALSE').';'."\n";
            $file .= '$config["comments_moderation"] = '.($_POST['comments-moderation']?'TRUE':'FALSE').';'."\n";
            $file .= '$config["confirmation_inscription"] = '.($_POST['confirmation-inscription']?'TRUE':'FALSE').';'."\n";
            $file .= '$config["maintenance"] = '.($_POST['maintenance']?'TRUE':'FALSE').';'."\n";
            $file .= '$config["maintenance_message"] = "'.convertTexte($_POST['maintenance_message']).'";'."\n";
            $file .= '$config["facebook"] = "'.$_POST['facebook'].'";'."\n";
            $file .= '$config["twitter"] = "'.$_POST['twitter'].'";'."\n";
            $file .= '$config["google"] = "'.$_POST['google'].'";'."\n";
            $file .= '$config["terms"] = "'.$postTerms.'";'."\n";
            $file .= '$config["facebookPageName"] = "'.$_POST['facebookPageName'].'";'."\n";
            $file .= '$config["facebookPageLink"] = "'.$_POST['facebookPageLink'].'";'."\n";
            $file .= '$config["clientId"] = "'.$_POST['clientId'].'";'."\n";
            $file .= '$config["clientSecret"] = "'.$_POST['clientSecret'].'";'."\n";
            $file .= '$config["facebook_app_id"] = "'.$_POST['facebook_app_id'].'";'."\n";
            $file .= '$config["facebook_app_secret"] = "'.$_POST['facebook_app_secret'].'";'."\n";
            $file .= '$config["consumerKey"] = "'.$_POST['consumerKey'].'";'."\n";
            $file .= '$config["consumerSecret"] = "'.$_POST['consumerSecret'].'";'."\n";
            $file .= '$config["mailchimpApi"] = \''.$_POST["mailchimpApi"].'\';'."\n";
            $file .= '$config["mailchimpList"] = \''.$_POST["mailchimpList"].'\';'."\n";
            $file .= '$config["amazonApiKey"] = \''.$_POST["amazonApiKey"].'\';'."\n";
            $file .= '$config["amazonSecretKey"] = \''.$_POST["amazonSecretKey"].'\';'."\n";
            $file .= '$config["amazonRegion"] = \''.$_POST["amazonRegion"].'\';'."\n";
            $file .= '$config["amazonBucket"] = \''.$_POST["amazonBucket"].'\';'."\n";
            $file .= '$config["amazonCloudFront"] = \''.$_POST["amazonCloudFront"].'\';'."\n";
            $file .= '$config["amazonBrowserUpload"] = \''.$_POST["amazonBrowserUpload"].'\';'."\n";
            $file .= '$config["hidePromo"] = '.(isset($_POST['hidePromo'])?'TRUE':'FALSE').';'."\n";
            $file .= '?'.'>';
            if(!write_file('./application/config/general_settings.php', $file)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            }
        }
        if(isset($_POST['customCss']) && !$this->config->item('demo')) {
            if(!write_file('./assets/css/custom.css', $_POST['customCss'])) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            }
        }
        if(isset($_POST['customJs']) && !$this->config->item('demo')) {
            if(!write_file('./assets/js/custom.js', $_POST['customJs'])) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                redirect(current_url().'/', 'location');
            }
        }
        $data['getCustomCss'] = file_get_contents('assets/css/custom.css');
        $data['getCustomJs'] = file_get_contents('assets/js/custom.js');
        $data['getPages'] = $this->settingsModel->getPages();
        $content = $this->load->view('dashboard/general_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function theme()
    {
        $data['title'] = 'Theme Settings';
        $data['getHeaderMenu1'] = $this->settingsModel->getMenus($this->config->item('headerMenu1'));
        $data['getHeaderMenu2'] = $this->settingsModel->getMenus($this->config->item('headerMenu2'));
        $data['getMenu1'] = $this->settingsModel->getMenus($this->config->item('footerMenu1'));
        $data['getMenu2'] = $this->settingsModel->getMenus($this->config->item('footerMenu2'));
        $data['getMenu3'] = $this->settingsModel->getMenus($this->config->item('footerMenu3'));
        $data['getCategories1'] = $this->settingsModel->getCategories($this->config->item('homeCategory1'));
        $data['getCategories2'] = $this->settingsModel->getCategories($this->config->item('homeCategory2'));
        $data['getCategories3'] = $this->settingsModel->getCategories($this->config->item('homeCategory3'));
        $data['getCategories4'] = $this->settingsModel->getCategories($this->config->item('homeCategory4'));
        $this->load->helper('file');
        if(array_key_exists('nbSlider', $_POST) && !$this->config->item('demo')) {
            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["nbSlider"] = \''.convertTexte($_POST['nbSlider']).'\';'."\n";
            if (isset($_POST['title1'])) {
                $file .= '$config["title1"] = \''.convertTexte($_POST['title1']).'\';'."\n";
            }
            if (isset($_POST['paragraph1'])) {
                $file .= '$config["paragraph1"] = \''.convertTexte($_POST['paragraph1']).'\';'."\n";
            }
            if (isset($_POST['button1'])) {
                $file .= '$config["button1"] = \''.convertTexte($_POST['button1']).'\';'."\n";
            }
            if (isset($_POST['button1link'])) {
                $file .= '$config["button1link"] = \''.convertTexte($_POST['button1link']).'\';'."\n";
            }
            if (isset($_POST['image1'])) {
                $file .= '$config["image1"] = \''.convertTexte($_POST['image1']).'\';'."\n";
            }
            if (isset($_POST['title2'])) {
                $file .= '$config["title2"] = \''.convertTexte($_POST['title2']).'\';'."\n";
            }
            if (isset($_POST['paragraph2'])) {
                $file .= '$config["paragraph2"] = \''.convertTexte($_POST['paragraph2']).'\';'."\n";
            }
            if (isset($_POST['button2'])) {
                $file .= '$config["button2"] = \''.convertTexte($_POST['button2']).'\';'."\n";
            }
            if (isset($_POST['button2link'])) {
                $file .= '$config["button2link"] = \''.convertTexte($_POST['button2link']).'\';'."\n";
            }
            if (isset($_POST['image2'])) {
                $file .= '$config["image2"] = \''.convertTexte($_POST['image2']).'\';'."\n";
            }
            if (isset($_POST['title3'])) {
                $file .= '$config["title3"] = \''.convertTexte($_POST['title3']).'\';'."\n";
            }
            if (isset($_POST['paragraph3'])) {
                $file .= '$config["paragraph3"] = \''.convertTexte($_POST['paragraph3']).'\';'."\n";
            }
            if (isset($_POST['button3'])) {
                $file .= '$config["button3"] = \''.convertTexte($_POST['button3']).'\';'."\n";
            }
            if (isset($_POST['button3link'])) {
                $file .= '$config["button3link"] = \''.convertTexte($_POST['button3link']).'\';'."\n";
            }
            if (isset($_POST['image3'])) {
                $file .= '$config["image3"] = \''.convertTexte($_POST['image3']).'\';'."\n";
            }
            if (isset($_POST['title4'])) {
                $file .= '$config["title4"] = \''.convertTexte($_POST['title4']).'\';'."\n";
            }
            if (isset($_POST['paragraph4'])) {
                $file .= '$config["paragraph4"] = \''.convertTexte($_POST['paragraph4']).'\';'."\n";
            }
            if (isset($_POST['button4'])) {
                $file .= '$config["button4"] = \''.convertTexte($_POST['button4']).'\';'."\n";
            }
            if (isset($_POST['button4link'])) {
                $file .= '$config["button4link"] = \''.convertTexte($_POST['button4link']).'\';'."\n";
            }
            if (isset($_POST['image4'])) {
                $file .= '$config["image4"] = \''.convertTexte($_POST['image4']).'\';'."\n";
            }
            if (isset($_POST['title5'])) {
                $file .= '$config["title5"] = \''.convertTexte($_POST['title5']).'\';'."\n";
            }
            if (isset($_POST['paragraph5'])) {
                $file .= '$config["paragraph5"] = \''.convertTexte($_POST['paragraph5']).'\';'."\n";
            }
            if (isset($_POST['button5'])) {
                $file .= '$config["button5"] = \''.convertTexte($_POST['button5']).'\';'."\n";
            }
            if (isset($_POST['button5link'])) {
                $file .= '$config["button5link"] = \''.convertTexte($_POST['button5link']).'\';'."\n";
            }
            if (isset($_POST['image5'])) {
                $file .= '$config["image5"] = \''.convertTexte($_POST['image5']).'\';'."\n";
            }
            if (isset($_POST['title6'])) {
                $file .= '$config["title6"] = \''.convertTexte($_POST['title6']).'\';'."\n";
            }
            if (isset($_POST['paragraph6'])) {
                $file .= '$config["paragraph6"] = \''.convertTexte($_POST['paragraph6']).'\';'."\n";
            }
            if (isset($_POST['button6'])) {
                $file .= '$config["button6"] = \''.convertTexte($_POST['button6']).'\';'."\n";
            }
            if (isset($_POST['button6link'])) {
                $file .= '$config["button6link"] = \''.convertTexte($_POST['button6link']).'\';'."\n";
            }
            if (isset($_POST['image6'])) {
                $file .= '$config["image6"] = \''.convertTexte($_POST['image6']).'\';'."\n";
            }
            $file .= '$config["headerMenu1"] = \''.convertTexte($_POST['headerMenu1']).'\';'."\n";
            $file .= '$config["headerMenu2"] = \''.convertTexte($_POST['headerMenu2']).'\';'."\n";
            $file .= '$config["footerMenu1Title"] = \''.convertTexte($_POST['menu1Title']).'\';'."\n";
            $file .= '$config["footerMenu1"] = \''.convertTexte($_POST['menu1']).'\';'."\n";
            $file .= '$config["footerMenu2Title"] = \''.convertTexte($_POST['menu2Title']).'\';'."\n";
            $file .= '$config["footerMenu2"] = \''.convertTexte($_POST['menu2']).'\';'."\n";
            $file .= '$config["footerMenu3Title"] = \''.convertTexte($_POST['menu3Title']).'\';'."\n";
            $file .= '$config["footerMenu3"] = \''.convertTexte($_POST['menu3']).'\';'."\n";
            $file .= '$config["footer_message"] = \''.convertTexte($_POST['footer_message']).'\';'."\n";
            $file .= '$config["socialIconsWidget"] = \''.convertTexte($_POST['socialIconsWidget']).'\';'."\n";
            $file .= '$config["socialIconsFooter"] = \''.convertTexte($_POST['socialIconsFooter']).'\';'."\n";
            $file .= '$config["facebookLogin"] = \''.convertTexte($_POST['facebookLogin']).'\';'."\n";
            $file .= '$config["googleLogin"] = \''.convertTexte($_POST['googleLogin']).'\';'."\n";
            $file .= '$config["twitterLogin"] = \''.convertTexte($_POST['twitterLogin']).'\';'."\n";
            $file .= '$config["backgroundLogin"] = \''.convertTexte($_POST['backgroundLogin']).'\';'."\n";
            $file .= '$config["homeCategory1"] = \''.convertTexte($_POST['homeCategory1']).'\';'."\n";
            $file .= '$config["homeCategory2"] = \''.convertTexte($_POST['homeCategory2']).'\';'."\n";
            $file .= '$config["homeCategory3"] = \''.convertTexte($_POST['homeCategory3']).'\';'."\n";
            $file .= '$config["homeCategory4"] = \''.convertTexte($_POST['homeCategory4']).'\';'."\n";
            $file .= '$config["contactBox1Title"] = \''.convertTexte($_POST['contactBox1Title']).'\';'."\n";
            $file .= '$config["contactBox1"] = \''.convertTexte($_POST['contactBox1']).'\';'."\n";
            $file .= '$config["contactBox2Title"] = \''.convertTexte($_POST['contactBox2Title']).'\';'."\n";
            $file .= '$config["contactBox2"] = \''.convertTexte($_POST['contactBox2']).'\';'."\n";
            $file .= '$config["vplColor"] = \''.convertTexte($_POST['vpl-color']).'\';'."\n";
            $file .= '?'.'>';
            if(!write_file('./application/config/theme_settings.php', $file)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                redirect(current_url().'/', 'location');
            }
        }
        $content = $this->load->view('dashboard/theme_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function mail()
    {
        $data['title'] = $this->lang->line('Emails');
        $this->load->helper('file');
        if(array_key_exists('titleMailConfirmation', $_POST) && !$this->config->item('demo')) {
            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["titleMailWelcome"] = \''.convertTexte($_POST['titleMailWelcome']).'\';'."\n";
            $file .= '$config["mailWelcome"] = \''.convertTexte($_POST['mailWelcome']).'\';'."\n";
            $file .= '$config["titleMailPasswordChanged"] = \''.convertTexte($_POST['titleMailPasswordChanged']).'\';'."\n";
            $file .= '$config["mailPasswordChanged"] = \''.convertTexte($_POST['mailPasswordChanged']).'\';'."\n";
            $file .= '$config["titleMailConfirmation"] = \''.convertTexte($_POST['titleMailConfirmation']).'\';'."\n";
            $file .= '$config["mailConfirmation"] = \''.convertTexte($_POST['mailConfirmation']).'\';'."\n";
            $file .= '$config["buttonMailConfirmation"] = \''.convertTexte($_POST['buttonMailConfirmation']).'\';'."\n";
            $file .= '$config["titleMailRecovery"] = \''.convertTexte($_POST['titleMailRecovery']).'\';'."\n";
            $file .= '$config["mailRecovery"] = \''.convertTexte($_POST['mailRecovery']).'\';'."\n";
            $file .= '$config["buttonMailRecovery"] = \''.convertTexte($_POST['buttonMailRecovery']).'\';'."\n";
            $file .= '?'.'>';
            if(!write_file('./application/config/mail_settings.php', $file)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                redirect(current_url().'/', 'location');
            }
        }
        $content = $this->load->view('dashboard/mail_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function seo()
    {
        $data['title'] = $this->lang->line('SEO');
        $this->load->helper('file');
        if(array_key_exists('author', $_POST) && !$this->config->item('demo')) {
            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["author"] = \''.convertTexte($_POST['author']).'\';'."\n";
            $file .= '$config["keywords"] = \''.convertTexte($_POST['keywords']).'\';'."\n";
            $file .= '$config["description"] = \''.convertTexte($_POST['description']).'\';'."\n";
            $file .= '$config["home_pag"] = "'.$_POST['home_pag'].'";'."\n";
            $file .= '$config["cat_pag"] = "'.$_POST['cat_pag'].'";'."\n";
            $file .= '$config["key_pag"] = "'.$_POST['key_pag'].'";'."\n";
            $file .= '$config["blog_pag"] = "'.$_POST['blog_pag'].'";'."\n";
            $file .= '$config["coms_pag"] = "'.$_POST['coms_pag'].'";'."\n";
            $file .= '$config["more_pag"] = "'.$_POST['more_pag'].'";'."\n";
            $file .= '$config["google_analytics"] = \''.convertTexte($_POST['google_analytics']).'\';'."\n";
            $file .= '$config["cache_activation"] = '.$_POST['cache_activation'].';'."\n";
            $file .= '$config["cache_expire"] = "'.$_POST['cache_expire'].'";'."\n";
            $file .= '?'.'>';
            if(!write_file('./application/config/seo_settings.php', $file)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                redirect(current_url('').'/', 'location');
            }
        }
        // Creating the Sitemap
        if(isset($_POST["sitemap"]) && !$this->config->item('demo')) {
            $entete = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $corps = '';
            $corps .= '<url><loc>'.site_url().'</loc></url>';
            $corps .= '<url><loc>'.site_url('login/').'</loc></url>';
            $corps .= '<url><loc>'.site_url('login/register/').'</loc></url>';
            $corps .= '<url><loc>'.site_url('login/recovery/').'</loc></url>';
            $corps .= '<url><loc>'.site_url('members/').'</loc></url>';
            // List of pages
            $sql = "SELECT url FROM 2d_pages";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $corps .= '<url><loc>'.site_url('page/'.$row->url.'/').'</loc></url>';
            }
            // List of categories
            $sql = "SELECT url FROM 2d_categories";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $corps .= '<url><loc>'.site_url('category/'.$row->url.'/').'</loc></url>';
            }
            // List of videos
            $sql = "SELECT url FROM 2d_videos";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $corps .= '<url><loc>'.site_url('video/'.$row->url.'/').'</loc></url>';
            }
            // List of members
            $sql = "SELECT url FROM 2d_users";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row) {
                $corps .= '<url><loc>'.site_url('user/'.$row->url.'/').'</loc></url>';
            }
              $flux = $entete.$corps.'</urlset>';
            if(!write_file('./sitemap.xml', $flux)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                $data['msg'] = '<div class="alert alert-icon alert-success alert-dismissible fade in" role="alert">
        							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        								<span aria-hidden="TRUE">×</span>
        							</button>
        							<i class="mdi mdi-check-all"></i>
        							'.$this->lang->line('<strong>Congratulations !</strong> Your Sitemap has been successfully generated').'
        					  	</div>';
            }
        }
        $content = $this->load->view('dashboard/seo_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function advertisements()
    {
        $data['title'] = $this->lang->line('Advertisements');
        $this->load->helper('file');
        if(array_key_exists('sidebartop', $_POST) && !$this->config->item('demo')) {
            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["sidebartop"] = \''.convertTexte($_POST['sidebartop']).'\';'."\n";
            $file .= '$config["sidebarbottom"] = \''.convertTexte($_POST['sidebarbottom']).'\';'."\n";
            $file .= '$config["sidebarcontent"] = \''.convertTexte($_POST['sidebarcontent']).'\';'."\n";
            $file .= '$config["videoads"] = \''.convertTexte($_POST['videoads']).'\';'."\n";
            $file .= '$config["videoadsactive"] = \''.convertTexte($_POST['videoadsactive']).'\';'."\n";
            $file .= '$config["adsduration"] = \''.convertTexte($_POST['adsduration']).'\';'."\n";
            $file .= '$config["adslink"] = \''.convertTexte($_POST['adslink']).'\';'."\n";
            $file .= '?'.'>';
            if(!write_file('./application/config/ads_settings.php', $file)) {
                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');
            } else {
                redirect(current_url().'/', 'location');
            }
        }
        $content = $this->load->view('dashboard/ads_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function payments()
    {
        $data['title'] = 'Subscription';
        $this->load->helper('file');
        
        if(isset($_POST["publishablekey"]) && !$this->config->item('demo')) {

            $file = '<'.'?php'."\n";
            $file .= 'defined("BASEPATH") OR exit("No direct script access allowed");'."\n";
            $file .= '$config["contentText"] = \''.convertTexte($_POST['contentText']).'\';'."\n";
            $file .= '$config["paymentMethod"] = \'' . convertTexte($_POST['paymentMethod']) . '\';' . "\n";
            $file .= '$config["hasSidebar"] = \''.convertTexte($_POST['hasSidebar']).'\';'."\n";
            $file .= '$config["publishablekey"] = \''.convertTexte($_POST['publishablekey']).'\';'."\n";
            $file .= '$config["secretkey"] = \''.convertTexte($_POST['secretkey']).'\';'."\n";
            $file .= '$config["plan"] = \''.convertTexte($_POST['plan']).'\';'."\n";
            $file .= '$config["planTitle"] = \''.convertTexte($_POST['planTitle']).'\';'."\n";
            $file .= '$config["planDescription"] = \''.convertTexte($_POST['planDescription']).'\';'."\n";
            $file .= '$config["planPrice"] = \''.str_replace(",", ".", $_POST['planPrice']).'\';'."\n";
            $file .= '$config["planCurrency"] = \''.str_replace(",", ".", $_POST['planCurrency']).'\';'."\n";
            $file .= '$config["planTrial"] = \''.str_replace(",", ".", $_POST['planTrial']).'\';'."\n";
            $file .= '$config["planItemList"] = \''.str_replace(",", ".", $_POST['planItemList']).'\';'."\n";
            $file .= '$config["planBtn"] = \''.str_replace(",", ".", $_POST['planBtn']).'\';'."\n";
            $file .= '$config["planActive"] = \''.str_replace(",", ".", $_POST['planActive']).'\';'."\n";
            if (isset($_POST['planFocus'])) {
                $file .= '$config["planFocus"] = \''.str_replace(",", ".", $_POST['planFocus']).'\';'."\n";
            }

            $file .= '$config["plan2"] = \''.convertTexte($_POST['plan2']).'\';'."\n";
            $file .= '$config["plan2Title"] = \''.convertTexte($_POST['plan2Title']).'\';'."\n";
            $file .= '$config["plan2Description"] = \''.convertTexte($_POST['plan2Description']).'\';'."\n";
            $file .= '$config["plan2Price"] = \''.str_replace(",", ".", $_POST['plan2Price']).'\';'."\n";
            $file .= '$config["plan2Currency"] = \''.str_replace(",", ".", $_POST['plan2Currency']).'\';'."\n";
            $file .= '$config["plan2Trial"] = \''.str_replace(",", ".", $_POST['plan2Trial']).'\';'."\n";
            $file .= '$config["plan2ItemList"] = \''.str_replace(",", ".", $_POST['plan2ItemList']).'\';'."\n";
            $file .= '$config["plan2Btn"] = \''.str_replace(",", ".", $_POST['plan2Btn']).'\';'."\n";
            $file .= '$config["plan2Active"] = \''.str_replace(",", ".", $_POST['plan2Active']).'\';'."\n";
            if (isset($_POST['plan2Focus'])) {
                $file .= '$config["plan2Focus"] = \''.str_replace(",", ".", $_POST['plan2Focus']).'\';'."\n";
            }

            $file .= '$config["plan3"] = \''.convertTexte($_POST['plan3']).'\';'."\n";
            $file .= '$config["plan3Title"] = \''.convertTexte($_POST['plan3Title']).'\';'."\n";
            $file .= '$config["plan3Description"] = \''.convertTexte($_POST['plan3Description']).'\';'."\n";
            $file .= '$config["plan3Price"] = \''.str_replace(",", ".", $_POST['plan3Price']).'\';'."\n";
            $file .= '$config["plan3Currency"] = \''.str_replace(",", ".", $_POST['plan3Currency']).'\';'."\n";
            $file .= '$config["plan3Trial"] = \''.str_replace(",", ".", $_POST['plan3Trial']).'\';'."\n";
            $file .= '$config["plan3ItemList"] = \''.str_replace(",", ".", $_POST['plan3ItemList']).'\';'."\n";
            $file .= '$config["plan3Btn"] = \''.str_replace(",", ".", $_POST['plan3Btn']).'\';'."\n";
            $file .= '$config["plan3Active"] = \''.str_replace(",", ".", $_POST['plan3Active']).'\';'."\n";
            if (isset($_POST['plan3Focus'])) {
                $file .= '$config["plan3Focus"] = \''.str_replace(",", ".", $_POST['plan3Focus']).'\';'."\n";
            }

            $file .= '$config["payTitle"] = \''.convertTexte($_POST['payTitle']).'\';'."\n";
            $file .= '$config["payDescription"] = \''.convertTexte($_POST['payDescription']).'\';'."\n";
            $file .= '$config["payPrice"] = \''.str_replace(",", ".", $_POST['payPrice']).'\';'."\n";
            $file .= '$config["payCurrency"] = \''.str_replace(",", ".", $_POST['payCurrency']).'\';'."\n";
            $file .= '$config["payPeriod"] = \''.str_replace(",", ".", $_POST['payPeriod']).'\';'."\n";
            $file .= '$config["payItemList"] = \''.str_replace(",", ".", $_POST['payItemList']).'\';'."\n";
            $file .= '$config["payBtn"] = \''.str_replace(",", ".", $_POST['payBtn']).'\';'."\n";
            $file .= '$config["payActive"] = \''.str_replace(",", ".", $_POST['payActive']).'\';'."\n";
            if (isset($_POST['payFocus'])) {
                $file .= '$config["payFocus"] = \''.str_replace(",", ".", $_POST['payFocus']).'\';'."\n";
            }

            $file .= '$config["pay2Title"] = \''.convertTexte($_POST['pay2Title']).'\';'."\n";
            $file .= '$config["pay2Description"] = \''.convertTexte($_POST['pay2Description']).'\';'."\n";
            $file .= '$config["pay2Price"] = \''.str_replace(",", ".", $_POST['pay2Price']).'\';'."\n";
            $file .= '$config["pay2Currency"] = \''.str_replace(",", ".", $_POST['pay2Currency']).'\';'."\n";
            $file .= '$config["pay2Period"] = \''.str_replace(",", ".", $_POST['pay2Period']).'\';'."\n";
            $file .= '$config["pay2ItemList"] = \''.str_replace(",", ".", $_POST['pay2ItemList']).'\';'."\n";
            $file .= '$config["pay2Btn"] = \''.str_replace(",", ".", $_POST['pay2Btn']).'\';'."\n";
            $file .= '$config["pay2Active"] = \''.str_replace(",", ".", $_POST['pay2Active']).'\';'."\n";
            if (isset($_POST['pay2Focus'])) {
                $file .= '$config["pay2Focus"] = \''.str_replace(",", ".", $_POST['pay2Focus']).'\';'."\n";
            }

            $file .= '$config["pay3Title"] = \''.convertTexte($_POST['pay3Title']).'\';'."\n";
            $file .= '$config["pay3Description"] = \''.convertTexte($_POST['pay3Description']).'\';'."\n";
            $file .= '$config["pay3Price"] = \''.str_replace(",", ".", $_POST['pay3Price']).'\';'."\n";
            $file .= '$config["pay3Currency"] = \''.str_replace(",", ".", $_POST['pay3Currency']).'\';'."\n";
            $file .= '$config["pay3Period"] = \''.str_replace(",", ".", $_POST['pay3Period']).'\';'."\n";
            $file .= '$config["pay3ItemList"] = \''.str_replace(",", ".", $_POST['pay3ItemList']).'\';'."\n";
            $file .= '$config["pay3Btn"] = \''.str_replace(",", ".", $_POST['pay3Btn']).'\';'."\n";
            $file .= '$config["pay3Active"] = \''.str_replace(",", ".", $_POST['pay3Active']).'\';'."\n";
            if (isset($_POST['pay3Focus'])) {
                $file .= '$config["pay3Focus"] = \''.str_replace(",", ".", $_POST['pay3Focus']).'\';'."\n";
            }
            $file .= '?'.'>';

            if(!write_file('./application/config/payment_settings.php', $file)) {

                $data['msg'] = alert('<strong>Ooops !</strong> Unable to write the file', 'danger');

            } else {

                redirect(current_url().'/', 'location');

            }
        }

        $content = $this->load->view('dashboard/payment_settings', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
