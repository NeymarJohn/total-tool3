<?php
class PostModel extends CI_Model
{
    public function getPost($getUrl)
    {
        $sql = "SELECT po.id AS id, po.title AS title_post, po.url AS url, po.content AS content, po.id_category AS id_category, po.ids_keywords AS ids_keywords, po.status AS status, po.image AS image, po.date_created AS date_created, ca.title AS category, ca.url AS url_category, us.username AS author, us.url AS url_user
                FROM 2d_posts po, 2d_posts_categories ca, 2d_users us
                WHERE ((po.url = '$getUrl') AND (ca.id = po.id_category) AND (us.id = po.author))";
        $query = $this->db->query($sql);
        if($result = $query->row()) {
            $timestamp = strtotime($result->date_created);
            $postDate = gmdate("M d, Y", $timestamp);
            return array(
             'id'            => $result->id,
             'title_post'    => $result->title_post,
             'url'           => $result->url,
             'id_category'   => $result->id_category,
             'ids_keywords'  => $result->ids_keywords,
             'category'      => $result->category,
             'url_category'  => $result->url_category,
             'content'       => $result->content,
             'status'        => $result->status,
             'image'         => $result->image,
             'author'        => $result->author,
             'url_user'      => $result->url_user,
             'date_created'  => $result->date_created,
             'post_date'     => $postDate
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function getKeywords($idsKeywords)
    {
        $idsKeywords = explode(',', $idsKeywords);
        $idsKeywords = array_map("delQuote", $idsKeywords);
        $getKeywords = '';
        foreach ($idsKeywords as $id) {
            $sql = "SELECT title, url FROM 2d_posts_keywords WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            if($result = $query->row()) {
                $getKeywords .= '<a href="'.site_url('post/keyword/'.$result->url.'/').'" class="p-r-5 p-b-10"><span class="label label-inverse">'.$result->title.'</span></a>';
            }
        }
        return $getKeywords;
    }

    public function addCom($idPost, $postCom, $postRelated)
    {
        $status = ($this->config->item('comments_moderation') === TRUE) ? '1' : '3';
        $sql = 'INSERT INTO 2d_posts_comments (comment, id_post, id_user, id_relation, status, date_created, ip) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $this->db->query($sql, array(strip_tags($postCom), $idPost, $this->session->id, (int)$postRelated, $status, date("Y-m-d H:i:s"), $this->input->ip_address()));
        $this->updateComs($this->session->id);
    }

    public function updateComs($idUser)
    {
        $sql = 'SELECT id FROM 2d_posts_comments WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_coms = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
    }

    public function likesComs($idCom, $likeType)
    {
        if($likeType == 1) {
            $sql = 'SELECT nb_like FROM 2d_posts_likes WHERE id_com = ? AND id_user = ?';
            $query = $this->db->query($sql, array($idCom, $this->session->id));
            if($result = $query->row()) {
                if($result->nb_like == 0) {
                    $sql = "UPDATE 2d_posts_likes SET nb_like = ?, nb_unlike = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(1, 0, $idCom, $this->session->id));
                } else {
                    $sql = "UPDATE 2d_posts_likes SET nb_like = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(0, $idCom, $this->session->id));
                }
            } else {
                $sql = "INSERT INTO 2d_posts_likes (id_user, id_com, nb_like, date_created) VALUES (?, ?, ?, ?)";
                $this->db->query($sql, array($this->session->id, $idCom, 1, date('Y-m-d H:i:s')));
            }
        } elseif ($likeType == 0) {
            $sql = 'SELECT nb_unlike FROM 2d_posts_likes WHERE id_com = ? AND id_user = ?';
            $query = $this->db->query($sql, array($idCom, $this->session->id));
            if($result = $query->row()) {
                if($result->nb_unlike == 0) {
                    $sql = "UPDATE 2d_posts_likes SET nb_unlike = ?, nb_like = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(1, 0, $idCom, $this->session->id));
                } else {
                    $sql = "UPDATE 2d_posts_likes SET nb_unlike = ? WHERE id_com = ? AND id_user = ?";
                    $this->db->query($sql, array(0, $idCom, $this->session->id));
                }
            } else {
                $sql = "INSERT INTO 2d_posts_likes (id_user, id_com, nb_unlike, date_created) VALUES (?, ?, ?, ?)";
                $this->db->query($sql, array($this->session->id, $idCom, 1, date('Y-m-d H:i:s')));
            }
        } else {
        }
        $sql = 'SELECT nb_like, nb_unlike FROM 2d_posts_likes WHERE id_com = ?';
        $query = $this->db->query($sql, array($idCom));
        $score = 0;
        foreach ($query->result() as $row) {
            $score += $row->nb_like-$row->nb_unlike;
        }
        $sql = "UPDATE 2d_posts_comments SET score = ? WHERE id = ?";
        $this->db->query($sql, array($score, $idCom));
    }

    public function checkLikesComs($idCom, $idUser)
    {
        $sql = "SELECT nb_like, nb_unlike FROM 2d_posts_likes WHERE ((id_com = ?) AND (id_user = ?))";
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

    public function getComs($idPost, $getPag = 0, $getOrder = false)
    {
        $sql = "SELECT id FROM 2d_posts_comments WHERE ((id_post = ?) AND (status = 3))";
        $query = $this->db->query($sql, array($idPost));
        $totalComs = $query->num_rows();
        $sql = "SELECT id FROM 2d_posts_comments WHERE ((id_post = ?) AND (id_relation = 0) AND (status = 3))";
        $query = $this->db->query($sql, array($idPost));
        $nbRows = $query->num_rows();
        if($getOrder == true) {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_posts_comments co, 2d_users us WHERE ((co.id_post = ?) AND (co.id_user = us.id) AND (id_relation = 0) AND (score > 0)) AND (co.status = 3) ORDER BY score DESC LIMIT 3";
            $query1 = $this->db->query($sql, array($idPost));
        } else {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_posts_comments co, 2d_users us WHERE ((co.id_post = ?) AND (co.id_user = us.id) AND (id_relation = 0)) AND (co.status = 3) ORDER BY date_created DESC LIMIT ?,?";
            $query1 = $this->db->query($sql, array($idPost, (int)$getPag, (int)$this->config->item('coms_pag')));
        }
        $getComs = '';
        foreach ($query1->result() as $row1) {
            $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_posts_comments co, 2d_users us WHERE ((id_relation = ?) AND (co.id_user = us.id))";
            $query2 = $this->db->query($sql, array($row1->id));
            $related1 = '';
            foreach ($query2->result() as $row2) {
                $sql = "SELECT co.id AS id, co.comment AS comment, co.date_created AS date_created, us.username AS username, us.url AS url, us.image AS image FROM 2d_posts_comments co, 2d_users us WHERE ((id_relation = ?) AND (co.id_user = us.id))";
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
    	                                        <a href="'.site_url('user/'.$row2->url.'/').'">'.$row2->username.'</a><span>'.$this->lang->line('about').' '.$time.'</span>
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

    public function getAllPosts($getPag = 0)
    {
        // Get all results (pagination)
        $sql = "SELECT id FROM 2d_posts WHERE status = 1";
        $query = $this->db->query($sql);
        $nbRows = $query->num_rows();
        // Get query result
        $sql = "SELECT po.id AS id, po.title AS title, po.url AS url, po.id_category AS id_category, po.content AS content, po.image AS image, po.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image, us.username AS username, us.url AS author_url
    			FROM 2d_posts po, 2d_posts_categories ca, 2d_users us
    			WHERE (ca.id = po.id_category) AND (po.author = us.id) AND (po.status = 1)
    			ORDER BY date_created DESC
    			LIMIT ?,?";
        $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('blog_pag')));
        if($query->num_rows() > 0) {
            $getCategoryPosts = '';
            foreach ($query->result() as $row) {
                $getCategoryPosts .= '<div class="col-sm-12">
                                        <a href="'.site_url('post/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            '.(!empty($row->image) ? '<img src="'.$row->image.'" class="thumb-img" alt="'.$row->title.'">' : '').'
                                        </a>
                                        <div class="container-mobile">
                                            <h2 class="h5"><a href="'.site_url('post/'.$row->url.'/').'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 50, '...').'</a> </h2>
                                            <span>'.$this->lang->line('by').' <a href="'.site_url('user/'.$row->author_url.'/').'">'.$row->username.'</a> | <a href="'.site_url('post/category/'.$row->cat_url.'/').'">'.$row->cat_title.'</a> | '.gmdate("M d, Y", strtotime($row->date_created)).'</span>
                                            <p>'.mb_strimwidth(strip_tags($row->content), 0, 300, '...').'</p>
                                            <a href="'.site_url('post/'.$row->url).'/" class="btn btn-inverse btn-sm">'.$this->lang->line('Read more').'</a>
                                            <hr>
                                        </div>
                                      </div>';
            }
            return array(
             'id'           => $row->id,
             'getBlocVideo' => $getCategoryPosts,
             'cat_title'    => $row->cat_title,
             'cat_url'      => $row->cat_url,
             'cat_image'    => $row->cat_image,
             'id_category'  => $row->id_category,
             'nbRows'       => $nbRows
             );
        } else {
            return array(
             'getBlocVideo' => null,
             'cat_title'    => null,
             'cat_url'      => null,
             'cat_image'    => null,
             'id_category'  => null,
             'nbRows'       => null
             );
        }
    }

    public function getCategoryPosts($getUrl, $getPag = 0)
    {
        // Get total result
        $sql = "SELECT po.id FROM 2d_posts po, 2d_posts_categories ca WHERE ((ca.url = ?) AND (ca.id = po.id_category) AND (po.status = 1))";
        $query = $this->db->query($sql, array($getUrl));
        $nbRows = $query->num_rows();
        // Get query results
        $sql = "SELECT po.id AS id, po.title AS title, po.url AS url, po.id_category AS id_category, po.content AS content, po.image AS image, po.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.description AS description, ca.image AS cat_image, us.username AS username, us.url AS author_url
    			FROM 2d_posts po, 2d_posts_categories ca, 2d_users us
    			WHERE ((ca.url = ?) AND (ca.id = po.id_category) AND (po.author = us.id) AND (po.status = 1))
    			ORDER BY date_created DESC
    			LIMIT ?,?";
        $query = $this->db->query($sql, array($getUrl, (int)$getPag, (int)$this->config->item('blog_pag')));
        if($query->num_rows() > 0) {
            $getCategoryPosts = '';
            foreach ($query->result() as $row) {
                $getCategoryPosts .= '<div class="col-sm-12">
                                        <a href="'.site_url('post/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            '.(!empty($row->image) ? '<img src="'.$row->image.'" class="thumb-img" alt="">' : '').'
                                        </a>
                                        <div class="container-mobile">
                                            <h2 class="h5"><a href="'.site_url('post/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 50, '...').'</a> </h2>
                                            <span>'.$this->lang->line('by').' <a href="'.site_url('user/'.$row->author_url.'/').'">'.$row->username.'</a> | <a href="'.site_url('post/category/'.$row->cat_url.'/').'">'.$row->cat_title.'</a> | '.gmdate("M d, Y", strtotime($row->date_created)).'</span>
                                            <p>'.mb_strimwidth(strip_tags($row->content), 0, 300, '...').'</p>
                                            <a href="'.site_url('post/'.$row->url).'/" class="btn btn-inverse btn-sm">'.$this->lang->line('Read more').'</a>
                                            <hr>
                                        </div>
                                      </div>';
            }
            return array(
             'id'           => $row->id,
             'getBlocVideo' => $getCategoryPosts,
             'cat_title'    => $row->cat_title,
             'cat_url'      => $row->cat_url,
             'description'  => $row->description,
             'cat_image'    => $row->cat_image,
             'id_category'  => $row->id_category,
             'nbRows'       => $nbRows
             );
        } else {
            return array(
             'getBlocVideo' => null,
             'cat_title'    => null,
             'cat_url'      => null,
             'description'  => null,
             'cat_image'    => null,
             'id_category'  => null,
             'nbRows'       => null
             );
        }
    }

    public function getKeywordPosts($getUrl, $getPag = 0)
    {
        $sql = "SELECT id, title, url, description, image FROM 2d_posts_keywords WHERE url = ?";
        $query = $this->db->query($sql, array($getUrl));
        if($row = $query->row()) {
            // Total of results (pagination)
            $sql = "SELECT id FROM 2d_posts WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%'";
            $query = $this->db->query($sql);
            $nbRows = $query->num_rows();
            // Query request
            $sql = "SELECT po.title AS title, po.url AS url, po.ids_keywords AS ids_keywords, po.content AS content, po.image AS image, po.date_created AS date_created, pc.title AS cat_title, pc.url AS cat_url, us.username AS username, us.url AS author_url
                    FROM 2d_posts po, 2d_posts_categories pc, 2d_users us
                    WHERE (po.id_category = pc.id) AND (po.author = us.id) AND (po.status = 1) AND (po.ids_keywords LIKE '%\"$row->id\"%')
                    ORDER BY date_created LIMIT ?,?";
            $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('blog_pag')));
            $getKeywordPosts = '';
            foreach ($query->result() as $result) {
                $getKeywordPosts .= '<div class="col-sm-12">
                                        <a href="'.site_url('post/'.$result->url).'/" class="image-popup" title="'.$result->title.'">
                                            '.(!empty($result->image) ? '<img src="'.$result->image.'" class="thumb-img" alt="'.$result->title.'">' : '').'
                                        </a>
                                        <div class="container-mobile">
                                            <h2 class="h5"><a href="'.site_url('post/'.$result->url.'/').'" title="'.$result->title.'">'.mb_strimwidth($result->title, 0, 50, '...').'</a> </h2>
                                            <span>'.$this->lang->line('by').' <a href="'.site_url('user/'.$result->author_url.'/').'">'.$result->username.'</a> | <a href="'.site_url('post/category/'.$result->cat_url.'/').'">'.$result->cat_title.'</a> | '.gmdate("M d, Y", strtotime($result->date_created)).'</span>
                                            <p>'.mb_strimwidth(strip_tags($result->content), 0, 300, '...').'</p>
                                            <a href="'.site_url('post/'.$result->url.'/').'" class="btn btn-inverse btn-sm">'.$this->lang->line('Read more').'</a>
                                            <hr>
                                        </div>
                                     </div>';
             }
             return array(
                'getKeywordPosts' => $getKeywordPosts,
                'key_title' => $row->title,
                'key_url' => $row->url,
                'description'  => $row->description,
                'key_image' => $row->image,
                'nbRows' => $nbRows
              );
        }
    }

    public function getLastPosts() {
        $sql = "SELECT title, url, content, image, date_created FROM 2d_posts WHERE status = 1 AND image != '' ORDER BY date_created DESC LIMIT 0, 6";
        $query = $this->db->query($sql, array());
        $getLastPosts = '';
        foreach ($query->result() as $row) {
            $date = date("F d, Y", strtotime($row->date_created));
            $getLastPosts .= '<div class="clearfix">
                                <a href="'.site_url('post/'.$row->url.'/').'"><img src="'.$row->image.'" class="thumb-tv img-rounded"></a>
                                <div class="widget-row">
                                    <a href="'.site_url('post/'.$row->url.'/').'">'.mb_strimwidth($row->title, 0, 50, '...').'</a>
                                    <small>'.$date.'</small>
                                </div>
                              </div>
                              <hr>';
        }
        return $getLastPosts;
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
}
