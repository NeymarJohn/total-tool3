<section>
    <div class="row profile-head">
        <div class="col-sm-12">
            <div class="container">
                <div class="profile-title clearfix">
                    <h1><b><?php echo $this->lang->line('members'); ?></b></h1>
                </div>
            </div>
        </div> <!-- End col -->
    </div> <!-- End row -->
	<div class="container container-mobile">
		<div class="row m-t-20">
			<div class="col-lg-9 col-md-8">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-box">
							<h2 class="header-title m-t-0"><?php echo $this->lang->line('usersMoreNotes'); ?></h2>
							<?php if(isset($getMembersNotes)) echo $getMembersNotes; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="card-box">
							<h2 class="header-title m-t-0"><?php echo $this->lang->line('usersMoreFavorites'); ?></h2>
							<?php if(isset($getMembersFavs)) echo $getMembersFavs; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="card-box">
							<h2 class="header-title m-t-0"><?php echo $this->lang->line('usersMoreComments'); ?></h2>
							<?php if(isset($getMembersComs)) echo $getMembersComs; ?>
						</div>
					</div>
				</div>
			</div> <!-- End col -->
			<div class="col-md-4 col-lg-3">
				<?php if(!empty($this->config->item('sidebartop'))) { ?>
					<div class="widget">
						<h4 class="h5"><?php echo $this->lang->line('Advertisement'); ?></h4>
						<?php echo $this->config->item('sidebartop'); ?>
					</div>
				<?php } ?>
				<?php if(!empty($this->config->item('facebookPageLink'))) { ?>
					<div class="widget">
						<h4 class="h5"><?php echo $this->lang->line('SOCIALIZE WITH US'); ?></h4>
						<div class="fb-page" data-href="<?php echo $this->config->item('facebookPageLink'); ?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="<?php echo $this->config->item('facebookPageLink'); ?>" class="fb-xfbml-parse-ignore"><a href="<?php echo $this->config->item('facebookPageLink'); ?>"><?php echo $this->config->item('facebookPageName'); ?></a></blockquote></div>
					</div>
				<?php } ?>
				<?php if(!empty($this->config->item('sidebarbottom'))) { ?>
					<div class="widget">
						<h4 class="h5"><?php echo $this->lang->line('Advertisement'); ?></h4>
						<?php echo $this->config->item('sidebarbottom'); ?>
					</div>
				<?php } ?>
			</div> <!-- End col -->
		</div> <!-- End row -->
	</div>
</section>
