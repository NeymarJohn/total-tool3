<?php
class VideoModel extends CI_Model
{
    public function getVideo($getUrl)
    {
        $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.description AS description, ga.id_category AS id_category, ga.ids_keywords AS ids_keywords, ga.note AS note, ga.played AS played, ga.trailer AS trailer, ga.subscription AS subscription, ga.status AS status, ga.type AS type, ga.embed AS embed, ga.image AS image, ga.file AS file, ga.date_created AS date_created, ca.title AS category, ca.url AS url_category FROM 2d_videos ga, 2d_categories ca WHERE ((ga.url = '$getUrl') AND (ca.id = ga.id_category) AND ga.status != 0)";
        $query = $this->db->query($sql);
        if($result = $query->row()) {
            return array(
             'id'             => $result->id,
             'title_video'    => $result->title,
             'url'            => $result->url,
             'id_category'    => $result->id_category,
             'ids_keywords'   => $result->ids_keywords,
             'category'       => $result->category,
             'url_category'   => $result->url_category,
             'description'    => $result->description,
             'note'           => $result->note,
             'played'         => $result->played,
             'trailer'        => $result->trailer,
             'type'           => $result->type,
             'embed'          => $result->embed,
             'subscription'   => $result->subscription,
             'status'         => $result->status,
             'image'          => $result->image,
             'file'           => $result->file,
             'date_created'   => $result->date_created
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function updateVideoStat($idVideo)
    {
        $sql = "SELECT played FROM 2d_videos WHERE id = ?";
        $query = $this->db->query($sql, array($idVideo));
        $result = $query->row();
        $newStat = $result->played+1;
        $sql = "UPDATE 2d_videos SET played = ? WHERE id = ?";
        $this->db->query($sql, array($newStat, $idVideo));
        $sql = "SELECT attribut, value FROM 2d_stats WHERE date_created = ? AND attribut = 5";
        $query = $this->db->query($sql, array(date('Y-m-d')));
        if($result = $query->row()) {
            $newStat = $result->value+1;
            $sql = "UPDATE 2d_stats SET value = ? WHERE date_created = ? AND attribut = 5";
            $this->db->query($sql, array($newStat, date('Y-m-d')));
        } else {
            $sql = "INSERT INTO 2d_stats (attribut, value, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array(5, 1, date('Y-m-d')));
        }
    }

    public function updateStats($type)
    {
        $sql = "SELECT attribut, value FROM 2d_stats WHERE date_created = ? AND attribut = ?";
        $query = $this->db->query($sql, array(date('Y-m-d'), $type));
        if($result = $query->row()) {
            $newStat = $result->value+1;
            $sql = "UPDATE 2d_stats SET value = ? WHERE date_created = ? AND attribut = ?";
            $this->db->query($sql, array($newStat, date('Y-m-d'), $type));
        } else {
            $sql = "INSERT INTO 2d_stats (attribut, value, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array($type, 1, date('Y-m-d')));
        }
    }

    public function reportVideo($idVideo)
    {
        $sql = "INSERT INTO 2d_notifications (type, new, id_relation, date_created, date_modified) VALUES (?, ?, ?, ?, ?)";
        $this->db->query($sql, array(1, TRUE, $idVideo, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
    }

    public function createPlaylist($title) {
        $sql = 'INSERT INTO 2d_playlists (title, id_user, date_created) VALUES (?, ?, ?)';
        $this->db->query($sql, array(strip_tags($title), $this->session->id, date("Y-m-d H:i:s")));
    }

    public function getUsersFav($idVideo)
    {
        $sql = "SELECT us.url AS url, us.username AS username, us.image AS image FROM 2d_favorites fa, 2d_users us WHERE ((fa.id_video = ?) AND (fa.id_user = us.id)) LIMIT 16";
        $query = $this->db->query($sql, array($idVideo));
        $getUsersFav = '';
        foreach ($query->result() as $row) {
            $getUsersFav .= '<a href="'.site_url('user/'.$row->url.'/').'">
								<img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" class="thumb-md img-circle m-r-5" alt="'.$row->username.'">
							</a>';
        }
        return $getUsersFav;
    }

    public function getFav($idVideo)
    {
        $sql = "SELECT id FROM 2d_favorites WHERE id_video = ? AND id_user = ?";
        $query = $this->db->query($sql, array($idVideo, $this->session->id));
        if($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getPlaylists($idVideo) {
        $sql = "SELECT id, ids_videos, title FROM 2d_playlists WHERE id_user = ?";
        $query = $this->db->query($sql, array($this->session->id));
        $getPlaylists = '';
        foreach ($query->result() as $row) {
            $ids_videos = explode(',', $row->ids_videos);
            $checked = (in_array($idVideo, $ids_videos)) ? 'checked' : '';
            $getPlaylists .= '<div class="checkbox">
								  <input type="checkbox" name="playlists[]" value="'.$row->id.'" '.$checked.'>
								  <label for="checkbox"> '.$row->title.' </label>
							  </div>';
        }
        return $getPlaylists;
    }

    public function updatePlaylists($idVideo, $idsPlaylists) {
        $sql = "SELECT id, ids_videos FROM 2d_playlists WHERE id_user = ?";
        $query = $this->db->query($sql, array($this->session->id));
        foreach ($query->result() as $row) {
            $ids_videos = explode(',', $row->ids_videos);
            $ids_videos = array_filter($ids_videos);
            if(!is_array($idsPlaylists) || !in_array($row->id, $idsPlaylists)) {
                if(array_search($idVideo, $ids_videos)) {
                    unset($ids_videos[array_search($idVideo, $ids_videos)]);
                }
            } else {
                if(!in_array($idVideo, $ids_videos)) {
                    $ids_videos[] = $idVideo;
                }
            }
            $ids_videos = implode(',', $ids_videos);
            $sql = "UPDATE 2d_playlists SET ids_videos = ?, date_modified = ? WHERE id = ?";
            $this->db->query($sql, array($ids_videos, date("Y-m-d H:i:s"), $row->id));
        }
    }

    public function getVideoPlaylist($data, $episode = FALSE)
    {
        if ($episode === FALSE) {
            if($this->session->subscriber === true) {
                $sql = "SELECT title, description, image, type, file, embed FROM 2d_videos WHERE id_category = ? AND status = 1 AND id != ? ORDER BY RAND() LIMIT 20";
            } else {
                $sql = "SELECT title, description, image, type, file, embed FROM 2d_videos WHERE id_category = ? AND status = 1 AND subscription != 1  AND id != ? ORDER BY RAND() LIMIT 20";
            }
            $query = $this->db->query($sql, array($data['id_category'], $data['id']));
             // Set first video
            if($data['type'] === '1'){
                $videoPlaylist['type'][] = 'HTML5';
                $videoPlaylist['src'][] = $data['file'];
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = '';
            } elseif ($data['type'] === '2') {
                $videoPlaylist['type'][] = 'youtube';
                $videoPlaylist['src'][] = '';
                $videoPlaylist['youtube'][] = $data['embed'];
                $videoPlaylist['vimeo'][] = '';
            } elseif ($data['type'] === '3') {
                $videoPlaylist['type'][] = 'vimeo';
                $videoPlaylist['src'][] = '';
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = $data['embed'];
            } elseif ($data['type'] === '4'){
                $videoPlaylist['type'][] = 'HTML5';
                $videoPlaylist['src'][] = 'http://' . $this->config->item('amazonCloudFront') . '/' . $data['file'];
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = '';
            }
            $videoPlaylist['title'][] = $data['title_video'];
            $videoPlaylist['description'][] = mb_strimwidth(strip_tags($data['description']), 0, 40, '...');
            $videoPlaylist['image'][] = $data['image'];
        } else {
            $sql = "SELECT title, description, image, type, file, embed FROM 2d_episodes WHERE id_relation = ? AND status = 1 ORDER BY season, episode";
            $query = $this->db->query($sql, array($data['id']));
        }
        foreach ($query->result() as $row) {
            if ($row->type === '0') {
                $videoPlaylist['type'][] = '';
                $videoPlaylist['src'][] = '';
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = '';
            } elseif($row->type === '1'){
                $videoPlaylist['type'][] = 'HTML5';
                $videoPlaylist['src'][] = $row->file;
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = '';
            } elseif ($row->type === '2') {
                $videoPlaylist['type'][] = 'youtube';
                $videoPlaylist['src'][] = '';
                $videoPlaylist['youtube'][] = $row->embed;
                $videoPlaylist['vimeo'][] = '';
            } elseif ($row->type === '3') {
                $videoPlaylist['type'][] = 'vimeo';
                $videoPlaylist['src'][] = '';
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = $row->embed;
            } elseif ($data['type'] === '4'){
                $videoPlaylist['type'][] = 'HTML5';
                $videoPlaylist['src'][] = 'http://' . $this->config->item('amazonCloudFront') . '/' . $data['file'];
                $videoPlaylist['youtube'][] = '';
                $videoPlaylist['vimeo'][] = '';
            }
            $videoPlaylist['title'][] = $row->title;
            $videoPlaylist['description'][] = mb_strimwidth(strip_tags($row->description), 0, 40, '...');
            $videoPlaylist['image'][] = $row->image;
        }
        $videoPlaylist['type'] = implode('|', $videoPlaylist['type']);
        $videoPlaylist['src'] = implode('|', $videoPlaylist['src']);
        $videoPlaylist['youtube'] = implode('|', $videoPlaylist['youtube']);
        $videoPlaylist['vimeo'] = implode('|', $videoPlaylist['vimeo']);
        $videoPlaylist['title'] = implode('|', $videoPlaylist['title']);
        $videoPlaylist['description'] = implode('|', $videoPlaylist['description']);
        $videoPlaylist['image'] = implode('|', $videoPlaylist['image']);
        return ($videoPlaylist) ? $videoPlaylist : FALSE;
    }

    public function getSaison($idVideo)
    {
        $sql = "SELECT DISTINCT season FROM 2d_episodes WHERE id_relation = ? ORDER BY season ASC";
        $query = $this->db->query($sql, array($idVideo));
        $getSaison = '';
        foreach ($query->result() as $row) {
            $getSaison .= '<option value="'.$row->season.'">Season '.$row->season.'</option>';
        }
        return $getSaison;
    }

    public function getNbSaison($idVideo)
    {
        $sql = "SELECT DISTINCT season FROM 2d_episodes WHERE id_relation = ? ORDER BY season ASC";
        $query = $this->db->query($sql, array($idVideo));
        return $query->result();
    }

    public function getEpisodes($idVideo, $season)
    {
        $getEpisodes = '';
        $sql = "SELECT title, description, image, season, episode, type, file, embed, status, date_created, date_modified FROM 2d_episodes WHERE id_relation = ? AND season = ? ORDER BY episode";
        $query = $this->db->query($sql, array($idVideo, $season));
        foreach ($query->result() as $row) {
            $getEpisodes .= '<div class="col-sm-12 col-md-6 col-lg-4 p-b-20 episode'.$season.'" style="display:none">
                                <div class="video-image" data-episode="'.$this->session->numVideo.'">
                                    <a href="#" class="image-popup" title="'.$row->title.'">
                                        <span class="play-button" style="opacity: 0;"></span>
                                        <img src="'.$row->image.'" class="thumb-img" alt="'.$row->title.'" style="opacity: 1;">
                                    </a>
                                </div>
                                <div class="video-description container-mobile">
                                    <h2><a href="#" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 45, '...').'</a></h2>
                                    <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                </div>
                            </div>';
            // Set episode number
            $this->session->numVideo++;
        }
        return $getEpisodes;
    }

    public function getNote($idVideo)
    {
        $sql = "SELECT note FROM 2d_notes WHERE id_video = ?";
        $query = $this->db->query($sql, array($idVideo));
        if($query->num_rows() > 0) {
            $getNote = 0;
            $i = 0;
            foreach ($query->result() as $row) {
                $getNote = $getNote + $row->note;
                $i++;
            }
            $getNote = $getNote / $i;
        } else {
            $getNote = 0;
            $i = 0;
        }
        return array(
         'getNote' => $getNote,
         'getNbNote' => $i
         );
    }

    public function getKeywords($idsKeywords)
    {
        $idsKeywords = explode(',', $idsKeywords);
        $idsKeywords = array_map("delQuote", $idsKeywords);
        $getKeywords = '';
        foreach ($idsKeywords as $id) {
            $sql = "SELECT title, url FROM 2d_keywords WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            if($result = $query->row()) {
                $getKeywords .= '<a href="'.site_url('keyword/'.$result->url.'/').'"><span class="label label-inverse">'.$result->title.'</span></a>';
            }
        }
        return $getKeywords;
    }

    public function updateNote($idVideo, $score)
    {
        $sql = "SELECT id FROM 2d_notes WHERE id_user = ? AND id_video = ?";
        $query = $this->db->query($sql, array($this->session->id, $idVideo));
        if($query->num_rows() > 0) {
            $sql = "UPDATE 2d_notes SET note = ?, date_created = ? WHERE id_user = ? AND id_video = ?";
            $this->db->query($sql, array($score, date("Y-m-d H:i:s"), $this->session->id, $idVideo));
        } else {
            $sql = "INSERT INTO 2d_notes (id_user, id_video, note, date_created) VALUES (?, ?, ?, ?)";
            $this->db->query($sql, array($this->session->id, $idVideo, $score, date("Y-m-d H:i:s")));
        }
        $getNote = $this->getNote($idVideo);
        $sql = "UPDATE 2d_videos SET note = ? WHERE id = ?";
        $this->db->query($sql, array($getNote['getNote'], $idVideo));
        $this->updateNotes($this->session->id);
    }

    public function updateNotes($idUser)
    {
        $sql = 'SELECT id FROM 2d_notes WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_notes = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
    }

    public function addFav($idVideo)
    {
        $sql = "SELECT id FROM 2d_favorites WHERE id_user = ? AND id_video = ?";
        $query = $this->db->query($sql, array($this->session->id, $idVideo));
        if($query->num_rows() <= 0) {
            $sql = "INSERT INTO 2d_favorites (id_user, id_video, date_created) VALUES (?, ?, ?)";
            $this->db->query($sql, array($this->session->id, $idVideo, date("Y-m-d H:i:s")));
        }
        $this->updateFavs($this->session->id, $idVideo);
        return TRUE;
    }

    public function delFav($idVideo)
    {
        $sql = 'DELETE FROM 2d_favorites WHERE id_video = ? AND id_user = ?';
        $this->db->query($sql, array($idVideo, $this->session->id));
        $this->updateFavs($this->session->id, $idVideo);
    }

    public function updateFavs($idUser, $idVideo)
    {
        $sql = 'SELECT id FROM 2d_favorites WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_favs = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
        $sql = 'SELECT id FROM 2d_favorites WHERE id_video = ?';
        $query = $this->db->query($sql, array($idVideo));
        $sql = "UPDATE 2d_videos SET nb_favs = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idVideo));
    }

    public function addCom($idVideo, $postCom, $postRelated)
    {
        $status = ($this->config->item('comments_moderation') === TRUE) ? '1' : '3';
        $sql = 'INSERT INTO 2d_comments (comment, id_video, id_user, id_relation, status, date_created, ip) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->db->query($sql, array(strip_tags($postCom), $idVideo, $this->session->id, (int)$postRelated, $status, date("Y-m-d H:i:s"), $this->input->ip_address()));
        $this->updateComs($this->session->id);
        return TRUE;
    }

    public function updateComs($idUser)
    {
        $sql = 'SELECT id FROM 2d_comments WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_coms = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
    }

    public function likesComs($idCom, $likeType)
    {
        if($likeType == 1) {
            $sql = 'SELECT nb_like FROM 2d_likes WHERE id_com = ? AND id_user = ?';
            $query = $this->db->query($sql, array($idCom, $this->session->id));
            if($result = $query->row()) {
                if($result->nb_like == 0) {
                    $sql = "UPDATE 2d_likes SET nb_like = ?, nb_unlike = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(1, 0, $idCom, $this->session->id));
                } else {
                    $sql = "UPDATE 2d_likes SET nb_like = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(0, $idCom, $this->session->id));
                }
            } else {
                $sql = "INSERT INTO 2d_likes (id_user, id_com, nb_like, date_created) VALUES (?, ?, ?, ?)";
                $this->db->query($sql, array($this->session->id, $idCom, 1, date('Y-m-d H:i:s')));
            }
        } elseif ($likeType == 0) {
            $sql = 'SELECT nb_unlike FROM 2d_likes WHERE id_com = ? AND id_user = ?';
            $query = $this->db->query($sql, array($idCom, $this->session->id));
            if($result = $query->row()) {
                if($result->nb_unlike == 0) {
                    $sql = "UPDATE 2d_likes SET nb_unlike = ?, nb_like = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(1, 0, $idCom, $this->session->id));
                } else {
                    $sql = "UPDATE 2d_likes SET nb_unlike = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(0, $idCom, $this->session->id));
                }
            } else {
                $sql = "INSERT INTO 2d_likes (id_user, id_com, nb_unlike, date_created) VALUES (?, ?, ?, ?)";
                $this->db->query($sql, array($this->session->id, $idCom, 1, date('Y-m-d H:i:s')));
            }
        } else {
        }
        $sql = 'SELECT nb_like, nb_unlike FROM 2d_likes WHERE id_com = ?';
        $query = $this->db->query($sql, array($idCom));
        $score = 0;
        foreach ($query->result() as $row) {
            $score += $row->nb_like-$row->nb_unlike;
        }
        $sql = "UPDATE 2d_comments SET score = ? WHERE id = ?";
        $this->db->query($sql, array($score, $idCom));
    }

    public function checkLikesComs($idCom, $idUser)
    {
        $sql = "SELECT nb_like, nb_unlike FROM 2d_likes WHERE ((id_com = ?) AND (id_user = ?))";
        $query = $this->db->query($sql, array($idCom, $idUser));
        if($result = $query->row()) {
            return array (
             'nbLike' => $result->nb_like,
             'nbUnlike' => $result->nb_unlike
             );
        } else {
            return array (
             'nbLike' => 0,
             'nbUnlike' => 0
             );
        }
    }

    public function addNotification($type = 5)
    {
        $sql = "INSERT INTO 2d_notifications (type, new, date_created, date_modified) VALUES (?, ?, ?, ?)";
        $query = $this->db->query($sql, array($type, TRUE, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        if($this->db->insert_id()) {
            $id = $this->db->insert_id();
        }
        return (isset($id)) ? $id : FALSE;
    }

    public function getComs($idVideo, $getPag, $getOrder = false)
    {
        $sql = "SELECT id FROM 2d_comments WHERE (id_video = ?) AND (status = 3)";
        $query = $this->db->query($sql, array($idVideo));
        $totalComs = $query->num_rows();
        $sql = "SELECT id FROM 2d_comments WHERE ((id_video = ?) AND (id_relation = 0)) AND (status = 3)";
        $query = $this->db->query($sql, array($idVideo));
        $nbRows = $query->num_rows();
        if($getOrder == true) {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_comments co, 2d_users us WHERE ((co.id_video = ?) AND (co.id_user = us.id) AND (id_relation = 0) AND (score > 0)) AND (co.status = 3) ORDER BY score DESC LIMIT 3";
            $query1 = $this->db->query($sql, array($idVideo));
        } else {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_comments co, 2d_users us WHERE ((co.id_video = ?) AND (co.id_user = us.id) AND (id_relation = 0)) AND (co.status = 3) ORDER BY date_created DESC LIMIT ?,?";
            $query1 = $this->db->query($sql, array($idVideo, (int)$getPag, (int)$this->config->item('coms_pag')));
        }
        $getComs = '';
        foreach ($query1->result() as $row1) {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_comments co, 2d_users us WHERE ((id_relation = ?) AND (co.id_user = us.id))";
            $query2 = $this->db->query($sql, array($row1->id));
            $related1 = '';
            foreach ($query2->result() as $row2) {
                $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_comments co, 2d_users us WHERE ((id_relation = ?) AND (co.id_user = us.id))";
                $query3 = $this->db->query($sql, array($row2->id));
                $related2 = '';
                foreach ($query3->result() as $row3) {
                    $time = timespan(strtotime($row3->date_created), time(), 1);
                    if($this->session->id) {
                        $data = $this->checkLikesComs($row3->id, $this->session->id);
                    }
                    $related2 .= '<div class="comment-indent">
                                    <div class="comment comment-box big">
    		                            <img src="'.(empty($row3->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row3->image)).'" alt="'.$row3->username.'" class="comment-avatar">
    		                            <div class="comment-body">
    		                                <div class="comment-text">
    		                                    <div class="comment-header">
    		                                        <a href="'.site_url('user/'.$row3->url.'/').'">'.$row3->username.'</a><span>'.$this->lang->line('about').' '.$time.'</span>
    		                                    </div>
    		                                    '.$row3->comment.'
    		                                </div>
    		                                <div class="comment-footer" data-id="'.$row3->id.'">
    		                                    <a href="#comments" class="finger-up"><i class="fa fa-thumbs-o-up '.(($this->session->id) && ($data['nbLike'] == 1) ? 'text-primary' : '').'"></i></a>
    											<a href="#comments" class="finger-down"><i class="fa fa-thumbs-o-down '.(($this->session->id) && ($data['nbUnlike'] == 1) ? 'text-danger' : '').'"></i></a>
    		                                </div>
    		                            </div>
    		                        </div>
                                </div>';
                }
                $time = timespan(strtotime($row2->date_created), time(), 1);
                if($this->session->id) {
                    $data = $this->checkLikesComs($row2->id, $this->session->id);
                }
                $related1 .= '<div class="comment-indent">
                                <div class="comment comment-box big">
    	                            <img src="'.(empty($row2->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row2->image)).'" alt="'.$row2->username.'" class="comment-avatar">
    	                            <div class="comment-body">
    	                                <div class="comment-text">
    	                                    <div class="comment-header">
    	                                        <a href="'.site_url('user/'.$row2->url.'/').'">'.$row2->username.'</a><span>'.$this->lang->line('about').' '.$time.' ago</span>
                                                <a href="#comments" id="reply" class="pull-right btn btn-inverse btn-xs">'.$this->lang->line('Reply').'</a>
    	                                    </div>
    	                                    '.$row2->comment.'
    	                                </div>
    	                                <div class="comment-footer" data-id="'.$row2->id.'">
    	                                    <a href="#comments" class="finger-up"><i class="fa fa-thumbs-o-up '.(($this->session->id) && ($data['nbLike'] == 1) ? 'text-primary' : '').'"></i></a>
    										<a href="#comments" class="finger-down"><i class="fa fa-thumbs-o-down '.(($this->session->id) && ($data['nbUnlike'] == 1) ? 'text-danger' : '').'"></i></a>
    	                                </div>
    	                            </div>
    	                        </div>
                                '.$related2.'
                            </div>';
            }
            $time = timespan(strtotime($row1->date_created), time(), 1);
            if($this->session->id) {
                $data = $this->checkLikesComs($row1->id, $this->session->id);
            }
            $getComs .= '<div class="comment comment-box big">
							<img src="'.(empty($row1->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row1->image)).'" alt="'.$row1->username.'" class="comment-avatar">
							<div class="comment-body">
								<div class="comment-text">
									<div class="comment-header">
										<a href="'.site_url('user/'.$row1->url.'/').'">'.$row1->username.'</a><span>'.$this->lang->line('about').' '.$time.'</span>
                                        <a href="#comments" id="reply" class="pull-right btn btn-inverse btn-xs">'.$this->lang->line('Reply').'</a>
                                    </div>
									'.$row1->comment.'
								</div>
								<div class="comment-footer" data-id="'.$row1->id.'">
									<a href="#comments" class="finger-up"><i class="fa fa-thumbs-o-up '.(($this->session->id) && ($data['nbLike'] == 1) ? 'text-primary' : '').'"></i></a>
									<a href="#comments" class="finger-down"><i class="fa fa-thumbs-o-down '.(($this->session->id) && ($data['nbUnlike'] == 1) ? 'text-danger' : '').'"></i></a>
								</div>
							</div>
						</div>
                        '.$related1;
        }
        return array(
         'nbRows' => $nbRows,
         'totalComs' => $totalComs,
         'getComs' => $getComs
         );
    }
}
