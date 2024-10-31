<?php


if ( ! defined( 'ABSPATH' ) ) { exit; }


//**************** THIS PART IS FOR PAGE SUBDOMAIN************//
add_filter( 'plugin_row_meta', 'pageassubdomain_plugin_row_meta', 10, 2 );

function pageassubdomain_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'page.php' ) !== false ) {
		$new_links = array(
					'<a href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/" target="_blank">Get Pro</a> | <a href="https://getsslcertificates.com/cart.php?a=add&pid=195" target="_blank">Get Wildcard SSL Certificate</a> | <a href="https://getsslcertificates.com/cart.php?a=add&pid=223" target="_blank">Get Simple SSL Certificate</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}





$obj_subpage = new PASFinitpagePlugin;

$obj_subpage->addpageActions();

$obj_subpage->addpageFilters();


function pasf_wps_init_page () {

	if (!is_admin()) {

		// Stuff changed in WP 2.8

		if (function_exists('set_transient')) {

			set_transient('rewrite_rules', "");

			update_option('rewrite_rules', "");

		} else {

			update_option('rewrite_rules', "");

		}

	}

}


function sub_page_link( $link, $id ){

  if(is_admin())
  return $link;
  
   $o_link = $link; 

   $link = str_replace('www.','',$link);

    if(is_ssl())
    $link = preg_replace('/(?<=https\:\/\/)([a-z0-9_\-\.]+)\/([a-z0-9\-\_]+)/','$2.$1', $link);
    else
    $link = preg_replace('/(?<=http\:\/\/)([a-z0-9_\-\.]+)\/([a-z0-9\-\_]+)/','$2.$1', $link);
    $matches = explode('/',$o_link);
    

    $pageslug = $matches[3];
    $spages = get_option('pages_subdomain',array(1));
    $spage = get_post($spages[0]);
    $page = get_page_by_path($pageslug);

    
    if(isset($spage->post_name) && $pageslug == $spage->post_name)
      return $link;
    return $o_link;
	

}
function sub_page_rewrite_rules($rules){
  	global $obj_subpage;
    $url = getenv( 'HTTP_HOST' );
    $domain = explode( ".", $url );
    $pageslug = $domain[0];
    $spage = get_option('pages_subdomain');
		$page = get_post($spage[0]);
		$page = isset($page->post_name) ? $page->post_name : '';

	   if($pageslug == $page ){

	     $newrules = $obj_subpage->getpageRewriteRules();		
       $rules = (is_array($newrules) && is_array($rules)) ? array_merge($newrules, $rules) : $rules;

      }

    return $rules;


}