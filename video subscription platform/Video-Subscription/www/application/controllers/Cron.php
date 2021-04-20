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

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('cronModel'));
    }

    public function index()
    {
        if (is_cli()) {
            $invoices = $this->cronModel->getInvoicesDateEnd();
            $nbInvoicesUpdated = $nbInvoiceCanceled = 0;
            foreach ($invoices as $row) {
                if($row->type === '1') {
                    $customer = $this->cronModel->checkSubscription($row->subscription_id);
                    if($customer) {
                        $updateInvoice = $this->cronModel->updateInvoice($customer, $row->subscription_id);
                        if($updateInvoice) {
                            $nbInvoicesUpdated++;
                        }
                        if($customer->status === 'canceled') {
                            $updateUser = $this->cronModel->updateUser($row->id_user, 0);
                        }
                        if($row->status === 'active' && $customer->status === 'canceled') {
                            $nbInvoiceCanceled++;
                        }
                    }
                } elseif ($row->type === '0') {
                    $updateUser = $this->cronModel->updateUser($row->id_user, 0);
                }
            }
            $idTask[] = $this->cronModel->updateTaskStats($nbInvoicesUpdated.' invoices updated');
            $idTask[] = $this->cronModel->updateTaskStats($nbInvoiceCanceled.' invoices canceled');
        }
    }
}
