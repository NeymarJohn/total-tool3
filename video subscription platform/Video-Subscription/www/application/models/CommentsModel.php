<?php
class CommentsModel extends CI_Model
{
    public function getTotalComs()
    {
        return $this->db->count_all('2d_comments');
    }

    public function getFiltersComs($status = 1)
    {
        $this->db->where('status', $status);
        $this->db->from('2d_comments');
        return $this->db->count_all_results();
    }

    public function getComments($status = 0)
    {
        $getComments = '';
        $statusReq = ($status === 1 || $status === 2) ? 'AND co.status = ?' : '';
        $sql = "SELECT co.id AS id, co.comment AS comment, co.id_video AS id_video, co.status AS status, co.date_created AS date_created, co.ip AS ip, us.id AS id_user, us.username AS username, ga.title AS title, ga.url AS url
                FROM 2d_comments co, 2d_users us, 2d_videos ga
                WHERE co.id_user = us.id AND co.id_video = ga.id $statusReq
                ORDER BY date_created DESC";
        $query = $this->db->query($sql, array($status));
        foreach ($query->result() as $row) {
            if($row->status === '1') {
                $status = '<span class="label label-table label-inverse">'.$this->lang->line('pending').'</span>';
            } elseif ($row->status === '2') {
                $status = '<span class="label label-table label-danger">'.$this->lang->line('spam').'</span>';
            } elseif ($row->status === '3') {
                $status = '<span class="label label-table label-success">'.$this->lang->line('approved').'</span>';
            }
            $uploaded = timespan(strtotime($row->date_created), time(), 1);
            $getComments .= '<tr>
            					<td>'.$row->id.'</td>
            					<td>'.$row->username.'</td>
                                <td>'.mb_strimwidth($row->title, 0, 25, '...').' (video)</td>
                                <td>'.$uploaded.'</td>
                                <td>'.mb_strimwidth($row->comment, 0, 200, '...').'</td>
                                <td>'.$status.'</td>
                                <td>'.$row->ip.'</td>
                                <td class="text-right">
                                    <a class="btn btn-icon waves-effect btn-success waves-light btn-xs" href="'.current_url().'?id='.$row->id.'&status=3&type=0"><i class="fa fa-thumbs-o-up"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-pink waves-light btn-xs" href="'.current_url().'?id='.$row->id.'&status=2&type=0"><i class="fa fa-thumbs-o-down"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('video/'.$row->url.'/?type=0').'"><i class="fa fa-search"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/comments/edit/'.$row->id.'/?type=0').'"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/comments/?del='.$row->id.'&id='.$row->id_user.'&type=0').'"><i class="fa fa-trash-o"></i></a>
                                </td>
            				</tr>';
        }
        return $getComments;
    }

    public function getPostComments($getComments = '', $status = 0)
    {
        $statusReq = ($status === 1 || $status === 2) ? 'AND co.status = ?' : '';
        $sql = "SELECT co.id AS id, co.comment AS comment, co.id_post AS id_post, co.status AS status, co.date_created AS date_created, co.ip AS ip, us.id AS id_user, us.username AS username, po.title AS title, po.url AS url
                FROM 2d_posts_comments co, 2d_users us, 2d_posts po
                WHERE co.id_user = us.id AND co.id_post = po.id $statusReq
                ORDER BY date_created DESC";
        $query = $this->db->query($sql, array($status));
        foreach ($query->result() as $row) {
            if($row->status === '1') {
                $status = '<span class="label label-table label-inverse">'.$this->lang->line('pending').'</span>';
            } elseif ($row->status === '2') {
                $status = '<span class="label label-table label-danger">'.$this->lang->line('spam').'</span>';
            } elseif ($row->status === '3') {
                $status = '<span class="label label-table label-success">'.$this->lang->line('approved').'</span>';
            }
            $uploaded = timespan(strtotime($row->date_created), time(), 1);
            $getComments .= '<tr>
            					<td>'.$row->id.'</td>
            					<td>'.$row->username.'</td>
                                <td>'.mb_strimwidth($row->title, 0, 25, '...').' (post)</td>
                                <td>'.$uploaded.'</td>
                                <td>'.mb_strimwidth($row->comment, 0, 200, '...').'</td>
                                <td>'.$status.'</td>
                                <td class="text-right">
                                    <a class="btn btn-icon waves-effect btn-success waves-light btn-xs" href="'.current_url().'?id='.$row->id.'&status=3&type=1"><i class="fa fa-thumbs-o-up"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-pink waves-light btn-xs" href="'.current_url().'?id='.$row->id.'&status=2&type=1"><i class="fa fa-thumbs-o-down"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-primary waves-light btn-xs" href="'.site_url('post/'.$row->url.'/').'"><i class="fa fa-search"></i> </a>
                                    <a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/comments/edit/'.$row->id.'/?type=1').'"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/comments/?del='.$row->id.'&id='.$row->id_user.'&type=1').'"><i class="fa fa-trash-o"></i></a>
                                </td>
            				</tr>';
        }
        return $getComments;
    }

    public function getUsers($idUser = '')
    {
        $getUsers = '';
        $sql = "SELECT id, username FROM 2d_users";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($idUser === $row->id) ? 'selected' : '';
            $getUsers .= '<option value="'.$row->id.'" '.$select.'>'.$row->username.'</option>';
        }
        return $getUsers;
    }

    public function getVideos($idVideo = '')
    {
        $getVideos = '';
        $sql = "SELECT id, title FROM 2d_videos";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($idVideo === $row->id) ? 'selected' : '';
            $getVideos .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getVideos;
    }

    public function getPosts($idPost = '')
    {
        $getVideos = '';
        $sql = "SELECT id, title FROM 2d_posts";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($idPost === $row->id) ? 'selected' : '';
            $getVideos .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getVideos;
    }

    public function getComment($idComment, $type)
    {
        if ($type === '0') {
            $sql = "SELECT comment, id_user, id_video AS id_type, status, ip, date_created FROM 2d_comments WHERE id = ?";
        } elseif ($type === '1') {
            $sql = "SELECT comment, id_user, id_post AS id_type, status, ip, date_created FROM 2d_posts_comments WHERE id = ?";
        }
        $query = $this->db->query($sql, array($idComment));
        if($result = $query->row()) {
            $timestamp = strtotime($result->date_created);
            $date = gmdate("M d, Y", $timestamp);
            return array(
             'comment' => $result->comment,
             'id_type' => $result->id_type,
             'id_user' => $result->id_user,
             'status' => $result->status,
             'ip' => $result->ip,
             'date_created' => $date
             );
        } else {
            return null;
        }
    }

    public function addComment($postAuthor, $postComment, $postVideo, $status)
    {
        $sql = "INSERT INTO 2d_comments (id_user, comment, id_video, status, date_created, date_modified, ip) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, array($postAuthor, $postComment, $postVideo, $status, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $this->input->ip_address()));
        $msg = alert($this->lang->line('The comment was created').' <a href="/dashboard/comments/edit/'.$this->db->insert_id().'/?type=0">'.$this->lang->line('Edit it').'</a> !');
        $this->updateComs($postAuthor);
        return $msg;
    }

    public function editComment($idComment, $postAuthor, $postComment, $postVideo, $status, $type = 0)
    {
        if ($type === '0') {
            $sql = "UPDATE 2d_comments SET id_user = ?, comment = ?, id_video = ?, status = ? WHERE id = ?";
        } elseif ($type === '1') {
            $sql = "UPDATE 2d_posts_comments SET id_user = ?, comment = ?, id_post = ?, status = ? WHERE id = ?";
        }
        $this->db->query($sql, array($postAuthor, $postComment, $postVideo, $status, $idComment));
        $msg = alert($this->lang->line('Saved changes'));
        return $msg;
    }

    public function changeStatus($idComment, $status, $type)
    {
        if ($type === '0') {
            $sql = "UPDATE 2d_comments SET status = ? WHERE id = ?";
        } elseif ($type === '1') {
            $sql = "UPDATE 2d_posts_comments SET status = ? WHERE id = ?";
        }
        $this->db->query($sql, array($status, $idComment));
        return alert($this->lang->line('Saved changes'));
    }

    public function banUser($idUser)
    {
        $sql = "UPDATE 2d_users SET status = ? WHERE id = ?";
        $this->db->query($sql, array(2, $idUser));
        return alert($this->lang->line('User Banned'));
    }

    public function delComment($idComment, $idUser, $type)
    {
        if ($type === '0') {
            $sql = 'DELETE FROM 2d_comments WHERE id = ?';
        } elseif ($type === '1') {
            $sql = 'DELETE FROM 2d_posts_comments WHERE id = ?';
        }
        $this->db->query($sql, array($idComment));
        $this->updateComs($idUser);
    }

    public function updateComs($idUser)
    {
        $sql = 'SELECT id FROM 2d_comments WHERE id_user = ?';
        $query = $this->db->query($sql, array($idUser));
        $sql = "UPDATE 2d_users SET nb_coms = ? WHERE id = ?";
        $this->db->query($sql, array($query->num_rows(), $idUser));
    }
}
