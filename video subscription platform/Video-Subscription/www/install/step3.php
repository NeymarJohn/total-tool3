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

$action = FALSE;
$appurl = (isset($_POST['appurl']))?$_POST['appurl']:'';
$db_host = (isset($_POST['dbhost']))?$_POST['dbhost']:'';
$db_user = (isset($_POST['dbuser']))?$_POST['dbuser']:'';
$db_password = (isset($_POST['dbpass']))?$_POST['dbpass']:'';
$db_name = (isset($_POST['dbname']))?$_POST['dbname']:'';

if($appurl != '' && $db_host != '' && $db_user != '' && $db_name != ''){
	try{
		$dbh = new pdo(
			"mysql:host=$db_host;dbname=$db_name",
			"$db_user",
			"$db_password",
			array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);

$input = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the \'Database Connection\'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	[\'dsn\']      The full DSN string describe a connection to the database.
|	[\'hostname\'] The hostname of your database server.
|	[\'username\'] The username used to connect to the database
|	[\'password\'] The password used to connect to the database
|	[\'database\'] The name of the database you want to connect to
|	[\'dbdriver\'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	[\'dbprefix\'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	[\'pconnect\'] TRUE/FALSE - Whether to use a persistent connection
|	[\'db_debug\'] TRUE/FALSE - Whether database errors should be displayed.
|	[\'cache_on\'] TRUE/FALSE - Enables/disables query caching
|	[\'cachedir\'] The path to the folder where cache files should be stored
|	[\'char_set\'] The character set used in communicating with the database
|	[\'dbcollat\'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	[\'swap_pre\'] A default table prefix that should be swapped with the dbprefix
|	[\'encrypt\']  Whether or not to use an encrypted connection.
|
|			\'mysql\' (deprecated), \'sqlsrv\' and \'pdo/sqlsrv\' drivers accept TRUE/FALSE
|			\'mysqli\' and \'pdo/mysql\' drivers accept an array with the following options:
|
|				\'ssl_key\'    - Path to the private key file
|				\'ssl_cert\'   - Path to the public key certificate file
|				\'ssl_ca\'     - Path to the certificate authority file
|				\'ssl_capath\' - Path to a directory containing trusted CA certificats in PEM format
|				\'ssl_cipher\' - List of *allowed* ciphers to be used for the encryption, separated by colons (\':\')
|				\'ssl_verify\' - TRUE/FALSE; Whether verify the server certificate or not (\'mysqli\' only)
|
|	[\'compress\'] Whether or not to use client compression (MySQL only)
|	[\'stricton\'] TRUE/FALSE - forces \'Strict Mode\' connections
|							- good for ensuring strict SQL while developing
|	[\'ssl_options\']	Used to set various SSL options that can be used when making SSL connections.
|	[\'failover\'] array - A array with 0 or more data for connections if the main should fail.
|	[\'save_queries\'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the \'default\' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = \'default\';
$query_builder = TRUE;

$db[\'default\'] = array(
	\'dsn\'	=> \'\',
	\'hostname\' => \''.$db_host.'\',
	\'username\' => \''.$db_user.'\',
	\'password\' => \''.$db_password.'\',
	\'database\' => \''.$db_name.'\',
	\'dbdriver\' => \'mysqli\',
	\'dbprefix\' => \'\',
	\'pconnect\' => FALSE,
	\'db_debug\' => (ENVIRONMENT !== \'production\'),
	\'cache_on\' => FALSE,
	\'cachedir\' => \'\',
	\'char_set\' => \'utf8\',
	\'dbcollat\' => \'utf8_general_ci\',
	\'swap_pre\' => \'\',
	\'encrypt\' => FALSE,
	\'compress\' => FALSE,
	\'stricton\' => FALSE,
	\'failover\' => array(),
	\'save_queries\' => TRUE
);';

		$f_msg = '<div class="alert alert-success">Can\'t create config file. The folder is not writable. You must manually configure your database configuration file : application/config/database.php <hr> '.$app_name.' required some folders writable. It seems folders is not writable. The App may not work properly. For common troubleshooting tips, please visit : <strong><a href="'.$support_url.'" target="_blank">'.$support_url.'</a></strong></div>';

		$wConfig = "../application/config/database.php";

		$fh = fopen($wConfig, 'w') or die($f_msg);
		fwrite($fh, $input);
		fclose($fh);

$input = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER[\'SERVER_ADDR\'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/
$config[\'base_url\'] = \''.$appurl.'\';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you\'ve renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config[\'index_page\'] = \'\';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of \'REQUEST_URI\' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| \'REQUEST_URI\'    Uses $_SERVER[\'REQUEST_URI\']
| \'QUERY_STRING\'   Uses $_SERVER[\'QUERY_STRING\']
| \'PATH_INFO\'      Uses $_SERVER[\'PATH_INFO\']
|
| WARNING: If you set this to \'PATH_INFO\', URIs will always be URL-decoded!
*/
$config[\'uri_protocol\']	= \'REQUEST_URI\';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
$config[\'url_suffix\'] = \'\';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config[\'language\']	= \'english\';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config[\'charset\'] = \'UTF-8\';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the \'hooks\' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config[\'enable_hooks\'] = TRUE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config[\'subclass_prefix\'] = \'MY_\';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config[\'composer_autoload\'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config[\'composer_autoload\'] = \'/path/to/vendor/autoload.php\';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config[\'composer_autoload\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match(\'/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config[\'permitted_uri_chars\'] = \'a-z 0-9~%.:_\-\';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string \'words\' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won\'t work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config[\'enable_query_strings\'] = FALSE;
$config[\'controller_trigger\'] = \'c\';
$config[\'function_trigger\'] = \'m\';
$config[\'directory_trigger\'] = \'d\';

/*
|--------------------------------------------------------------------------
| Allow $_GET array
|--------------------------------------------------------------------------
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set \'allow_get_array\' to FALSE.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config[\'allow_get_array\'] = TRUE;

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you\'ll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config[\'log_threshold\'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config[\'log_path\'] = \'\';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default \'php\' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to \'php\'.
|
*/
$config[\'log_file_extension\'] = \'\';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config[\'log_file_permissions\'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config[\'log_date_format\'] = \'Y-m-d H:i:s\';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config[\'error_views_path\'] = \'\';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config[\'cache_path\'] = \'\';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array(\'q\') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config[\'cache_query_string\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config[\'encryption_key\'] = \'\';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| \'sess_driver\'
|
|	The storage driver to use: files, database, redis, memcached
|
| \'sess_cookie_name\'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| \'sess_expiration\'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| \'sess_save_path\'
|
|	The location to save sessions to, driver dependent.
|
|	For the \'files\' driver, it\'s a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the \'database\' driver, it\'s a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| \'sess_match_ip\'
|
|	Whether to match the user\'s IP address when reading the session data.
|
|	WARNING: If you\'re using the database driver, don\'t forget to update
|	         your session table\'s PRIMARY KEY when changing this setting.
|
| \'sess_time_to_update\'
|
|	How many seconds between CI regenerating the session ID.
|
| \'sess_regenerate_destroy\'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for \'cookie_prefix\' and \'cookie_httponly\', which are ignored here.
|
*/
$config[\'sess_driver\'] = \'database\';
$config[\'sess_cookie_name\'] = \'co_session\';
$config[\'sess_expiration\'] = 7200;
$config[\'sess_save_path\'] = "2d_sessions";
$config[\'sess_match_ip\'] = FALSE;
$config[\'sess_time_to_update\'] = 300;
$config[\'sess_regenerate_destroy\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| \'cookie_prefix\'   = Set a cookie name prefix if you need to avoid collisions
| \'cookie_domain\'   = Set to .your-domain.com for site-wide cookies
| \'cookie_path\'     = Typically will be a forward slash
| \'cookie_secure\'   = Cookie will only be set if a secure HTTPS connection exists.
| \'cookie_httponly\' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of \'cookie_prefix\' and
|       \'cookie_httponly\') will also affect sessions.
|
*/
$config[\'cookie_prefix\']	= \'\';
$config[\'cookie_domain\']	= \'\';
$config[\'cookie_path\']		= \'/\';
$config[\'cookie_secure\']	= FALSE;
$config[\'cookie_httponly\'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config[\'standardize_newlines\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config[\'global_xss_filtering\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| \'csrf_token_name\' = The token name
| \'csrf_cookie_name\' = The cookie name
| \'csrf_expire\' = The number in seconds the token should expire.
| \'csrf_regenerate\' = Regenerate token on every submission
| \'csrf_exclude_uris\' = Array of URIs which ignore CSRF checks
*/
$config[\'csrf_protection\'] = FALSE;
$config[\'csrf_token_name\'] = \'csrf_test_name\';
$config[\'csrf_cookie_name\'] = \'csrf_cookie_name\';
$config[\'csrf_expire\'] = 7200;
$config[\'csrf_regenerate\'] = TRUE;
$config[\'csrf_exclude_uris\'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not \'echo\' any values with compression enabled.
|
*/
$config[\'compress_output\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are \'local\' or any PHP supported timezone. This preference tells
| the system whether to use your server\'s local time as the master \'now\'
| reference, or convert it to the configured one timezone. See the \'date
| helper\' page of the user guide for information regarding date handling.
|
*/
$config[\'time_reference\'] = \'local\';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config[\'rewrite_short_tags\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor\'s IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	\'10.0.1.200,192.168.5.0/24\'
| Array:		array(\'10.0.1.200\', \'192.168.5.0/24\')
*/
$config[\'proxy_ips\'] = \'\';';

		$f_msg = '<div class="alert alert-success">Can\'t create config file. The folder is not writable. You must manually configure your configuration file : application/config/config.php <hr> '.$app_name.' required some folders writable. It seems folders is not writable. The App may not work properly. For common troubleshooting tips, please visit : <strong><a href="'.$support_url.'" target="_blank">'.$support_url.'</a></strong></div>';

		$wConfig = "../application/config/config.php";

		$fh = fopen($wConfig, 'w') or die($f_msg);
		fwrite($fh, $input);
		fclose($fh);

		$action = TRUE;
		$msg = '<div class="alert alert-success">Your configuration files were created correctly</div>';
	}
	catch(PDOException $e){
		$msg = '<div class="alert alert-danger">Connection failed: '.$e->getMessage().'</div>';
	}
}else{
	if(isset($_GET['error']) && $_GET['error']==1){
		$msg = '<div class="alert alert-danger">We could not connect to your database. Please re-enter your login credentials.</div>';
	}
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
				<?=isset($msg)?$msg:''?>
				<form action="<?=($action)?'step4.php':'step3.php'?>" method="post">
					<fieldset>
						<legend>Database Connection and Site config</legend>

						<div class="form-group">
							<label for="appurl">Application URL</label>
							<input type="url" class="form-control" id="appurl" name="appurl" value="<?=($action)?$appurl:''?>" placeholder="http://www.example.com/" <?=($action)?'readonly':''?>>
							<span class='help-block'>Your URL website</span>
						</div>
						<div class="form-group">
							<label for="dbhost">Database Host</label>
							<input type="text" class="form-control" id="dbhost" name="dbhost" value="<?=($action)?$db_host:'localhost'?>" <?=($action)?'readonly':''?>>
							<span class="help-block">You should be able to get this info from your web host, if <strong>localhost</strong> doesn’t work.</span>
						</div>
						<div class="form-group">
							<label for="dbuser">Database Username</label>
							<input type="text" class="form-control" id="dbuser" name="dbuser" value="<?=($action)?$db_user:''?>" <?=($action)?'readonly':''?>>
							<span class="help-block">Your database username.</span>
						</div>
						<div class="form-group">
							<label for="dbpass">Database Password</label>
							<input type="text" class="form-control" id="dbpass" name="dbpass" value="<?=($action)?$db_password:''?>" <?=($action)?'readonly':''?>>
							<span class="help-block">Your database password.</span>
						</div>
						<div class="form-group">
							<label for="dbname">Database Name</label>
							<input type="text" class="form-control" id="dbname" name="dbname" value="<?=($action)?$db_name:''?>" <?=($action)?'readonly':''?>>
							<span class="help-block">The name of the database.</span>
						</div>
						<div class="form-group text-right m-b-0">
							<button type="submit" id="ib_submit" class="btn btn-inverse waves-effect waves-light"><?=($action)?'Continue':'Submit'?></button>
						</div>
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
