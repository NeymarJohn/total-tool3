<?php
class MenusModel extends CI_Model
{
    public function getMenus($idMenu = '') {
        $getMenus = '';
        $sql = "SELECT id, title FROM 2d_menus";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $select = ($idMenu === $row->id) ? 'selected' : '';
            $getMenus .= '<option value="'.$row->id.'" '.$select.'>'.$row->title.'</option>';
        }
        return $getMenus;
    }

    public function getDefaultPages($idMenu = '')
    {
        $getDefaultPages = '<li class="dd-item" data-menu="'.$idMenu.'" data-id="d:1">
                                <div class="dd-handle">
                                    '.$this->lang->line('Home').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:2">
                                <div class="dd-handle">
                                    '.$this->lang->line('Videos').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:3">
                                <div class="dd-handle">
                                    '.$this->lang->line('Posts').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:4">
                                <div class="dd-handle">
                                    '.$this->lang->line('Contact').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:5">
                                <div class="dd-handle">
                                    '.$this->lang->line('Languages').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:6">
                                <div class="dd-handle">
                                    '.$this->lang->line('Members').'
                                </div>
                            </li>
                            <li class="dd-item" data-menu="'.$idMenu.'" data-id="d:7">
                                <div class="dd-handle">
                                    '.$this->lang->line('Subscribe').'
                                </div>
                            </li>';
        return $getDefaultPages;
    }

    public function getCategories($idMenu = '')
    {
        $getCategories = '';
        $sql = "SELECT id, title FROM 2d_categories";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getCategories .= '<li class="dd-item" data-menu="'.$idMenu.'" data-id="c:'.$row->id.'">
                                    <div class="dd-handle">
                                        '.$row->title.'
                                    </div>
                                </li>';
        }
        return $getCategories;
    }

    public function getPages($idMenu = '')
    {
        $getPages = '';
        $sql = "SELECT id, title FROM 2d_pages";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getPages .= '<li class="dd-item" data-menu="'.$idMenu.'" data-id="p:'.$row->id.'">
                                    <div class="dd-handle">
                                        '.$row->title.'
                                    </div>
                                </li>';
        }
        return $getPages;
    }

    public function getPostCategories($idMenu = '')
    {
        $getPostCategories = '';
        $sql = "SELECT id, title FROM 2d_posts_categories";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $getPostCategories .= '<li class="dd-item" data-menu="'.$idMenu.'" data-id="pc:'.$row->id.'">
                                    <div class="dd-handle">
                                        '.$row->title.'
                                    </div>
                                </li>';
        }
        return $getPostCategories;
    }

    public function getMenu($id)
    {
        $sql = "SELECT id, title, ids_menu FROM 2d_menus WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        if($row = $query->row()) {
            $array = explode(',', $row->ids_menu);
            $title = $row->title;
            $getMenu = '';
            foreach ($array as $value) {
                $valueArray = explode('|', $value);
                if(count($valueArray) <= 1) { // category
                    $idArray = explode(':', $valueArray[0]);
                    if($idArray[0] === 'c') {
                        $sql = "SELECT id, title FROM 2d_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="c:'.$row1->id.'">
                                            <div class="dd-handle">
                                                '.$row1->title.'
                                            </div>
                                        </li>';
                        }
                    } elseif ($idArray[0] === 'p') {
                        $sql = "SELECT id, title FROM 2d_pages WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="p:'.$row1->id.'">
                                            <div class="dd-handle">
                                                '.$row1->title.'
                                            </div>
                                        </li>';
                        }
                    } elseif ($idArray[0] === 'pc') {
                        $sql = "SELECT id, title FROM 2d_posts_categories WHERE id = ?";
                        $query = $this->db->query($sql, array($idArray[1]));
                        if($row1 = $query->row()) {
                            $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="pc:'.$row1->id.'">
                                            <div class="dd-handle">
                                                '.$row1->title.'
                                            </div>
                                        </li>';
                        }
                    } elseif ($idArray[0] === 'd') {
                        if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); }
                        if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); }
                        if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); }
                        if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); }
                        if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); }
                        if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); }
                        if ($idArray[1] === '7') { $thisId = 7; $thisTitle = $this->lang->line('Subscribe'); }
                            $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="d:'.$thisId.'">
                                            <div class="dd-handle">
                                                '.$thisTitle.'
                                            </div>
                                        </li>';
                    }
                } else { // category with sub-categories
                    $i = 0;
                    $getSubMenu = '';
                    foreach ($valueArray as $key) {
                        $idArray = explode(':', $key);
                        $i++;
                        if($idArray[0] === 'c') {
                            $sql = "SELECT id, title FROM 2d_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="c:'.$row1->id.'">
                                                        <div class="dd-handle">
                                                            '.$row1->title.'
                                                        </div>
                                                    </li>';
                                } else {
                                    $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="c:'.$row1->id.'">
                                                    <div class="dd-handle">
                                                        '.$row1->title.'
                                                    </div>
                                                    <ol class="dd-list">
                                                        '.$getSubMenu.'
                                                    </ol>
                                                </li>';
                                }
                            }
                        } elseif($idArray[0] === 'p') {
                            $sql = "SELECT id, title FROM 2d_pages WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="p:'.$row1->id.'">
                                                        <div class="dd-handle">
                                                            '.$row1->title.'
                                                        </div>
                                                    </li>';
                                } else {
                                    $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="p:'.$row1->id.'">
                                                    <div class="dd-handle">
                                                        '.$row1->title.'
                                                    </div>
                                                    <ol class="dd-list">
                                                        '.$getSubMenu.'
                                                    </ol>
                                                </li>';
                                }
                            }
                        } elseif($idArray[0] === 'pc') {
                            $sql = "SELECT id, title FROM 2d_posts_categories WHERE id = ?";
                            $query = $this->db->query($sql, array($idArray[1]));
                            if($row1 = $query->row()) {
                                if(count($valueArray) !== $i) {
                                    $getSubMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="pc:'.$row1->id.'">
                                                        <div class="dd-handle">
                                                            '.$row1->title.'
                                                        </div>
                                                    </li>';
                                } else {
                                    $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="pc:'.$row1->id.'">
                                                    <div class="dd-handle">
                                                        '.$row1->title.'
                                                    </div>
                                                    <ol class="dd-list">
                                                        '.$getSubMenu.'
                                                    </ol>
                                                </li>';
                                }
                            }
                        } elseif($idArray[0] === 'd') {
                            if ($idArray[1] === '1') { $thisId = 1; $thisTitle = $this->lang->line('Home'); }
                            if ($idArray[1] === '2') { $thisId = 2; $thisTitle = $this->lang->line('Videos'); }
                            if ($idArray[1] === '3') { $thisId = 3; $thisTitle = $this->lang->line('Posts'); }
                            if ($idArray[1] === '4') { $thisId = 4; $thisTitle = $this->lang->line('Contact'); }
                            if ($idArray[1] === '5') { $thisId = 5; $thisTitle = $this->lang->line('Languages'); }
                            if ($idArray[1] === '6') { $thisId = 6; $thisTitle = $this->lang->line('Members'); }
                            if ($idArray[1] === '7') { $thisId = 7; $thisTitle = $this->lang->line('Subscribe'); }
                            if(count($valueArray) !== $i) {
                                $getSubMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="p:'.$thisId.'">
                                                    <div class="dd-handle">
                                                        '.$thisTitle.'
                                                    </div>
                                                </li>';
                            } else {
                                $getMenu .= '<li class="dd-item" data-menu="'.$row->id.'" data-id="p:'.$thisId.'">
                                                <div class="dd-handle">
                                                    '.$thisTitle.'
                                                </div>
                                                <ol class="dd-list">
                                                    '.$getSubMenu.'
                                                </ol>
                                            </li>';
                            }
                        }
                    }
                }
            }
        } else {
            $getMenu = NULL;
            $title = NULL;
        }
        return array(
            'getMenu' => $getMenu,
            'title' => $title
        );
    }

    public function addMenu($title)
    {
        $sql = "INSERT INTO 2d_menus (ids_menu, title, date_created, date_modified) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, array('d:1', $title, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        return $this->db->insert_id();
    }

    public function updateMenu($serialized, $id = '')
    {
        $sql = "SELECT id, title FROM 2d_menus WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        if($result = $query->row()) {
            $sql = "UPDATE 2d_menus SET title = ?, ids_menu = ?, date_modified = ? WHERE id = ?";
            $this->db->query($sql, array($result->title, $serialized, date("Y-m-d H:i:s"), $id));
            return $this->lang->line('Saved changes');
        } else {
            $sql = "INSERT INTO 2d_menus (title, ids_menu, date_created, date_modified) VALUES (?, ?, ?, ?)";
            $this->db->query($sql, array($result->title, $serialized, date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
            return $this->lang->line('Saved changes');
        }
    }

    public function deleteMenu($idMenu = '')
    {
        $sql = 'DELETE FROM 2d_menus WHERE id = ?';
        $this->db->query($sql, array($idMenu));
    }
}
