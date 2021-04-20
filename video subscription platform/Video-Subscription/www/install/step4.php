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
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', 0);
@set_time_limit(0);
require ('app_info.php');

$appurl = (isset($_POST['appurl']))?$_POST['appurl']:'';
$db_host = (isset($_POST['dbhost']))?$_POST['dbhost']:'';
$db_user = (isset($_POST['dbuser']))?$_POST['dbuser']:'';
$db_password = (isset($_POST['dbpass']))?$_POST['dbpass']:'';
$db_name = (isset($_POST['dbname']))?$_POST['dbname']:'';

if($appurl == '' OR $db_host == '' OR $db_user == '' OR $db_name == ''){
	header("location: step3.php");
	exit;
}

try{
	$dbh = new pdo(
		"mysql:host=$db_host;dbname=$db_name",
		"$db_user",
		"$db_password",
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
		);
	$sql = file_get_contents('primary.sql');
	$dbh->exec($sql);
	$dbc = TRUE;
}
catch(PDOException $e){
	header("location: step3.php?error=1");
	exit;
}
if($dbc){
	$msg = '<div class="alert alert-success">The data has been successfully added to your database</div>';
}else{
	$msg = '<div class="alert alert-danger">MySQL Connection was successfull but an error occured while adding data on MySQL. Unsuccessfull Installation. Please refer manual installation or contact us : '.$support_url.' for helping on installation</div>';
}
?>
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
				<form action="<?=($dbc)?'step5.php':'step3.php'?>" method="post">
					<fieldset>
						<legend>Database import</legend>
						<?php
						(isset($msg))?$msg:'';
						if($dbc){
						?>
							<p>Now you can log in to your website using these credentials and<br>then change the default password for the account :<br><br>
							- admin@coffeetheme.com<br>
							- password
							</p>
							<p class="text-danger">For security reasons, you must remove the installation directory from your server.</p>
							<div class="form-group text-right m-b-0">
								<button type="submit" id="ib_submit" class="btn btn-inverse waves-effect waves-light">Continue</button>
							</div>
						<?php } else { ?>
							<div class="form-group text-right m-b-0">
								<button type="submit" id="ib_submit" class="btn btn-inverse waves-effect waves-light">Retry</button>
							</div>
						<?php } ?>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<script>
		var resizefunc = [];
	</script>
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
