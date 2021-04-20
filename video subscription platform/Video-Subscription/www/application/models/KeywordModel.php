<?php
class KeywordModel extends CI_Model
{
    public function getBlocsVideo($getUrl, $getOrder, $getPag)
    {
        $sql = "SELECT id, title, url, image FROM 2d_keywords WHERE url = ?";
        $query = $this->db->query($sql, array($getUrl));
        if($row = $query->row()) {
            // Total of results (pagination)
            $sql = "SELECT id FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%'";
            $query = $this->db->query($sql);
            $nbRows = $query->num_rows();
            // Query requests for each filter (rated, news, popular, title)
            if($getOrder === 'rated') {
                $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
                $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs, subscription FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%' ORDER BY note $order LIMIT ?,?";
            } elseif($getOrder === 'title') {
                $order = ($this->input->get('sort')) ? 'DESC' : 'ASC';
                $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs, subscription FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%' ORDER BY title $order LIMIT ?,?";
            } elseif($getOrder === 'popular') {
                $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
                $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs, subscription FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%' ORDER BY played $order LIMIT ?,?";
            } elseif($getOrder === 'favorites') {
                $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
                $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs, subscription FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%' ORDER BY nb_favs $order LIMIT ?,?";
            } else {
                $order = ($this->input->get('sort')) ? 'ASC' : 'DESC';
                $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs, subscription FROM 2d_videos WHERE status = 1 AND ids_keywords LIKE '%\"$row->id\"%' ORDER BY date_created $order LIMIT ?,?";
            }
            $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('key_pag')));
            $getBlocVideo = '';
            foreach ($query->result() as $result) {
                $getBlocVideo .= '<div class="col-sm-12 col-md-6 col-lg-4 p-b-20">
                                    <div class="video-image">
    									<a href="'.site_url('video/'.$result->url).'/" class="image-popup" title="'.$result->title.'">
                                            <span class="play-button"></span>
                                            <span class="info-video"></span>
                                            <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$result->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$result->played.'</span></span>
                                            <img src="'.(empty($result->image) ? site_url('assets/images/default-video.jpg') : $result->image).'" class="thumb-img" alt="">
    									</a>
                                    </div>
                                    <div class="video-description container-mobile">
    									<h2 class="h5"><a href="'.site_url('video/'.$result->url).'" title="'.$result->title.'">'.mb_strimwidth($result->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h2>
                                        <p>'.mb_strimwidth(strip_tags($result->description), 0, 180, '...').'</p>
                                    </div>
								</div>';
            }
            return array(
                'getBlocVideo' => $getBlocVideo,
                'key_title' => $row->title,
                'key_url' => $row->url,
                'key_image' => $row->image,
                'id_keyword'  => $row->id,
                'nbRows' => $nbRows
             );
        } else {
            show_404($page = '', $log_error = false);
        }
    }

    public function search($postSearch, $idKeyword, $getPag)
    {
        // Total of results (pagination)
        $sql = "SELECT id FROM 2d_videos WHERE (status = 1) AND (ids_keywords LIKE '%\"$idKeyword\"%') AND (title LIKE '%$postSearch%' OR url LIKE '%$postSearch%' OR description LIKE '%$postSearch%')";
        $query = $this->db->query($sql);
        $nbRows = $query->num_rows();
        // Query request
        $sql = "SELECT title, url, ids_keywords, description, image, played, nb_favs FROM 2d_videos WHERE (status = 1) AND (ids_keywords LIKE '%\"$idKeyword\"%') AND (title LIKE '%$postSearch%' OR url LIKE '%$postSearch%' OR description LIKE '%$postSearch%') GROUP BY title LIMIT ?, ?";
        $query = $this->db->query($sql, array((int)$getPag, (int)$this->config->item('key_pag')));
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
                                            <p>'.mb_strimwidth($row->description, 0, 180, '...').'</p>
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
