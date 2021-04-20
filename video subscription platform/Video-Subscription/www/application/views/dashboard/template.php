<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="" />
	<meta name="description" content="">
	<meta name="author" content="" />
	<meta http-equiv="default-style" content="text/css">
	<!-- Favicon -->
	<link rel="icon" href="<?php echo site_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
	<!-- Title -->
	<title><?php if (isset($title))  echo $title . ' - ' . $this->config->item('sitename'); ?></title>
	<!-- Plugins -->
	<link href="<?php echo site_url('assets/plugins/datatables/jquery.dataTables.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/buttons.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/fixedHeader.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/responsive.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/scroller.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/dataTables.colVis.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/datatables/fixedColumns.dataTables.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/morris/morris.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/codemirror/css/codemirror.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/codemirror/css/material.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/nestable/jquery.nestable.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/nvd3/nv.d3.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/cropper/cropper.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/footable/css/footable.core.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/plugins/ladda-buttons/css/ladda-themeless.min.css'); ?>" rel="stylesheet" type="text/css">
	<!-- Core App -->
	<link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('assets/css/sweet-alert.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo site_url('assets/css/flags32.css'); ?>" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script src="<?php echo site_url('assets/js/modernizr.min.js'); ?>"></script>
</head>

<body class="fixed-left back">
	<!-- Begin page -->
	<div id="wrapper">
		<!-- Top Bar Start -->
		<div class="topbar">
			<!-- LOGO -->
			<div class="topbar-left">
				<div class="text-center">
					<a href="<?php echo site_url(''); ?>" class="logo"><?php if (NULL !== $this->config->item('logo')) echo $this->config->item('logo'); ?></a>
				</div>
			</div>
			<!-- Button mobile view to collapse sidebar menu -->
			<div class="navbar navbar-default" role="navigation">
				<div class="container">
					<div class="">
						<div class="pull-left">
							<button class="button-menu-mobile open-left waves-effect waves-light">
								<i class="md md-menu"></i>
							</button>
							<span class="clearfix"></span>
						</div>
						<ul class="nav navbar-nav navbar-right pull-right">
							<li class="dropdown top-menu-item-xs">
								<a href="#" data-target="#" id="notifications" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
									<i class="icon-bell"></i> <?php if ($nbNotifications >= 1) echo '<span class="badge badge-xs badge-danger">' . $nbNotifications . '</span>'; ?>
								</a>
								<ul class="dropdown-menu dropdown-menu-lg">
									<li class="notifi-title"><span class="label label-default pull-right"><?php if ($nbNotifications >= 1) echo $this->lang->line('New') . ' ' . $nbNotifications; ?></span><?php echo $this->lang->line('Notification'); ?></li>
									<li class="list-group slimscroll-noti notification-list">
										<?php if (isset($getNotifications)) echo $getNotifications; ?>
									</li>
									<li>
										<a href="<?php echo site_url('dashboard/notifications/'); ?>" class="list-group-item text-right">
											<small class="font-600"><?php echo $this->lang->line('See all notifications'); ?></small>
										</a>
									</li>
								</ul>
							</li>
							<li class="dropdown top-menu-item-xs">
								<a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo (isset($this->session->name_image)) ? (site_url('uploads/images/users/' . $this->session->name_image)) : (site_url('assets/images/default-user.png')); ?>" alt="<?php echo $this->session->username; ?>" class="img-circle"> </a>
								<ul class="dropdown-menu">
									<?php if ($this->session->id) { ?>
										<li><a href="<?php echo site_url('user/' . $this->session->url . '/'); ?>"><i class="ti-user m-r-10 text-custom"></i> <?php echo $this->lang->line('profile'); ?></a></li>
										<li><a href="<?php echo site_url('user/settings/' . $this->session->url . '/'); ?>"><i class="ti-agenda m-r-10 text-custom"></i> <?php echo $this->lang->line('settings'); ?></a></li>
									<?php } ?>
									<?php if ($this->session->admin) { ?>
										<li><a href="<?php echo site_url('dashboard/'); ?>"><i class="ti-settings m-r-10 text-custom"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
									<?php } ?>
									<?php if (!$this->session->id) { ?>
										<li><a href="<?php echo site_url('login/register/'); ?>"><i class="ti-lock m-r-10 text-custom"></i> <?php echo $this->lang->line('signup'); ?></a></li>
									<?php } ?>
									<li class="divider"></li>
									<li><a href="<?php echo site_url((isset($_SESSION['username'])) ? 'login/logout' : 'login/'); ?>"><i class="ti-power-off m-r-10 <?php echo (isset($_SESSION['username'])) ? 'text-danger' : 'text-success'; ?>"></i> <?php echo (isset($_SESSION['username'])) ? $this->lang->line('logout') : $this->lang->line('login'); ?></a></li>
								</ul>
							</li>
						</ul>
					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<!-- Top Bar End -->

		<!-- ========== Left Sidebar Start ========== -->
		<div class="left side-menu">
			<div class="sidebar-inner slimscrollleft">
				<!--- Divider -->
				<div id="sidebar-menu">
					<ul>
						<li class="text-muted menu-title"><?php echo $this->lang->line('navigation'); ?></li>
						<li><a href="<?php echo site_url('dashboard/'); ?>" class="waves-effect"><i class="ti-home"></i><span> <?php echo $this->lang->line('dashboard'); ?> </span> </a></li>
						<li class="has_sub">
							<a href="javascript:void(0);" class="waves-effect"><i class="ti-video-clapper"></i> <span> <?php echo $this->lang->line('Videos'); ?> </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="<?php echo site_url('dashboard/videos/add/'); ?>" class="waves-effect"><i class=" ti-plus"></i><span> <?php echo $this->lang->line('New Video'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/videos/'); ?>" class="waves-effect"><i class="ti-video-clapper"></i><span> <?php echo $this->lang->line('All Videos'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/categories/'); ?>" class="waves-effect"><i class="ti-package"></i><span> <?php echo $this->lang->line('Categories'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/keywords/'); ?>" class="waves-effect"><i class="ti-pin-alt"></i><span> <?php echo $this->lang->line('Keywords'); ?> </span> </a></li>
							</ul>
						</li>
						<li class="has_sub">
							<a href="javascript:void(0);" class="waves-effect"><i class="ti-layout"></i> <span> <?php echo $this->lang->line('Posts'); ?> </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="<?php echo site_url('dashboard/posts/add/'); ?>" class="waves-effect"><i class=" ti-plus"></i><span> <?php echo $this->lang->line('New Post'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/posts/'); ?>" class="waves-effect"><i class="ti-layout"></i><span> <?php echo $this->lang->line('All Posts'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/postscategories/'); ?>" class="waves-effect"><i class="ti-package"></i><span> <?php echo $this->lang->line('Categories'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/postskeywords/'); ?>" class="waves-effect"><i class="ti-pin-alt"></i><span> <?php echo $this->lang->line('Keywords'); ?> </span> </a></li>
							</ul>
						</li>
						<li class="has_sub">
							<a href="javascript:void(0);" class="waves-effect"><i class="ti-files"></i> <span> <?php echo $this->lang->line('pages'); ?> </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="<?php echo site_url('dashboard/pages/add/'); ?>" class="waves-effect"><i class=" ti-plus"></i><span> <?php echo $this->lang->line('New Page'); ?> </span> </a></li>
								<li><a href="<?php echo site_url('dashboard/pages/'); ?>" class="waves-effect"><i class="ti-files"></i><span> <?php echo $this->lang->line('All Pages'); ?> </span> </a></li>
							</ul>
						</li>
						<li><a href="<?php echo site_url('dashboard/users/'); ?>" class="waves-effect"><i class="ti-user"></i><span> <?php echo $this->lang->line('users'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/comments/'); ?>" class="waves-effect"><i class="ti-comments"></i><!-- <span class="label label-primary pull-right">9</span> --><span> <?php echo $this->lang->line('Comments'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/medias/'); ?>" class="waves-effect"><i class="ti-image"></i><span> <?php echo $this->lang->line('medias'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/menus/'); ?>" class="waves-effect"><i class="ti-menu"></i><span> <?php echo $this->lang->line('Menus'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/payments/'); ?>" class="waves-effect"><i class="ti-wallet"></i><span> <?php echo $this->lang->line('Payments'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/newsletters/'); ?>" class="waves-effect"><i class="ti-email"></i><span> <?php echo $this->lang->line('Newsletter'); ?> </span> </a></li>
						<li><a href="<?php echo site_url('dashboard/tools/'); ?>" class="waves-effect"><i class=" ti-reload"></i><span> <?php echo $this->lang->line('Maintenance'); ?> </span> </a></li>
						<li class="has_sub">
							<a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i> <span> <?php echo $this->lang->line('appSettings'); ?> </span> <span class="menu-arrow"></span></a>
							<ul class="list-unstyled">
								<li><a href="<?php echo site_url('dashboard/settings/'); ?>" class="waves-effect"><i class=" ti-harddrives"></i> <span> <?php echo $this->lang->line('General'); ?> </span></a></li>
								<li><a href="<?php echo site_url('dashboard/settings/theme/'); ?>" class="waves-effect"><i class=" ti-spray"></i> <span> <?php echo $this->lang->line('Theme'); ?> </span></a></li>
								<li><a href="<?php echo site_url('dashboard/settings/payments/'); ?>" class="waves-effect"><i class=" ti-shopping-cart"></i> <span> <?php echo $this->lang->line('Payments'); ?> </span></a></li>
								<li><a href="<?php echo site_url('dashboard/settings/seo/'); ?>" class="waves-effect"><i class="ti-stats-up"></i> <span> <?php echo $this->lang->line('SEO'); ?> </span></a></li>
								<li><a href="<?php echo site_url('dashboard/settings/mail/'); ?>" class="waves-effect"><i class="ti-email "></i> <span> <?php echo $this->lang->line('Emails'); ?> </span></a></li>
								<li><a href="<?php echo site_url('dashboard/settings/advertisements/'); ?>" class="waves-effect"><i class="ti-layout-media-center-alt"></i> <span> <?php echo $this->lang->line('Ads'); ?> </span></a></li>
							</ul>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
				<div class="help-box hidden-sm hidden-md hidden-xs">
					<p class=""><span class="text-custom">Support:</span> <br /> <a class="link-muted" href="http://www.coffeetheme.com/support/" target="_blank">www.coffeetheme.com</a></p>
				</div>
			</div>
		</div> <!-- Left Sidebar End -->

		<!-- ============================================================== -->
		<!-- Start right Content here -->
		<!-- ============================================================== -->
		<div class="content-page">
			<!-- Start content -->
			<div class="content">
				<div class="container ">
					<!-- Page-Title -->
					<div class="row p-t-10">
						<div class="col-sm-12">
							<h4 class="page-title m-b-20"><?php if (isset($title)) echo $title; ?></h4>
						</div>
					</div>
					<?php if (isset($content)) echo $content; ?>
				</div> <!-- container -->
			</div> <!-- content -->
			<footer class="footer">
				Coffee Theme © <?php echo date('Y'); ?>. <?php echo $this->lang->line('signature'); ?>
				<span class="pull-right"><?php echo $this->lang->line('version'); ?> 1.4.2</span>
			</footer>
		</div> <!-- end content-page -->
		<!-- ============================================================== -->
		<!-- End Right content here -->
		<!-- ============================================================== -->
	</div> <!-- end wrapper -->
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
	<!-- Datatable  -->
	<script src="<?php echo site_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.bootstrap.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.buttons.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/buttons.bootstrap.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/jszip.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/pdfmake.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/vfs_fonts.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/buttons.html5.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/buttons.print.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.fixedHeader.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.keyTable.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/responsive.bootstrap.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.scroller.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.colVis.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/datatables/dataTables.fixedColumns.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/datatables.init.js'); ?>"></script>
	<!-- Loading buttons -->
	<script src="<?php echo site_url('assets/plugins/ladda-buttons/js/spin.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/ladda-buttons/js/ladda.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/ladda-buttons/js/ladda.jquery.min.js'); ?>"></script>
	<!-- Alert js -->
	<script src="<?php echo site_url('assets/js/sweet-alert.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.sweet-alert.init.js'); ?>"></script>
	<!-- Input  -->
	<script src="<?php echo site_url('assets/plugins/select2/js/select2.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/bootstrap-select/js/bootstrap-select.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/multiselect/js/jquery.multi-select.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js'); ?>"></script>
	<!-- Graph -->
	<script src="<?php echo site_url('assets/plugins/peity/jquery.peity.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/morris/morris.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/raphael/raphael-min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.dashboard.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/d3/d3.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/nvd3/nv.d3.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.nvd3.init.js'); ?>"></script>
	<!-- Code mirror -->
	<script src="<?php echo site_url('assets/plugins/codemirror/js/codemirror.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/codemirror/js/formatting.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/codemirror/js/css.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/codemirror/js/javascript.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.codemirror.init.js'); ?>"></script>
	<!-- Nestable -->
	<script src="<?php echo site_url('assets/plugins/nestable/jquery.nestable.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/nestable.js'); ?>"></script>
	<!--FooTable-->
	<script src="<?php echo site_url('assets/plugins/footable/js/footable.all.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.footable.js'); ?>"></script>
	<!-- Core App  -->
	<script src="<?php echo site_url('assets/js/jquery.core.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/jquery.app.js'); ?>"></script>
	<!--form validation init-->
	<script src="<?php echo site_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
	<!-- Amazon S3 upload -->
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>
	<script src="<?php echo site_url('assets/js/amazonS3.lib.js'); ?>"></script>
	<script>
		var config = {
			// Place any uploads within the descending folders
			// so ['test1', 'test2'] would become /test1/test2/filename
			//var folders = ['uploads'];        
			upload_path: ['videos'],
			allowed_types: ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'docs', 'zip'],
			max_size: 5120,
			make_unique_filename: true,
			s3_key_field: "key",
			progress_loader_div: "progress-bar-area",
			uploaded_files_field_id: "uploaded"
		};
		ciS3Integration.setConfig(config);
		ciS3Integration.s3Upload("direct-upload");

		function callFileValidation(fileField) {
			// Then we can call our custom function using
			//return false;      
			return ciS3Integration.doFileValidation(fileField);
		}
	</script>

	<!-- Loading buttons -->
	<script>
		$(document).ready(function() {
			// Bind normal buttons
			$('.ladda-button').ladda('bind', {
				timeout: 2000
			});
			// Bind progress buttons and simulate loading progress
			Ladda.bind('.progress-demo .ladda-button', {
				callback: function(instance) {
					var progress = 0;
					var interval = setInterval(function() {
						progress = Math.min(progress + Math.random() * 0.1, 1);
						instance.setProgress(progress);
						if (progress === 1) {
							instance.stop();
							clearInterval(interval);
						}
					}, 200);
				}
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($(".cnt1").length > 0) {
				tinymce.init({
					selector: "textarea.cnt1",
					theme: "modern",
					height: 500,
					plugins: [
						"advlist autolink link image lists charmap print preview hr anchor pagebreak",
						"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
						"save table contextmenu directionality emoticons template paste textcolor"
					],
					toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
					style_formats: [{
							title: 'Title h1',
							block: 'h1'
						},
						{
							title: 'Title h2',
							block: 'h2'
						},
						{
							title: 'Title h3',
							block: 'h3'
						},
						{
							title: 'Bold text',
							inline: 'b'
						},
						{
							title: 'Table styles'
						},
						{
							title: 'Table row 1',
							selector: 'tr',
							classes: 'tablerow1'
						}
					]
				});
			}
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($(".cnt2").length > 0) {
				tinymce.init({
					selector: "textarea.cnt2",
					theme: "modern",
					height: 150,
					menubar: false,
					plugins: ["code"],
					toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code",
					style_formats: [{
							title: 'Title h1',
							block: 'h1'
						},
						{
							title: 'Title h2',
							block: 'h2'
						},
						{
							title: 'Title h3',
							block: 'h3'
						},
						{
							title: 'Bold text',
							inline: 'b'
						},
					]
				});
			}
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($(".cnt3").length > 0) {
				tinymce.init({
					selector: "textarea.cnt3",
					theme: "modern",
					height: 100,
					menubar: false,
					statusbar: false,
					toolbar: false,
				});
			}
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#datatable').dataTable();
			$('#datatable-keytable').DataTable({
				keys: true
			});
			$('#datatable-responsive').DataTable();
			$('#datatable-colvid').DataTable({
				"dom": 'C<"clear">lfrtip',
				"colVis": {
					"buttonText": "Change columns"
				}
			});
			var table = $('#datatable-fixed-header').DataTable({
				fixedHeader: true
			});
			var table = $('#datatable-fixed-col').DataTable({
				scrollY: "300px",
				scrollX: true,
				scrollCollapse: true,
				paging: false,
				fixedColumns: {
					leftColumns: 1,
					rightColumns: 1
				}
			});
		});
		TableManageButtons.init();
	</script>
	<script src="<?php echo site_url('assets/js/jquery.form-advanced.init.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/custom.js'); ?>"></script>
</body>

</html>