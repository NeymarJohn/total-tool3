<?php
class PagesModel extends CI_Model
{

    public function getPages()
    {
        $getPages = '';
        $sql = "SELECT id, title, url, sub_page, date_created, date_modified FROM 2d_pages";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $sub_page = $this->lang->line('None');
            if(!empty($row->sub_page)){
                $sub_page = $this->getSubPage($row->sub_page);
            }
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $timestamp = strtotime($row->date_modified);
            $date_modified = gmdate("M d, Y", $timestamp);
            $getPages .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.mb_strimwidth($row->title, 0, 35, '...').'</td>
					<td>'.$row->url.'</td>
					<td>'.$sub_page.'</td>
					<td>'.$date_created.'</td>
					<td>'.$date_modified.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('page/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/pages/edit/'.$row->id.'/').'"><i class="fa fa-pencil"></i></a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/pages/?del='.$row->id.'').'"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>';
        }
        return $getPages;
    }

    public function getSubPage($id)
    {
        $sql = "SELECT title FROM 2d_pages WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        if($result = $query->row()) {
            $result = $result->title;
        }
        return ($result) ? $result : FALSE;
    }

    public function getListPages($idSubPage) {

        $getListPages = '<option value="NULL">'.$this->lang->line('None').'</option>';
        $sql = "SELECT id, title, url FROM 2d_pages";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($row->id === $idSubPage) ? 'selected' : '';
            $getListPages .= '<option value="'.$row->id.'" '.$select.' >'.$row->title.'</option>';
        }
        return $getListPages;
    }

    public function getPage($idPage)
    {
        $sql = "SELECT title, url, content, sub_page, layout FROM 2d_pages WHERE id = ?";
        $query = $this->db->query($sql, array($idPage));
        if($result = $query->row()) {
            return array(
             'title_page'     => $result->title,
             'url_page'       => $result->url,
             'content_page'   => $result->content,
             'customPage'     => $result->layout,
             'sub_page'       => $result->sub_page
             );
        } else {
            return null;
        }
    }

    public function addPage($postTitle, $postURL, $postContent, $postSubPage, $postCustomPage)
    {
        $sql = "INSERT INTO 2d_pages (title, url, content, sub_page, layout, date_created, date_modified) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, array($postTitle, $postURL, $postContent, $postSubPage, $postCustomPage, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        $msg = alert($this->lang->line('Saved changes').' <a href="/dashboard/pages/edit/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        return $msg;
    }

    public function editPage($idPage, $postTitle, $postURL, $postContent, $postSubPage, $postCustomPage)
    {
        $postSubPage = ($postSubPage !== 'NULL') ? $postSubPage : NULL;
        $sql = "SELECT title, url FROM 2d_pages WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($result = $query->row()) {
            if($result->title === $postTitle) {
                $sql = "UPDATE 2d_pages SET url = ?, content = ?, date_modified = ?, sub_page = ?, layout = ? WHERE id = ?";
                $this->db->query($sql, array($postURL, $postContent, date("Y-m-d H:i:s"), $postSubPage, $postCustomPage, $idPage));
                $msg = alert($this->lang->line('Saved changes'));
            } elseif($result->url === $postURL) {
                $sql = "UPDATE 2d_pages SET title = ?, content = ?, date_modified = ?, sub_page = ?, layout = ? WHERE id = ?";
                $this->db->query($sql, array($postTitle, $postContent, date("Y-m-d H:i:s"), $postSubPage, $postCustomPage, $idPage));
                $msg = alert($this->lang->line('Saved changes'));
            }
        } else {
            $sql = "UPDATE 2d_pages SET title = ?, url = ?, content = ?, date_modified = ?, sub_page = ?, layout = ? WHERE id = ?";
            $this->db->query($sql, array($postTitle, $postURL, $postContent, date("Y-m-d H:i:s"), $postSubPage, $postCustomPage, $idPage));
            $msg = alert($this->lang->line('Saved changes'));
        }
        return $msg;
    }

    public function delPage($idPage)
    {
        $sql = 'DELETE FROM 2d_pages WHERE id = ?';
        $this->db->query($sql, array($idPage));
    }

}
