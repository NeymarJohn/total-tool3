<?php
class ApiModel extends CI_Model
{
    public function getCategories()
    {
        $sql = "SELECT id, title, url FROM 2d_categories";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getNbVideos($category)
    {
        $sql = "SELECT id FROM 2d_videos WHERE id_category = ?";
        $query = $this->db->query($sql, array($category->id));
        return array(
            'category' => $category->title,
            'number-of-videos' => $query->num_rows(),
            'url' => site_url('category/'.$category->url.'/')
        );
    }

    public function getTotalUsers()
    {
        $sql = "SELECT id FROM 2d_users WHERE status = ?";
        $query = $this->db->query($sql, array(1));
        return array(
            'number-of-users' => $query->num_rows()
        );
    }

    public function getUser($urlUser)
    {
        $sql = "SELECT username, url, location, about, nb_favs, nb_notes, nb_coms, country_name, city FROM 2d_users WHERE url = ?";
        $query = $this->db->query($sql, array($urlUser));
        return $query->result();
    }
}
