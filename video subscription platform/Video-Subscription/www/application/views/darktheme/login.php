<div class="account-pages" style="background: url(<?php echo (!empty($this->config->item('backgroundLogin'))) ? $this->config->item('backgroundLogin') : '../images/bg-landing.png'; ?>);">
    <div class="dark-background"></div>
</div>
<div class="clearfix"></div>
<div class="wrapper-page">
	<div class="landing-box">
		<div class="panel-heading account-logo-box">
			<h3 class="text-center"><a href="<?php echo site_url(); ?>" class="logo account"><?php if(NULL!==$this->config->item('logo')) echo $this->config->item('logo'); ?></a></h3>
		</div>
		<div class="panel-body">
			<div class="text-center"><?php if(isset($msg)) echo $msg; ?></div>
			<form class="form-horizontal" action="<?php echo site_url('login/'); ?>" method="post">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="email" name="email" required="" placeholder="<?php echo $this->lang->line('email'); ?>" <?php if(isset($rememberMe)) echo 'value="'.$rememberMe.'"'; ?>>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" name="password" required="" placeholder="<?php echo $this->lang->line('password'); ?>" value="<?php if($this->config->item('demo')) echo 'password'; ?>">
					</div>
				</div>
				<div class="form-group ">
					<div class="col-xs-12">
						<div class="checkbox checkbox-black">
							<input id="checkbox-signup" type="checkbox" name="rememberme" value="true" <?php if(isset($rememberMe)) echo 'checked'; ?>>
							<label for="checkbox-signup">
								<?php echo $this->lang->line('rememberMe'); ?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group text-center">
					<div class="col-xs-12">
						<button class="btn btn-black btn-custom-login" type="submit"><i class="fa fa-rocket m-r-5"></i> <?php echo $this->lang->line('login'); ?></button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<a href="<?php echo site_url('login/recovery/'); ?>" class="text-dark"><i class="fa fa-lock m-r-5"></i> <?php echo $this->lang->line('forgotPassword'); ?></a>
					</div>
				</div>
                <div class="form-group m-b-0 text-center">
					<div id="social-button-connect" class="col-sm-12">
                        <?php if ($this->config->item('facebookLogin')) { ?>
                            <a href="<?php if(isset($fAuthUrl)) echo $fAuthUrl; ?>" class="btn btn-facebook waves-effect waves-light m-t-20">
                               <i class="fa fa-facebook m-r-5"></i> <?php echo $this->lang->line('Facebook'); ?>
                            </a>
                        <?php } ?>
                        <?php if ($this->config->item('twitterLogin')) { ?>
                            <a href="<?php if(isset($tAuthUrl)) echo $tAuthUrl; ?>" class="btn btn-twitter waves-effect waves-light m-t-20">
                               <i class="fa fa-twitter m-r-5"></i> <?php echo $this->lang->line('Twitter'); ?>
                            </a>
                        <?php } ?>
                        <?php if ($this->config->item('googleLogin')) { ?>
                            <a href="<?php if(isset($gAuthUrl)) echo $gAuthUrl; ?>" class="btn btn-googleplus waves-effect waves-light m-t-20">
                               <i class="fa fa-google-plus m-r-5"></i> <?php echo $this->lang->line('Google+'); ?>
                            </a>
                        <?php } ?>
					</div>
				</div>
			</form>
		</div> <!-- end panel body -->
	</div> <!-- end landing box -->
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><?php echo $this->lang->line('dontHaveAccount'); ?> <a href="<?php echo site_url('login/register/'); ?>" class="text-red m-l-5"><b><?php echo $this->lang->line('signup'); ?></b></a></p>
		</div>
	</div>
</div> <!-- end wrapper page -->
