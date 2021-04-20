<?php
// *************************************************************************
// *                                                                       *
// * Copyright (c) Nicolas Grimonpont. All Rights Reserved                 *
// * Email: support@coffeetheme.com                                        *
// *                                                                       *
// * Website: http://www.coffeetheme.com                                   *
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only in accordance with the terms of such license and with the        *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************
require ('app_info.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="../assets/images/favicon_1.ico">
	<title><?php echo $app_name; ?> Installer</title>
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script src="../assets/js/modernizr.min.js"></script>
</head>
<body>
	<div class="account-pages"></div>
	<div class="clearfix"></div>
	<div class="wrapper-install">
		<div class="landing-box">
			<div class="panel-heading account-logo-box"> 
				<h3 class="text-center"><a href="" class="logo account"><span><?php echo $app_name; ?> Installer</span></a></h3>
			</div> 
			<div class="panel-body">
				<h4>About :</h4>
				<div>Application Name: <?php echo $app_name; ?></div>
				<div>Release Date: <?php echo $release_date; ?></div>
				<div>By: <?php echo $author_name; ?> <a href="http://<?php echo $app_url; ?>" target="_blank"><?php echo $app_url; ?></a></div>
				<h4>Support :</h4>
				<p>If you find bugs or you have ideas for improvement, please contact us using the forum :</p>
				<p><a href="<?php echo $support_url; ?>" target="_blank"><?php echo $support_url; ?></a></p>
				<h4>License :</h4>
				<p>The Regular License grants you, the purchaser, an ongoing, non-exclusive, worldwide license to make use
				of the digital work (Item) you have selected.</p>
				<p><strong>You are licensed to use the Item to create one single End Product for yourself or for one client (a
				"single application"), and the End Product can be distributed for Free.
				You can create one End Product for a client, and you can transfer that single End Product to your client
				for any fee. This license is then transferred to your client.</strong></p>
				<p><strong>You can't Sell the End Product, except to one client.</strong></p>
				<p><strong>You can't re-distribute the Item as stock, in a tool or template, or with source files. You
				can't do this with an Item either on its own or bundled with other items, and even if you modify the
				Item. You can't re-distribute or make available the Item as-is or with superficial modifications.
				These things are not allowed even if the re-distribution is for Free.</strong></p>
				<p><strong>Although you can modify the Item and therefore delete unwanted components before creating your
				single End Product, you can't extract and use a single component of an Item on a stand-alone
				basis.</strong></p>
				<p>This license can be terminated if you breach it. If that happens, you must stop making copies of or
				distributing the End Product until you remove the Item from it.</p>
				<p>The author of the Item retains ownership of the Item but grants you the license on these terms. This
				license is between the author of the Item and you. Envato Pty Ltd is not a party to this license or the
				one giving you the license.</p>
				<p>Read The Full License Here <a href="http://codecanyon.net/licenses/regular" target="_blank">http://codecanyon.net/licenses/regular</a></p>
				<div class="form-group text-right m-b-0">
					<a href="step2.php" class="btn btn-inverse waves-effect waves-light">Accept &amp; Continue</a>
				</div>
			</div>   
		</div>                              
	</div>
	<script>
		var resizefunc = [];
	</script>
	<!-- jQuery  -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/detect.js"></script>
	<script src="../assets/js/fastclick.js"></script>
	<script src="../assets/js/jquery.slimscroll.js"></script>
	<script src="../assets/js/jquery.blockUI.js"></script>
	<script src="../assets/js/waves.js"></script>
	<script src="../assets/js/wow.min.js"></script>
	<script src="../assets/js/jquery.nicescroll.js"></script>
	<script src="../assets/js/jquery.scrollTo.min.js"></script>
	<script src="../assets/js/jquery.core.js"></script>
	<script src="../assets/js/jquery.app.js"></script>
</body>
</html>