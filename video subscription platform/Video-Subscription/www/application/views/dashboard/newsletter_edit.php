<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
    <div class="row">
    	<div class="col-sm-12">
    		<?php
    		if(isset($msg)) echo $msg;
    		if(isset($error)) echo alert($error, 'danger');
    		?>
    		<div class="card-box">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-20">
                                    <label for="email"><?php echo $this->lang->line('Email'); ?></label>
                                    <input type="text" class="form-control" name="email" value="<?php if(isset($getNewsletter->email)) echo (!$this->config->item('demo')) ? $getNewsletter->email : 'demo@coffeetheme.com'; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group m-b-20">
        							<label for="title"><?php echo $this->lang->line('Is member ?'); ?></label>
        							<input type="text" class="form-control" value="<?php if(isset($getNewsletter->is_member)) echo ($getNewsletter->is_member === '1') ? $this->lang->line('Yes') : $this->lang->line('No'); ?>" disabled>
        						</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group m-b-20">
        							<label for="title"><?php echo $this->lang->line('IP'); ?></label>
        							<input type="text" class="form-control" value="<?php if(isset($getNewsletter->ip)) echo $getNewsletter->ip; ?>" disabled>
        						</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-20">
        							<label for="status"><?php echo $this->lang->line('Status'); ?></label>
        							<select class="form-control select2" name="status">
                                        <option value="0" <?php if(isset($getNewsletter->status) && $getNewsletter->status === '0') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                        <option value="1" <?php if(isset($getNewsletter->status) && $getNewsletter->status === '1') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                                    </select>
        						</div>
                                <div class="form-group text-right m-b-0">
        							<button type="submit" class="btn btn-inverse waves-effect waves-light"><?php echo $this->lang->line('submit'); ?></button>
        							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        						</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Date created'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getNewsletter->date_created)) echo gmdate("M d, Y", strtotime($getNewsletter->date_created)); ?>" disabled>
						</div>
                    </div>
                </div>
    		</div> <!-- End card-box -->
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
