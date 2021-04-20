<?php
class CategoryModel extends CI_Model
{
    public function getBlocsVideo($getUrl, $getOrder, $getPag)
    {
        // Total of results in this category (pagination)
        $sql = "SELECT ca.id FROM 2d_videos ga, 2d_categories ca WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))";
        $query = $this->db->query($sql, array($getUrl));
        $nbRows = $query->num_rows();
        // Query requests for each filter (rated, date, popular, title)
        if($getOrder === 'rated') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY note $order
				LIMIT ?,?";
        } elseif($getOrder === 'title') {
            $order = ($this->input->get('sort')) ? 'DESC' : 'ASC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY title $order
				LIMIT ?,?";
        } elseif($getOrder === 'popular') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY played $order
				LIMIT ?,?";
        } elseif($getOrder === 'favorites') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY nb_favs $order
				LIMIT ?,?";
        } else {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.url = ?) AND (ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY date_created $order
				LIMIT ?,?";
        }
        $query = $this->db->query($sql, array($getUrl, (int)$getPag, (int)$this->config->item('cat_pag')));
        if($query->num_rows() > 0) {
            $getBlocVideo = '';
            foreach ($query->result() as $row) {
                // Comparison of dates for displaying the new tab on the video
                $date_created = date_parse($row->date_created);
                $datetime1 = date_create($date_created['year'].'-'.$date_created['month'].'-'.$date_created['day']);
                $datetime2 = date_create(date("Y-m-d"));
                $interval = date_diff($datetime1, $datetime2);
                $time = $interval->format('%a');
                $classShow = ($time <= 90) ? 'show' : '';
                $getBlocVideo .= '<div class="col-sm-12 col-md-6 col-lg-4 p-b-20">
                                      <div class="video-image">
        								  <a href="'.site_url('video/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                              <span class="play-button"></span>
                                              <span class="info-video"></span>
                                              <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$row->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$row->played.'</span></span>
                                              <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img" alt="">
        								  </a>
                                      </div>
                                      <div class="video-description container-mobile">
        								  <h2 class="h5"><a href="'.site_url('video/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h2>
                                          <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                       </div>
    								</div>';
            }
            return array(
             'getBlocVideo' => $getBlocVideo,
             'cat_title'    => $row->cat_title,
             'cat_url'      => $row->cat_url,
             'cat_image'    => $row->cat_image,
             'id_category'  => $row->id_category,
             'nbRows'       => $nbRows
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function getAllVideos($getOrder, $getPag)
    {
        // Total of results (pagination)
        $sql = "SELECT ca.id AS id FROM 2d_videos ga, 2d_categories ca WHERE ((ca.id = ga.id_category) AND (ga.status = 1))";
        $query = $this->db->query($sql);
        $nbRows = $query->num_rows();
        // Query requests for each filter (rated, news, popular, title)
        if($getOrder === 'rated') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY note $order
				LIMIT ?,?";
        } elseif($getOrder === 'title') {
            $order = ($this->input->get('sort')) ? 'DESC' : 'ASC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY title $order
				LIMIT ?,?";
        } elseif($getOrder === 'popular') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY played $order
				LIMIT ?,?";
        } elseif($getOrder === 'favorites') {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY nb_favs $order
				LIMIT ?,?";
        } else {
            $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
            $sql = "SELECT ga.id AS id, ga.title AS title, ga.url AS url, ga.id_category AS id_category, ga.description AS description, ga.played AS played, ga.nb_favs AS nb_favs, ga.image AS image, ga.note AS note, ga.subscription AS subscription, ga.date_created AS date_created, ca.title AS cat_title, ca.url AS cat_url, ca.image AS cat_image
				FROM 2d_videos ga, 2d_categories ca
				WHERE ((ca.id = ga.id_category) AND (ga.status = 1))
				ORDER BY date_created $order
				LIMIT ?,?";
        }
        $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('cat_pag')));
        if($query->num_rows() > 0) {
            $getBlocVideo = '';
            foreach ($query->result() as $row) {
                // Comparison of dates for displaying the new tab on the video
                $date_created = date_parse($row->date_created);
                $datetime1 = date_create($date_created['year'].'-'.$date_created['month'].'-'.$date_created['day']);
                $datetime2 = date_create(date("Y-m-d"));
                $interval = date_diff($datetime1, $datetime2);
                $time = $interval->format('%a');
                $classShow = ($time <= 90) ? 'show' : '';
                $getBlocVideo .= '<div class="col-sm-12 col-md-6 col-lg-4 p-b-20">
                                    <div class="video-image">
    									<a href="'.site_url('video/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            <span class="play-button"></span>
                                            <span class="info-video"></span>
                                            <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$row->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$row->played.'</span></span>
                                            <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img" alt="">
                                        </a>
                                    </div>
                                    <div class="video-description container-mobile">
    									<h2 class="h5"><a href="'.site_url('video/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h2>
                                        <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                    </div>
								</div>';
            }
            return array(
             'getBlocVideo' => $getBlocVideo,
             'cat_title'    => $row->cat_title,
             'cat_url'      => $row->cat_url,
             'cat_image'    => $row->cat_image,
             'id_category'  => $row->id_category,
             'nbRows'       => $nbRows
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function search($postSearch, $getPag, $idCategory = FALSE, $searchAll = TRUE)
    {
        if($searchAll) {
            $sql = "SELECT title, url, description, image, played, nb_favs, subscription FROM 2d_videos WHERE (status = 1) AND (title LIKE '%$postSearch%' OR url LIKE '%$postSearch%' OR description LIKE '%$postSearch%') GROUP BY title LIMIT ?, ?";
            $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('cat_pag')));
        } else {
            $sql = "SELECT title, url, description, image, played, nb_favs, subscription FROM 2d_videos WHERE (id_category = ?) AND (status = 1) AND (title LIKE '%$postSearch%' OR url LIKE '%$postSearch%' OR description LIKE '%$postSearch%') GROUP BY title LIMIT ?, ?";
            $query = $this->db->query($sql, array($idCategory, (int)$getPag, (int)$this->config->item('cat_pag')));
        }
        $nbRows = $query->num_rows();
        $getBlocVideo = '';
        foreach ($query->result() as $row) {
            $getBlocVideo .= '<div class="col-sm-12 col-md-6 col-lg-4 p-b-20">
                                    <div class="video-box">
                                        <a href="'.site_url('video/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            <span class="play-button"></span>
                                            <span class="info-video"></span>
                                            <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$row->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$row->played.'</span></span>
                                            <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img" alt="">
                                        </a>
                                        <div class="video-description container-mobile">
                                            <h2 class="h5"><a href="'.site_url('video/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h2>
                                            <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                        </div>
                                    </div>
                                </div>';
        }
        return array(
         'getBlocVideo' => $getBlocVideo,
         'nbRows' => $nbRows
         );
    }

    public function getNote($idVideo)
    {
        $sql = "SELECT note FROM 2d_notes WHERE id_video = ?";
        $query = $this->db->query($sql, array($idVideo));
        if($query->num_rows() > 0) {
            $note = 0;
            $i = 0;
            foreach ($query->result() as $row) {
                $note = $note + $row->note;
                $i++;
            }
            $note = $note / $i;
        } else {
            $note = 0;
        }
        return $note;
    }
}
