<?php
class DashboardModel extends CI_Model
{
    public function getNbActiveSubscriptions()
    {
        $sql = "SELECT price FROM 2d_payments WHERE type = 1 AND (status = ? OR status = ?)";
        $query = $this->db->query($sql, array('active', 'trialing'));
        $count = 0;
        $price = 0;
        foreach ($query->result() as $row) {
            $count = $query->num_rows();
            $price = $price + $row->price;
        }
        return array(
            'count' => $count,
            'price' => $price
        );
    }
    public function getNbDayIncomes()
    {
        $sql = "SELECT price FROM 2d_payments WHERE DATE_FORMAT(date_created, '%M %d %Y') = DATE_FORMAT(?, '%M %d %Y')";
        $query = $this->db->query($sql, array(date("Y-m-d")));
        $count = 0;
        $price = 0;
        foreach ($query->result() as $row) {
            $count = $query->num_rows();
            $price = $price + $row->price;
        }
        return array(
            'count' => $count,
            'price' => $price
        );
    }

    public function getNbMonthIncomes()
    {
        $sql = "SELECT price FROM 2d_payments WHERE Month(date_created) = Month(?) AND Year(date_created) = Year(?)";
        $query = $this->db->query($sql, array(date("Y-m-d"), date("Y-m-d")));
        $count = 0;
        $price = 0;
        foreach ($query->result() as $row) {
            $count = $query->num_rows();
            $price = $price + $row->price;
        }
        return array(
            'count' => $count,
            'price' => $price
        );
    }

    public function getNbIncomes()
    {
        $sql = "SELECT price FROM 2d_payments";
        $query = $this->db->query($sql);
        $count = 0;
        $price = 0;
        foreach ($query->result() as $row) {
            $count = $query->num_rows();
            $price = $price + $row->price;
        }
        return array(
            'count' => $count,
            'price' => $price
        );
    }

    public function getNbMembers()
    {
        $sql = "SELECT id FROM 2d_users";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function getStatsMembers()
    {
        $sql = "SELECT date_created FROM 2d_users";
        $query = $this->db->query($sql);
        $nbsDay = array(0,1,2,3,4,5,6,7,8,9,10);
        foreach ($query->result() as $row) {
            // Comparaison des dates
            $dateCreated = date_parse($row->date_created);
            $dateTime1 = date_create($dateCreated['year'].'-'.$dateCreated['month'].'-'.$dateCreated['day']);
            $dateTime2 = date_create(date("Y-m-d"));
            $interval = date_diff($dateTime1, $dateTime2);
            $nbDay = $interval->format('%a');
            if($nbDay <= 10) {
                $nbsDay[] = $nbDay;
            }
        }
        $nbsDay = array_count_values($nbsDay);
        $statsMembers = ""; $i=0;
        foreach ($nbsDay as $key) {
            if($i == 10) {
                $seg = '';
            } else {
                $seg = ',';
            }
            $key -= 1; $i++;
            $statsMembers .= $key.$seg;
        }
        return $statsMembers;
    }

    public function getNbComments()
    {
        $sql = "SELECT id FROM 2d_comments";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function getStatsComments()
    {
        $sql = "SELECT date_created FROM 2d_comments";
        $query = $this->db->query($sql);
        $nbsDay = array(0,1,2,3,4,5,6,7,8,9,10);
        foreach ($query->result() as $row) {
            // Comparaison des dates
            $dateCreation = date_parse($row->date_created);
            $dateTime1 = date_create($dateCreation['year'].'-'.$dateCreation['month'].'-'.$dateCreation['day']);
            $dateTime2 = date_create(date("Y-m-d"));
            $interval = date_diff($dateTime1, $dateTime2);
            $nbDay = $interval->format('%a');
            if($nbDay <= 10) {
                $nbsDay[] = $nbDay;
            }
        }
        $nbsDay = array_count_values($nbsDay);
        $statsComments = ""; $i=0;
        foreach ($nbsDay as $key) {
            if($i == 10) {
                $seg = '';
            } else {
                $seg = ',';
            }
            $key -= 1; $i++;
            $statsComments .= $key.$seg;
        }
        return $statsComments;
    }

    public function getLocationMembers()
    {
        $date = new DateTime();
        $date->modify('-1 year');
        $formatDate = $date->format('Y-m-d');
        $sql = "SELECT country_code, value FROM 2d_stats_location WHERE Year(date_created) = Year('$formatDate')";
        $query = $this->db->query($sql);
        $lastValues = $query->result();
        $date->modify('+1 year');
        $formatDate = $date->format('Y-m-d');
        $sql = "SELECT country_code, country_name, value FROM 2d_stats_location WHERE Year(date_created) = Year('$formatDate') ORDER BY value DESC LIMIT 0,5";
        $query = $this->db->query($sql);
        $output = '';
        foreach ($query->result() as $row) {
            $spanClass = 'text-custom';
            $class = 'fa fa-caret-up text-success';
            foreach ($lastValues as $lastValue) {
                if ($row->country_code === $lastValue->country_code) {
                    $spanClass = ($lastValue->value <= $row->value) ? 'text-custom' : 'text-danger';
                    $class = ($lastValue->value <= $row->value) ? 'fa fa-caret-up text-success' : 'fa fa-caret-down text-alert';
                }
            }
            $output .= '<tr>
                            <td><span class="f32"><span class="flag '.strtolower($row->country_code).'"></span></span></td>
                            <td>'.$row->country_name.'</td>
                            <td><span class="'.$spanClass.'"><i class="'.$class.' m-r-5"></i>'.$row->value.'</span></td>
                        </tr>';
        }
        return $output;
    }

    public function getTotalLocationMembers()
    {
        $sql = "SELECT country_code, country_name, value FROM 2d_stats_location ORDER BY value DESC LIMIT 0,5";
        $query = $this->db->query($sql);
        $array = array();
        foreach ($query->result() as $row) {
            $array[$row->country_code]['value'] = (isset($array[$row->country_code]['value'])) ? $array[$row->country_code]['value'] + $row->value : $row->value;
            $array[$row->country_code]['country_name'] = $row->country_name;
            $array[$row->country_code]['country_code'] = $row->country_code;
        }
        $output = '';
        foreach ($array as $row) {
            $output .= '<tr>
                            <td><span class="f32"><span class="flag '.strtolower($row['country_code']).'"></span></span></td>
                            <td>'.$row['country_name'].'</td>
                            <td><span class="text-custom">'.$row['value'].'</span></td>
                        </tr>';
        }
        return $output;
    }

    public function getInvoices()
    {
        $sql = "SELECT id, type, subscription_id, status, date_created, date_end, price FROM 2d_payments ORDER BY date_created DESC LIMIT 0,8";
        $query = $this->db->query($sql);
        $getInvoices = '';
        foreach ($query->result() as $row) {
            $type = ($row->type === '1') ? 'Subscription' :'Payment';
            $dateEnd = ($row->type === '1') ? gmdate("M d, Y", strtotime($row->date_end)) :gmdate("M d, Y", strtotime($row->date_created));
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
            $getInvoices .= '<tr>
                                <td>'.$row->id.'</td>
                                <td>'.$type.'</td>
                                <td>'.$row->subscription_id.'</td>
                                <td>'.gmdate("M d, Y", strtotime($row->date_created)).'</td>
                                <td>'.$dateEnd.'</td>
                                <td>'.$status.'</td>
                                <td>'.$row->price.'</td>
                            </tr>';
        }
        return $getInvoices;
    }

    public function getActivity($attribut = 2)
    {
        $date = new DateTime();
        $arrayStats = array();
        for ($i = 0; $i <= 10; $i++) {
            ($i != 0 ) ? $newDate = $date->modify('-1 day') : $newDate = $date;
            $newDate = $date->format('Y-m-d');
            $sql = "SELECT value FROM 2d_stats WHERE date_created = ? AND attribut = ?";
            $query = $this->db->query($sql, array($newDate, $attribut));
            if($result = $query->row()) {
                $arrayStats[] = $result->value;
            } else {
                $arrayStats[] = 0;
            }
        }
        $stats = ""; $i=0;
        foreach ($arrayStats as $key) {
            $seg = ($i !== 10) ? ',' : '';
            $i++;
            $stats .= $key.$seg;
        }
        return $stats;
    }

    public function getNbPlayed()
    {
        $sql = "SELECT played FROM 2d_videos";
        $query = $this->db->query($sql);
        $played = 0;
        foreach ($query->result() as $row) {
            $played += $row->played;
        }
        return $played;
    }

    public function getDaysStats($attribut = 1)
    {
        $date = new DateTime();
        $arraySales = array();
        for ($i = 0; $i <= 10; $i++) {
            ($i != 0 ) ? $newDate = $date->modify('-1 day') : $newDate = $date;
            $newDate = $date->format('Y-m-d');
            $sql = "SELECT value FROM 2d_stats WHERE date_created = ? AND attribut = ?";
            $query = $this->db->query($sql, array($newDate, $attribut));
            if($result = $query->row()) {
                $arraySales[] = $result->value;
            } else {
                $arraySales[] = 0;
            }
        }
        $statsSales = ""; $i=0;
        foreach ($arraySales as $key) {
            $seg = ($i !== 10) ? ',' : '';
            $i++;
            $statsSales .= $key.$seg;
        }
        return $statsSales;
    }

    public function getMonthsStats($attribut = 1)
    {
        $date = new DateTime();
        $arraySales = array();
        for ($i = 0; $i <= 11; $i++) {
            ($i != 0 ) ? $newDate = $date->modify('-1 month') : $newDate = $date;
            $newDate = $date->format('Y-m-d');
            $sql = "SELECT value FROM 2d_stats WHERE attribut = ? AND Month(date_created) = Month('$newDate') AND Year(date_created) = Year('$newDate')";
            $query = $this->db->query($sql, array($attribut));
            $sumValues = 0;
            foreach ($query->result() as $row) {
                $sumValues = $sumValues + $row->value;
            }
            $arraySales[] = $sumValues;
        }
        $statsSales = ""; $i=0;
        foreach ($arraySales as $key) {
            $seg = ($i !== 11) ? ',' : '';
            $i++;
            $statsSales .= $key.$seg;
        }
        return $statsSales;
    }

    public function getLastVideosAdded()
    {
        $getLastVideosAdded = '';
        $sql = "SELECT ga.id AS id, ga.title AS videoTitle, ga.url AS url, ga.image AS image, ca.title AS catTitle FROM 2d_videos ga, 2d_categories ca WHERE ga.id_category = ca.id ORDER BY ga.date_created DESC LIMIT 0,5";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getLastVideosAdded .=
             '<tr>
				<td><img src="'.(empty($row->image) ? site_url('assets/images/default-video.jpg') : $row->image).'" class="thumb-sm pull-left m-r-10 img-circle" alt=""> '.$row->videoTitle.' </td>
				<td>'.$row->catTitle.'</td>
				<td>
					<a href="'.site_url('dashboard/videos/edit/'.$row->id).'" class="table-action-btn"><i class="md md-edit"></i></a>
				</td>
			</tr>';
        }
        return $getLastVideosAdded;
    }

    public function getLastcomments()
    {
        $getLastcomments = '';
        $sql = "SELECT co.id AS id, co.comment AS comment, ga.title AS title, us.image AS image FROM 2d_comments co, 2d_videos ga, 2d_users us WHERE co.id_user = us.id AND co.id_video = ga.id ORDER BY co.date_created DESC LIMIT 0,5";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getLastcomments .=
             '<tr>
				<td><img src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" class="thumb-sm pull-left m-r-10 img-circle" alt=""> '.character_limiter($row->comment, 30).' </td>
				<td>'.$row->title.'</td>
				<td>
					<a href="'.site_url('dashboard/comments/edit/'.$row->id).'" class="table-action-btn"><i class="md md-edit"></i></a>
				</td>
			</tr>';
        }
        return $getLastcomments;
    }
}
