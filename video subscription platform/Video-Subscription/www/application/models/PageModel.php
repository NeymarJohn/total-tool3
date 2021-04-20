<?php
class PageModel extends CI_Model
{
    public function getPage($getUrl)
    {
        $sql = "SELECT title, url, content, sub_page, layout FROM 2d_pages WHERE url = ?";
        $query = $this->db->query($sql, array($getUrl));
        if($result = $query->row()) {
            return array(
             'title_page'   => $result->title,
             'url'     => $result->url,
             'content' => $result->content,
             'sub_page' => $result->sub_page,
             'customPage' => $result->layout
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function getSubPage($idSubPage) {
        $sql = "SELECT title, url FROM 2d_pages WHERE id = ?";
        $query = $this->db->query($sql, array((int)$idSubPage));
        if($result = $query->row()) {
            return array(
             'titleSubPage'   => $result->title,
             'urlSubPage'  => $result->url
             );
        }
    }
}
