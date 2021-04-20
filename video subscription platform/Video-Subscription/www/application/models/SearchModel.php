<?php
class SearchModel extends CI_Model
{
    public function search($postSearch, $postPageVideo, $postPagePost, $postPageUser)
    {
        // Number of videos results
        $nbVideos = $this->db->select('title, url, description');
        $nbVideos = $this->db->from('2d_videos');
        $nbVideos = $this->db->like('title', $postSearch);
        $nbVideos = $this->db->or_like('url', $postSearch);
        $nbVideos = $this->db->or_like('description', $postSearch);
        $nbVideos = $this->db->get();
        $nbVideos = $nbVideos->num_rows();
        // Result for pagination
        $query = $this->db->select('title, url, description');
        $query = $this->db->from('2d_videos');
        $query = $this->db->like('title', $postSearch);
        $query = $this->db->or_like('url', $postSearch);
        $query = $this->db->or_like('description', $postSearch);
        $query = $this->db->limit(10, $postPageVideo);
        $query = $this->db->get();
        $getSearchVideos = '';
        foreach ($query->result() as $row) {
            $getSearchVideos .=
             '<div class="search-item">
					<h3 class="h5 font-600 m-b-5"><a href="'.site_url('video/'.$row->url.'').'">'.$row->title.'</a></h3>
					<div class="font-13 text-success m-b-10">
						'.site_url('video/'.$row->url.'/').'
					</div>
					<p>'.mb_strimwidth(strip_tags($row->description), 0, 300, '...').'</p>
				</div>';
        }
        // Number of posts results
        $nbPosts = $this->db->select('title, url, content');
        $nbPosts = $this->db->from('2d_posts');
        $nbPosts = $this->db->like('title', $postSearch);
        $nbPosts = $this->db->or_like('url', $postSearch);
        $nbPosts = $this->db->or_like('content', $postSearch);
        $nbPosts = $this->db->get();
        $nbPosts = $nbPosts->num_rows();
        // Result for pagination
        $query = $this->db->select('title, url, content');
        $query = $this->db->from('2d_posts');
        $query = $this->db->like('title', $postSearch);
        $query = $this->db->or_like('url', $postSearch);
        $query = $this->db->or_like('content', $postSearch);
        $query = $this->db->limit(10, $postPageVideo);
        $query = $this->db->get();
        $getSearchPosts = '';
        foreach ($query->result() as $row) {
            $getSearchPosts .=
             '<div class="search-item">
					<h3 class="h5 font-600 m-b-5"><a href="'.site_url('post/'.$row->url.'').'">'.$row->title.'</a></h3>
					<div class="font-13 text-success m-b-10">
						'.site_url('post/'.$row->url.'/').'
					</div>
					<p>'.mb_strimwidth(strip_tags($row->content), 0, 300, '...').'</p>
				</div>';
        }
        // Number of users results
        $nbUsers = $this->db->select('username, url, image, about');
        $nbUsers = $this->db->from('2d_users');
        $nbUsers = $this->db->like('url', $postSearch);
        $nbUsers = $this->db->or_like('username', $postSearch);
        $nbUsers = $this->db->or_like('about', $postSearch);
        $nbUsers = $this->db->get();
        $nbUsers = $nbUsers->num_rows();
        // Result for pagination
        $query = $this->db->select('username, url, image, about');
        $query = $this->db->from('2d_users');
        $query = $this->db->like('url', $postSearch);
        $query = $this->db->or_like('username', $postSearch);
        $query = $this->db->or_like('about', $postSearch);
        $query = $this->db->limit(10, $postPageUser);
        $query = $this->db->get();
        $getSearchUsers = '';
        foreach ($query->result() as $row) {
            $getSearchUsers .=
             '<div class="search-item">
					<div class="media">
						<div class="media-left">
							<a href="'.site_url('/user/'.$row->url.'/').'"> <img class="media-object img-circle" alt="'.$row->username.'" src="'.(empty($row->image) ? site_url('assets/images/default-user.png') : site_url('uploads/images/users/'.$row->image)).'" style="width: 64px; height: 64px;"> </a>
						</div>
						<div class="media-body">
							<h4 class="media-heading"><a href="'.site_url('/user/'.$row->url.'/').'">'.$row->username.'</a></h4>
							<p>
								<b>'.$this->lang->line('About:').'</b>
								<br/>
								<span class="text-muted">'.$row->about.'</span>
							</p>
						</div>
					</div>
				</div>';
        }
        return array(
         'getSearchVideos' => $getSearchVideos,
         'nbVideos' => $nbVideos,
         'getSearchPosts' => $getSearchPosts,
         'nbPosts' => $nbPosts,
         'getSearchUsers' => $getSearchUsers,
         'nbUsers' => $nbUsers
         );
    }
}
