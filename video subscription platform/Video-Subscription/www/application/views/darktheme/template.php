<?php
if($this->config->item('maintenance')) { redirect('maintenance/index/'); } ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="<?php if(NULL!==$this->config->item('keywords')) echo $this->config->item('keywords'); ?>" />
	<meta name="description" content="<?php if(NULL!==$this->config->item('description')) echo $this->config->item('description'); ?>">
	<meta name="author" content="<?php if(NULL!==$this->config->item('author')) echo $this->config->item('author'); ?>" />
	<meta http-equiv="default-style" content="text/css">
	<meta property="article:published_time" content="<?php echo date("Y-m-d H:i:s") ?>">
	<meta property="og:description" content="<?php if(isset($ogDescription)) echo mb_strimwidth(strip_tags($ogDescription), 0, 300, '...'); ?>" />
	<meta property="og:title" content="<?php if(isset($title)) echo $title; ?>" />
	<meta property="og:url" content="<?php echo current_url(); ?>" />
	<meta property="og:type" content="article" />
    <meta property="og:image" content="<?php if(isset($ogImage)) echo $ogImage; ?>" />
    <!-- Favicon -->
	<link rel="icon" href="<?php echo site_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <!-- Title -->
	<title><?php if(isset($title)) echo $title; ?></title>
    <!-- Plugins -->
    <link href="<?php echo site_url('assets/css/sweet-alert.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo site_url('assets/css/bootstrap-slider.min.css'); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo site_url('assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/spinkit/8-circle.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/jquery-player/css/vpl.css'); ?>" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo site_url('assets/plugins/jquery-player/css/vpl-scrollbar.css'); ?>" rel="stylesheet" type="text/css"/>
    <!-- Basic styles -->
	<link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/css/darktheme.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/css/flags16.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
	<link href="<?php echo site_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.9&appId=118257285376287";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
	<script src="<?php echo site_url('assets/js/modernizr.min.js'); ?>"></script>
</head>
<body class="fixed-left front darktheme">
	<div id="wrapper">
        <!-- Demo Bar -->
        <?php if($this->config->item('demo') && !$this->session->demo_panel) { ?>
            <div id="demo-panel">
                <ul>
                    <li class="theme-select">
                        <a href="#">Select a theme<i class="fa fa-angle-down"></i></a>
                        <ul>
                            <li><a href="<?php echo site_url('Linkswitch/switchTheme/default/'); ?>">Default <span style="background:#82b340">Include</span></a></li>
                            <li><a href="<?php echo site_url('Linkswitch/switchTheme/darktheme/'); ?>">Dark Theme <span style="background:#DE1212">Include</span></a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="links-panel">
                    <li><a href="https://codecanyon.net/item/videoplay-video-subscription-platform/20656861?ref=coffeetheme">Buy now</a></li>
                    <li><a href="<?php echo site_url('Linkswitch/switchPanel/'); ?>"><i class="fa fa-times"></i> Remove Frame</a></li>
                    <li><a href="https://codecanyon.net/item/videoplay-video-subscription-platform/20656861?ref=coffeetheme"><i class="fa fa-shopping-cart"></i></a></li>
                    <li><a href="<?php echo site_url('Linkswitch/switchPanel/'); ?>"><i class="fa fa-times"></i></a></li>
                </ul>
            </div>
        <?php } ?>
		<!-- Top Bar -->
		<div class="topbar <?php echo ($this->config->item('demo') && !$this->session->demo_panel) ? 'with-demo-panel' : ''; ?>">
			<div class="navbar navbar-default <?php if(isset($hideMenu)) echo 'navbar-hide bg-transparent'; ?>" role="navigation">
				<div class="container">
                        <!-- Button mobile view to collapse sidebar menu -->
                        <div class="pull-left">
                            <button class="button-menu-mobile open-left waves-effect waves-light">
                                <i class="md md-menu"></i>
                            </button>
                            <span class="clearfix"></span>
                        </div>
                        <!-- LOGO -->
        				<div class="topbar-left">
    						<a href="<?php echo site_url(''); ?>" class="logo"><?php if(NULL!==$this->config->item('logo')) echo $this->config->item('logo'); ?></a>
        				</div>
                        <?php if($this->session->id) { ?>
                            <ul class="nav navbar-nav navbar-right pull-right">
    							<li class="dropdown top-menu-item-xs">
    								<a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo (isset($this->session->name_image)) ? (site_url('uploads/images/users/'.$this->session->name_image)) : (site_url('assets/images/default-user.png')); ?>" alt="<?php echo $this->session->username; ?>" class="img-circle"> </a>
    								<ul class="dropdown-menu">
    									<li><a href="<?php echo site_url('user/'.$this->session->url.'/'); ?>"><i class="ti-user m-r-10 text-custom"></i> <?php echo $this->lang->line('Profile'); ?></a></li>
    									<li><a href="<?php echo site_url('user/settings/'.$this->session->url.'/'); ?>"><i class="ti-agenda m-r-10 text-custom"></i> <?php echo $this->lang->line('Settings'); ?></a></li>
        								<?php if($this->session->admin) { ?>
        									<li><a href="<?php echo site_url('dashboard/'); ?>"><i class="ti-settings m-r-10 text-custom"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
        								<?php } ?>
    									<li class="divider"></li>
    									<li><a href="<?php echo site_url((isset($this->session->username)) ? 'login/logout' : 'login/'); ?>"><i class="ti-power-off m-r-10 <?php echo (isset($this->session->username)) ? 'text-danger' : 'text-success'; ?>"></i> <?php echo (isset($this->session->username)) ? $this->lang->line('logout') : $this->lang->line('login'); ?></a></li>
    								</ul>
    							</li>
    						</ul>
                        <?php } else { ?>
                            <ul class="nav navbar-nav navbar-right navbar-login">
                                <li class="top-menu-item-xs hidden-xs"><a href="<?php echo site_url('login/') ?>"><i class="fa fa-lock"></i> <?php echo $this->lang->line('Login'); ?></a></li>
                                <li class="top-menu-item-xs"><a class="btn btn-red btn-login waves-effect waves-light" href="<?php echo site_url('login/register/') ?>"> <?php echo $this->lang->line('Sign Up'); ?></a></li>
                            </ul>
                        <?php } ?>
                        <ul class="nav navbar-nav navbar-menu pull-left">
                            <?php if(isset($getMainMenu)) echo $getMainMenu; ?>
						</ul>
					<!--/.nav-collapse -->
				</div>
			</div>
			<?php if(current_url() !== base_url()) { ?>
			<div class="navbar navbar-default navbar-second" role="navigation">
				<div class="container">
					<ul class="nav navbar-nav">
						<?php if(isset($getSecondMenu)) echo $getSecondMenu; ?>
					</ul>
					<form action="/search" role="search" class="navbar-left app-search pull-right hidden-xs">
						 <input type="text" id="search" name="q" placeholder="<?php echo $this->lang->line('searchForm'); ?>" class="form-control">
						 <a href="#" onclick="window.location.href='<?php echo site_url('search?q='); ?>'+document.getElementById('search').value;"><i class="fa fa-search"></i></a>
					</form>
				</div>
			</div>
			<?php } ?>
        </div>
		<!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">
                <!--- Divider -->
                <div id="sidebar-menu">
                    <ul>
                        <?php if(isset($getMobileMenu)) echo $getMobileMenu; ?>
                    </ul>
                </div>
            </div>
        </div> <!-- Left Sidebar End -->

		<!-- ============================================================== -->
		<!-- Start right Content here -->
		<!-- ============================================================== -->
		<div class="content-page <?php echo ($this->config->item('demo') && !$this->session->demo_panel) ? 'with-demo-panel' : ''; ?>">
			<!-- Start content -->
			<div class="content">
				<?php if(isset($content)) echo $content; ?>
			</div> <!-- End content -->
			<div class="footer-widgets">
				<div class="container container-mobile">
					<div class="row">
						<div class="col-sm-12 col-md-4 footer-widget-box">
							<h3 class="brand"><?php echo $this->config->item('sitename'); ?></h3>
							<p><?php echo $this->config->item('footer_message'); ?></p>
                            <div class="p-b-20">
                                <form id="newsletter-form" action="<?php echo current_url(); ?>" method="post">
                                    <input type="hidden" name="authenticity_token" value="<?php echo random(30); ?>">
                                    <div class="input-group m-t-20 m-b-10 p-r-20">
                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                        <span class="input-group-btn">
                                            <button type="button" name="newsletter" class="btn waves-effect waves-light btn-red"><?php echo $this->lang->line('Subscribe'); ?></button>
                                        </span>
                                    </div>
                                    <div id="newsletter-alert"></div>
                                </form>
                            </div>
                            <?php if($this->config->item('socialIconsWidget')) { ?>
                                <ul class="social-icons social-icons-widget">
    								<?php if($this->config->item('demo')) { ?>
    		                            <li><a href="http://www.coffeetheme.com" class="coffee-color"><i class="fa fa-coffee"></i></a></li>
    								<?php } ?>
    								<?php if($this->config->item('facebook')) { ?>
                                        <li><a href="<?php echo $this->config->item('facebook'); ?>" class="facebook-color"><i class="fa fa-facebook"></i></a></li>
    								<?php } ?>
    								<?php if($this->config->item('twitter')) { ?>
                                        <li><a href="<?php echo $this->config->item('twitter'); ?>" class="twitter-color"><i class="fa fa-twitter"></i></a></li>
    								<?php } ?>
    								<?php if($this->config->item('google')) { ?>
                                        <li><a href="<?php echo $this->config->item('google'); ?>" class="google-color"><i class="fa fa-google-plus"></i></a></li>
    								<?php } ?>
    							</ul>
                            <?php } ?>
						</div>
						<div class="col-sm-12 col-md-3 footer-widget-box">
							<h3><?php echo $this->config->item('footerMenu1Title'); ?></h3>
							<ul>
								<?php if(isset($getFooterMenu1)) echo $getFooterMenu1; ?>
							</ul>
						</div>
						<div class="col-sm-12 col-md-3 footer-widget-box">
							<h3><?php echo $this->config->item('footerMenu2Title'); ?></h3>
							<ul>
								<?php if(isset($getFooterMenu2)) echo $getFooterMenu2; ?>
							</ul>
						</div>
						<div class="col-sm-12 col-md-2 footer-widget-box">
							<h3><?php echo $this->config->item('footerMenu3Title'); ?></h3>
							<ul>
                                <?php if(isset($getFooterMenu3)) echo $getFooterMenu3; ?>
							</ul>
						</div>
					</div>
				</div>
            </div>
            <footer class="footer navbar-default">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-7 col-md-9 footer-row">
                            <span class="copyright"><?php echo $this->lang->line('Copyright'); ?> © 2019 <?php if(NULL!==$this->config->item('sitename')) echo $this->config->item('sitename'); ?></span>
                        </div>
                        <div class="col-sm-5 col-md-3 footer-row">
                            <?php if($this->config->item('socialIconsFooter')) { ?>
                                <ul class="social-icons pull-right">
                                    <?php if($this->config->item('demo')) { ?>
                                    <li><a href="http://www.coffeetheme.com"><i class="fa fa-coffee"></i></a></li>
                                    <?php } ?>
                                    <?php if($this->config->item('facebook')) { ?>
                                    <li><a href="<?php echo $this->config->item('facebook'); ?>"><i class="fa fa-facebook"></i></a></li>
                                    <?php } ?>
                                    <?php if($this->config->item('twitter')) { ?>
                                    <li><a href="<?php echo $this->config->item('twitter'); ?>"><i class="fa fa-twitter"></i></a></li>
                                    <?php } ?>
                                    <?php if($this->config->item('google')) { ?>
                                    <li><a href="<?php echo $this->config->item('google'); ?>"><i class="fa fa-google-plus"></i></a></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div> <!-- end row -->
                </div> <!-- end container -->
            </footer>
		</div>
		<!-- ============================================================== -->
		<!-- End Right content here -->
		<!-- ============================================================== -->

		<!-- Back to top -->
        <a href="#" class="back-to-top" id="back-to-top"> <i class="fa fa-angle-up"></i> </a>
	</div>
	<div class="background"></div>
	<!-- END wrapper -->
	<script>
		var resizefunc = [];
	</script>
	<!-- jQuery  -->
	<script src="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/detect.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/fastclick.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.slimscroll.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.blockUI.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/waves.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/wow.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.nicescroll.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>
	<!-- Rating js -->
    <script src="<?php echo site_url('assets/plugins/raty-fa/jquery.raty-fa.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/jquery.rating.js'); ?>"></script>
    <!-- Alert js -->
    <script src="<?php echo site_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/jquery.sweet-alert.init.js'); ?>"></script>
	<!-- Slider js -->
    <script src="<?php echo site_url('assets/js/bootstrap-slider.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.ui-sliders.js'); ?>"></script>
    <!-- Core jQuery -->
	<script src="<?php echo site_url('assets/js/jquery.core.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.app.js'); ?>"></script>
    <!-- Plugins -->
	<script src="<?php echo site_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js');?>"></script>
    <script src="<?php echo site_url('assets/plugins/owl.carousel/dist/owl.carousel.min.js');?>"></script>
    <?php if ($this->uri->segment(1) === 'video') { ?>
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/froogaloop.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/jquery.mCustomScrollbar.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/THREEx.FullScreen.js'); ?>"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/videoPlayer.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/Playlist.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/ZeroClipboard.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/plugins/jquery-player/js/iphone-inline-video.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/jquery.vpl.init.js'); ?>" type="text/javascript"></script>
    <?php } ?>
    <!-- Scripts -->
    <script src="<?php echo site_url('assets/js/scripts.js');?>"></script>
    <script src="<?php echo site_url('assets/js/custom.js');?>"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Category, Home, User Pages
			$("a.image-popup")
				.mouseenter(function() {
				  	$(this).children().css('opacity', '1');
				  	$(this).parent().next().css('opacity', '1');
				})
				.mouseleave(function() {
				  	$(this).children('.play-button, .play-button-small, .info-video-text').css('opacity', '0');
                });
            $("#video-column a.image-popup, .home-content a.image-popup")
				.mouseleave(function() {
				  	$(this).parent().next().css('opacity', '0');
				});
            // Demo theme select
            $('#demo-panel .theme-select > a').click(function(){
                $('#demo-panel .theme-select ul').toggle();
            });
		});
    </script>
    <script type="text/javascript">
    $("#owl-slider").owlCarousel({
                loop:true,
                nav:false,
                autoplay:true,
                autoplayTimeout:4000,
                autoplayHoverPause:true,
                animateOut: 'fadeOut',
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }
            });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.owl-multi').owlCarousel({
                loop:true,
                margin:20,
                nav:true,
                autoplay:false,
                dots:false,
                responsive:{
                    0:{
                        items:1
                    },
                    480:{
                        items:1
                    },
                    700:{
                        items:2
                    },
                    1000:{
                        items:3
                    },
                    1100:{
                        items:3
                    }
                }
            })
        });
    </script>
    <!-- Google Analytic -->
	<?php echo $this->config->item('google_analytics'); ?>
</body>
</html>
