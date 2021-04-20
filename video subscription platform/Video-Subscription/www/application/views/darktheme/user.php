<section id="profile">
    <div class="row profile-head">
        <div class="col-sm-12">
            <div class="container">
                <div class="profile-title clearfix">
                    <img class="img-rounded thumb-lg" src="<?php echo (!empty($image)) ? (site_url('uploads/images/users/'.$image)) : (site_url('assets/images/default-user.png')); ?>" alt="">
                    <h1><b><?php if(isset($username)) echo $username; ?></b></h1>
                    <span><?php echo $this->lang->line('Member since'); ?> <?php echo gmdate("F Y", strtotime($date_created)); ?></span>
                </div>
                <ul>
					<li <?php echo ($this->uri->segment(2) === $url) ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/'.$url.'/'); ?>"><?php echo $this->lang->line('Profile'); ?></a></li>
                    <li <?php echo ($this->uri->segment(2) === 'favorites') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/favorites/'.$url.'/'); ?>"><?php echo $this->lang->line('Favorites'); ?></a></li>
                    <li <?php echo ($this->uri->segment(2) === 'playlists') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/playlists/'.$url.'/'); ?>"><?php echo $this->lang->line('Playlists'); ?></a></li>
                    <li <?php echo ($this->uri->segment(2) === 'notes') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/notes/'.$url.'/'); ?>"><?php echo $this->lang->line('Notes'); ?></a></li>
                    <li <?php echo ($this->uri->segment(2) === 'comments') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/comments/'.$url.'/'); ?>"><?php echo $this->lang->line('Comments'); ?></a></li>
                    <?php if($id === $this->session->userdata('id')) { ?>
                        <li <?php echo ($this->uri->segment(2) === 'settings') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/settings/'.$url.'/'); ?>"><?php echo $this->lang->line('Settings'); ?></a></li>
                        <?php if (!$this->config->item('freeWebsite')) { ?>
                            <li <?php echo ($this->uri->segment(2) === 'subscription') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/subscription/'.$url.'/'); ?>"><?php echo $this->lang->line('Subscription'); ?></a></li>
                        <?php } ?>
                        <li <?php echo ($this->uri->segment(2) === 'history') ? 'class="active"' : ''; ?>><a href="<?php echo site_url('user/history/'.$url.'/'); ?>"><?php echo $this->lang->line('Historical'); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <div class="container">
        <div class="row">
			<div class="col-sm-12">
                <div class="col-sm-12 <?php echo ($this->config->item('hasSidebar') === '0') ? 'col-lg-12' : 'col-lg-8'; ?>">
					<?php if ($seg = $this->uri->segment(2) === 'favorites') { ?>
                        <div class="card-box video-grid">
                            <div class="row">
								<div class="col-sm-12">
									<h2 class="header-title m-t-0 m-b-20"><?php echo $this->lang->line('favorites'); ?></h2>
								</div>
							</div>
							<div class="row">
                                <?php echo (($getFavsVideos) ? $getFavsVideos : ('<div class="col-sm-12">'.$this->lang->line('noDataYet')).'</div>');  ?>
		                    </div>
							<div class="text-right">
								<?php if(isset($pagination)) echo $pagination; ?>
							</div>
                        </div> <!-- End card-box -->
                    <?php } elseif ($seg = $this->uri->segment(2) === 'playlists') { ?>
                        <div class="card-box playlists-page">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 class="header-title inline-block m-t-0 m-b-20"><?php echo $this->lang->line('Playlists'); ?></h2>
                                    <button
                                       type="button"
                                       class="btn btn-link pull-right p-0"
                                       data-container="body"
                                       title=""
                                       data-toggle="popover"
                                       data-html="true"
                                       data-placement="bottom"
                                       data-content='<form method="post" action="<?php echo current_url(); ?>">
                                                         <p>Playlist Title</p>
                                                         <div class="input-group m-t-10">
                                                             <input type="text" name="playlistTitle" class="form-control">
                                                             <span class="input-group-btn"><button type="submit" class="btn waves-effect waves-light btn-red">Submit</button></span>
                                                         </div>
                                                     </form>'
                                       data-original-title="">
		                               <?php echo $this->lang->line('Create new playlist'); ?>
                                   </button>
								</div>
                            </div>
                            <div class="row">
                                <?php echo (($getPlaylists) ? $getPlaylists : ('<div class="col-sm-12">'.$this->lang->line('noDataYet')).'</div>');  ?>
    						</div>
                        </div> <!-- End card-box -->
                	<?php } elseif ($seg = $this->uri->segment(2) === 'playlist') { ?>
                        <div class="card-box">
                            <div class="row">
                                <div class="col-xs-9">
                                    <h2 class="header-title m-t-0 m-b-20"><?php echo $this->lang->line('Playlist:'); ?> <?php if(isset($getPlaylistTitle)) echo $getPlaylistTitle; ?></h2>
                                </div>
                                <div class="col-xs-3">
                                    <?php if($id === $this->session->id) { ?>
                                        <a href="<?php echo site_url('/user/playlist/'.$url.'/?del='.$getPlaylistId) ?>"><?php echo $this->lang->line('Delete this playlist'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <?php echo (($getPlaylist) ? $getPlaylist : ('<div class="col-sm-12">'.$this->lang->line('noDataYet')).'</div>');  ?>
    						</div>
                        </div> <!-- End card-box -->
					<?php } elseif ($seg = $this->uri->segment(2) === 'notes') { ?>
                        <div class="card-box notes-page">
                            <div class="row">
								<div class="col-sm-12">
									<h2 class="header-title m-t-0"><?php echo $this->lang->line('notes'); ?></h2>
								</div>
							</div>
                            <?php if($getNotesVideos) { ?>
                                <table class="table table-striped m-0 text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo $this->lang->line('video'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('note'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo (($getNotesVideos) ? $getNotesVideos : $this->lang->line('noDataYet')); ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php echo $this->lang->line('noDataYet'); ?>
                                    </div>
                                </div>
                            <?php } ?>
							<div class="text-right">
								<?php if(isset($pagination)) echo $pagination; ?>
							</div>
                        </div> <!-- End card-box -->
                    <?php } elseif ($seg = $this->uri->segment(2) === 'comments') { ?>
                        <div class="card-box">
                            <div class="row">
								<div class="col-sm-12">
									<h2 class="header-title m-t-0"><?php echo $this->lang->line('comments'); ?></h2>
								</div>
							</div>
							<?php echo (($getComsVideos) ? $getComsVideos : $this->lang->line('noDataYet')); ?>
							<div class="text-right">
								<?php if(isset($pagination)) echo $pagination; ?>
							</div>
                        </div> <!-- End card-box -->
                    <?php } elseif ($seg = $this->uri->segment(2) === 'settings') { ?>
                        <div class="card-box">
                            <div class="row">
                                <?php if(isset($msg)) echo $msg; ?>
        						<div id="content" class="col-sm-12 col-lg-8">
        							<form class="form" role="form" method="post" action="<?php echo current_url(); ?>">
        								<div class="form-group">
        									<label for="author"><?php echo $this->lang->line('username'); ?></label>
        									<input type="text" class="form-control" name="username" value="<?php if(isset($username)) echo $username; ?>" placeholder="Username" />
        								</div>
        								<div class="form-group">
        									<label for="author"><?php echo $this->lang->line('email'); ?></label> <small>(<?php echo $this->lang->line('private'); ?>)</small>
        									<input type="text" class="form-control" name="email" value="<?php if(isset($email)) echo $email; ?>" placeholder="Email" />
        								</div>
        								<div class="form-group">
        									<label for="author"><?php echo $this->lang->line('location'); ?></label>
        									<input type="text" class="form-control" name="location" value="<?php if(isset($location)) echo $location; ?>" placeholder="Country" />
        								</div>
        								<div class="form-group">
        									<label for="description"><?php echo $this->lang->line('aboutMe'); ?></label>
        									<textarea class="form-control" rows="5" name="about"><?php if(isset($about)) echo $about; ?></textarea>
        								</div>
        								<div class="form-group">
        									<label for="keywords"><?php echo $this->lang->line('facebook'); ?></label>
        									<input type="url" class="form-control" name="facebook" value="<?php if(isset($facebook)) echo $facebook; ?>" placeholder="https://www.facebook.com/myprofile" />
        								</div>
        								<div class="form-group">
        									<label for="keywords"><?php echo $this->lang->line('twitter'); ?></label>
        									<input type="url" class="form-control" name="twitter" value="<?php if(isset($twitter)) echo $twitter; ?>" placeholder="https://www.twitter.com/myprofile" />
        								</div>
        								<div class="form-group">
        									<label for="keywords"><?php echo $this->lang->line('google'); ?></label>
        									<input type="url" class="form-control" name="google" value="<?php if(isset($google)) echo $google; ?>" placeholder="https://plus.google.com/myprofile" />
        								</div>
        								<div class="form-group">
        									<label for="keywords"><?php echo $this->lang->line('linkedin'); ?></label>
        									<input type="url" class="form-control" name="linkedin" value="<?php if(isset($linkedin)) echo $linkedin; ?>" placeholder="https://www.linkedin.com/in/myprofile" />
        								</div>
                                        <div class="form-group">
        									<label for="id_playlist"><?php echo $this->lang->line('Do you want to display a playlist on your profile?'); ?></label>
        									<select class="form-control" name="id_playlist">
                                                <?php if(isset($getPlaylistsList)) echo $getPlaylistsList; ?>
        									</select>
        								</div>
        								<div class="form-group">
        									<label for="auth_coms"><?php echo $this->lang->line('commentsOnYourProfile'); ?></label>
        									<select class="form-control" name="auth_coms">
        										<option value="1" <?php if($auth_coms == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('yes'); ?></option>
        										<option value="0" <?php if($auth_coms == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('no'); ?></option>
        									</select>
        								</div>
        								<div class="form-group text-right m-b-0">
        									<button class="btn btn-red waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        									<button type="reset" class="btn btn-red waves-effect waves-light m-l-5"><?php echo $this->lang->line('reset'); ?></button>
        								</div>
        							</form>
        						</div> <!-- End col -->
        						<div id="sidebar" class="col-sm-12 col-lg-4">
        							<form method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8">
        								<div class="form-group m-b-20">
        									<label class="control-label"><?php echo $this->lang->line('Avatar image'); ?></label> <small class="text-muted">(.gif, .jpg, .png)</small>
        									<input type="file" name="userImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-dark" data-placeholder="<?php if(isset($image)) echo $image; ?>">
                                        </div>
        								<div class="form-group text-right m-b-0">
        									<button class="btn btn-red waves-effect waves-light" type="submit" name="submit" value="1"><?php echo $this->lang->line('submit'); ?></button>
        									<button class="btn btn-red waves-effect waves-light" type="submit" name="delete" value="1"><?php echo $this->lang->line('delete'); ?></button>
        								</div>
        							</form>
                                    <form method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8">
        								<div class="form-group m-b-20 m-t-20">
        									<label class="control-label"><?php echo $this->lang->line('Profile image'); ?></label> <small class="text-muted">(.gif, .jpg, .png)</small>
        									<input type="file" name="userProfileImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-dark" data-placeholder="<?php if(isset($profile_image)) echo $profile_image; ?>">
                                        </div>
        								<div class="form-group text-right m-b-0">
        									<button class="btn btn-red waves-effect waves-light" type="submit" name="submitProfileImage" value="1"><?php echo $this->lang->line('submit'); ?></button>
        									<button class="btn btn-red waves-effect waves-light" type="submit" name="deleteProfileImage" value="1"><?php echo $this->lang->line('delete'); ?></button>
        								</div>
        							</form>
        						</div> <!-- End col -->
        					</div> <!-- End row -->
                        </div> <!-- End card-box -->
                    <?php } elseif ($seg = $this->uri->segment(2) === 'history') { ?>
                        <div class="card-box">
                            <div class="row">
								<div class="col-sm-12">
									<h2 class="header-title m-t-0"><?php echo $this->lang->line('Historical'); ?></h2>
								</div>
							</div>
							<table class="table table-striped m-0 text-center">
								<thead>
									<tr>
                                        <th class="text-center"><?php echo $this->lang->line('Type'); ?></th>
										<th class="text-center"><?php echo $this->lang->line('Reference'); ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('Price'); ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('Status'); ?></th>
										<th class="text-center"><?php echo $this->lang->line('Date'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php echo (($getHistory) ? $getHistory : $this->lang->line('noDataYet')); ?>
								</tbody>
							</table>
							<div class="text-right">
								<?php if(isset($pagination)) echo $pagination; ?>
							</div>
                        </div>
                    <?php } elseif ($seg = $this->uri->segment(2) === 'subscription') { ?>

                         <?php if($this->config->item('paymentMethod') === 'Stripe'){ ?>
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if(isset($msg)) echo $msg; ?>
                                        <h2 class="header-title m-t-0"><?php echo $this->lang->line('Subscription'); ?></h2>
                                        <?php echo $this->config->item('contentText'); ?>
                                        <div class="prices-boxes">
                                            <?php if ($this->config->item('payActive') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-1 <?php if($this->config->item('payFocus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('payPrice'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('payDescription'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('payItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('payTitle')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('payDescription')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('payPrice')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('payCurrency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('payBtn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="0">
                                                            <input type="hidden" name="paymentPeriod" value="<?php echo html_escape($this->config->item('payPeriod')); ?>">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($this->config->item('pay2Active') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-2 <?php if($this->config->item('pay2Focus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('pay2Price'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('pay2Description'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('pay2ItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('pay2Title')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('pay2Description')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('pay2Price')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('pay2Currency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('pay2Btn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="0">
                                                            <input type="hidden" name="paymentPeriod" value="<?php echo html_escape($this->config->item('pay2Period')); ?>">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($this->config->item('pay3Active') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-3 <?php if($this->config->item('pay3Focus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('pay3Price'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('pay3Description'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('pay3ItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('pay3Title')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('pay3Description')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('pay3Price')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('pay3Currency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('pay3Btn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="0">
                                                            <input type="hidden" name="paymentPeriod" value="<?php echo html_escape($this->config->item('pay3Period')); ?>">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($this->config->item('planActive') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-4 <?php if($this->config->item('planFocus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('planPrice'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('planDescription'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('planItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('planTitle')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('planDescription')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('planPrice')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('planCurrency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('planBtn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="1">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($this->config->item('plan2Active') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-5 <?php if($this->config->item('plan2Focus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('plan2Price'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('plan2Description'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('plan2ItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('plan2Title')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('plan2Description')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('plan2Price')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('plan2Currency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('plan2Btn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="1">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($this->config->item('plan3Active') === '1') { ?>
                                                <div class="col-sm-4 p-b-20">
                                                    <div class="price_card price-box-6 <?php if($this->config->item('plan3Focus') === '1') echo 'active'; ?>">
                                                        <div class="pricing-header bg-primary">
                                                            <span class="price">$<?php echo $this->config->item('plan3Price'); ?></span>
                                                            <span class="name"><?php echo $this->config->item('plan3Description'); ?></span>
                                                        </div>
                                                        <div class="price-features">
                                                            <?php echo $this->config->item('plan3ItemList'); ?>
                                                        </div>
                                                        <form action="<?php echo current_url().'/'; ?>" method="POST">
                                                            <script
                                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                data-key="<?php echo html_escape($this->config->item('publishablekey')); ?>"
                                                                data-email="<?php if(isset($email)) echo $email; ?>"
                                                                data-image=""
                                                                data-name="<?php echo html_escape($this->config->item('plan3Title')); ?>"
                                                                data-description="<?php echo html_escape($this->config->item('plan3Description')); ?>"
                                                                data-amount="<?php echo str_replace(".", "", $this->config->item('plan3Price')) ?>"
                                                                data-locale="auto"
                                                                data-currency="<?php echo html_escape($this->config->item('plan3Currency')); ?>"
                                                                data-label="<?php echo html_escape($this->config->item('plan3Btn')); ?>">
                                                            </script>
                                                            <input type="hidden" name="typeSubscription" value="1">
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-box -->
                         <?php } elseif ($this->config->item('paymentMethod') === 'Pesapal') { ?>
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if (isset($msg)) echo $msg; ?>
                                        <h2 class="header-title m-t-0"><?php echo $this->lang->line('Subscription'); ?></h2>
                                        <?php echo $this->config->item('contentText'); ?>
                                        <div class="prices-boxes">
                                            <div class="row">
                                                <?php if ($this->config->item('pesapal1Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-1 <?php if ($this->config->item('pesapal1Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('pesapal1Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('pesapal1Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('pesapal1ItemList'); ?>
                                                            </div>
                                                            <button class="btn pesapal custom-button-el" data-subscription="pesapal1" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('pesapal1Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('pesapal2Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-2 <?php if ($this->config->item('pesapal2Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('pesapal2Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('pesapal2Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('pesapal2ItemList'); ?>
                                                            </div>
                                                            <button class="btn pesapal custom-button-el" data-subscription="pesapal2" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('pesapal2Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('pesapal3Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-3 <?php if ($this->config->item('pesapal3Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('pesapal3Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('pesapal3Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('pesapal3ItemList'); ?>
                                                            </div>
                                                            <button class="btn pesapal custom-button-el" data-subscription="pesapal3" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('pesapal3Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                
                                            </div>
                                            <!-- Modal -->
                                            <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content p-10">
                                                        <!-- <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h4 class="modal-title">d</h4>
                                                        </div> -->
                                                        <div class="modal-body p-0">
                                                            
                                                        </div>
                                                        <!-- <div class="modal-footer">
                                                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">close</button>
                                                        </div> -->
                                                        <div class="loader">
                                                            <div class="sk-circle">
                                                                <div class="sk-circle1 sk-child"></div>
                                                                <div class="sk-circle2 sk-child"></div>
                                                                <div class="sk-circle3 sk-child"></div>
                                                                <div class="sk-circle4 sk-child"></div>
                                                                <div class="sk-circle5 sk-child"></div>
                                                                <div class="sk-circle6 sk-child"></div>
                                                                <div class="sk-circle7 sk-child"></div>
                                                                <div class="sk-circle8 sk-child"></div>
                                                                <div class="sk-circle9 sk-child"></div>
                                                                <div class="sk-circle10 sk-child"></div>
                                                                <div class="sk-circle11 sk-child"></div>
                                                                <div class="sk-circle12 sk-child"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End modal -->
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-box -->
                        <?php } elseif ($this->config->item('paymentMethod') === 'PayPalCheckout') { ?>
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if (isset($msg)) echo $msg; ?>
                                        <h2 class="header-title m-t-0"><?php echo $this->lang->line('Subscription'); ?></h2>
                                        <?php echo $this->config->item('contentText'); ?>
                                        <div class="prices-boxes">
                                            <div class="row">
                                                <?php if ($this->config->item('paypal1Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-1 <?php if ($this->config->item('paypal1Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal1Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal1Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal1ItemList'); ?>
                                                            </div>
                                                            <a href="<?php echo current_url().'?paypal-offer=1'; ?>" class="btn paypal custom-button-el">
                                                                <span><?php echo $this->config->item('paypal1Btn'); ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('paypal2Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-2 <?php if ($this->config->item('paypal2Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal2Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal2Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal2ItemList'); ?>
                                                            </div>
                                                            <a href="<?php echo current_url().'?paypal-offer=2'; ?>" class="btn paypal custom-button-el">
                                                                <span><?php echo $this->config->item('paypal2Btn'); ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('paypal3Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-3 <?php if ($this->config->item('paypal3Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal3Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal3Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal3ItemList'); ?>
                                                            </div>
                                                            <a href="<?php echo current_url().'?paypal-offer=3'; ?>" class="btn paypal custom-button-el">
                                                                <span><?php echo $this->config->item('paypal3Btn'); ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-box -->
                         <?php } elseif ($this->config->item('paymentMethod') === 'PayPalPro') { ?>
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if (isset($msg)) echo $msg; ?>
                                        <h2 class="header-title m-t-0"><?php echo $this->lang->line('Subscription'); ?></h2>
                                        <?php echo $this->config->item('contentText'); ?>
                                        <div class="prices-boxes">
                                            <div class="row">
                                                <?php if ($this->config->item('paypal1Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-1 <?php if ($this->config->item('paypal1Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal1Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal1Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal1ItemList'); ?>
                                                            </div>
                                                            <button type="button" class="btn paypal custom-button-el" data-subscription="paypalpro1" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('paypal1Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('paypal2Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-2 <?php if ($this->config->item('paypal2Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal2Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal2Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal2ItemList'); ?>
                                                            </div>
                                                            <button type="button" class="btn paypal custom-button-el" data-subscription="paypalpro2" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('paypal2Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($this->config->item('paypal3Active') === '1') { ?>
                                                    <div class="col-sm-4 p-b-20">
                                                        <div class="price_card price-box-3 <?php if ($this->config->item('paypal3Focus') === '1') echo 'active'; ?>">
                                                            <div class="pricing-header bg-primary">
                                                                <span class="price">$<?php echo $this->config->item('paypal3Price'); ?></span>
                                                                <span class="name"><?php echo $this->config->item('paypal3Description'); ?></span>
                                                            </div>
                                                            <div class="price-features">
                                                                <?php echo $this->config->item('paypal3ItemList'); ?>
                                                            </div>
                                                            <button type="button" class="btn paypal custom-button-el" data-subscription="paypalpro3" data-toggle="modal" data-target="#modal">
                                                                <span><?php echo $this->config->item('paypal3Btn'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <!-- Modal -->
                                            <div id="modal" class="modal fade bs-example-modal-sm custom-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <header class="modal-header" role="banner">
                                                            <div>
                                                                <span class="Header-navClose" aria-label="Close"></span>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ion-close-circled"></i></button>
                                                            </div>
                                                            <h1><?php echo $this->config->item('sitename'); ?></h1>
                                                            <h2></h2>
                                                            <div class="header-account" style="position: relative;">
                                                                <div>   
                                                                    <div class="header-logged-bar"></div>
                                                                    <small class="header-logged-email" title="<?php echo $this->config->item('emailsite'); ?>"><?php echo $this->config->item('emailsite'); ?></span>
                                                                </div>
                                                            </div>
                                                        </header>
                                                        <div class="modal-body p-0">
                                                            <form action="<?php echo current_url(); ?>" method="post">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                                                        <input type="text" class="form-control" name="card_number" placeholder="Card Number" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-inline clearfix">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                                                        <input type="text" class="form-control" name="card_expiration" placeholder="MMAAAA" required>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                                                        <input type="text" class="form-control" name="card_cvv" placeholder="CVV" required>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="paypal-offer" value="">
                                                                <button type="submit" class="btn custom-button-el btn-block waves-effect waves-light btn-md m-t-20">
                                                                    <span><?php echo $this->lang->line('Pay'); ?> <i class="dynamic-price">39,99</i></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End modal -->
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-box -->
                         <?php } ?>
                    <?php } else { ?>
                        <?php if(isset($msg)) echo $msg; ?>
                        <div class="card-box text-center">
                            <img src="<?php echo (!empty($profile_image)) ? site_url('uploads/images/users/'.$profile_image) : site_url('assets/images/default-profile.jpg'); ?>" alt="<?php if(isset($username)) echo $username; ?>" class="thumb-img img-responsive">
                        </div>
                        <h2 class="header-title m-t-0"><?php echo $this->lang->line('favorites'); ?></h2>
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="owl-carousel owl-theme" id="owl-favorites">
                                        <?php echo (($getFavsVideos) ? $getFavsVideos : $this->lang->line('noDataYet'));  ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($getPlaylist)) { ?>
                            <h2 class="header-title m-t-0"><?php if(isset($getPlaylistTitle)) echo $getPlaylistTitle; ?></h2>
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="owl-carousel owl-theme" id="owl-playlist">
                                            <?php echo $getPlaylist; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
        				<?php if(isset($auth_coms) && $auth_coms != 0) { ?>
                            <h2 class="header-title m-t-0"><?php echo $this->lang->line('shoutbox'); ?></h2>
            				<div class="p-b-20">
            					<?php echo ($getComsProfile) ? $getComsProfile : $this->lang->line('noDataYet') ; ?>
            				</div>
                            <?php if(isset($this->session->id)) { ?>
                				<form method="post">
                                    <span class="input-icon icon-right">
                                        <textarea rows="8" class="form-control textarea-box" name="com_message"></textarea>
                                    </span>
                                    <input id="related" type="hidden" name="related" value="">
                                    <button type="submit" class="btn btn-sm btn-red waves-effect waves-light m-t-b-20"><?php echo $this->lang->line('Submit Comment'); ?></button>
                				</form>
            				<?php } else { ?>
                                <div class="custom-box">
                                    <span><?php echo $this->lang->line('loginForComment'); ?></span>
                                </div>
            				<?php } ?>
        				<?php } ?>
					<?php } ?>
                </div> <!-- end col -->
                <?php if ($this->config->item('hasSidebar')) { ?>
                    <div class="col-sm-12 col-lg-4">
                        <div class="profile-detail card-box">
        					<h4 class="text-uppercase font-600"><?php echo $this->lang->line('aboutMe'); ?></h4>
        					<p class="text-muted font-13 m-b-30"><?php if($about) echo $about; ?></p>
        					<div class="text-left">
        						<p class="text-muted font-13"><strong><?php echo $this->lang->line('username'); ?> :</strong> <span class="m-l-15"><?php if(isset($username)) echo $username; ?></span></p>
        						<?php if($location) echo '<p class="text-muted font-13"><strong>Location :</strong> <span class="m-l-15">'.$location.'</span></p>'; ?>
        					</div>
        					<div class="button-list m-t-20">
        						<?php if($facebook) { ?>
        						<a href="<?php echo $facebook; ?>" class="btn btn-facebook waves-effect waves-light"><i class="fa fa-facebook"></i></a>
        						<?php } ?>
        						<?php if($twitter) { ?>
        						<a href="<?php echo $twitter; ?>" class="btn btn-twitter waves-effect waves-light"><i class="fa fa-twitter"></i></a>
        						<?php } ?>
        						<?php if($google) { ?>
        						<a href="<?php echo $google; ?>" class="btn btn-googleplus waves-effect waves-light"><i class="fa fa-google"></i></a>
        						<?php } ?>
        						<?php if($linkedin) { ?>
        						<a href="<?php echo $linkedin; ?>" class="btn btn-linkedin waves-effect waves-light"><i class="fa fa-linkedin"></i></a>
        						<?php } ?>
        					</div>
        				</div>
                        <?php if ($subscriber === '1' && isset($badge)) { ?>
                            <div class="card-box badge-widget">
                                <p><i class="fa fa-trophy fa-2x"></i> <?php echo $badge; ?></p>
                            </div>
                        <?php } ?>
                    </div> <!-- end col -->
                <?php } ?>
			</div> <!-- end col -->
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>

<script type="text/javascript">
window.onload = function() {
    $('.price_card button').click(function () {
        var data = $(this).data('subscription');
        $('.modal-body input[name="paypal-offer"]').val(data);
        var text = $('span.name', $(this).parent()).html();
        var price = $('span.price', $(this).parent()).html();
        $('#modal h2').html(text);
        $('#modal button i.dynamic-price').html(price);
    }); 
    $("#owl-favorites, #owl-playlist").owlCarousel({
        loop:true,
        nav:false,
        margin:20,
        autoplay:false,
        dots:false,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:3
            },
            1000:{
                items:3
            }
        }
    });
};
</script>
