<form class="form" role="form" method="post" action="/settings/seo/">
    <?php if(isset($msg)) echo $msg; ?>
    <div class="card-box">
        <div class="row">
           	<div class="col-sm-12">
				<h2 class="m-t-0 header-title"><b><?php echo $this->lang->line('SEO'); ?></b></h2>
				<p class="text-muted font-13"><?php echo $this->lang->line('Define SEO settings'); ?></p>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="author"><?php echo $this->lang->line('author'); ?></label>
                            <input type="text" class="form-control" name="author" value="<?php echo $this->config->item('author'); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="keywords"><?php echo $this->lang->line('keywords'); ?></label>
                            <input type="text" class="form-control" name="keywords" value="<?php echo $this->config->item('keywords'); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" rows="9" name="description"><?php echo $this->config->item('description'); ?></textarea>
                        </div>
    					<div class="form-group">
    						<label for="google_analytics"><?php echo $this->lang->line('Google analytics'); ?></label>
    						<textarea class="form-control" rows="9" name="google_analytics" placeholder="Google analytics code"><?php echo $this->config->item('google_analytics'); ?></textarea>
    					</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('homePagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('home_pag'); ?>" name="home_pag">
    					</div>
    					<div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('categoryPagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('cat_pag'); ?>" name="cat_pag">
    					</div>
                        <div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('Keyword pagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('key_pag'); ?>" name="key_pag">
    					</div>
                        <div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('Blog pagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('blog_pag'); ?>" name="blog_pag">
    					</div>
    					<div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('commentsPagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('coms_pag'); ?>" name="coms_pag">
    					</div>
    					<div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('profilePagination'); ?></label>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('more_pag'); ?>" name="more_pag">
    					</div>
                        <div class="form-group">
    						<label for="maintenance"><?php echo $this->lang->line('Cache'); ?></label>
    						<select class="form-control  selectpicker show-tick" data-style="btn-white" name="cache_activation">
    							<option value="1" <?php if($this->config->item('cache_activation') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('active'); ?></option>
    							<option value="0" <?php if($this->config->item('cache_activation') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('inactive'); ?></option>
    							<option value="2" <?php if($this->config->item('cache_activation') == 2) echo 'selected="selected"'; ?>><?php echo $this->lang->line('dlete'); ?></option>
    						</select>
    					</div>
    					<div class="form-group">
    						<label class="control-label"><?php echo $this->lang->line('cacheExpiration'); ?></label> <small>(<?php echo $this->lang->line('numberOfMinutes'); ?>)</small>
    						<input class="vertical-spin" type="text" value="<?php echo $this->config->item('cache_expire'); ?>" name="cache_expire">
    					</div>
                    </div>
                </div>
            </div> <!-- End col -->
        </div> <!-- End row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group text-right m-b-0">
                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                </div>
            </div>
        </div>
	</div> <!-- End card-box -->
</form>
<div class="row">
    <div class="col-sm-12">
            <div class="card-box">
				<form class="form-horizontal" role="form" method="post" action="/settings/seo/">
					<h2 class="m-t-0 header-title"><b><?php echo $this->lang->line('Sitemap'); ?></b></h2>
					<p class="text-muted font-13"><?php echo $this->lang->line('generateSitemap'); ?></p>
					<?php if(isset($msg2)) echo $msg2; ?>
					<input type="hidden" name="sitemap" />
					<button type="submit" class="btn btn-inverse w-md waves-effect waves-light m-b-5"> <i class="fa fa-rocket m-r-5"></i> <span><?php echo $this->lang->line('sitemapGeneration'); ?></span> </button>
					<a href="/sitemap.xml" target="_blank"><button type="button" class="btn btn-inverse w-md waves-effect waves-light m-b-5"><span><?php echo $this->lang->line('viewSitemap'); ?></span></button></a>
				</form>
            </div>
		</div> <!-- End card-box -->
	</div> <!-- End col -->
</div> <!-- End row -->
