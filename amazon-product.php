<?php 
/*
*Plugin Name: Amazon product
*Description: Its amazing plugin for fetch product from amozon and showing in custom post where you want
*Plugin URI: https://www.netscriptindia.com/
*version:  3.4	
*Author: NetScriptIndia
*Author URI: #
*
*/

define('BASEPATH', plugin_dir_path( __FILE__ ), true);

/******register post type here*********/
include_once(BASEPATH.'inc/amazon-post-type.php');
/******add custom meta box to custome post type**********/
include_once(BASEPATH.'inc/amazon-meta-box.php');
/************amazon product style and script****************/
include_once(BASEPATH.'inc/amazon-style-script.php');
/************amazon option page***************/
include_once(BASEPATH.'inc/amazon-option.php');
/************amazon Buuton Script***************/
include_once(BASEPATH.'inc/amazon-button-script.php');
/************amazon Search Product***************/
include_once(BASEPATH.'inc/amazon-search-product.php');
/************amazon Search Product for UK***************/
//include_once(BASEPATH.'inc/amazon-search-product-uk.php');
/************amazon product shortcode***************/
include_once(BASEPATH.'template/amazon-shortcode.php');
/************amazon product category template*************/
include_once(BASEPATH.'inc/amazon-category.php');
/************amazon product VC COMPOSER SHORTCODE BACKEND FRONTEND*************/
include_once(BASEPATH.'template/amazon-vc-shortcode.php');


/************amazon product VC COMPOSER SHORTCODE BACKEND FRONTEND -- Table of content *************/
include_once(BASEPATH.'template/amazon-vc-table-content-shortcode.php');

/************** Gift Guide Post type * Under amazon-product menu***************/
include_once(BASEPATH.'inc/amazon-gift-post-type.php');
include_once(BASEPATH.'inc/gift-guide-meta-box.php');include_once(BASEPATH.'template/amazon-gift-guide-vc-shortcode.php');
?>