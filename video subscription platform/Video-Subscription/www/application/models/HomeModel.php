<?php
class HomeModel extends CI_Model
{
    public function getBlocsVideo($getOrder, $getLimit = 0, $getLimitTo = 10)
    {
        if($getOrder === 'rated') {
            $sql = "SELECT id, title, description, url, id_category, played, nb_favs, note, image, subscription, date_created FROM 2d_videos WHERE status = 1 ORDER BY note DESC LIMIT ?,?";
        } elseif($getOrder === 'news') {
            $sql = "SELECT id, title, description, url, id_category, played, nb_favs, note, image, subscription, date_created FROM 2d_videos WHERE status = 1 ORDER BY date_created DESC LIMIT ?,?";
        } elseif($getOrder === 'popular') {
            $sql = "SELECT id, title, description, url, id_category, played, nb_favs, note, image, subscription, date_created FROM 2d_videos WHERE status = 1 ORDER BY played DESC LIMIT ?,?";
        } else {
            $sql = "SELECT id, title, description, url, id_category, played, nb_favs, note, image, subscription, date_created FROM 2d_videos WHERE status = 1 ORDER BY title LIMIT ?,?";
        }
        $query = $this->db->query($sql, array((int)$getLimit, (int)$getLimitTo));
        $getBlocVideo = '';
        foreach ($query->result() as $row) {
            // Comparison of dates for displaying the new tab on the video
            $date_created = date_parse($row->date_created);
            $datetime1 = date_create($date_created['year'].'-'.$date_created['month'].'-'.$date_created['day']);
            $datetime2 = date_create(date("Y-m-d"));
            $interval = date_diff($datetime1, $datetime2);
            $time = $interval->format('%a');
            $classShow = ($time<=90)?'show':'';
            $getBlocVideo .= '<div class="item">
                                <div class="video-box m-b-20">
                                    <div class="video-image">
                                        <a href="'.site_url('video/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img" alt="'.$row->title.'">
                                            <span class="play-button"></span>
                                            <span class="info-video"></span>
                                            <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$row->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$row->played.'</span></span>
                                        </a>
                                    </div>
                                    <div class="video-description container-mobile">
                                        <h3><a href="'.site_url('video/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h3>
                                        <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                    </div>
                                </div>
                            </div>';
        }
        return $getBlocVideo;
    }

    public function getCategories($idCategory, $getLimit = 0, $getLimitTo = 10)
    {
        $sql = "SELECT id, title, description, url, id_category, played, nb_favs, note, image, subscription, date_created FROM 2d_videos WHERE id_category = ? AND status = 1 ORDER BY date_created DESC LIMIT ?,?";
        $query = $this->db->query($sql, array($idCategory, (int)$getLimit, (int)$getLimitTo));
        $getCategories = '';
        foreach ($query->result() as $row) {
            $getCategories .= '<div class="item">
                                <div class="video-box m-b-20">
                                    <div class="video-image">
                                        <a href="'.site_url('video/'.$row->url).'/" class="image-popup" title="'.$row->title.'">
                                            <img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-img" alt="'.$row->title.'">
                                            <span class="play-button"></span>
                                            <span class="info-video"></span>
                                            <span class="info-video-text"><span><i class="fa fa-heart"></i>'.$row->nb_favs.'</span><span><i class="fa fa-eye"></i>'.$row->played.'</span></span>
                                        </a>
                                    </div>
                                    <div class="video-description container-mobile">
                                        <h3><a href="'.site_url('video/'.$row->url).'" title="'.$row->title.'">'.mb_strimwidth($row->title, 0, 25, '...').'</a> '.(!empty($row->subscription) ? '<span class="label label-success pull-right">'.$this->lang->line('Subscribers').'</span>' : '<span class="label label-inverse pull-right">'.$this->lang->line('Free').'</span>').'</h3>
                                        <p>'.mb_strimwidth(strip_tags($row->description), 0, 180, '...').'</p>
                                    </div>
                                </div>
                            </div>';
        }
        return $getCategories;
    }

    public function getCategoriesTitle($id)
    {
        $sql = "SELECT title FROM 2d_categories WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return ($query->row()) ? $query->row() : FALSE;
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
