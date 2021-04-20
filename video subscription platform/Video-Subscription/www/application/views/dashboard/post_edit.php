<div class="row">
	<div class="col-sm-12">
		<?php
		if(isset($msg)) echo $msg;
		if(isset($error)) echo alert($error, 'danger');
		?>
		<div class="card-box">
			<div class="row">
				<div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-9'; ?>">
					<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Title'); ?></label>
							<input type="text" class="form-control" name="title" placeholder="<?php echo $this->lang->line('postTitle'); ?>" value="<?php if(isset($title_post)) echo $title_post; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="url"><?php echo $this->lang->line('URL'); ?></label> <small class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</small>
							<input type="text" class="form-control" name="url" placeholder="<?php echo $this->lang->line('postUrl'); ?>" value="<?php if(isset($url)) echo $url; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="description"><?php echo $this->lang->line('Content'); ?></label>
							<textarea type="text" class="form-control cnt1" name="content" placeholder="<?php echo $this->lang->line('postDescription'); ?>"><?php if(isset($content)) echo $content; ?></textarea>
						</div>
						<div class="form-group m-b-20">
							<label for="category"><?php echo $this->lang->line('Category'); ?></label>
							<select class="form-control select2" name="category"> <?php if(isset($getCategories)) echo $getCategories; ?> </select>
						</div>
						<div class="form-group m-b-20">
							<label for="keywords"><?php echo $this->lang->line('Keywords'); ?></label>
							<select class="select2 select2-multiple select2-hidden-accessible" multiple="" data-placeholder="<?php echo $this->lang->line('choose'); ?> ..." tabindex="-1" aria-hidden="true" name="keywords[]">
								<?php if(isset($getKeywords)) echo $getKeywords; ?>
							</select>
						</div>
						<div class="form-group m-b-20">
							<label for="status"><?php echo $this->lang->line('Status'); ?></label>
							<select class="form-control selectpicker show-tick" data-style="btn-white" name="status">
								<option value="1" <?php if(isset($status) && $status === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
								<option value="0" <?php if(isset($status) && $status === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
							</select>
						</div>
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
					</form>
				</div> <!-- End col -->
				<?php if($this->uri->segment(3, 0) === 'edit') { ?>
                <div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-3'; ?>">
					<form method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8">
						<div class="form-group m-b-20">
							<label class="control-label"><?php echo $this->lang->line('Cover image'); ?></label> <small class="text-muted">(.gif, .jpg, .png)</small>
							<input type="file" name="userImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($image)) echo $image; ?>">
							<input type="hidden" name="hiddenImage">
						</div>
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
						</div>
					</form>
				</div> <!-- End col -->
				<?php } ?>
			</div> <!-- End row -->
		</div> <!-- End card-box -->
	</div> <!-- End col -->
</div> <!-- End row -->
