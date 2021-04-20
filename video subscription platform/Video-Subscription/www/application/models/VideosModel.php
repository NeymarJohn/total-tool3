<?php
class VideosModel extends CI_Model
{
    public function getVideos()
    {
        $getVideos = '';
        $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.played AS played, ga.status AS status, ga.type AS type, ga.date_created AS date_created, ca.title AS title_category FROM 2d_videos ga, 2d_categories ca WHERE ga.id_category = ca.id";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $status = ($row->status === '1') ? '<span class="label label-table label-success">Active</span>' : '<span class="label label-table label-inverse">Inactive</span>';
            if ($row->type === '0') {
                $source = $this->lang->line('Embedded');
            } elseif ($row->type === '1') {
                $source = $this->lang->line('Hosted');
            } elseif ($row->type === '2') {
                $source = 'Youtube';
            } elseif ($row->type === '3') {
                $source = 'Vimeo';
            } elseif ($row->type === '4') {
                $source = 'Amazon S3';
            }
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $getVideos .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.mb_strimwidth($row->title, 0, 35, '...').'</td>
					<td>'.$row->title_category.'</td>
					<td>'.$row->played.'</td>
					<td>'.$status.'</td>
					<td>'.$source.'</td>
					<td>'.$date_created.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('video/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/videos/edit/'.$row->id.'/').'"><i class="fa fa-pencil"></i></a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/videos/?del='.$row->id.'').'"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>';
        }
        return $getVideos;
    }

    public function getEpisodes()
    {
        $getEpisodes = '';
        $sql = "SELECT ep.id AS id, ep.title AS title, ep.season AS season, ep.episode AS episode, ep.status AS status, ep.type AS type, ep.date_created AS date_created, vi.title AS title_video, vi.url AS url FROM 2d_episodes ep, 2d_videos vi WHERE vi.id = ep.id_relation";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $status = ($row->status === '1') ? '<span class="label label-table label-success">Active</span>' : '<span class="label label-table label-inverse">Inactive</span>';
            if ($row->type === '0') {
                $source = $this->lang->line('Embedded');
            } elseif ($row->type === '1') {
                $source = $this->lang->line('Hosted');
            } elseif ($row->type === '2') {
                $source = 'Youtube';
            } elseif ($row->type === '3') {
                $source = 'Vimeo';
            } elseif ($row->type === '4') {
                $source = 'Amazon S3';
            }
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $getEpisodes .=
             '<tr class="text-center">
					<td>'.$row->id.'</td>
					<td>'.$row->title_video.'</td>
					<td>'.mb_strimwidth($row->title, 0, 35, '...').'</td>
					<td>'.$row->season.'</td>
					<td>'.$row->episode.'</td>
					<td>'.$status.'</td>
					<td>'.$source.'</td>
					<td>'.$date_created.'</td>
					<td>
						<a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('video/'.$row->url.'/').'"> <i class="fa fa-search"></i> </a>
						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/videos/editepisode/'.$row->id.'/').'"><i class="fa fa-pencil"></i></a>
						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/videos/episodes/?del='.$row->id.'').'"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>';
        }
        return $getEpisodes;
    }

    public function getVideosList($idRelation = '')
    {
        $getVideosList = '';
        $sql = "SELECT id, title FROM 2d_videos ORDER BY title";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($idRelation === $row->id) ? 'selected' : '';
            $getVideosList .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return ($getVideosList) ? $getVideosList : FALSE;
    }

    public function getCategories($idCategory = '')
    {
        $getCategories = '';
        $sql = "SELECT id, title FROM 2d_categories";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select = ($idCategory === $row->id) ? 'selected' : '';
                $getCategories .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
            }
        } else {
            redirect('/dashboard/categories/add/?cat=FALSE');
        }
        return $getCategories;
    }

    public function getKeywords($idVideo = FALSE)
    {
        if($idVideo){
            $sql = "SELECT ids_keywords FROM 2d_videos WHERE id = ?";
            $query = $this->db->query($sql, array($idVideo));
            if($result = $query->row()) {
                $ids_keywords = explode(',', $result->ids_keywords);
                $ids_keywords = array_map("delQuote", $ids_keywords);
            } else {
                $ids_keywords = array();
            }
        }
        $sql = "SELECT id, title FROM 2d_keywords";
        $query = $this->db->query($sql);
        $getKeywords = '';
        foreach ($query->result() as $row) {
            $select = '';
            if($idVideo){
                $select = (in_array($row->id, $ids_keywords)) ? 'selected' : '';
            }
            $getKeywords .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getKeywords;
    }

    public function getVideo($idVideo)
    {
        $sql = "SELECT ga.title AS title, ga.url AS url, ga.description AS description, ga.type AS type, ga.embed AS embed, ga.status AS status, ga.image AS image, ga.file AS file, ga.trailer AS trailer, ga.subscription AS subscription, ca.title AS category, ca.id AS id_category FROM 2d_videos ga, 2d_categories ca WHERE ((ga.id = ?) AND (ga.id_category = ca.id))";
        $query = $this->db->query($sql, array($idVideo));
        if($result = $query->row()) {
            return array(
             'title_video'        => $result->title,
             'url_video'          => $result->url,
             'description_video'  => $result->description,
             'id_category'        => $result->id_category,
             'trailer'            => $result->trailer,
             'category_video'     => $result->category,
             'type_video'         => $result->type,
             'embed_url'          => $result->embed,
             'status_video'       => $result->status,
             'subscription_video' => $result->subscription,
             'image'              => $result->image,
             'file'               => $result->file
             );
        } else {
            return null;
        }
    }

    public function getEpisode($idVideo)
    {
        $sql = "SELECT title, description, season, episode, id_relation, type, file, embed, image, status, date_created, date_modified FROM 2d_episodes WHERE id = ?";
        $query = $this->db->query($sql, array($idVideo));
        if($result = $query->row()) {
            return array(
             'title_episode'   => $result->title,
             'description'     => $result->description,
             'season'          => $result->season,
             'episode'         => $result->episode,
             'id_relation'     => $result->id_relation,
             'type'            => $result->type,
             'file'            => $result->file,
             'embed'           => $result->embed,
             'image'           => $result->image,
             'status'          => $result->status,
             'date_modified'   => $result->date_modified
             );
        } else {
            return null;
        }
    }

    public function addVideo($postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus)
    {
        $sql = "SELECT title, url FROM 2d_videos WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($query->num_rows() > 0) {
            $msg = alert('The video already exists', 'danger');
        } else {
            $sql = "INSERT INTO 2d_videos (title, url, description, id_category, ids_keywords, type, embed, subscription, status, date_created, date_modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, array($postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
            $msg = alert($this->lang->line('The video was created').' <a href="/dashboard/videos/edit/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        }
        return $msg;
    }

    public function addEpisode($post)
    {
        $sql = "INSERT INTO 2d_episodes (title, description, season, episode, id_relation, type, embed, status, date_created, date_modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, array($post['title'], $post['description'], $post['season'], $post['episode'], $post['video'], $post['type'], $post['embed'], $post['status'],  date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        $msg = alert($this->lang->line('The episode was created').' <a href="/dashboard/videos/editepisode/'.$this->db->insert_id().'">'.$this->lang->line('Edit it').'</a> !');
        return $msg;
    }

    public function editVideo($idVideo, $postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus)
    {
        $sql = "SELECT id, title, url FROM 2d_videos WHERE title = ? OR url = ?";
        $query = $this->db->query($sql, array($postTitle, $postURL));
        if($result = $query->row()) {
            if($idVideo === $result->id) {
                if ($result->title === $postTitle) {
                    $sql = "UPDATE 2d_videos SET url = ?, description = ?, id_category = ?, ids_keywords = ?, type = ?, embed = ?, subscription = ?, status = ?, date_modified = ? WHERE id = ?";
                    $this->db->query($sql, array($postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus, date("Y-m-d H:i:s"), $idVideo));
                    $msg = alert($this->lang->line('Saved changes'));
                } elseif ($result->url === $postURL) {
                    $sql = "UPDATE 2d_videos SET title = ?, description = ?, id_category = ?, ids_keywords = ?, type = ?, embed = ?, subscription = ?, status = ?, date_modified = ? WHERE id = ?";
                    $this->db->query($sql, array($postTitle, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus, date("Y-m-d H:i:s"), $idVideo));
                    $msg = alert($this->lang->line('Saved changes'));
                }
            } else {
                if ($result->title === $postTitle) {
                    $msg = alert('This title already exists, please choose another title.');
                } elseif ($result->url === $postURL) {
                    $msg = alert('This URL already exists, please choose another URL.');
                }
            }
            
        } else {
            $sql = "UPDATE 2d_videos SET title = ?, url = ?, description = ?, id_category = ?, ids_keywords = ?, type = ?, embed = ?, subscription = ?, status = ? WHERE id = ?";
            $this->db->query($sql, array($postTitle, $postURL, $postDescription, $postIdCategory, $postKeywords, $postType, $postEmbed, $postSubscription, $postStatus, $idVideo));
            $msg = alert($this->lang->line('Saved changes'));
        }
        return $msg;
    }

    public function updateVideoType($idVideo)
    {
        $sql = "UPDATE 2d_videos SET type = ? WHERE id = ?";
        return $this->db->query($sql, array(2, $idVideo));
    }

    public function editEpisode($post, $idVideo)
    {
        $sql = "UPDATE 2d_episodes SET title = ?, description = ?, season = ?, episode = ?, id_relation = ?, type = ?, embed = ?, status = ?, date_modified = ? WHERE id = ?";
        $this->db->query($sql, array($post['title'], $post['description'], $post['season'], $post['episode'], $post['video'], $post['type'], $post['embed'], $post['status'], date("Y-m-d H:i:s"), $idVideo));
        return alert($this->lang->line('Saved changes'));
    }

    public function updateImage($id, $image, $type = 0)
    {
        if($type === 0) {
            $sql = "UPDATE 2d_videos SET image = ? WHERE id = ?";
        } else {
            $sql = "UPDATE 2d_episodes SET image = ? WHERE id = ?";
        }
        $this->db->query($sql, array($image, $id));
    }

    public function updateFile($id, $file, $type = 0)
    {
        if ($type === 0) {
            $sql = "UPDATE 2d_videos SET file = ? WHERE id = ?";
        } else {
            $sql = "UPDATE 2d_episodes SET file = ? WHERE id = ?";
        }
        $this->db->query($sql, array($file, $id));
        return $this->db->affected_rows();
    }

    public function delVideo($idVideo)
    {
        // Removing images and files related to the video
        $sql = "SELECT image, file FROM 2d_videos WHERE id = ?";
        $query = $this->db->query($sql, array($idVideo));
        if($result = $query->row()) {
            if(!empty($result->image)) {
                $file = 'uploads/images/videos/'.$result->image;
                if(is_readable($file)) {
                    unlink($file);
                }
            }
            if(!empty($result->file)) {
                $file = 'uploads/files/videos/'.$result->file;
                if(is_readable($file)) {
                    unlink($file);
                }
            }
        }
        // Removing comments related to the video
        $sql = 'DELETE FROM 2d_comments WHERE id_video = ?';
        $this->db->query($sql, array($idVideo));
        // Removing favorites related to the video
        $sql = 'DELETE FROM 2d_favorites WHERE id_video = ?';
        $this->db->query($sql, array($idVideo));
        // Removing notes related to the video
        $sql = 'DELETE FROM 2d_notes WHERE id_video = ?';
        $this->db->query($sql, array($idVideo));
        // Removing the video
        $sql = 'DELETE FROM 2d_videos WHERE id = ?';
        $this->db->query($sql, array($idVideo));
    }

    public function delEpisode($idEpisode)
    {
        // Removing images and files related to the episode
        $sql = "SELECT image, file FROM 2d_episodes WHERE id = ?";
        $query = $this->db->query($sql, array($idEpisode));
        if($result = $query->row()) {
            if(!empty($result->image)) {
                $file = 'uploads/images/videos/'.$result->image;
                if(is_readable($file)) {
                    unlink($file);
                }
            }
            if(!empty($result->file)) {
                $file = 'uploads/files/videos/'.$result->file;
                if(is_readable($file)) {
                    unlink($file);
                }
            }
        }
        // Removing the episode
        $sql = 'DELETE FROM 2d_episodes WHERE id = ?';
        $this->db->query($sql, array($idEpisode));
    }

    public function updateTrailer($id, $url)
    {
        $sql = "UPDATE 2d_videos SET trailer = ? WHERE id = ?";
        $this->db->query($sql, array($url, $id));
        return $this->db->affected_rows();
    }
}
