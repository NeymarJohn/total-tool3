<?php
class CronModel extends CI_Model
{
    public function getInvoicesDateEnd()
    {
        $date = date("Y-m-d", strtotime("-1 day", time()));
        $sql = "SELECT id_user, type, status, subscription_id FROM 2d_payments WHERE Year(date_end) = Year('$date') AND Month(date_end) = Month('$date') AND Day(date_end) = Day('$date')";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function checkSubscription($subscriptionId)
    {
        require_once('./application/vendor/stripe/init.php');
        \Stripe\Stripe::setApiKey($this->config->item('secretkey'));
        try {
            $customer = \Stripe\Subscription::retrieve($subscriptionId);
            return $customer;
        } catch(Exception $e) {
            error_log("unable to check status for:, error:" . $e->getMessage());
            exit;
        }
    }

    public function updateInvoice($customer, $subscriptionId)
    {
        $sql = "UPDATE 2d_payments SET status = ?, date_modified = ?, date_end = ?, trial_start = ?, trial_end = ? WHERE subscription_id = ?";
        $this->db->query($sql, array($customer->status, date("Y-m-d H:i:s"), date('Y-m-d H:i:s', $customer->current_period_end), date('Y-m-d H:i:s', $customer->trial_start), date('Y-m-d H:i:s', $customer->trial_end), $subscriptionId));
        return $this->db->affected_rows();
    }

    public function updateUser($idUser, $isSuscriber)
    {
        $sql = "UPDATE 2d_users SET subscriber = ? WHERE id = ?";
        $this->db->query($sql, array($isSuscriber, $idUser));
        return $this->db->affected_rows();
    }

    public function updateTaskStats($string)
    {
        $sql = "INSERT INTO 2d_stats_cron (message, date_created) VALUES (?, ?)";
        $query = $this->db->query($sql, array($string, date("Y-m-d H:i:s")));
        return $this->db->insert_id();

    }
}
