<?php
class NewsletterModel extends CI_Model
{
    public function checkNewsletter($email)
    {
        $sql = "SELECT id FROM 2d_newsletter WHERE email = ?";
        $query = $this->db->query($sql, array($email));
        if($row = $query->row()) {
            $id = $row->id;
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function checkUsers($email)
    {
        $sql = "SELECT id FROM 2d_users WHERE email = ?";
        $query = $this->db->query($sql, array($email));
        if($row = $query->row()) {
            $id = $row->id;
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function addEmail($email, $isMember = FALSE)
    {
        $sql = "INSERT INTO 2d_newsletter (email, is_member, status, ip, date_created, date_modified) VALUES (?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, array($email, $isMember, 1, $this->input->ip_address(), date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        if($this->db->insert_id()) {
            $id = $this->db->insert_id();
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function addNotification()
    {
        $sql = "INSERT INTO 2d_notifications (type, new, date_created, date_modified) VALUES (?, ?, ?, ?)";
        $query = $this->db->query($sql, array(0, TRUE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        if($this->db->insert_id()) {
            $id = $this->db->insert_id();
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function getUsersList()
    {
        $sql = "SELECT email FROM 2d_newsletter WHERE status = ?";
        $query = $this->db->query($sql, array(1));
        return $query->result();
    }
}
