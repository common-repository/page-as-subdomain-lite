<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This class initize the plugin
 */

class PASFinitpagePlugin extends PASFsubpageSubdomain{

    function __construct(){

        parent::__construct();

    }

    function addpageActions() {

		add_action( 'init', 'pasf_wps_init_page', 2 ); 

    }

    function addpageFilters(){



		add_filter( 'page_rewrite_rules', 'sub_page_rewrite_rules' );
		add_filter( 'page_link', 'sub_page_link', 100, 2 );


    }

}