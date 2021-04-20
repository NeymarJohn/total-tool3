<?php
class NewslettersModel extends CI_Model
{
    public function getNewsletters()
    {
        $getNewsletters = '';
        $sql = "SELECT id, email, is_member, status, ip, date_created, date_modified FROM 2d_newsletter ORDER BY date_modified";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $isMember = ($row->is_member === '1') ? '<span class="label label-table label-success">'.$this->lang->line('Member').'</span>' : '<span class="label label-table label-inverse">'.$this->lang->line('Visitor').'</span>';
            $status = ($row->status === '1') ? '<span class="label label-table label-success">'.$this->lang->line('Active').'</span>' : '<span class="label label-table label-inverse">'.$this->lang->line('Inactive').'</span>';
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $getNewsletters .= '<tr class="text-center">
            					<td>'.$row->id.'</td>
            					<td>'.((!$this->config->item('demo')) ? $row->email : 'demo@coffeetheme.com').'</td>
            					<td>'.$isMember.'</td>
                                <td>'.$status.'</td>
                                <td>'.$row->ip.'</td>
                                <td>'.$date_created.'</td>
            					<td>
            						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/newsletters/edit/'.$row->id.'/').'"> <i class="fa fa-pencil"></i> </a>
            						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/newsletters/?del='.$row->id.'').'"> <i class="fa fa-trash-o"></i> </a>
            					</td>
            				</tr>';
        }
        return $getNewsletters;
    }

    public function getNewsletter($id)
    {
        $sql = "SELECT id, email, is_member, status, ip, date_created, date_modified FROM 2d_newsletter WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return ($query->row()) ? $query->row() : FALSE;
    }

    public function editNewsletter($id, $post)
    {
        $sql = "UPDATE 2d_newsletter SET email = ?, status = ? WHERE id = ?";
        $this->db->query($sql, array($post['email'], $post['status'], $id));
        return TRUE;
    }

    public function delEmail($idEmail)
    {
        $sql = 'DELETE FROM 2d_newsletter WHERE id = ?';
        $this->db->query($sql, array($idEmail));
    }
}
