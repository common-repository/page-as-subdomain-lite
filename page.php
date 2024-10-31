<?php

/*

* Plugin Name: Page as Subdomain Free
* Description: A WordPress Plugin which Convert Single Page into subdomain.
* Plugin URI: https://wordpress.org/plugins/page-as-subdomain-lite/
* Version: 2.5.2
* Author: alisaleem252 | Gigsix Studio
* Author URI: https://gigsix.com/
* Text Domain: pageassubdomain
*/


define('pageassubdomainPATH', dirname(__FILE__) );
define('pageassubdomainURL', plugin_dir_url( __FILE__ ) );


require_once(pageassubdomainPATH.'/inc/class.PASFsubpageSubdomain.php');
require_once(pageassubdomainPATH.'/inc/class.PASFinitpagePlugin.php');
require_once(pageassubdomainPATH.'/inc/init.php');