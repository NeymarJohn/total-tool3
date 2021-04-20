<?php
class PostskeywordsModel extends CI_Model
{
    public function getKeywords()
    {
        $getKeywords = '';
        $sql = "SELECT id, title, url FROM 2d_posts_keywords";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getKeywords .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.$row->title.'</td>
					<td>'.$row->url.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('keyword/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/postskeywords/edit/'.$row->id.'/').'"> <i class="fa fa-pencil"></i> </a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/postskeywords/?del='.$row->id.'').'"> <i class="fa fa-trash-o"></i> </a>
					</td>
				</tr>';
        }
        return $getKeywords;
    }

    public function getKeyword($idKeyword)
    {
        $sql = "SELECT id, title, url, description, image FROM 2d_posts_keywords WHERE id = ?";
        $query = $this->db->query($sql, array($idKeyword));
        if($result = $query->row()) {
            return array(
             'title_keyword' => $result->title,
             'url_keyword'   => $result->url,
             'description'   => $result->description,
             'image'         => $result->image
             );
        } else {
            return null;
        }
    }

    public function addKeyword($postTitle, $postURL)
    {
        $sql = "SELECT title, url FROM 2d_posts_keywords WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($query->num_rows() > 0) {
            $msg = alert('The keyword already exists', 'danger');
        } else {
            $sql = "INSERT INTO 2d_posts_keywords (title, url, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array($postTitle, $postURL, date("Y-m-d H:i:s")));
            $msg = alert($this->lang->line('The keyword was created').' <a href="/dashboard/postskeywords/edit/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        }
        return $msg;
    }

    public function editKeyword($idKeyword, $postTitle, $postURL, $postDescription)
    {
        $sql = "SELECT title, url FROM 2d_posts_keywords WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($result = $query->row()) {
            if($result->title === $postTitle && $result->url === $postURL) {
                $sql = "UPDATE 2d_posts_keywords SET description = ? WHERE id = ?";
                $this->db->query($sql, array($postDescription, $idKeyword));
                $msg = alert($this->lang->line('Saved changes'));
            } elseif($result->title === $postTitle) {
                $sql = "UPDATE 2d_posts_keywords SET url = ?, description = ? WHERE id = ?";
                $this->db->query($sql, array($postURL, $postDescription, $idKeyword));
                $msg = alert($this->lang->line('Saved changes'));
            } elseif($result->url === $postURL) {
                $sql = "UPDATE 2d_posts_keywords SET title = ?, description = ? WHERE id = ?";
                $this->db->query($sql, array($postTitle, $postDescription, $idKeyword));
                $msg = alert($this->lang->line('Saved changes'));
            }
        } else {
            $sql = "UPDATE 2d_posts_keywords SET title = ?, url = ?, description = ? WHERE id = ?";
            $this->db->query($sql, array($postTitle, $postURL, $postDescription, $idKeyword));
            $msg = alert($this->lang->line('Saved changes'));
        }
        return $msg;
    }

    public function updateImage($idKeyword, $image)
    {
        $sql = "UPDATE 2d_posts_keywords SET image = ? WHERE id = ?";
        $this->db->query($sql, array($image, $idKeyword));
    }

    public function delKeyword($idKeyword)
    {
        $sql = 'DELETE FROM 2d_posts_keywords WHERE id = ?';
        $this->db->query($sql, array($idKeyword));
        return alert($this->lang->line('Keyword deleted'));
    }
}
