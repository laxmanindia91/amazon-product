<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class amazon_vc_table_content_shortcode{	
	
	public function __construct() {
		add_action( 'init', array($this,  'amazon_product_vc_shortcode_table_content' ) ); //vc_before_init
	    add_shortcode( 'amazon_product_tc_shortcode', array($this, 'amazon_product_tc_shortcode_callback' ) );
	}
	public function amazon_product_vc_shortcode_table_content() {
		
		$categories_array = array( __( 'All Categories', 'js_composer' ) => 'mp-all-categories' );
		$category_list = get_terms( 'amazon_category', array( 'hide_empty' => false ) );
		if( is_array( $category_list ) && ! empty( $category_list ) ) {
			foreach ( $category_list as $category_details ) {   
				$begin = __(' (ID: ', 'js_composer');
				$end = __(')', 'js_composer');
				$categories_array[ $category_details->name] = $category_details->slug;  
			}
		}
		vc_map(
			array(
				'name' => __( 'Amazon table of content' ),
				'base' => 'amazon_product_tc_shortcode',
				'category' => __( 'amazon_category' ),
				'params' => array(
					array(
						'type' 			=> 'textfield',
						'holder' 		=> 'div',
						'class' 		=> '',
						'heading' 		=> 'Title',
						'param_name'	=> 'amazon_title',
						'value' 		=> '',
						'description'	=> 'Set your title here',
					),
					array(
						'type' 			=> 'textfield',
						'holder' 		=> 'div',
						'class' 		=> '',
						'heading' 		=> 'Content Count',
						'param_name'	=> 'amazon_number',
						'value' 		=> '',
						'description'	=> 'Set how many table of content to display default (10)',
					),
					array(
						'type' 			=> 'dropdown',
						'heading' 		=> 'Amazon Product Category',
						'param_name' 	=> 'amazon_drop_cat',
						'value' 		=> $categories_array,
						'description' 	=> __( 'Select category for showing product' ),
					),
					array(
						'type' 			=> 'dropdown',
						'heading' 		=> 'Change Table View',
						'param_name' 	=> 'amazon_change_view',
						'value' 		=> array(
												'Open view' => 'open_view',
												'Close View' => 'close_view',
											),
						'description' 	=> __( 'Select View for showing product' ),
					),
					array(
						'type' 			=> 'textfield',
						'holder' 		=> 'div',
						'class' 		=> '',
						'heading' 		=> 'Amazon Extra Class',
						'param_name' 	=> 'amazon_extra_class',
						'value' 		=> '',
						'description' 	=> __( 'Set your extra class for custom css' ),
					),
				)
			)
		);
	}
	/**
	* Function for displaying Title functionality
	*
	* @param array $atts    - the attributes of shortcode
	* @param string $content - the content between the shortcodes tags
	*
	* @return string $html - the HTML content for this shortcode.
	*/
	public function amazon_product_tc_shortcode_callback( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'amazon_title' => '',
				'amazon_number' => 10,
				'amazon_drop_cat' => '',
				'amazon_extra_class'=>'',
				'amazon_change_view' => '',
			), $atts, 'amazon_product_tc_shortcode'
		);
		//ob_flush();
		ob_start();
		
		if($atts['amazon_change_view']== 'close_view'){
			include_once(dirname(__FILE__) .'/inc/table-content-view-two.php');
		}else{
			include_once(dirname(__FILE__) .'/inc/table-content-view-one.php');
		}
		return ob_get_clean();
	}
}
new amazon_vc_table_content_shortcode;