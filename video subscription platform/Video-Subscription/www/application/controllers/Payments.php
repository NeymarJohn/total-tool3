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

class Payments extends CI_Controller
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
        $this->load->model(array('paymentsModel'));
    }

    public function index()
    {
        // Delete a payment
        $idPayment = $this->input->get('del', true);
        if(isset($idPayment) && !$this->config->item('demo')) {
            $this->paymentsModel->delPayment($idPayment);
        }
        // Get payments data
        $data['getPayments'] = $this->paymentsModel->getPayments();
        $data['title'] = $this->lang->line('Payments');
        $content = $this->load->view('dashboard/payments', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }

    public function edit($idPayment = '')
    {
        $data['getPayment'] = $this->paymentsModel->getPayment($idPayment);
        $data['title'] = $this->lang->line('Payments');
        $content = $this->load->view('dashboard/payment_edit', $data, true);
        $this->load->view('dashboard/template', array('content' => $content));
    }
}
