<?php
class SettingsModel extends CI_Model
{
    public function getPages()
    {
        $getPages = '';
        $sql = "SELECT url, title FROM 2d_pages";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($this->config->item('post_terms') === $row->url) ? 'selected' : '';
            $getPages .= '<option value="'.$row->url.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getPages;
    }

    public function getMenus($id)
    {
        $getMenus = '';
        $sql = "SELECT id, title FROM 2d_menus";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($id === $row->id) ? 'selected' : '';
            $getMenus .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getMenus;
    }

    public function getCategories($id)
    {
        $getCategories = '<option value="false">None</option>';
        $sql = "SELECT id, title FROM 2d_categories";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($id === $row->id) ? 'selected' : '';
            $getCategories .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getCategories;
    }
}
