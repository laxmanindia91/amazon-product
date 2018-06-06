<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class amazon_gift_guide_vc_shortcode{	
	public function __construct(){
		add_action( 'init', array($this,  'gift_guide_product_vc_shortcode' ) );
	    add_shortcode( 'gift_guide_shortcode', array($this, 'gift_guide_product_vc_shortcode_front' ) );
	}

	public function gift_get_type_posts_data( $post_type = 'gift_guide' ) {	
		$posts = get_posts( 
			array(
			'posts_per_page' 	=> -1,		
			'post_type'			=> $post_type,	
			)
		);	
		$result = array();	
		foreach ( $posts as $post ){
			$result[] = array(			
				'value' => $post->ID,
				'label' => $post->post_title,
			);	
		}
		return $result;
	}
 	public function autocomplete_param_settings_field( $settings, $value ) {
		$autocompletetext .= '<style>.ui-autocomplete{z-index:999999 !important;}</style>';
		$autocompletetext .= '<input type="text" name="' . esc_attr( $settings['param_name'] ).'" class="search_gift_guide wpb_vc_param_value wpb-textinput '.esc_attr($settings['param_name']).' '.esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" placeholder="Search gift guide...">';
		return $autocompletetext; // New button element
	}
	public function hidden_field_param_settings_field( $settings, $value ) {
		$autocompletetext .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class=" wpb_vc_param_value wpb-textinput ' . esc_attr( $settings['class'] ) . ' ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '">';
		return $autocompletetext; // New button element
	}

	
	public function gift_guide_product_vc_shortcode() {
		vc_add_shortcode_param( 'auto_complete', array($this,'autocomplete_param_settings_field') );
		vc_add_shortcode_param( 'vc_hidden_field', array($this,'hidden_field_param_settings_field') );
		vc_map(
			array(
				'name' => __( 'Gift Guide' ),
				'base' => 'gift_guide_shortcode',
				'category' => __( 'Amazon Product' ),
				'params' => array(
					array(
						'type' => 'auto_complete',
						'heading' => 'Title',
						'class'   => 'search_title',
						'param_name' => 'gift_title',
						'value' => '',
						'description' => __( 'Type here get gift' ),
					),
					array(
						'type' => 'vc_hidden_field',
						'heading' => '',
						'class'   => 'hidden_post_value',
						'param_name' => 'gift_id',
						'value' => '',
						'description' => '',
					),
					array(
						"type"        => "dropdown",
						"heading"     => __("View options"),
						"param_name"  => "amazon_front_view",
						'value' 	  => array(
											'-- Select View --' => '',
											'Home View' => 'home',
										),
		                "description" => __("display posts view.")
	                ),
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => '',
						'heading' => 'Content word count',
						'param_name' => 'content_word_count',
						'value' => '',
						'description' => __( 'Characters should be less then 380!!' ),
					),
					array(
						  "type" => "checkbox",
						  "class" => "gift_more_text",
						  "heading" => __( "Show More Text"),
						  "param_name" => "gift_more_text",
						  "value" => __( "" ),
						  "description" => __( "For more then 380 characters.")
						),
					array(
						  "type" => "checkbox",
						  "class" => "gift_load_more",
						  "heading" => __( "Enable Autoload posts"),
						  "param_name" => "enable_auto_load",
						  "value" => __( "" ),
						  "description" => __( "This will work only on Home View.")
					),
					array(
						"type"        => "dropdown",
						"heading"     => __("Show post options"),
						"param_name"  => "amazon_show_post",
						"value"       => array(
                        	'1'   => '-- Select option --',
                            '2'   => 'Show both Gift Guide and Posts',
                            '3'   => 'Show only posts',
							'4'   => 'Show only Gift Guide'
                        ),
		                "description" => __("display all the posts.")
	                ),
					array(
						'type' 			=> 'dropdown',
						'heading' 		=> __("Select Column(s)"),
						'param_name' 	=> "amazon_show_col",
						'value' 		=> array(
											'-- Select option --' => '',
												'2 Column' => '2-column',
												'3 Column' => '3-column',
											),
						'description' 	=> __( 'Select View for showing product' ),
					),
					array(
						'type' 			=> 'dropdown',
						'heading' 		=> __("Select Button view style"),
						'param_name' 	=> "amazon_button_style",
						'value' 		=> array(
											'-- Select option --' => '',
												'My kid needs that' => 'mykidneedsthat',
												'Look Whats Cool' => 'lookwhatscool',
											),
						'description' 	=> __( 'Select Button style for Gift' ),
					),
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => 'inbound_button_text',
						'heading' => 'Inbound button text',
						'param_name' => 'inbound_button_text',
						'value' => '',
						'description' => __( '' ),
					),
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => 'afiliate_button_text',
						'heading' => 'Afiliate button text',
						'param_name' => 'afiliate_button_text',
						'value' => '',
						'description' => __( '' ),
					),
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => '',
						'heading' => 'Extra Class',
						'param_name' => 'gift_extra_class',
						'value' => '',
						'description' => __( 'Set your extra class for custom css' ),
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
	public function gift_guide_product_vc_shortcode_front( $atts, $content, $post ) {
		//ob_flush();
		ob_start();
		$atts = shortcode_atts(
			array(
				'gift_title' => '',
				'gift_extra_class'=>'',
				'gift_id' =>'',
				'amazon_show_post' =>'',
				'amazon_front_view' =>'', 
				'enable_auto_load' => '',
				'gift_more_text' => '',
				'amazon_show_col' =>'',
				'amazon_button_style' =>'',
				'content_word_count' => '',
				'inbound_button_text' => '',
				'afiliate_button_text'  => '',
			), $atts, 'gift_guide_shortcode'
		);
		//Dynamic column class
		if($atts['amazon_show_col'] == '2-column'){
			$dyncol = 'vc_col-md-6 vc_col-xs-12 column2';
		}else{
			$dyncol = 'vc_col-md-4 vc_col-sm-6 vc_col-xs-12 column3';
		}
		
		//button style class
		if($atts['amazon_button_style'] == 'lookwhatscool'){
			$buttonstyle = 'btn-alt';
		}else{
			$buttonstyle = 'see btn btn-info btn-block kidbutton';
		}
		
		if($atts['amazon_front_view'] == 'home'){
			//Home view
			include_once('inc/amazon-home-view.php');
		}else{
			//post view
			if($atts['amazon_show_post'] == 'Show only posts'){
				//post 
				include_once('inc/amazon-gift-guide-post-view.php');
			}elseif($atts['amazon_show_post'] == 'Show only Gift Guide'){
				//gift
				include_once('inc/amazon-gift-guide-gift-view.php');
			}else{
				//both
				include_once('inc/amazon-gift-guide-both-view.php');
			}
		}
		
		
		//$output1 = ob_get_clean();
		//echo $output1; 
		return ob_get_clean();  
	}
				
	public function getLocationInfoByIp2(){
		$client  = $_SERVER['HTTP_CLIENT_IP'];
		$forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		$result  = array('country'=>'', 'city'=>'');
		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$ip_data_in = curl_exec($ch); // string
		curl_close($ch);  
		$ip_data = json_decode($ip_data_in);
		if($ip_data && $ip_data->geoplugin_countryName != null){
			$result['country'] = $ip_data->geoplugin_countryCode;
			//$result['city'] = $ip_data->geoplugin_city;
		}
		return $result;
	}
	
}

new amazon_gift_guide_vc_shortcode; 
