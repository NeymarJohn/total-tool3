<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
    <div class="row">
    	<div class="col-sm-12">
    		<?php
    		if(isset($msg)) echo $msg;
    		if(isset($error)) echo alert($error, 'danger');
    		?>
    		<div class="card-box">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Type'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->type)) echo ($getPayment->type === '1') ? $this->lang->line('Subscription') : $this->lang->line('Payments'); ?>" disabled>
                        </div>
					</div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Username'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->username)) echo $getPayment->username; ?>" disabled>
						</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('IP'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->ip)) echo $getPayment->ip; ?>" disabled>
						</div>
                    </div>
                </div>
    			<div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Subscription ID'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->subscription_id)) echo $getPayment->subscription_id; ?>" disabled>
						</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Status'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->status)) echo $getPayment->status; ?>" disabled>
						</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Price'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->price)) echo $getPayment->price; ?>" disabled>
						</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Currency'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->currency)) echo $getPayment->currency; ?>" disabled>
						</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Date created'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->date_created)) echo $getPayment->date_created; ?>" disabled>
						</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Date end'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->date_end)) echo $getPayment->date_end; ?>" disabled>
                        </div>
					</div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Trial start'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->trial_start)) echo $getPayment->trial_start; ?>" disabled>
						</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-20">
							<label for="title"><?php echo $this->lang->line('Trial end'); ?></label>
							<input type="text" class="form-control" value="<?php if(isset($getPayment->trial_end)) echo $getPayment->trial_end; ?>" disabled>
						</div>
                    </div>
                </div>
    		</div> <!-- End card-box -->
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
