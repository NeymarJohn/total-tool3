<section id="video" class="p-b-30">
    <div class="row video-player">
        <div class="col-sm-12">
            <div class="player-background" style="background-image: url('<?php if(isset($image)) echo $image; ?>');">
                <div class="player-dark-background" data-id="<?php if(isset($id)) echo $id; ?>">
                    <div class="player-custom">
                        <div class="text-center">
                            <?php if($subscription === "1" && $this->session->subscriber !== true) { ?>
                                <div class="video-header text-center">
                                    <h2><?php echo $this->lang->line('Sorry, this video is only available to Subscribers') ?></h2>
                                    <div class="col-sm-12 col-md-4 col-md-offset-4">
                                        <a href="<?php echo ($this->session->url) ? site_url('user/subscription/'.$this->session->url.'/') : site_url('login/register/'); ?>" class="btn btn-red btn-slider waves-effect waves-light btn-lg"><?php echo $this->lang->line('Signup Now to Become Subscriber') ?></a>
                                        <?php if (isset($trailer)) { ?>
                                            <p class="m-t-20"><a href="#" id="watch-trailer">Watch Trailer</a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div id="vpl-trailer" class="embed-responsive embed-responsive-16by9" style="display:none">
                                    <video id="vpl-video-trailer">
                                        <source src="<?php if (isset($trailer)) echo $trailer; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            <?php } else { ?>
                                <?php if ($type === '1' || $type === '2' || $type === '3'|| $type === '4') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <div id="video-player"
                                            data-src="<?php if(isset($getVideoPlaylist['src'])) echo $getVideoPlaylist['src']; ?>"
                                            data-type="<?php if(isset($getVideoPlaylist['type'])) echo $getVideoPlaylist['type']; ?>"
                                            data-youtubeID="<?php if(isset($getVideoPlaylist['youtube'])) echo $getVideoPlaylist['youtube']; ?>"
                                            data-vimeoID="<?php if(isset($getVideoPlaylist['vimeo'])) echo $getVideoPlaylist['vimeo']; ?>"
                                            data-imageURL="<?php if(isset($getVideoPlaylist['image'])) echo $getVideoPlaylist['image']; ?>"
                                            data-cover="<?php if(isset($image)) echo $image; ?>"
                                            data-title="<?php if(isset($getVideoPlaylist['title'])) echo $getVideoPlaylist['title']; ?>"
                                            data-description="<?php if(isset($getVideoPlaylist['description'])) echo $getVideoPlaylist['description']; ?>"
                                            data-ads="<?php echo ($this->config->item('videoadsactive') === '1') ? 'yes' : 'no'; ?>"
                                            data-adsduration="<?php echo $this->config->item('adsduration'); ?>"
                                            data-adslink="<?php echo $this->config->item('adslink'); ?>"
                                            data-adsvideo="<?php echo $this->config->item('videoads'); ?>"
                                            data-color="<?php echo ((!empty($this->config->item('vplColor'))) ? $this->config->item('vplColor') : '#DE1212'); ?>">
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="video-responsive" src="<?php if(isset($embed)) echo $embed; ?>" width="1140" height="741" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container container-mobile">
        <div id="content" class="row">
			<div class="col-sm-12">
                <div class="video-title">
                    <div class="video-title-right">
                        <span><i class="fa fa-eye"></i> <?php if(isset($played)) echo $played; ?> <?php echo $this->lang->line('Views') ?></span>
                        <?php if(isset($this->session->id)) { ?>
                            <a href="<?php echo site_url('video/'.$url.'/?fav='.(($getFav === 1) ? 'del' : 'add')); ?>" class="btn btn-red waves-effect waves-light"> <i class="<?php echo ($getFav === 1) ? 'fa fa-heart' : 'fa fa-heart-o'; ?> m-r-5"></i><span> <?php echo ($getFav === 1) ? $this->lang->line('favorite')  : $this->lang->line('favorite') ?></span></a>
                        <?php } else { ?>
                            <button class="btn btn-red waves-effect waves-light sa-not-registed"><i class="fa fa-heart-o m-r-5"></i><span> <?php echo $this->lang->line('favorite'); ?></span></button>
                        <?php } ?>
                    </div>
                    <h1><b><?php if(isset($title_video)) echo $title_video; ?></b></h1>
                    <div id="<?php echo (isset($this->session->id)) ? 'rating' : 'nr-rating'; ?>" class="inline" data-score="<?php if(isset($getNote)) echo $getNote; ?>" data-video="<?php if(isset($id)) echo $id; ?>"></div> <small class="text-muted">(<?php if(isset($getNbNote)) echo $getNbNote; ?>)</small>
                </div>
                <hr>
                <p class="text-muted"><?php if(isset($description)) echo $description; ?></p>
                <div class="page-categories">
                    <?php echo $this->lang->line('Category :') ?> <span class="text-muted"><a href="<?php echo site_url('category/'.$url_category.'/'); ?>"><?php if(isset($category)) echo $category; ?></a></span>
                </div>
                <?php if(!empty($getKeywords)) { ?>
                    <div class="page-tags">
                        <?php echo $this->lang->line('Tags :') ?> <?php echo $getKeywords; ?>
                    </div>
                <?php } ?>
                <?php if(!empty($author)) { ?>
                    <h5 class="font-600"><?php echo $this->lang->line('author'); ?> :</h5>
                    <p class="text-muted"><?php echo $author; ?></p>
                <?php } ?>
                <?php if(isset($getPlaylists)) { ?>
                    <div>
                        <span class="page-playlist-text"><?php echo $this->lang->line('Playlists :') ?></span>
                        <button
                            type="button"
                            class="btn btn-link font-15 p-t-b-0"
                            data-container="body"
                            title=""
                            data-toggle="popover"
                            data-html="true"
                            data-placement="bottom"
                            data-content='<form method="post" action="<?php echo current_url(); ?>">
                                              <p><b>Add to :</b></p>
                                              <?php echo $getPlaylists; ?>
                                              <input type="submit" name="submitPlaylist" value="Submit" class="btn btn-red btn-block waves-effect waves-light">
                                          </form>'
                            data-original-title="">
                            <?php echo $this->lang->line('Add to playlist') ?>
                        </button>
                        <button
                           type="button"
                           class="btn btn-link font-15 p-t-b-0"
                           data-container="body"
                           title=""
                           data-toggle="popover"
                           data-html="true"
                           data-placement="bottom"
                           data-content='<form method="post" action="<?php echo current_url(); ?>">
                                             <p>Playlist Title</p>
                                             <div class="input-group m-t-10">
                                                 <input type="text" name="playlistTitle" class="form-control">
                                                 <span class="input-group-btn"><button type="submit" class="btn waves-effect waves-light btn-red">Submit</button></span>
                                             </div>
                                         </form>'
                           data-original-title="">
                           <?php echo $this->lang->line('Create new playlist') ?>
                       </button>
                    </div>
                <?php } ?>
                <i class="fa fa-exclamation-circle pull-right" data-toggle="popover" data-html="true" data-placement="left" data-content='<a href="<?php echo current_url().'/?report=1'; ?>"><?php echo $this->lang->line('This video does not work ?'); ?></a>'></i>
                <?php if($nbSaison) { ?>
                    <h4 class="p-t-20"><?php echo $this->lang->line('Episodes') ?></h4>
                    <div>
                        <div class="filter-row clearfix container-mobile">
                            <div class="form-group">
                                <select id="season" class="form-control">
                                    <?php if(isset($getSaison)) echo $getSaison; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <?php if(isset($getEpisodes)) echo $getEpisodes; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="p-t-b-20">
                    <h4><?php echo $this->lang->line('Share This Video') ?></h4>
                    <?php echo share(current_url(), (isset($title_video)) ? $title_video : '', (isset($description)) ? $description : ''); ?>
                </div>
                <hr>
            </div>
            <div class="col-sm-12 col-lg-9">
                <div class="row">
                    <div class="col-sm-12">
                        <h4><i class="fa fa-comment"></i> <span><?php if(isset($totalComs)) echo $totalComs ?> <?php echo $this->lang->line('Comments'); ?></span></h4>
                        <div id="comments-list">
                            <?php if(!empty($getBestComs['getComs'])) { ?>
                                <h3><?php echo $this->lang->line('bestComments'); ?></h3>
                                <?php echo $getBestComs['getComs']; ?>
                            <?php } ?>
                            <?php if(!empty($getComs)) { ?>
                                <h3><?php echo $this->lang->line('Comments'); ?></h3>
                                <?php echo $getComs; ?>
                                <div class="text-center"><?php if(isset($getPagination)) echo $getPagination; ?></div>
                            <?php } ?>
                        </div>
                        <h4 id="comments" class="p-t-20"><?php echo $this->lang->line('Leave a Reply') ?> <span id="cancelReply" style="display:none;">| <a href="#"><?php echo $this->lang->line('Cancel Reply') ?></a></span></h4>
                        <?php if(isset($this->session->id)) { ?>
                            <form method="post">
                                <span class="input-icon icon-right">
                                    <textarea rows="8" class="form-control textarea-box" name="com_message"></textarea>
                                </span>
                                <input id="related" type="hidden" name="related" value="">
                                <button type="submit" class="btn btn-sm btn-red waves-effect waves-light m-t-b-20"><?php echo $this->lang->line('Submit Comment') ?></button>
                            </form>
                        <?php } else { ?>
                            <div class="custom-box">
                                <span><?php echo $this->lang->line('loginForComment'); ?></span>
                            </div>
                        <?php } ?>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end content -->
			<div class="col-sm-12 col-lg-3 ">
    			<?php if(!empty($getUsersFav)) { ?>
                    <h4><?php echo $this->lang->line('theyLikeVideo'); ?></h4>
    				<div class="users-widget">
    				    <?php echo $getUsersFav; ?>
    				</div>
    			<?php } ?>
			</div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>

<script>
window.onload = function() {
    setTimeout(function(){
        var id = $('.player-dark-background').data("id");
        $.get("/video/updateVideoStat/"+id);
    }, 30000);
    $('#watch-trailer').click(function(event){
        event.preventDefault();
        $('#vpl-trailer, .video-header').toggle();
        var video = $('#vpl-video-trailer');
        video['0'].play();
    });
    $('.video-image').click(function () {
        $('.vpl-play-button').click();
        $('#mCSB_1 .vpl-item')[$(this).data('episode')].click();
        $('.video-title h1 b').text($(this).children().attr('title'));
    });
    $('.video-description h2').click(function () {
        $('.vpl-play-button').click();
        var episode = $(this).parent().prev().data('episode');
        $('#mCSB_1 .vpl-item')[episode].click();
        $('.video-title h1 b').text($('a', this).html());
    });
    $('#mCSB_1 .vpl-item').click(function () {
        $('.video-title h1 b').text($('.vpl-title', $(this)).text());
    });
    $('[class*=episode1]').show();
    $('#season').on('change', function () {
        var season = this.value;
        $('[class*=episode]').hide();
        $('[class*=episode' + season + ']').show();
    })
	$("a#reply").click(function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top
        }, 500);
        $('textarea.textarea-box').focus();
		var id = $(this).parent().parent().next().data("id");
		$("input#related").val(id);
        $('span#cancelReply').show();
	});
    $("span#cancelReply").click(function(event) {
        event.preventDefault();
        $("input#related").val('');
        $('span#cancelReply').hide();
	});
	$("a.finger-up").click(function(event) {
        event.preventDefault();
		var id = $(this).parent().data("id");
		$.get("/video/likesComs/"+id+"/1");
        var pos = $('.comment-footer[data-id="'+id+'"]');
		pos.children().filter('.finger-up').children().toggleClass('text-primary');
		pos.children().filter('.finger-down').children().removeClass('text-danger');
	});
	$("a.finger-down").click(function(event) {
        event.preventDefault();
		var id = $(this).parent().data("id");
		$.get("/video/likesComs/"+id+"/0");
        var pos = $('.comment-footer[data-id="'+id+'"]');
		pos.children().filter('.finger-down').children().toggleClass('text-danger');
		pos.children().filter('.finger-up').children().removeClass('text-primary');
	});
};
</script>
