<?php
class MediasModel extends CI_Model
{
    public function deleteDbImg($file)
    {
        $sql = "SELECT id FROM 2d_videos WHERE image = ?";
        $query = $this->db->query($sql, array($file));
        if($result = $query->row()) {
            $sql = "UPDATE 2d_videos SET image = '' WHERE id = ?";
            $this->db->query($sql, array($result->id));
        }
    }

    public function deleteDbFile($file)
    {
        $sql = "SELECT id FROM 2d_videos WHERE file = ?";
        $query = $this->db->query($sql, array($file));
        if($result = $query->row()) {
            $sql = "UPDATE 2d_videos SET file = '' WHERE id = ?";
            $this->db->query($sql, array($result->id));
        }
    }

    public function getSwfVideo($file)
    {
        $sql = "SELECT title FROM 2d_videos WHERE file = ?";
        $query = $this->db->query($sql, array(site_url('uploads/files/videos/'.$file)));
        if($result = $query->row()) {
            return array(
             'title' => $result->title
             );
        } else{
            return array(
             'title' => $this->lang->line('Unattached')
             );
        }
    }

}
