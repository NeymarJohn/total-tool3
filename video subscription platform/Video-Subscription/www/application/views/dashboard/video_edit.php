<div class="row">
	<div class="col-sm-12">
		<?php
		if(isset($msg)) echo $msg;
		if(isset($error)) echo alert($error, 'danger');
		?>
		<?php if($this->uri->segment(3) === 'add') { ?>
			<a href="<?php echo site_url('dashboard/videos/addepisode/'); ?>" class="btn btn-default btn-action waves-effect waves-light"><?php echo $this->lang->line('New episode'); ?></a>
		<?php } ?>
		<div class="card-box">
			<div class="row">
				<div class="<?php echo ($this->uri->segment(3, 0) === 'add') ? 'col-sm-12' : 'col-sm-9'; ?>">
					<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
						<div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('videoTitle'); ?></label>
							<input type="text" class="form-control" name="title" placeholder="<?php echo $this->lang->line('videoTitle'); ?>" value="<?php if(isset($title_video)) echo $title_video; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="url"><?php echo $this->lang->line('videoUrl'); ?></label> <span class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</span>
							<input type="text" class="form-control" name="url" placeholder="<?php echo $this->lang->line('videoUrl'); ?>" value="<?php if(isset($url_video)) echo $url_video; ?>">
						</div>
						<div class="form-group m-b-20">
							<label for="description"><?php echo $this->lang->line('videoDescription'); ?></label>
							<textarea type="text" class="form-control cnt1" name="description" placeholder="<?php echo $this->lang->line('videoDescription'); ?>"><?php if(isset($description_video)) echo $description_video; ?></textarea>
						</div>
						<div class="form-group m-b-20">
							<label for="category"><?php echo $this->lang->line('videoCategory'); ?></label>
							<select class="form-control select2" name="category"> <?php if(isset($getCategories)) echo $getCategories; ?> </select>
						</div>
						<div class="form-group m-b-20">
							<label for="keywords"><?php echo $this->lang->line('videoKeywords'); ?></label>
							<select class="select2 select2-multiple select2-hidden-accessible" multiple="" data-placeholder="<?php echo $this->lang->line('choose'); ?> ..." tabindex="-1" aria-hidden="true" name="keywords[]">
								<?php if(isset($getKeywords)) echo $getKeywords; ?>
							</select>
						</div>
						<div class="form-group m-b-20">
							<label for="type"><?php echo $this->lang->line('videoSource'); ?></label>
							<select class="form-control selectpicker show-tick" data-style="btn-white" name="type">
								<option value="0" <?php if(isset($type_video) && $type_video === '0') echo 'selected'; ?>><?php echo $this->lang->line('Embedded'); ?></option>
								<option value="1" <?php if(isset($type_video) && $type_video === '1') echo 'selected'; ?>><?php echo $this->lang->line('Hosted'); ?></option>
								<option value="2" <?php if(isset($type_video) && $type_video === '2') echo 'selected'; ?>><?php echo $this->lang->line('YouTube'); ?></option>
								<option value="3" <?php if(isset($type_video) && $type_video === '3') echo 'selected'; ?>><?php echo $this->lang->line('Vimeo'); ?></option>
								<option value="4" <?php if(isset($type_video) && $type_video === '4') echo 'selected'; ?>><?php echo $this->lang->line('Amazon S3'); ?></option>
							</select>
						</div>
						<div id="embed" class="form-group m-b-20" <?php if(isset($type_video) && $type_video == 1 || isset($type_video) && $type_video == 4) echo 'style="display:none;' ?>>
							<label for="embed_url"><?php echo $this->lang->line('Video URL (embed) / YouTube ID / Vimeo ID'); ?></label>
							<input type="text" class="form-control" name="embed" placeholder="External video URL" value="<?php if(isset($embed_url)) echo $embed_url; ?>">
						</div>
                        <div class="form-group m-b-20">
                            <label for="subscription"><?php echo $this->lang->line('Subscription'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="subscription">
                                <option value="1" <?php if(isset($subscription_video) && $subscription_video === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                <option value="0" <?php if(isset($subscription_video) && $subscription_video === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                            </select>
                        </div>
						<div class="form-group m-b-20">
							<label for="status"><?php echo $this->lang->line('videoStatus'); ?></label>
							<select class="form-control selectpicker show-tick" data-style="btn-white" name="status">
								<option value="1" <?php if(isset($status_video) && $status_video === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
								<option value="0" <?php if(isset($status_video) && $status_video === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
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
    							<label class="control-label"><?php echo $this->lang->line('videoCover'); ?></label> <p><small class="text-muted">(.gif, .jpg, .png)</small></p>
    							<input type="file" name="userImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($image)) echo $image; ?>">
    							<input type="hidden" name="hiddenImage">
    						</div>
    						<div class="form-group text-right m-b-0">
    							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
    						</div>
    					</form>
						<?php if ($this->config->item('amazonBrowserUpload') === '1' && $type_video == 4) { ?>
							<!-- Amazon S3 browser Upload -->
							<form class="direct-upload" method="post" action="<?php echo $s3_details['url'];?>" role="form" enctype="multipart/form-data" accept-charset="utf-8" <?php echo ($type_video == 1 || $type_video == 4) ? '' : 'style="display:none;"'; ?>>
								<?php
								foreach ($s3_details['inputs'] as $name => $value) { ?>
									<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
								<?php } ?>
								<div class="form-group m-b-20 m-t-10">
									<label for="file" class="control-label" title="Allowed file types: jpg|jpeg|png|gif|pdf|doc|docs|zip Max upload limit: 5MB"><?php echo $this->lang->line('videoFile'); ?></label> <small>(<a class="addLink" href="#">Custom link</a>)</small>
									<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
									<input type="hidden" name="key" value="">
									<input id="file" type="file" name="file" class="filestyle" onchange="return" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($file)) echo $file; ?>">
									<!-- Progress Bars to show upload completion percentage -->
									<div class="progress-bar-area"></div>
								</div>
							</form>
							<form action="" id="uploaded_files" enctype="multipart/form-data" method="post" accept-charset="utf-8" <?php echo ($type_video == 1 || $type_video == 4) ? '' : 'style="display:none;"'; ?>>
								<textarea id="uploaded" name="uploaded_files" style="display:none;"></textarea>
								<div class="form-group text-right m-b-0">
									<button id="submit-browser-file" class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
								</div>
							</form>
						<?php } else { ?>
							<form class="userFile" method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8" <?php echo ($type_video == 1 || $type_video == 4) ? '' : 'style="display:none;"'; ?>>
								<div class="form-group m-b-20 m-t-10">
									<label class="control-label"><?php echo $this->lang->line('videoFile'); ?></label> <small>(<a class="addLink" href="#">Custom link</a>)</small>
									<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
									<input type="file" name="userFile" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="<?php if(isset($file)) echo $file; ?>">
									<input type="hidden" name="hiddenFile">
									<input type="hidden" name="file">
								</div>
								<div class="form-group text-right m-b-0">
									<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
								</div>
							</form>
						<?php } ?>
						<form class="userInput" method="post" action="<?php echo current_url() . '/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8" style="display:none;">
    						<div class="form-group m-b-20 m-t-10">
								<label class="control-label"><?php echo $this->lang->line('videoFile'); ?></label> <small class="text-right">(<a class="addLink" href="#">Custom link</a>)</small>
								<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
    							<input type="text" name="userInput" class="form-control" value="<?php if (isset($file)) echo $file; ?>">
    						</div>
    						<div class="form-group text-right m-b-0">
    							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
    						</div>
						</form>
						<form class="userTrailer" method="post" action="<?php echo current_url() . '/'; ?>" role="form">
    						<div class="form-group m-b-20 m-t-10">
								<label class="control-label"><?php echo $this->lang->line('Trailer'); ?></label>
								<p><small class="text-muted">(.mp4, .mov, .ogg, .webm)</small></p>
    							<input type="text" name="userTrailer" class="form-control" value="<?php if (isset($trailer)) echo $trailer; ?>">
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
			$(".direct-upload, #uploaded_files").toggle();
		});
	};
</script>
