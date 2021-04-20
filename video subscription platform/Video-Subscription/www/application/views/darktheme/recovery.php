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
			<form method="post" action="<?php echo site_url('login/recovery/'); ?>" role="form" class="text-center m-t-20">
				<div class="form-group m-b-0">
					<div class="input-group">
						<input type="email" name="email" class="form-control" placeholder="Enter Email" required="" <?php if(isset($remember_me)) echo 'value="'.$remember_me.'"'; ?>>
						<span class="input-group-btn">
							<button type="submit" class="btn btn-black btn-custom-login">
								<?php echo $this->lang->line('reset'); ?>
							</button>
						</span>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><?php echo $this->lang->line('dontHaveAccount'); ?> <a href="<?php echo site_url('login/register/'); ?>" class="text-red m-l-5"><b><?php echo $this->lang->line('signup'); ?></b></a></p>
		</div>
	</div>
</div>
