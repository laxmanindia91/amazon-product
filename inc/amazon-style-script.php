<?php 
/*********add wp style and script for backend*******************/

/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
 class amazon_enque_style_script {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'amazon_enqueue_admin_style_script') );
	}

	public function amazon_enqueue_admin_style_script($post_type) {
		if(get_post_type( $_GET['post'] ) == 'amazon_product')
		{   
			wp_register_style( 'amazon_wp_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/amazon-style.css', false, '1.0.0' );
			wp_enqueue_style( 'amazon_wp_admin_css' );
			wp_enqueue_script( 'amazon_wp_admin_script', plugin_dir_url( __FILE__ ) . 'assets/js/amazon-script.js', array('jquery'), '1.0' );
			wp_localize_script( 'amazon_wp_admin_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

 }
new amazon_enque_style_script;