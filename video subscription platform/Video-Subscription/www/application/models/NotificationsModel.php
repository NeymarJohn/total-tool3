<?php
class NotificationsModel extends CI_Model
{
    public function getTotalNotifications()
    {
        return $this->db->count_all('2d_notifications');
    }

    public function getAllNotifications($getPag)
    {
        $getNotifications = '';
        $sql = "SELECT type, new, id_relation, date_created, date_modified FROM 2d_notifications ORDER BY date_created DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array((int)$getPag, 20));
        foreach ($query->result() as $row) {
            if ($row->type === '0') {
                $type = $this->lang->line('Newsletter');
                $notification = $this->lang->line('New subscriber');
            } elseif ($row->type === '1') {
                $type = $this->lang->line('Report');
                $notification = '<a href="'.site_url('dashboard/videos/edit/'.$row->id_relation.'').'">'.$this->lang->line('This video has been reported').'</a>';
            } elseif ($row->type === '2') {
                $type = $this->lang->line('Member');
                $notification = $this->lang->line('New member');
            } elseif ($row->type === '3') {
                $type = $this->lang->line('Sale');
                $notification = $this->lang->line('New sale');
            } elseif ($row->type === '4') {
                $type = $this->lang->line('Subscriber');
                $notification = $this->lang->line('New subscriber');
            } elseif ($row->type === '5') {
                $type = $this->lang->line('Comment');
                $notification = $this->lang->line('New comment on post');
            } else {
                $type = '';
                $notification = '';
            }
            $new = ($row->new) ? '<span class="label label-table label-pink">'.$this->lang->line('New').'</span>' : '<span class="label label-table label-success">'.$this->lang->line('Saw').'</span>';
            $time = timespan(strtotime($row->date_created), time(), 1);
            $getNotifications .= '<tr>
                                      <td>'.$type.'</td>
                                      <td>'.$notification.'</td>
                                      <td>'.$new.'</td>
                                      <td>'.$time.'</td>
                                  </tr>';
        }
        return (isset($getNotifications)) ? $getNotifications : FALSE;
    }

    public function updateStatus()
    {
        $sql = "UPDATE 2d_notifications SET new = ? WHERE new = ?";
        $this->db->query($sql, array(0, 1));
        return $this->db->affected_rows();
    }
}
