<section id="category">
    <div class="row category-head" style="background-image: url('<?php echo (!empty($cat_image) ? $cat_image : '/assets/images/polygon.png'); ?>');">
        <div class="<?php if(!empty($cat_image)) echo 'category-dark-background'; ?>">
            <div class="col-sm-12">
                <div class="container">
                    <div class="category-title container-mobile">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-md-offset-4">
                                <form action="<?php echo current_url().'/'; ?>" method="get">
                                    <div class="input-group">
                                        <input type="text" name="q" class="form-control input-lg" placeholder="<?php echo $this->lang->line('Search within these results'); ?>" value="<?php echo $this->input->get('q', true) ?>">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-lg waves-effect waves-light btn-black input-lg"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if($this->uri->segment(1) === 'videos') { ?>
                            <h1><?php echo $this->lang->line('All the videos') ?></h1>
                        <?php } else { ?>
                            <h1><?php if(isset($cat_title)) echo $cat_title; ?></h1>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
                <?php if(!$this->input->get('q')) { ?>
                    <div class="filter-row clearfix container-mobile">
                    	<span><?php echo $this->lang->line('Sort by:') ?></span>
                        <div class="form-group">
                            <select id="order-select" class="form-control">
                                <?php if ($this->uri->segment(1) === 'videos') { ?>
                                    <option value="<?php echo site_url('videos/');?>" <?php echo (!$this->uri->segment(2)) ? 'selected' : ''; ?>><?php echo $this->lang->line('Date') ?></option>
                                    <option value="<?php echo site_url('videos/popular/');?>" <?php echo ($this->uri->segment(2) === 'popular') ? 'selected' : ''; ?>><?php echo $this->lang->line('Views') ?></option>
                                    <option value="<?php echo site_url('videos/rated/');?>" <?php echo ($this->uri->segment(2) === 'rated') ? 'selected' : ''; ?>><?php echo $this->lang->line('Rating') ?></option>
                                    <option value="<?php echo site_url('videos/title/');?>" <?php echo ($this->uri->segment(2) === 'title') ? 'selected' : ''; ?>><?php echo $this->lang->line('Title') ?></option>
                                    <option value="<?php echo site_url('videos/favorites/');?>" <?php echo ($this->uri->segment(2) === 'favorites') ? 'selected' : ''; ?>><?php echo $this->lang->line('Favorites') ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo site_url('category/'.$cat_url.'/');?>" <?php echo (!$this->uri->segment(3)) ? 'selected' : ''; ?>><?php echo $this->lang->line('Date') ?></option>
                                    <option value="<?php echo site_url('category/'.$cat_url.'/popular/');?>" <?php echo ($this->uri->segment(3) === 'popular') ? 'selected' : ''; ?>><?php echo $this->lang->line('Views') ?></option>
                                    <option value="<?php echo site_url('category/'.$cat_url.'/rated/');?>" <?php echo ($this->uri->segment(3) === 'rated') ? 'selected' : ''; ?>><?php echo $this->lang->line('Rating') ?></option>
                                    <option value="<?php echo site_url('category/'.$cat_url.'/title/');?>" <?php echo ($this->uri->segment(3) === 'title') ? 'selected' : ''; ?>><?php echo $this->lang->line('Title') ?></option>
                                    <option value="<?php echo site_url('category/'.$cat_url.'/favorites/');?>" <?php echo ($this->uri->segment(3) === 'favorites') ? 'selected' : ''; ?>><?php echo $this->lang->line('Favorites') ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <a href="<?php echo current_url().($this->input->get('sort') ? '/' : '/?sort=inverse'); ?>"><i class="<?php echo ($this->input->get('sort')) ? 'fa fa-sort-amount-desc' : 'fa fa-sort-amount-asc'; ?>"></i></a>
                        <div class="filter-btn">
                            <button id="grid-show"><i class="fa fa-th-list"></i></button>
                			<button id="row-show" class="active"><i class="fa fa-th"></i></button>
                        </div>
                    </div>
                <?php } else { ?>
                    <h2><?php echo $this->lang->line('Search results for:') ?> <?php echo ucfirst($this->input->get('q', true)) ?></h2>
                    <hr>
                    <?php if($nbRows < 1) { ?>
                        <p><?php echo $this->lang->line('No results found') ?></p>
                    <?php } ?>
                <?php } ?>
                <div class="row">
                    <div id="video-column" class="clearfix">
                        <?php if(isset($getBlocVideo)) echo $getBlocVideo; ?>
                    </div>
                    <div id="video-row" class="clearfix">
                        <?php if(isset($getBlocVideo)) echo $getBlocVideo; ?>
                    </div>
                </div>
				<div class="row">
					<div class="col-sm-12">
						<div class="text-right container-mobile">
							<?php if(isset($pagination)) echo $pagination; ?>
						</div>
					</div> <!-- end col -->
                </div> <!-- end row -->
                <?php if(!empty($this->config->item('sidebarcontent'))) { ?>
				    <div class="row">
					    <div class="col-sm-12">
                          <?php echo $this->config->item('sidebarcontent'); ?>
                        </div>
                    </div>
                <?php } ?>
			</div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>

<script type="text/javascript">
    window.onload = function() {
        // style view
        $('#video-row').children().attr('class', 'col-sm-12 video-row');
        $('#video-row .video-image').attr('class', 'col-sm-3 video-image');
        $('#video-row .video-description').attr('class', 'col-sm-9 video-description');
        $('.video-row').append('<hr>');
        // events
        $('#order-select').change(function(){
            window.location.href = $(this).val();
        });
        $('#grid-show, #row-show').click(function(){
            $('#video-row, #video-column').toggle();
            $('#row-show, #grid-show').toggleClass('active');
        });
    };
</script>
