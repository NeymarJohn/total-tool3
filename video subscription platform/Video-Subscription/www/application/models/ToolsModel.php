<?php
class ToolsModel extends CI_Model
{
    public function deleteAccounts()
    {
        $sql = 'DELETE FROM 2d_users WHERE status = ?';
        $this->db->query($sql, array(0));
    }

    public function deleteStats()
    {
        $date = date("Y-m-d", strtotime("-1 month", time()));
        $sql = 'DELETE FROM 2d_stats WHERE date_created <= ?';
        $this->db->query($sql, array($date));
    }

    public function getTotalTaskActivity()
    {
        $sql = "SELECT id FROM 2d_stats_cron";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function getTaskActivity($getPag = 0)
    {
        $sql = "SELECT id, message, date_created FROM 2d_stats_cron ORDER BY date_created DESC LIMIT ?, ?";
        $query = $this->db->query($sql, array((int)$getPag, 10));
        $getTaskActivity = '';
        foreach ($query->result() as $row) {
            $time = timespan(strtotime($row->date_created), time(), 1);
            $getTaskActivity .= '<tr>
                                    <td>'.$row->id.'</td>
                                    <td>'.$row->message.'</td>
                                    <td><span class="label label-table label-success">'.$this->lang->line('Complete').'</span></td>
                                    <td>'.$time.'</td>
                                </tr>';
        }
        return $getTaskActivity;
    }
}
