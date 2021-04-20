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

class Dashboard extends CI_Controller
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
        $this->load->model(array('dashboardModel'));
    }

    public function index()
    {
        $data['title'] = $this->lang->line('dashboard');
        // General stats
        $nbIncomes = $this->dashboardModel->getNbIncomes();
        $data['nbIncomesCount'] = $nbIncomes['count'];
        $data['nbIncomesPrice'] = $nbIncomes['price'];
        $data['nbMembers'] = $this->dashboardModel->getNbMembers();
        $data['nbPlayed'] = $this->dashboardModel->getNbPlayed();
        // Active subscriptions
        $nbActiveSubscriptions = $this->dashboardModel->getNbActiveSubscriptions();
        $data['nbActiveSubscriptionsCount'] = $nbActiveSubscriptions['count'];
        $data['nbActiveSubscriptionsPrice'] = $nbActiveSubscriptions['price'];
        // Daily Sales
        $nbDayIncomes = $this->dashboardModel->getNbDayIncomes();
        $data['nbDayIncomesCount'] = $nbDayIncomes['count'];
        $data['nbDayIncomesPrice'] = $nbDayIncomes['price'];
        // Monthly sales
        $nbMonthIncomes = $this->dashboardModel->getNbMonthIncomes();
        $data['nbMonthIncomesCount'] = $nbMonthIncomes['count'];
        $data['nbMonthIncomesPrice'] = $nbMonthIncomes['price'];
        // Sales Stats
        $data['statsSubscriptions'] = $this->dashboardModel->getDaysStats(1);
        $data['statsPayments'] = $this->dashboardModel->getDaysStats(0);
        // Month Sales Stats
        $data['statsMonthSubscriptions'] = $this->dashboardModel->getMonthsStats(1);
        $data['statsMonthPayments'] = $this->dashboardModel->getMonthsStats(0);
        // Widgets stats
        $data['statsMembers'] = $this->dashboardModel->getStatsMembers();
        $data['nbComments'] = $this->dashboardModel->getNbComments();
        $data['statsComments'] = $this->dashboardModel->getStatsComments();
        $data['statsPlayed'] = $this->dashboardModel->getActivity(5);
        // Location
        $data['locationMembers'] = $this->dashboardModel->getLocationMembers();
        $data['totalLocationMembers'] = $this->dashboardModel->getTotalLocationMembers();
        // Invoices
        $data['getInvoices'] = $this->dashboardModel->getInvoices();
        // Activity stats
        $data['getNotesActivity'] = $this->dashboardModel->getActivity(2);
        $data['getFavsActivity'] = $this->dashboardModel->getActivity(3);
        $data['getComsActivity'] = $this->dashboardModel->getActivity(4);
        // Latest comments & videos
        $data['getLastcomments'] = $this->dashboardModel->getLastcomments();
        $data['getLastVideosAdded'] = $this->dashboardModel->getLastVideosAdded();
        $content = $this->load->view('dashboard/home', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
