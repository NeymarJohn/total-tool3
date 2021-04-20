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
require ('app_info.php');
$cururl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$appurl = str_replace('/install/step5.php', '', $cururl);
header("location: $appurl/login/");
