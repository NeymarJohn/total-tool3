<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// Default routes
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// simple routes
$route['login'] = 'login/index/';
$route['search'] = 'search/index/';
$route['members'] = 'members/index/';
$route['dashboard'] = 'dashboard/index/';
$route['contact'] = 'contact/index/';
$route['subscribe'] = 'subscribe/index/';
// TODO: delete this
$route['test'] = 'test/index/';
// Frontend routes
$route['video/(:any)'] = 'video/index/$1';
$route['post/(:any)'] = 'post/index/$1';
$route['page/(:any)'] = 'page/index/$1';
$route['user/(:any)'] = 'user/index/$1';
// Posts routes
$route['posts'] = 'post/allPosts/';
$route['posts/(:num)'] = 'post/allPosts/$1';
// Category routes
$route['videos'] = 'category/index/';
$route['videos/(:num)'] = 'category/index///$1';
$route['videos/(:any)'] = 'category/index//$1';
$route['videos/(:any)/(:num)'] = 'category/index//$1/$2';
$route['category/(:any)'] = 'category/index/$1';
$route['category/(:any)/(:num)'] = 'category/index/$1//$2';
$route['category/(:any)/(:any)'] = 'category/index/$1/$2';
$route['category/(:any)/(:any)/(:num)'] = 'category/index/$1/$2/$3';
// Keyword routes
$route['keyword/(:any)'] = 'keyword/index/$1';
$route['keyword/(:any)/(:num)'] = 'keyword/index/$1//$2';
$route['keyword/(:any)/(:any)'] = 'keyword/index/$1/$2';
$route['keyword/(:any)/(:any)/(:num)'] = 'keyword/index/$1/$2/$3';
// Home routes
$route['(:num)'] = 'home/index//$1';
$route['(:any)'] = 'home/index/$1';
$route['(:any)/(:num)'] = 'home/index/$1/$2';
// Backend routes
$route['dashboard/(:any)'] = '$1';
$route['dashboard/(:any)/(:any)'] = '$1/$2';
$route['dashboard/(:any)/(:any)/(:any)'] = '$1/$2/$3';
