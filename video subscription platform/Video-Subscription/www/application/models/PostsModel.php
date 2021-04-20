<?php
class PostsModel extends CI_Model
{
    public function getPosts()
    {
        $getPosts = '';
        $sql = "SELECT ga.id AS id, ga.title AS title_post, ga.url AS url, ga.status AS status, ga.date_created AS date_created, ca.title AS title_category FROM 2d_posts ga, 2d_posts_categories ca WHERE ga.id_category = ca.id";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $status = ($row->status === '1') ? '<span class="label label-table label-success">Active</span>' : '<span class="label label-table label-inverse">Inactive</span>';
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $getPosts .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.mb_strimwidth($row->title_post, 0, 35, '...').'</td>
					<td>'.$row->title_category.'</td>
					<td>'.$status.'</td>
					<td>'.$date_created.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('post/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/posts/edit/'.$row->id.'/').'"><i class="fa fa-pencil"></i></a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/posts/?del='.$row->id.'').'"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>';
        }
        return $getPosts;
    }

    public function getCategories($idCategory = '')
    {
        $getCategories = '';
        $sql = "SELECT id, title FROM 2d_posts_categories";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select = ($idCategory === $row->id) ? 'selected' : '';
                $getCategories .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
            }
        } else {
            redirect('/dashboard/postscategories/add/?cat=FALSE');
        }
        return $getCategories;
    }

    public function getKeywords($idPost = FALSE)
    {
        if($idPost) {
            $sql = "SELECT ids_keywords FROM 2d_posts WHERE id = ?";
            $query = $this->db->query($sql, array($idPost));
            if($result = $query->row()) {
                $ids_keywords = explode(',', $result->ids_keywords);
                $ids_keywords = array_map("delQuote", $ids_keywords);
            } else {
                $ids_keywords = array();
            }
        }
        $sql = "SELECT id, title FROM 2d_posts_keywords";
        $query = $this->db->query($sql);
        $getKeywords = '';
        foreach ($query->result() as $row) {
            $select = '';
            if($idPost) {
                $select = (in_array($row->id, $ids_keywords)) ? 'selected' : '';
            }
            $getKeywords .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getKeywords;
    }

    public function getPost($idPost)
    {
        $sql = "SELECT ga.title AS title_post, ga.url AS url, ga.content AS content, ga.status AS status, ga.image AS image, ca.title AS category, ca.id AS id_category FROM 2d_posts ga, 2d_posts_categories ca WHERE ((ga.id = ?) AND (ga.id_category = ca.id))";
        $query = $this->db->query($sql, array($idPost));
        if($result = $query->row()) {
            return array(
             'title_post'   => $result->title_post,
             'url'          => $result->url,
             'content'      => $result->content,
             'id_category'  => $result->id_category,
             'category'     => $result->category,
             'status'       => $result->status,
             'image'        => $result->image
             );
        } else {
            return null;
        }
    }

    public function addPost($postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $author)
    {
        $sql = "SELECT title, url FROM 2d_posts WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($query->num_rows() > 0) {
            $msg = alert($this->lang->line('This post already exists'), 'danger');
        } else {
            $sql = "INSERT INTO 2d_posts (title, url, content, id_category, ids_keywords, status, author, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array($postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $author, date("Y-m-d H:i:s")));
            $msg = alert($this->lang->line('The post was created').' <a href="/dashboard/posts/edit/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        }
        return $msg;
    }

    public function editPost($idPost, $postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $author)
    {
        $sql = "SELECT title, url FROM 2d_posts WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($result = $query->row()) {
            if($result->title === $postTitle) {
                $sql = "UPDATE 2d_posts SET url = ?, content = ?, id_category = ?, ids_keywords = ?, status = ?, author = ? WHERE id = ?";
                $this->db->query($sql, array($postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $author, $idPost));
                $msg = alert($this->lang->line('Saved changes'));
            } elseif($result->url === $postURL) {
                $sql = "UPDATE 2d_posts SET title = ?, content = ?, id_category = ?, ids_keywords = ?, status = ?, author = ? WHERE id = ?";
                $this->db->query($sql, array($postTitle, $postContent, $postIdCategory, $postKeywords, $postStatus, $author, $idPost));
                $msg = alert($this->lang->line('Saved changes'));
            }
        } else {
            $sql = "UPDATE 2d_posts SET title = ?, url = ?, content = ?, id_category = ?, ids_keywords = ?, status = ?, author = ? WHERE id = ?";
            $this->db->query($sql, array($postTitle, $postURL, $postContent, $postIdCategory, $postKeywords, $postStatus, $author, $idPost));
            $msg = alert($this->lang->line('Saved changes'));
        }
        return $msg;
    }

    public function updateImage($idPost, $imgPost)
    {
        $sql = "UPDATE 2d_posts SET image = ? WHERE id = ?";
        $this->db->query($sql, array($imgPost, $idPost));
    }

    public function delPost($idPost)
    {
        // Removing images and files related to the post
        $sql = "SELECT image, file FROM 2d_posts WHERE id = ?";
        $query = $this->db->query($sql, array($idPost));
        if($result = $query->row()) {
            if(!empty($result->image)) {
                $file = 'uploads/images/posts/'.$result->image;
                if(is_readable($file)) {
                    unlink($file);
                }
            }
        }
        // Removing comments related to the post
        $sql = 'DELETE FROM 2d_posts_comments WHERE id_post = ?';
        $this->db->query($sql, array($idPost));
        // Removing the post
        $sql = 'DELETE FROM 2d_posts WHERE id = ?';
        $this->db->query($sql, array($idPost));
    }

}
