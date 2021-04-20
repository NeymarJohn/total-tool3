<?php
class AutoloadModel extends CI_Model
{
    public function getMenu($id)
    {
        $sql = "SELECT id, title, ids_menu FROM 2d_menus WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        if($row = $query->row()) {
            $array = explode(',', $row->ids_menu);
            $getMenu = '';
            foreach ($array as $value) {
                $valueArray = explode('|', $value);
                if(count($valueArray) <= 1) { // category
                    $idArray = explode(':', $valueArray[0]);
                    if($idArray[0] === 'c') {
                        $sql = "SELECT id, title, url FROM 2d_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'p') {
                        $sql = "SELECT id, title, url FROM 2d_pages WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('page/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'pc') {
                        $sql = "SELECT id, title, url FROM 2d_posts_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('post/category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'd') {
                        if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); $url = site_url(); }
                        if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); $url = site_url('videos/'); }
                        if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); $url = site_url('posts/'); }
                        if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); $url = site_url('contact/'); }
                        if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); $url = '#'; }
                        if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); $url = site_url('members/'); }
                        if ($idArray[1] === '7') { $thisId = 7; $thisTitle = (!$this->config->item('demo')) ? $this->lang->line('Subscribe') : $this->lang->line('Landing page'); $url = site_url('subscribe/'); }
                        // Add exception for dropdown language menu
                        if ($idArray[1] === '5') {
                            $getMenu .= '<li class="dropdown">
                                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$thisTitle.' <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="'.site_url('Linkswitch/switchLang/english').'" class="dropdown-toggle waves-effect waves-light"><span class="f16"><span class="flag us"></span></span>'.$this->lang->line('English').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/french').'" class="dropdown-toggle waves-effect waves-light"><span class="f16"><span class="flag fr"></span></span>'.$this->lang->line('French').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/spanish').'" class="dropdown-toggle waves-effect waves-light"><span class="f16"><span class="flag es"></span></span>'.$this->lang->line('Spanish').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/swedish').'" class="dropdown-toggle waves-effect waves-light"><span class="f16"><span class="flag se"></span></span>'.$this->lang->line('Swedish').'</a></li>
                                            </ul>
                                        </li>';
                        } else {
                            $getMenu .= '<li><a href="'.$url.'" class="dropdown-toggle waves-effect waves-light">'.$thisTitle.'</a></li>';
                        }
                    }
                } else { // category with sub-categories
                    $i = 0;
                    $getSubMenu = '';
                    foreach ($valueArray as $key) {
                        $idArray = explode(':', $key);
                        $i++;
                        if($idArray[0] === 'c') {
                            $sql = "SELECT id, title, url FROM 2d_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="dropdown">
                    								<a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="caret"></span></a>
                    								<ul class="dropdown-menu">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'p') {
                            $sql = "SELECT id, title, url FROM 2d_pages WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="dropdown">
                    								<a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="caret"></span></a>
                    								<ul class="dropdown-menu">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'pc') {
                            $sql = "SELECT id, title, url FROM 2d_posts_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('post/category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="dropdown">
                    								<a href="'.site_url('post/category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="caret"></span></a>
                    								<ul class="dropdown-menu">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'd') {
                            if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); $url = site_url(); }
                            if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); $url = site_url('videos/'); }
                            if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); $url = site_url('posts/'); }
                            if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); $url = site_url('contact/'); }
                            if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); $url = '#'; }
                            if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); $url = site_url('members/'); }
                            if ($idArray[1] === '7') { $thisId = 7; $thisTitle = (!$this->config->item('demo')) ? $this->lang->line('Subscribe') : $this->lang->line('Landing page'); $url = site_url('subscribe/'); }
                            if(count($valueArray) !== $i) {
                                $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="dropdown-toggle waves-effect waves-light">'.$row1->title.'</a></li>';
                            } else {
                                $getMenu .= '<li class="dropdown">
                                                <a href="'.$url.'" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    '.$getSubMenu.'
                                                </ul>
                                            </li>';
                            }
                        }
                    }
                }
            }
        } else {
            $getMenu = NULL;
        }
        return $getMenu;
    }

    public function getMobileMenu($id)
    {
        $sql = "SELECT id, title, ids_menu FROM 2d_menus WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        if($row = $query->row()) {
            $array = explode(',', $row->ids_menu);
            $getMenu = '<li class="text-muted menu-title">' . $row->title . '</li>';
            foreach ($array as $value) {
                $valueArray = explode('|', $value);
                if(count($valueArray) <= 1) { // category
                    $idArray = explode(':', $valueArray[0]);
                    if($idArray[0] === 'c') {
                        $sql = "SELECT id, title, url FROM 2d_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'p') {
                        $sql = "SELECT id, title, url FROM 2d_pages WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('page/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'pc') {
                        $sql = "SELECT id, title, url FROM 2d_posts_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li><a href="'.site_url('post/category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                        }
                    } elseif ($idArray[0] === 'd') {
                        if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); $url = site_url(); }
                        if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); $url = site_url('videos/'); }
                        if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); $url = site_url('posts/'); }
                        if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); $url = site_url('contact/'); }
                        if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); $url = '#'; }
                        if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); $url = site_url('members/'); }
                        if ($idArray[1] === '7') { $thisId = 7; $thisTitle = (!$this->config->item('demo')) ? $this->lang->line('Subscribe') : $this->lang->line('Landing page'); $url = site_url('subscribe/'); }
                        // Add exception for dropdown language menu
                        if ($idArray[1] === '5') {
                            $getMenu .= '<li class="has_sub">
                                            <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$thisTitle.' <span class="caret"></span></a>
                                            <ul class="list-unstyled">
                                                <li><a href="'.site_url('Linkswitch/switchLang/english').'" class="dropdown-toggle waves-effect waves-light">'.$this->lang->line('English').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/french').'" class="dropdown-toggle waves-effect waves-light">'.$this->lang->line('French').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/spanish').'" class="dropdown-toggle waves-effect waves-light">'.$this->lang->line('Spanish').'</a></li>
                                                <li><a href="'.site_url('Linkswitch/switchLang/swedish').'" class="dropdown-toggle waves-effect waves-light">'.$this->lang->line('Swedish').'</a></li>
                                            </ul>
                                        </li>';
                        } else {
                            $getMenu .= '<li><a href="'.$url.'" class="dropdown-toggle waves-effect waves-light">'.$thisTitle.'</a></li>';
                        }
                    }
                } else { // category with sub-categories
                    $i = 0;
                    $getSubMenu = '';
                    foreach ($valueArray as $key) {
                        $idArray = explode(':', $key);
                        $i++;
                        if($idArray[0] === 'c') {
                            $sql = "SELECT id, title, url FROM 2d_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="has_sub">
                    								<a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="caret"></span></a>
                    								<ul class="list-unstyled">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'p') {
                            $sql = "SELECT id, title, url FROM 2d_pages WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="has_sub">
                    								<a href="javascript:void(0);" class="waves-effect" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span class="menu-arrow"></span></a>
                    								<ul class="list-unstyled">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'pc') {
                            $sql = "SELECT id, title, url FROM 2d_posts_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li><a href="'.site_url('post/category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                                } else {
                                    $getMenu .= '<li class="has_sub">
                    								<a href="javascript:void(0);" class="waves-effect" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span <span class="menu-arrow"></span>></span></a>
                    								<ul class="list-unstyled">
                    									'.$getSubMenu.'
                    								</ul>
                    							</li>';
                                }
                            }
                        } elseif($idArray[0] === 'd') {
                            if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); $url = site_url(); }
                            if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); $url = site_url('videos/'); }
                            if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); $url = site_url('posts/'); }
                            if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); $url = site_url('contact/'); }
                            if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); $url = '#'; }
                            if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); $url = site_url('members/'); }
                            if ($idArray[1] === '7') { $thisId = 7; $thisTitle = (!$this->config->item('demo')) ? $this->lang->line('Subscribe') : $this->lang->line('Landing page'); $url = site_url('subscribe/'); }
                            if(count($valueArray) !== $i) {
                                $getSubMenu .= '<li><a href="'.site_url('category/'.$row1->url.'/').'" class="waves-effect">'.$row1->title.'</a></li>';
                            } else {
                                $getMenu .= '<li class="has_sub">
                                                <a href="javascript:void(0);" class="waves-effect" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$row1->title.' <span <span class="menu-arrow"></span>></span></a>
                                                <ul class="list-unstyled">
                                                    '.$getSubMenu.'
                                                </ul>
                                            </li>';
                            }
                        }
                    }
                }
            }
        } else {
            $getMenu = NULL;
        }
        return $getMenu;
    }

    public function getNotifications($getNotifications = '')
    {
        $nbNotifications = 0;
        for ($type = 0; $type <= 5; $type++) {
            $sql = "SELECT type, new, date_created, date_modified FROM 2d_notifications WHERE type = ? AND new = ? ORDER BY date_created DESC";
            $query = $this->db->query($sql, array($type, TRUE));
            $numRows = $query->num_rows();
            if ($type === 0) {
                $class = 'fa fa-envelope-o noti-purple';
                $titleNotification ='New user(s) in newsletter';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new user(s) in list';
            } elseif ($type === 1) {
                $class = 'fa fa-thumb-tack noti-warning';
                $titleNotification ='New video(s) reported';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new video(s) reported';
            } elseif ($type === 2) {
                $class = 'fa fa-user-plus noti-success';
                $titleNotification ='New member(s)';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new member(s)';
            } elseif ($type === 3) {
                $class = 'fa fa-cart-plus noti-pink';
                $titleNotification ='New sale(s)';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new sale(s)';
            } elseif ($type === 4) {
                $class = 'fa fa-credit-card noti-primary  ';
                $titleNotification ='New subscriber(s)';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new subscriber(s)';
            } elseif ($type === 5) {
                $class = 'fa fa-comments-o noti-custom';
                $titleNotification ='New comment(s)';
                $msgNotification ='There are <span class="text-primary font-600">'.$numRows.'</span> new comment(s)';
            }
            if ($query->num_rows() >= 1) {
                $getNotifications .= '<a href="javascript:void(0);" class="list-group-item">
                                           <div class="media">
                                              <div class="pull-left p-r-10">
                                                 <em class="'.$class.'"></em>
                                              </div>
                                              <div class="media-body">
                                                 <h5 class="media-heading">'.$titleNotification.'</h5>
                                                 <p class="m-0">
                                                     <small>'.$msgNotification.'</small>
                                                 </p>
                                              </div>
                                           </div>
                                        </a>';
                $nbNotifications = $nbNotifications + $numRows;
            }
        }
        return array(
            'getNotifications' => $getNotifications,
            'nbNotifications' => $nbNotifications
        );
    }

    public function setThemeSession()
    {
        if ($this->config->item('demo')) {
            if (!$this->session->theme) {
                $this->session->set_userdata('theme', 'default');
            }
        } else {
            if ($this->config->item('theme') === 'darktheme') {
                $this->session->set_userdata('theme', 'darktheme');
            } else {
                $this->session->set_userdata('theme', 'default'); 
            }
        }
    }
}
