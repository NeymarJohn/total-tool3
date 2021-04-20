<div class="row">
	<div class="col-sm-12">
		<?php if(isset($msg)) echo $msg; ?>
		<div class="card-box">
			<form method="post" action="<?php echo current_url().((isset($_GET['type']) ? '/?type='.$_GET['type'] : '')); ?>" role="form">
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group m-b-20">
							<label for="author"><?php echo $this->lang->line('author'); ?></label> <small>(<a href="<?php echo current_url().((isset($_GET['type']) ? '?type='.$_GET['type'].'&ban=1' : '')); ?>">Ban this user</a>)</small>
							<select class="form-control select2" name="author">
								<?php if(isset($getUsers)) echo $getUsers; ?>
							</select>
						</div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group m-b-20">
                            <label for="ip"><?php echo $this->lang->line('Date created'); ?></label>
                            <input type="text" class="form-control" disabled value="<?php if(isset($date_created)) echo $date_created; ?>">
                        </div>
                    </div>
                    <div class="col-sm-12">
						<div class="form-group m-b-20">
							<label for="comment"><?php echo $this->lang->line('comment'); ?></label>
							<textarea type="text" class="form-control" name="comment" placeholder="Comment"><?php if(isset($comment)) echo $comment; ?></textarea>
						</div>
						<div class="form-group m-b-20">
							<label for="video"><?php echo $this->lang->line('video'); ?></label>
							<select class="form-control select2" name="video">
								<?php if(isset($getVideos)) echo $getVideos; ?>
							</select>
						</div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group m-b-20">
							<label for="type"><?php echo $this->lang->line('status'); ?></label>
							<select class="form-control selectpicker show-tick" data-style="btn-white" name="status">
								<option value="1" <?php if(isset($status) && $status === '1') echo 'selected'; ?>><?php echo $this->lang->line('Pending'); ?></option>
                                <option value="2" <?php if(isset($status) && $status === '2') echo 'selected'; ?>><?php echo $this->lang->line('Spam'); ?></option>
								<option value="3" <?php if(isset($status) && $status === '3') echo 'selected'; ?>><?php echo $this->lang->line('Approved'); ?></option>
							</select>
						</div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group m-b-20">
                            <label for="ip"><?php echo $this->lang->line('IP adress'); ?></label>
                            <input type="text" class="form-control" disabled value="<?php if(isset($ip)) echo $ip; ?>">
                        </div>
                    </div>
                    <div class="col-sm-12">
						<div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
					</div> <!-- End col -->
				</div> <!-- End row -->
			</form>
		</div> <!-- End card-box -->
	</div> <!-- End col -->
</div> <!-- End row -->
