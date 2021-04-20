<!-- MAINTENANCE -->
<section>
	<div class="container-alt">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="home-wrapper">
					<img src="<?php echo site_url('assets/images/maintenance.gif'); ?>" alt="" height="180">
					<h1 class="home-text text-uppercase">
						<span class="text-primary"><?php echo $this->lang->line('Site is'); ?></span>
						<span class="text-pink"><?php echo $this->lang->line('Under'); ?></span>
						<span class="text-info"><?php echo $this->lang->line('Maintenance'); ?></span>
					</h1>
					<h4 class="text-muted"><?php if(isset($message)) echo $message; ?></h4>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END MAINTENANCE -->
<script>
    window.onload = function() {
        $("html, body").addClass("bg-landing");
    };
</script>
