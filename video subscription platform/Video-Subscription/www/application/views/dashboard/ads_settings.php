<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
	<div class="row">
		<div class="col-sm-12">
			<?php if(isset($msg)) echo $msg; ?>
			<div class="card-box">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Video advertisements'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure advertisements that will appear in the video player'); ?></p>
						<div class="form-group">
                            <label for="videoads"><?php echo $this->lang->line('Video file'); ?></label> <small>(<?php echo $this->lang->line('External link or Hosted video'); ?>)</small>
                            <input type="text" name="videoads" class="form-control" value="<?php echo $this->config->item('videoads'); ?>">
						</div>
						<div class="form-group">
                            <label for="adslink"><?php echo $this->lang->line('Video link'); ?></label>
                            <input type="text" name="adslink" class="form-control" value="<?php echo $this->config->item('adslink'); ?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
        					<label for="adsduration"><?php echo $this->lang->line('Duration'); ?> </label> <small>(s)</small>
							<input type="number" name="adsduration" class="form-control" value="<?php echo $this->config->item('adsduration'); ?>">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
        					<label for="videoadsactive"><?php echo $this->lang->line('Activation'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="videoadsactive">
        						<option value="1" <?php if($this->config->item('videoadsactive') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
        						<option value="0" <?php if($this->config->item('videoadsactive') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
        					</select>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
					</div> <!-- End col -->
				</div> <!-- End row -->
			</div> <!-- End card-box -->
			<div class="card-box">
				<div class="row">
					<div class="col-sm-12">
						<h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Website advertisements'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure website advertisements'); ?></p>
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('sidebarAdContent'); ?></label>
							<textarea class="form-control" name="sidebarcontent" rows="5"><?php echo $this->config->item('sidebarcontent'); ?></textarea>
						</div>
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('sidebarAdTop'); ?></label>
							<textarea class="form-control" name="sidebartop" rows="5"><?php echo $this->config->item('sidebartop'); ?></textarea>
						</div>
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('sidebarAdBottom'); ?></label>
							<textarea class="form-control" name="sidebarbottom" rows="5"><?php echo $this->config->item('sidebarbottom'); ?></textarea>
						</div>
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
					</div> <!-- End col -->
				</div> <!-- End row -->
			</div> <!-- End card-box -->
		</div> <!-- End col -->
	</div> <!-- End row -->
</form>
