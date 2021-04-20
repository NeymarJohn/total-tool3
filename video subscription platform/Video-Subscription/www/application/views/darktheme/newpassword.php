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
			<form class="form-horizontal m-t-20" action="<?php echo site_url('login/changepass/?mail='.$email.'&key='.$passkey); ?>" method="post">
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="password" name="password" required="" placeholder="<?php echo $this->lang->line('newPassword'); ?>">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" type="password" name="confirm" required="" placeholder="<?php echo $this->lang->line('confirmPassword'); ?>">
					</div>
				</div>
				<div class="form-group text-center m-t-40">
					<div class="col-xs-12">
						<button class="btn btn-black btn-custom-login" type="submit"><?php echo $this->lang->line('submit'); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><?php echo $this->lang->line('dontHaveAccount'); ?> <a href="<?php echo site_url('login/'); ?>" class="text-red m-l-5"><b><?php echo $this->lang->line('signup'); ?></b></a></p>
		</div>
	</div>
</div>
