<form class="form" role="form" method="post" action="/settings/mail/">
    <div class="row">
    	<div class="col-sm-12">
    		<?php if(isset($msg)) echo $msg; ?>
			<div class="row">
                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Welcome'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the mail that will be sent'); ?></p>
						<div class="form-group">
						    <label for="titleMailWelcome"><?php echo $this->lang->line('Title'); ?></label>
						    <input type="text" class="form-control" name="titleMailWelcome" placeholder="<?php echo $this->lang->line('titleMailWelcome'); ?>" value="<?php echo $this->config->item('titleMailWelcome'); ?>">
						</div>
						<div class="form-group">
							<label><?php echo $this->lang->line('Mail content'); ?></label>
							<textarea type="text" class="form-control cnt2" name="mailWelcome"><?php echo $this->config->item('mailWelcome'); ?></textarea>
						</div>
                        <div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Password changed'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the mail that will be sent'); ?></p>
						<div class="form-group">
						    <label for="titleMailPasswordChanged"><?php echo $this->lang->line('Title'); ?></label>
						    <input type="text" class="form-control" name="titleMailPasswordChanged" placeholder="<?php echo $this->lang->line('titleMailPasswordChanged'); ?>" value="<?php echo $this->config->item('titleMailPasswordChanged'); ?>">
						</div>
						<div class="form-group">
							<label><?php echo $this->lang->line('Mail content'); ?></label>
							<textarea type="text" class="form-control cnt2" name="mailPasswordChanged"><?php echo $this->config->item('mailPasswordChanged'); ?></textarea>
						</div>
                        <div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Confirmation inscription'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the mail that will be sent'); ?></p>
                        <div class="form-group">
						    <label for="titleMailConfirmation"><?php echo $this->lang->line('Title'); ?></label>
						    <input type="text" class="form-control" name="titleMailConfirmation" placeholder="Title" value="<?php echo $this->config->item('titleMailConfirmation'); ?>">
						</div>
						<div class="form-group">
							<label><?php echo $this->lang->line('Mail content'); ?></label>
                            <textarea type="text" class="form-control cnt2" name="mailConfirmation"><?php echo $this->config->item('mailConfirmation'); ?></textarea>
						</div>
                        <div class="form-group">
						    <label for="buttonMailConfirmation"><?php echo $this->lang->line('Button label'); ?></label>
						    <input type="text" class="form-control" name="buttonMailConfirmation" placeholder="Button label" value="<?php echo $this->config->item('buttonMailConfirmation'); ?>">
						</div>
                        <div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Account recovery'); ?></b></h4>
						<p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the mail that will be sent'); ?></p>
						<div class="form-group">
						    <label for="titleMailRecovery"><?php echo $this->lang->line('Title'); ?></label>
						    <input type="text" class="form-control" name="titleMailRecovery" placeholder="<?php echo $this->lang->line('titleMailPassword'); ?>" value="<?php echo $this->config->item('titleMailRecovery'); ?>">
						</div>
						<div class="form-group">
							<label><?php echo $this->lang->line('Mail content'); ?></label>
							<textarea type="text" class="form-control cnt2" name="mailRecovery"><?php echo $this->config->item('mailRecovery'); ?></textarea>
						</div>
                        <div class="form-group">
						    <label for="buttonMailRecovery"><?php echo $this->lang->line('Button label'); ?></label>
						    <input type="text" class="form-control" name="buttonMailRecovery" placeholder="Button label" value="<?php echo $this->config->item('buttonMailRecovery'); ?>">
						</div>
                        <div class="form-group text-right m-b-0">
							<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
							<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
						</div>
                    </div>
                </div>
            </div>
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
