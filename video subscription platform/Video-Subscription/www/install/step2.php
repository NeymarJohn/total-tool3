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
                <?php
                $passed = '';
                $text = '';
                if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                    $text .= '<h5>To Run the '.$app_name.' script, you need at least PHP version 7.0.0</h5><h5>Your PHP Version is : '.PHP_VERSION.' <span class="label label-table label-success">PASSED</span></h5>';
                    $passed .= '1';
                } else {
                    $text .= '<h5 class="m-b-0">To Run '.$app_name.' You need at least PHP version 7.0.0</h5><h5>Your PHP Version is : '.PHP_VERSION.' <span class="label label-table label-danger">PASSED</span></h5>';
                    $passed .= '0';
                }
                if (extension_loaded('PDO')) {
                    $text .= '<h5>PDO is installed on your server : <span class="label label-table label-success">PASSED</span></h5>';
                    $passed .= '1';
                } else {
                    $text = '<h5>PDO is installed on your server : <span class="label label-table label-danger">PASSED</span></h5>';
                    $passed .= '0';
                }
                if (extension_loaded('pdo_mysql')) {
                    $text .= '<h5>PDO MySQL driver is enabled on your server : <span class="label label-table label-success">PASSED</span></h5>';
                    $passed .= '1';
                } else {
                    $text .= '<h5>PDO MySQL driver is not enabled on your server : <span class="label label-table label-danger">PASSED</span></h5>';
                    $passed .= '0';
                }
                if ($passed == '111') {
                    echo($text.'<h5>Great! System Test Completed. You can run '.$app_name.' on your server. Click Continue For Next Step.</h5>
                       <div class="form-group text-right m-b-0">
                            <a href="step3.php" class="btn btn-inverse waves-effect waves-light">Continue</a>
                        </div>');
                } else {
                    echo($text.'<h5>Sorry. The requirements of $app_name is not available on your server.
                       Please contact us with this page : <a href="'.$support_url.'" target="_blank">'.$support_url.'</a> with this code : '.$passed.' or contact with your server administrator</h5>
                       <div class="form-group text-right m-b-0">
                         <a href="#" class="btn btn-inverse waves-effect waves-light">Correct The Problem To Continue</a>
                       </div>');
                } ?>
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