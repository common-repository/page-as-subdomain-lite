<?php


if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This class is for admin menu and generating re-write rules remotely from other servers respecting privacy
 */

class PASFsubpageSubdomain{


    function  __construct() {

    //    $this->woofield ='product_cat';

	add_action('admin_menu',array(&$this,'admin_menu'));
	

    }
	function admin_menu(){
		add_submenu_page('edit.php?post_type=page', 'Page as Subdomain', 'Subdomain', 'manage_options', 'page_subdomain', array(&$this,'show_menu'));
	}
	function show_menu(){
		if(isset($_POST['spage']) && wp_verify_nonce($_POST['_wpnonce'],'pasf_action_savepageid')){
			
			$spages = $_POST['spage'];
			foreach($spages as $spage){
				$spages[] = is_int(sanitize_text_field($spage)) ? sanitize_text_field($spage) : 0;
				}
			update_option('pages_subdomain',$spages);

            $donottrack = isset($_POST['donottrack']) ? update_option('pagessubdomain_donottrack',1) : update_option('pagessubdomain_donottrack',false);
            

		}
        $donottrack = get_option('pagessubdomain_donottrack');
		$spages = get_option('pages_subdomain',array('no'));
		?>
		<div class="wrap">
        <h1><?php echo __("Select Page",'pageassubdomain') ?></h1>
        <form method="post">
        <table class="form-table">
        <tr><h1><?php echo __("You can have 1 page as subdomain",'pageassubdomain') ?></h1></tr>
        <tr>
        <th><label><?php echo __("Select A Page",'pageassubdomain') ?>:</label></th>
        
        <?php $args = array(
					'sort_order' => 'asc',
					'sort_column' => 'post_title',
					'parent' => 0,
					'exclude_tree' => '',
					'number' => '',
					'post_type' => 'page',
					'post_status' => 'publish'
				); 
				$pages = get_pages($args); //print_r($pages);?>
        <td>
        <select name="spage[]">
        <option value="no" <?php echo ($spages[0] == 'no' ? 'selected="selected"' : '')?>><?php echo __("SELECT A PAGE",'pageassubdomain') ?></option>
        <?php foreach ($pages as $page){ ?>
        <option value="<?php echo $page->ID ?>" <?php echo ($spages[0] == $page->ID ? 'selected="selected"' : '')?>><?php echo $page->post_title?></option>
        <?php } ?>
        </select>
        
        <?php if($spages[0] !== 'no') {?>
        <a target="_blank" href="<?php echo get_permalink($spages[0]);?>"><?php echo __("View Page",'pageassubdomain') ?></a><span class="dashicons dashicons-external"></span>
        <p><?php echo __("The subdomain link will not showup in the dashboard, but if you have added this page link in the frontend it will be linked to subdomain, this is to avoid any conflicts.",'pageassubdomain') ?></p>
        <?php } ?>
        <p></p>
        </td>
        
        </tr>
        <tr>
        <th><label><?php echo __("Do Not Track Usage",'pageassubdomain') ?>:</label></th>
        <td><input type="checkbox" name="donottrack"  <?php checked(1,$donottrack)?> /> <p><?php echo sprintf( __( 'We track usage to improve the quality of this plugin and provide support, tracking includes the admin email address of this website, your data is protected and secure, you can read the complete %1$sPrivacy Policy%2$s here.', 'pageassubdomain' ), '<a target="_blank" href="http://thesetemplates.info/privacy-policy/">', '</a>' );?></p></td>
        </tr>
        
        <tr>
        <th><label><?php echo __("Add More Pages",'pageassubdomain') ?>:</label></th>
        <td><a href="https://webostock.com/market-item/wordpress-page-subdomain-pro/31776/" target="_blank"><?php echo __("Upgrade",'pageassubdomain') ?> Convert thousands of pages into subdomains with single wildcard</a></td>    
        </tr>
        
        <tr>
        <?php
		if ( function_exists('wp_nonce_field') ) 
			wp_nonce_field('pasf_action_savepageid'); 
		?>
        <td></td>
        <td><input type="submit" class="button-primary" value="Save Settings" /></td>
        </tr>
        
        <tr>
        <th><?php echo __("WildCard Configuration",'pageassubdomain') ?></th>
        <td><?php echo sprintf( __( 'Get %1$sPremium Version%2$s with unlimited pages as subdomain, Learn How to Configure WildCard Subdomain %3$sConfiguring_Wildcard_Subdomains%4$s', 'pageassubdomain' ), 
'<a target="_blank" href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/">', 
'</a>', 
'<a href="https://codex.wordpress.org/Configuring_Wildcard_Subdomains">', 
'</a>' );?></td>
        </tr>
        </table>
        
        </form>
        </div>

        <table class="form-table">
        <tr>

        <th><?php echo __("How to use this plugin",'pageassubdomain') ?>?</th>
        <td><iframe width="560" height="315" src="https://www.youtube.com/embed/Vdcu7M5VT4c" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </td>
        </tr>
        </table>
        <?php require_once(pageassubdomainPATH.'/inc/moreaddons.php');		
	}






    function getpageRewriteRules(){
        $spages = get_option('pages_subdomain',array(1));
        $spages1 = get_post($spages[0]);	
        $rules = array();
        $admin_email = get_option('pagessubdomain_donottrack') ? 'privacy@thesetemplates.info' : get_option('admin_email');


        $field = $spages1->post_name;
$rules["trackback/?$"] = "index.php?pagename=".$field."&tb=1";
	$rules["feed/(feed|rdf|rss|rss2|atom)/?$"] = 'index.php?pagename='.$field.'&feed=$matches[1]';
	$rules["(feed|rdf|rss|rss2|atom)/?$"] = 'index.php?pagename='.$field.'&feed=$matches[1]';
	$rules["page/?([0-9]{1,})/?$"] = 'index.php?pagename='.$field.'&paged=$matches[1]';
	$rules["comment-page-([0-9]{1,})/?$"] = 'index.php?pagename='.$field.'&cpage=$matches[1]';
	$rules["wc-api(/(.*))?/?$"] = 'index.php?pagename='.$field.'&wc-api=$matches[2]';
	$rules["$"] = "index.php?pagename=".$field;
	$rules['([^/]+)/?$'] = 'index.php?pagename='.$field.'/$matches[1]';
        return $rules;
    }
}