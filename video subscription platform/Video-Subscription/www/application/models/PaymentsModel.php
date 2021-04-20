<?php
class PaymentsModel extends CI_Model
{
    public function getPayments()
    {
        $getPayments = '';
        $sql = "SELECT pa.id AS id, pa.price AS price, pa.currency AS currency, pa.type AS type, pa.subscription_id AS subscription_id, pa.date_created AS date_created, pa.date_end AS date_end, pa.status AS status, pa.ip AS ip, us.username AS username FROM 2d_payments pa, 2d_users us WHERE (pa.id_user = us.id)";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $type = ($row->type === '1') ? 'Subscription' : 'Payment';
            $timestamp = strtotime($row->date_created);
            $date_created = gmdate("M d, Y", $timestamp);
            $timestamp = strtotime($row->date_end);
            $date_end = gmdate("M d, Y", $timestamp);
            if ($row->status === 'succeeded') {
                $status = '<span class="label label-success">'.$row->status.'</span>';
            } elseif ($row->status === 'active') {
                $status = '<span class="label label-primary">'.$row->status.'</span>';
            } elseif ($row->status === 'canceled') {
                $status = '<span class="label label-pink">'.$row->status.'</span>';
            } elseif ($row->status === 'trialing') {
                $status = '<span class="label label-purple">'.$row->status.'</span>';
            } else {
                $status = '<span class="label label-info">'.$row->status.'</span>';
            }
            $getPayments .= '<tr class="text-center">
            					<td>'.$row->id.'</td>
            					<td>'.$row->username.'</td>
            					<td>'.$row->currency.' '.$row->price.'</td>
                                <td>'.$type.'</td>
                                <td>'.$row->subscription_id.'</td>
                                <td>'.$date_created.'</td>
                                <td>'.$date_end.'</td>
                                <td>'.$status.'</td>
            					<td>'.$row->ip.'</td>
            					<td>
            						<a class="btn btn-icon waves-effect btn-default waves-light btn-xs" href="'.site_url('dashboard/payments/edit/'.$row->id.'/').'"> <i class="fa fa-pencil"></i> </a>
            						<a class="btn btn-icon waves-effect btn-danger waves-light btn-xs" href="'.site_url('dashboard/payments/?del='.$row->id.'').'"> <i class="fa fa-trash-o"></i> </a>
            					</td>
            				</tr>';
        }
        return $getPayments;
    }

    public function getPayment($idPayment)
    {
        $sql = "SELECT pa.type AS type, pa.subscription_id AS subscription_id, pa.price AS price, pa.currency AS currency, pa.status AS status, pa.ip AS ip, pa.date_created AS date_created, pa.date_modified AS date_modified, pa.date_end AS date_end, pa.trial_start AS trial_start, pa.trial_end AS trial_end, us.username AS username
                FROM 2d_payments pa, 2d_users us
                WHERE pa.id = ? AND pa.id_user = us.id";
        $query = $this->db->query($sql, array($idPayment));
        return ($query->row()) ? $query->row() : FALSE;
    }

    public function delPayment($idPayment)
    {
        $sql = 'DELETE FROM 2d_payments WHERE id = ?';
        $this->db->query($sql, array($idPayment));
    }
}
