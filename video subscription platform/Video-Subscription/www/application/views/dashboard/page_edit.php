<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
    <div class="row">
    	<div class="col-sm-12">
    		<?php if(isset($msg)) echo $msg; ?>
    		<div class="card-box">
    			<div class="row">
    				<div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-9'; ?>">
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('pageTitle'); ?></label>
							<input type="text" class="form-control" name="title" placeholder="Page title" value="<?php if(isset($title_page)) echo $title_page; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="url"><?php echo $this->lang->line('pageUrl'); ?></label> <span class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</span>
							<input type="text" class="form-control" name="url" placeholder="Page URL" value="<?php if(isset($url_page)) echo $url_page; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="content"><?php echo $this->lang->line('pageContent'); ?></label>
							<textarea type="text" class="form-control cnt1" name="content"><?php if(isset($content_page)) echo $content_page; ?></textarea>
						</div>
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
	                </div> <!-- End col -->
    				<?php if($this->uri->segment(3, 0) === 'edit') { ?>
                        <div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-3'; ?>">
                            <div class="form-group m-b-20">
    							<label for="subpage"><?php echo $this->lang->line('Sub Page'); ?></label>
    							<select class="form-control select2" name="subpage">
                                    <?php if(isset($getListPages)) echo $getListPages; ?>
                                </select>
    						</div>
                            <div class="form-group m-b-20">
    							<label for="customPage"><?php echo $this->lang->line('Layout'); ?></label>
    							<select class="form-control select2" name="customPage">
                                    <option value="0" <?php if(isset($customPage) && $customPage == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Normal'); ?></option>
                                    <option value="1" <?php if(isset($customPage) && $customPage == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Custom Page'); ?></option>
                                </select>
    						</div>
    						<div class="form-group text-right m-b-0">
    							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
    						</div>
        				</div> <!-- End col -->
    				<?php } ?>
    			</div> <!-- End row -->
    		</div> <!-- End card-box -->
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
