<section id="post">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="post-title container-mobile">
                    <h1><b><?php if(isset($title_post)) echo $title_post ?></b></h1>
                    <span><?php echo $this->lang->line('by') ?> <a href="<?php echo site_url('user/'.$author.'/') ?>">Admin</a> | <a href="<?php echo site_url('post/category/'.$url_category.'/')?>"><?php if(isset($category)) echo $category ?></a> | <?php if(isset($post_date)) echo $post_date; ?></span>
                </div>
                <hr>
            </div>
            <div id="content" class="col-sm-12 col-lg-9">
                <img class="img-responsive p-b-20" src="<?php if(isset($image)) echo $image ?>" alt="<?php if(isset($title_post)) echo $title_post ?>">
                <div class="container-mobile">
                    <p><?php if(isset($content)) echo $content ?></p>
                    <?php if(isset($getKeywords)) echo $getKeywords ?>
                    <div class="p-t-20">
                        <h4><?php echo $this->lang->line('Share This Video'); ?></h4>
                        <?php echo share(current_url(), (isset($title_video)) ? $title_video : '', (isset($description)) ? $description : ''); ?>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4><i class="fa fa-comment"></i> <span><?php if(isset($totalComs)) echo $totalComs ?> <?php echo $this->lang->line('comments'); ?></span></h4>
                            <div id="comments-list">
                                <?php if(!empty($getBestComs['getComs'])) { ?>
                                    <h3><?php echo $this->lang->line('bestComments'); ?></h3>
                                    <?php echo $getBestComs['getComs']; ?>
                                <?php } ?>
                                <?php if(!empty($getComs)) { ?>
                                    <h3><?php echo $this->lang->line('comments'); ?></h3>
                                    <?php echo $getComs; ?>
                                    <div class="text-center"><?php if(isset($getPagination)) echo $getPagination; ?></div>
                                <?php } ?>
                            </div>
                            <h4 id="comments" class="p-t-20"><?php echo $this->lang->line('Leave a Reply'); ?> <span id="cancelReply" style="display:none;">| <a href="#"><?php echo $this->lang->line('Cancel Reply'); ?></a></span></h4>
                            <?php if(isset($this->session->id)) { ?>
                                <form method="post">
                                    <span class="input-icon icon-right">
                                        <textarea rows="8" class="form-control textarea-box" name="com_message"></textarea>
                                    </span>
                                    <input id="related" type="hidden" name="related" value="">
                                    <button type="submit" class="btn btn-sm btn-inverse waves-effect waves-light m-t-b-20"><?php echo $this->lang->line('Submit Comment'); ?></button>
                                </form>
                            <?php } else { ?>
                                <div class="custom-box">
                                    <span><?php echo $this->lang->line('loginForComment'); ?></span>
                                </div>
                            <?php } ?>
                        </div> <!-- end col -->
                    </div>
                </div>
            </div> <!-- end content -->
			<div id="sidebar" class="col-sm-12 col-lg-3">
                <div class="container-mobile">
                    <?php if(!empty($this->config->item('sidebartop'))) { ?>
                        <div class="widget">
                            <h4 class="h5"><?php echo $this->lang->line('Advertisement'); ?></h4>
                            <?php echo $this->config->item('sidebartop'); ?>
                        </div>
                    <?php } ?>
                    <div class="widget widget-latest">
                        <h4 class="h5"><?php echo $this->lang->line('RECENT POSTS'); ?></h4>
                        <?php if(isset($getLastPosts)) echo $getLastPosts; ?>
                    </div>
                    <?php if(!empty($this->config->item('facebookPageLink'))) { ?>
                        <div class="widget">
                            <h4 class="h5"><?php echo $this->lang->line('SOCIALIZE WITH US'); ?></h4>
                            <div class="fb-page" data-href="<?php echo $this->config->item('facebookPageLink'); ?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="<?php echo $this->config->item('facebookPageLink'); ?>" class="fb-xfbml-parse-ignore"><a href="<?php echo $this->config->item('facebookPageLink'); ?>"><?php echo $this->config->item('facebookPageName'); ?></a></blockquote></div>
                        </div>
                    <?php } ?>
                    <?php if(!empty($this->config->item('sidebarbottom'))) { ?>
                        <div class="widget">
                            <h4 class="h5"><?php echo $this->lang->line('Advertisement'); ?></h4>
                            <?php echo $this->config->item('sidebarbottom'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div> <!-- end content -->
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>

<script>
window.onload = function() {
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
		$.get("/post/likesComs/"+id+"/1");
		var pos = $(this);
		pos.children().toggleClass('text-primary');
		pos.next().children().removeClass('text-danger');
	});
	$("a.finger-down").click(function(event) {
        event.preventDefault();
		var id = $(this).parent().data("id");
		$.get("/post/likesComs/"+id+"/0");
		var pos = $(this);
		pos.children().toggleClass('text-danger');
		pos.prev().children().removeClass('text-primary');
	});
};
</script>
