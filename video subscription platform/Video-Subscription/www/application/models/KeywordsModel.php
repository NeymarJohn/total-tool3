<?php
class KeywordsModel extends CI_Model
{
    public function getKeywords()
    {
        $getKeywords = '';
        $sql = "SELECT id, title, url FROM 2d_keywords";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getKeywords .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.$row->title.'</td>
					<td>'.$row->url.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('keyword/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/keywords/edit/'.$row->id.'/').'"> <i class="fa fa-pencil"></i> </a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/keywords/?del='.$row->id.'').'"> <i class="fa fa-trash-o"></i> </a>
					</td>
				</tr>';
        }
        return $getKeywords;
    }

    public function getKeyword($idKeyword)
    {
        $sql = "SELECT id, title, url, image FROM 2d_keywords WHERE id = ?";
        $query = $this->db->query($sql, array($idKeyword));
        if($result = $query->row()) {
            return array(
             'title_keyword' => $result->title,
             'url_keyword'   => $result->url,
             'image'         => $result->image
             );
        } else {
            return null;
        }
    }

    public function addKeyword($postTitle, $postURL)
    {
        $sql = "SELECT title, url FROM 2d_keywords WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($query->num_rows() > 0) {
            $msg = alert($this->lang->line('This keyword already exists'), 'danger');
        } else {
            $sql = "INSERT INTO 2d_keywords (title, url, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array($postTitle, $postURL, date("Y-m-d H:i:s")));
            $msg = alert($this->lang->line('The keyword was created').' <a href="/dashboard/keywords/edit/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        }
        return $msg;
    }

    public function editKeyword($idKeyword, $postTitle, $postURL)
    {
        $sql = "SELECT title, url FROM 2d_keywords WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($result = $query->row()) {
            if($result->title === $postTitle && $result->url === $postURL) {
                $msg = alert($this->lang->line('This keyword and url already exists'), 'danger');
            } elseif($result->title === $postTitle) {
                $sql = "UPDATE 2d_keywords SET url = ? WHERE id = ?";
                $this->db->query($sql, array($postURL, $idKeyword));
                $msg = alert($this->lang->line('Saved changes'));
            } elseif($result->url === $postURL) {
                $sql = "UPDATE 2d_keywords SET title = ? WHERE id = ?";
                $this->db->query($sql, array($postTitle, $idKeyword));
                $msg = alert($this->lang->line('Saved changes'));
            }
        } else {
            $sql = "UPDATE 2d_keywords SET title = ?, url = ? WHERE id = ?";
            $this->db->query($sql, array($postTitle, $postURL, $idKeyword));
            $msg = alert($this->lang->line('Saved changes'));
        }
        return $msg;
    }

    public function updateImage($idKeyword, $image)
    {
        $sql = "UPDATE 2d_keywords SET image = ? WHERE id = ?";
        $this->db->query($sql, array($image, $idKeyword));
    }

    public function delKeyword($idKeyword)
    {
        $sql = 'DELETE FROM 2d_keywords WHERE id = ?';
        $this->db->query($sql, array($idKeyword));
        return alert($this->lang->line('Keyword deleted'));
    }
}
