<?php
class MembersModel extends CI_Model
{
    public function getMembersNotes()
    {
        $sql = "SELECT url, username, image FROM 2d_users WHERE status != 0 ORDER BY nb_notes DESC LIMIT 20";
        $query = $this->db->query($sql);
        $getMembersNotes = '';
        foreach ($query->result() as $row) {
            $getMembersNotes .= '<a href="'.site_url('user/'.$row->url.'/').'"><img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" class="thumb-lg img-thumbnail m-r-5 m-b-5" alt="'.$row->username.'"></a>';
        }
        return $getMembersNotes;
    }

    public function getMembersFavs()
    {
        $sql = "SELECT url, username, image FROM 2d_users WHERE status != 0 ORDER BY nb_favs DESC LIMIT 20";
        $query = $this->db->query($sql);
        $getMembersFavs = '';
        foreach ($query->result() as $row) {
            $getMembersFavs .= '<a href="'.site_url('user/'.$row->url.'/').'"><img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" class="thumb-lg img-thumbnail m-r-5 m-b-5" alt="'.$row->username.'"></a>';
        }
        return $getMembersFavs;
    }

    public function getMembersComs()
    {
        $sql = "SELECT url, username, image FROM 2d_users WHERE status != 0 ORDER BY nb_coms DESC LIMIT 20";
        $query = $this->db->query($sql);
        $getMembersComs = '';
        foreach ($query->result() as $row) {
            $getMembersComs .= '<a href="'.site_url('user/'.$row->url.'/').'"><img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" class="thumb-lg img-thumbnail m-r-5 m-b-5" alt="'.$row->username.'"></a>';
        }
        return $getMembersComs;
    }
}
