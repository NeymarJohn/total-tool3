<div class="row">
	<div class="col-sm-12">
		<?php
		if(isset($msg)) echo $msg;
		if(isset($error)) echo alert($error, 'danger');
		?>
		<?php if($this->uri->segment(3) === 'addepisode') { ?>
			<a href="<?php echo site_url('dashboard/videos/add/'); ?>" class="btn btn-default btn-action waves-effect waves-light"><?php echo $this->lang->line('New Video'); ?></a>
		<?php } ?>
		<div class="card-box">
			<div class="row">
				<div class="<?php echo ($this->uri->segment(3, 0) === 'addepisode') ? 'col-sm-12' : 'col-sm-9'; ?>">
					<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group m-b-20">
									<label for="video"><?php echo $this->lang->line('Video'); ?></label>
									<select class="form-control select2" name="video"> <?php if(isset($getVideosList)) echo $getVideosList; ?> </select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group m-b-20">
									<label for="title"><?php echo $this->lang->line('Title'); ?></label>
									<input type="text" class="form-control" name="title" placeholder="<?php echo $this->lang->line('Episode title'); ?>" value="<?php if(isset($title_episode)) echo $title_episode; ?>">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group m-b-20">
									<label for="description"><?php echo $this->lang->line('Description'); ?></label>
									<textarea type="text" class="form-control cnt1" name="description" placeholder="<?php echo $this->lang->line('videoDescription'); ?>"><?php if(isset($description)) echo $description; ?></textarea>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group m-b-20">
									<label for="episode"><?php echo $this->lang->line('Episode number'); ?></label>
									<input type="number" min="1" class="form-control" name="episode" value="<?php if(isset($episode)) echo $episode; ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group m-b-20">
									<label for="season"><?php echo $this->lang->line('Season number'); ?></label>
									<input type="number" min="1" class="form-control" name="season" value="<?php if(isset($season)) echo $season; ?>">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group m-b-20">
									<label for="type"><?php echo $this->lang->line('videoSource'); ?></label>
									<select class="form-control selectpicker show-tick" data-style="btn-white" name="type">
										<option value="0" <?php if(isset($type) && $type === '0') echo 'selected'; ?>><?php echo $this->lang->line('Embedded'); ?></option>
										<option value="1" <?php if(isset($type) && $type === '1') echo 'selected'; ?>><?php echo $this->lang->line('Hosted'); ?></option>
										<option value="2" <?php if(isset($type) && $type === '2') echo 'selected'; ?>><?php echo $this->lang->line('YouTube'); ?></option>
										<option value="3" <?php if(isset($type) && $type === '3') echo 'selected'; ?>><?php echo $this->lang->line('Vimeo'); ?></option>
										<option value="4" <?php if(isset($type) && $type === '4') echo 'selected'; ?>><?php echo $this->lang->line('Amazon S3'); ?></option>
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div id="embed" class="form-group m-b-20" <?php if(isset($type) && $type == 1 || isset($type) && $type == 4) echo 'style="display:none;' ?>>
									<label for="embed_url"><?php echo $this->lang->line('Video URL (embed) / YouTube ID / Vimeo ID'); ?></label>
									<input type="text" class="form-control" name="embed" placeholder="External video URL" value="<?php if(isset($embed)) echo $embed; ?>">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group m-b-20">
									<label for="status"><?php echo $this->lang->line('Status'); ?></label>
									<select class="form-control selectpicker show-tick" data-style="btn-white" name="status">
										<option value="1" <?php if(isset($status) && $status === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
										<option value="0" <?php if(isset($status) && $status === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group text-right m-b-0">
									<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
									<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
								</div>
							</div>
						</div> <!-- End row -->
					</form>
				</div> <!-- End col -->
				<?php if($this->uri->segment(3, 0) === 'editepisode') { ?>
    				<div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-3'; ?>">
    					<form method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8">
    						<div class="form-group m-b-20">
    							<label class="control-label"><?php echo $this->lang->line('videoCover'); ?></label> <p><small class="text-muted">(.gif, .jpg, .png)</small></p>
    							<input type="file" name="userImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($image)) echo $image; ?>">
    							<input type="hidden" name="hiddenImage">
    						</div>
    						<div class="form-group text-right m-b-0">
    							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
    						</div>
    					</form>
    					<form class="userFile" method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8" <?php echo ($type == 1 || $type == 4) ? '' : 'style="display:none;"'; ?>>
    						<div class="form-group m-b-20 m-t-10">
								<label class="control-label"><?php echo $this->lang->line('videoFile'); ?></label> <small class="text-right">(<a class="addLink" href="#">Custom link</a>)</small>
								<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
    							<input type="file" name="userFile" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($file)) echo $file; ?>">
    							<input type="hidden" name="hiddenFile">
    							<input type="hidden" name="file">
    						</div>
    						<div class="form-group text-right m-b-0">
    							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
    						</div>
						</form>
						<form class="userInput" method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8" style="display:none;">
    						<div class="form-group m-b-20 m-t-10">
								<label class="control-label"><?php echo $this->lang->line('videoFile'); ?></label> <small class="text-right">(<a class="addLink" href="#">Custom link</a>)</small>
								<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
    							<input type="text" name="userInput" class="form-control" value="<?php if(isset($file)) echo $file; ?>">
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

<script>
	window.onload = function() {
		$("select[name='type']").change(function() {
		    var str = $(this).val();
		    if(str == 0 || str == 2 || str == 3) {
		    	$("#embed").show();
		    	$(".userFile").hide();
		    } else {
		    	$("#embed").hide();
		    	$(".userFile").show();
		    }
		});
		$('.addLink').click(function(e){
			e.preventDefault();
			$(".userFile").toggle();
			$(".userInput").toggle();
		});
	};
</script>
