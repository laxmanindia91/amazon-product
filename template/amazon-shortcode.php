<?php 
/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
class amazon_shortcode {

	public function __construct() {
		add_shortcode( 'amazon-shortcode', array( $this, 'amazon_shortcode_embend') );
		add_action( 'wp_enqueue_scripts', array( $this, 'amazon_enqueue_frontend_style_script') );
		add_action( 'wp_ajax_nopriv_amazon_link_request', array( $this, 'amazon_link_request') );
		add_action( 'wp_ajax_amazon_link_request', array( $this, 'amazon_link_request') );
		
		add_action( 'wp_ajax_nopriv_gift_link_request', array( $this, 'gift_link_request') );
		add_action( 'wp_ajax_gift_link_request', array( $this, 'gift_link_request') );
		
		add_action( 'wp_ajax_nopriv_get_post_name', array( $this, 'get_post_name') );
		add_action( 'wp_ajax_get_post_name', array( $this, 'get_post_name') );
		
		add_action( 'wp_ajax_nopriv_gift_link_request_post', array( $this, 'gift_link_request_post') );
		add_action( 'wp_ajax_gift_link_request_post', array( $this, 'gift_link_request_post') );
		
		add_action( 'wp_ajax_nopriv_gift_link_request_with_post', array( $this, 'gift_link_request_with_post') );
		add_action( 'wp_ajax_gift_link_request_with_post', array( $this, 'gift_link_request_with_post') );
		
		add_action( 'wp_ajax_nopriv_gift_guide_dyn_gift_link', array( $this, 'gift_guide_dyn_gift_link') );
		add_action( 'wp_ajax_gift_guide_dyn_gift_link', array( $this, 'gift_guide_dyn_gift_link') );
		
		add_action( 'wp_ajax_nopriv_gift_guide_dyn_link', array( $this, 'gift_guide_dyn_link') );
		add_action( 'wp_ajax_gift_guide_dyn_link', array( $this, 'gift_guide_dyn_link') );
		
	}
	public function amazon_link_request()
	{
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$product   					= get_post( $_POST['return_product_url'] );
		$amazon_content 			= apply_filters( 'the_content', $product->post_content );
		$amazon_title 				= $product->post_title;
		$amazon_name 				= $product->post_name;
		$amazon_product_image		= get_post_meta($product->ID,'amazon_product_image',true);
		$amazon_insertPrice 		= get_post_meta($product->ID,'amazon_insertPrice',true);
		$amazon_searchtitle 		= get_post_meta($product->ID,'amazon_searchtitle',true);
		$amazon_pros 				= get_post_meta($product->ID,'amazon_pros',true);
		$amazon_cons 				= get_post_meta($product->ID,'amazon_cons',true);
		$amazon_summary 			= get_post_meta($product->ID,'amazon_summary',true);
		
		$amazon_searchurl 			= get_post_meta($product->ID,'amazon_searchurl',true);
		$amazon_img_searchurl 		= get_post_meta($product->ID,'amazon_img_searchurl',true);
		
		$amazon_link_usa 			= get_post_meta($product->ID,'amazon_link',true);
		$amazon_link_uk 			= get_post_meta($product->ID,'amazon_link_uk',true);
		
		$categories = get_the_terms( $product->ID, 'amazon_category' );
		foreach( $categories as $category ) {
			$amazon_product_cat = $category->name;
		}
		
		$search_keyword 			= explode('&',$amazon_searchurl);
		$search_keyword 			= explode('field-keywords=',$search_keyword[1]);
		$search_key 				= $search_keyword[1];
		
		
		if($countery == 'GB'){ //GB 
			if(!empty($amazon_link_uk)){
				$amazon_link = $amazon_link_uk.'?tag='.get_option('amazon_uk');
				$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
				$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk').'&brand='.$amazon_searchtitle;
			}else{
				$amazon_url = str_replace('.com','.co.uk', $amazon_img_searchurl);
				$amazon_link = $amazon_url.'&tag='.get_option('amazon_uk');
				$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
				$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk').'&brand='.$amazon_searchtitle;
			}
		}else{
			$amazon_link = $amazon_link_usa.'?tag='.get_option('amazon_us');
			$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_us').'&brand='.$amazon_searchtitle;
		}
		
		//Here i have changed $search_key -> $amazon_product_cat (line-88)
		
		$titel_link = '<a data-check="success" href="'. $amazon_link .'" target="_blank" rel="nofollow">'. $amazon_title .'</a>';
		$image_link = '<a data-check="success" href="'. $amazon_link .'" target="_blank" rel="nofollow"><img src="'.$amazon_product_image .'" class="product_image attachment-listing" alt="'. $amazon_title .'"></a>';
		$more_by_link = 'View More <strong>'. $amazon_product_cat .'</strong> By <a data-check="success" href="'. $amazon_searchurl .'" target="_blank" rel="nofollow">'. $amazon_searchtitle .' »</a>';
		$read_more = 'Read  &nbsp;&nbsp;<a data-check="success" href="'. $amazon_link .'" target="_blank" class="product-info" rel="nofollow"> Buyer Reviews » </a>';
		$dynamic_button_url = '<a class="btn-alt" data-check="success" href="'. $amazon_link .'" target="_blank" rel="nofollow">'."Check Price Now <i class='fa fa-arrow-right' aria-hidden='true'></i>".'</a>';
		echo json_encode(array('titel_link'=>$titel_link,'image_link'=>$image_link,'more_by_link'=>$more_by_link,'read_more'=>$read_more,'dynamic_button_url'=>$dynamic_button_url));
		die();
	}
	
	
	
	public function gift_link_request(){
		
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$post_id = $_POST['post_id'];
		$column = $_POST['col'];
		$style = $_POST['style'];
		$site = $_POST['site'];
		
		$d='';
		if($site == 'lookwhatscool'){$d='';}else{$d='gift-button';}
		$html = '';
			
		$title = explode('^',get_post_meta($post_id, 'gift_title', true));	
		$link = explode('^',get_post_meta($post_id, 'gift_link', true));
		$img = explode('^',get_post_meta($post_id, 'gift_image', true));
		$gift_link_uk = explode('^',get_post_meta($post_id, 'gift_link_uk', true));
		$gift_link_inbound = explode('^',get_post_meta($post_id, 'gift_link_inbound', true));
		$description = explode('^',get_post_meta($post_id, 'gift_description', true));
		
		$gift_array = array();
		
		for($gift=0; $gift<count($title); $gift++){
			$gift_array[$gift][]=$title[$gift];
			//$gift_array[$gift][]=$link[$gift];
			
			if($countery == 'GB'){ //GB
				if(!empty($gift_link_uk[$gift])){
					$gift_array[$gift][] = $gift_link_uk[$gift].'?tag='.get_option('amazon_uk');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.co.uk/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_uk');
				}
			}elseif($countery == 'IN'){ //US
				if(!empty($link[$gift])){
					$gift_array[$gift][] = $link[$gift].'?tag='.get_option('amazon_us');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
				}
			}
			else{
				$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
			}
			
			$gift_array[$gift][]=$img[$gift];
			$gift_array[$gift][]=$description[$gift];
			$gift_array[$gift][]=$gift_link_inbound[$gift];
		}
		$count=0;
		//$html .='<div class="vc_col-sm-12">';
		foreach($gift_array as $gift) 
		{ 
			/*if(($count%3)===0){
				$html .='</div>	<div style="height: 10px; overflow:hidden; clear:both;"></div><div class="vc_col-sm-12">';
			}*/
			$html .='<div class="'.$column.'">
				<div class="gift_image-wrap gift_item">
					<div class="title-wrap gift-inner-wrap productname">
						<h3><a href="'. $gift[1] .'" rel="nofollow" target="_blank">'.$gift[0] .'</a></h3>
					</div>
					<p class="image aligncenter gift_img">
						<a href="'. $gift[1] .'" rel="nofollow" target="_blank"><img class="lazy" data-original="" alt="" src="'. site_url().$gift[2] .'"></a>
					</p>
					
					<div class="gift-inner-wrap content">
						<p style="text-align: justify;">'. substr($gift[3], 0, 378) .'</p>
					</div>
					<div class="'.$d.' check_price">';
					if(!empty($gift[4])){
							$html .= '<a href=" '. $gift[4] .'" class="'.$style.'" rel="nofollow" target="_blank">Check It Out <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
					}else{
						$html .= '<a href=" '. $gift[1] .'" class="'.$style.'" rel="nofollow" target="_blank">CHECK IT OUT <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
					}
					$html .= '</div>
				</div>
			</div>';
			$count++;
		 }
		 $html .='';
			echo json_encode(array('html'=>$html));
		die();
	}
	
	// For post view
	public function gift_link_request_post(){
		
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$post_id = $_POST['post_id'];
		$column = $_POST['col'];
		$style = $_POST['style'];
		$site = $_POST['site'];
		
		$d='';
		if($site == 'lookwhatscool'){$d='';}else{$d='gift-button';}
		$html = '';
			
		$post_title 	  = get_post_meta($post_id, 'gift_post_title', true);	
		$link_us 		  = get_post_meta($post_id, 'gift_post_link_us', true);
		$link_uk 		  = get_post_meta($post_id, 'gift_post_link_uk', true);
		$gift_post_image  = get_post_meta($post_id, 'gift_post_image', true);
		$post_content  	  = get_post_meta($post_id, 'gift_guide_content', true);
		
		if($countery == 'GB'){ //GB
			if(!empty($link_uk)){
				$link = $link_uk .'?tag='.get_option('amazon_uk');
			}else{
				$search_link = 'https://www.amazon.co.uk/s/?url=search-alias&field-keywords='.$post_title.'&tag='.get_option('amazon_uk');
			}
		}else{ //US
			if(!empty($link_us)){
				$link = $link_us .'?tag='.get_option('amazon_us');
			}else{
				$search_link = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$post_title.'&tag='.get_option('amazon_us');
			}
		}
		
		$html .='<div class="'. $column .'">
				<div class="gift_image-wrap gift_item">
					<div class="title-wrap gift-inner-wrap productname">
						<h3><a href="'. $link .'" rel="nofollow" target="_blank">'.$post_title .'</a></h3>
					</div>
					<p class="image aligncenter gift_img">
						<a href="'. $link .'" rel="nofollow" target="_blank"><img class="lazy" data-original="" alt="" src="'. site_url().$gift_post_image .'"></a>
					</p>
					
					<div class="gift-inner-wrap content">
						<p style="text-align: justify;">'. substr($post_content, 0, 378) .'</p>
					</div>
					<div class="'.$d.' check_price">';
		$html .= '<a href="'. $link .'" class="'.$style.'" rel="nofollow" target="_blank">Check It Out <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
		$html .= '</div></div></div>';
		
		$html .='';
		echo json_encode(array('html'=>$html));
		die();
	}
	
	// Gift Guide with post (Both)
	public function gift_link_request_with_post(){
		
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$post_id = $_POST['post_id'];
		$column = $_POST['col'];
		$style = $_POST['style'];
		$site = $_POST['site'];
		$more = $_POST['moretext'];
		$d='';
		if($site == 'lookwhatscool'){$d='';}else{$d='gift-button';}
		$html = '';
			
		$title = explode('^',get_post_meta($post_id, 'gift_title', true));	
		$link = explode('^',get_post_meta($post_id, 'gift_link', true));
		$img = explode('^',get_post_meta($post_id, 'gift_image', true));
		$gift_link_uk = explode('^',get_post_meta($post_id, 'gift_link_uk', true));
		$gift_link_inbound = explode('^',get_post_meta($post_id, 'gift_link_inbound', true));
		$description = explode('^',get_post_meta($post_id, 'gift_description', true));
		
		$gift_array = array();
		
		for($gift=0; $gift<count($title); $gift++){
			$gift_array[$gift][]=$title[$gift];
			//$gift_array[$gift][]=$link[$gift];
			
			if($countery == 'GB'){ //GB
				if(!empty($gift_link_uk[$gift])){
					$gift_array[$gift][] = $gift_link_uk[$gift].'?tag='.get_option('amazon_uk');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.co.uk/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_uk');
				}
			}elseif($countery == 'IN'){ //US
				if(!empty($link[$gift])){
					$gift_array[$gift][] = $link[$gift].'?tag='.get_option('amazon_us');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
				}
			}
			else{
				$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
			}
			
			$gift_array[$gift][]=$img[$gift];
			$gift_array[$gift][]=$description[$gift];
			$gift_array[$gift][]=$gift_link_inbound[$gift];
		}
		$count=0;
		//$html .='<div class="vc_col-sm-12">';
		if($more==1){
			$html .= '<div class="vc_row">';	
		}
		foreach($gift_array as $gift) 
		{ 
			if($more==1){
				if($count%2===0){
					$html .= '</div><div class="vc_row">';	
				}
			}
			$html .='<div class="'.$column.'">
				<div class="gift_image-wrap gift_item">
					<div class="title-wrap gift-inner-wrap productname">
						<h3><a href="'. $gift[1] .'" rel="nofollow" target="_blank">'.$gift[0] .'</a></h3>
					</div>
					<p class="image aligncenter gift_img">
						<a href="'. $gift[1] .'" rel="nofollow" target="_blank"><img class="lazy" data-original="" alt="" src="'. site_url().$gift[2] .'"></a>
					</p>
					
					<div class="gift-inner-wrap content">
						<p style="text-align: justify;">'. substr($gift[3], 0, 378) .'</p>
					</div>
					<div class="'.$d.' check_price">';
					if(!empty($gift[4])){
							$html .= '<a href=" '. $gift[4] .'" class="'.$style.'" rel="nofollow" target="_blank">Check It Out <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
					}else{
						$html .= '<a href=" '. $gift[1] .'" class="'.$style.'" rel="nofollow" target="_blank">CHECK IT OUT <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
					}
					$html .= '</div>
				</div>
			</div>';
		if($more==1){  $html .= '</div>'; }
			$count++;
		 }
		 $html .='';
			echo json_encode(array('html'=>$html));
		die();
	}
	
	// Home page design dyn link (gift)
	public function gift_guide_dyn_gift_link(){
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$post_id = $_POST['post_id'];
		$column = $_POST['col'];
		$style = $_POST['style'];
		$site = $_POST['site'];
		
		$word_count = $_POST['count'];
		$afiliate_button_text = $_POST['afiliatetext'];
		$inbound_button_text = $_POST['inboundtext'];
		
		$d='';
		if($site == 'lookwhatscool'){$d='';}else{$d='gift-button';}
		$html = '';
			
		$title = explode('^',get_post_meta($post_id, 'gift_title', true));	
		$link = explode('^',get_post_meta($post_id, 'gift_link', true));
		$img = explode('^',get_post_meta($post_id, 'gift_image', true));
		$gift_link_uk = explode('^',get_post_meta($post_id, 'gift_link_uk', true));
		$gift_link_inbound = explode('^',get_post_meta($post_id, 'gift_link_inbound', true));
		$description = explode('^',get_post_meta($post_id, 'gift_description', true));
		
		$gift_array = array();
		
		for($gift=0; $gift<count($title); $gift++){
			$gift_array[$gift][]=$title[$gift];
			//$gift_array[$gift][]=$link[$gift];
			
			if($countery == 'GB'){ //GB
				if(!empty($gift_link_uk[$gift])){
					$gift_array[$gift][] = $gift_link_uk[$gift].'?tag='.get_option('amazon_uk');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.co.uk/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_uk');
				}
			}elseif($countery == 'US'){ //US
				if(!empty($link[$gift])){
					$gift_array[$gift][] = $link[$gift].'?tag='.get_option('amazon_us');
				}else{
					$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
				}
			}
			else{
				$gift_array[$gift][] = 'https://www.amazon.com/s/?url=search-alias&field-keywords='.$title[$gift].'&tag='.get_option('amazon_us');
			}
			
			$gift_array[$gift][]=$img[$gift];
			$gift_array[$gift][]=$description[$gift];
			$gift_array[$gift][]=$gift_link_inbound[$gift];
		}
		$count=0;
		$html = '';
		foreach($gift_array as $gift) 
		{ 
			if($gift[4]){
				$link_in = $gift[4];
				$follow = 'dofollow';
			}else{
				$link_in = $gift[1];
				$follow = 'nofollow';
			}
			$html .='<div class="'.$column.' ajax_lp"   id="ajax_lp_'. $count .'">
				<div class="gift-grid__item">
					<article class="post-preview">
						<figure class="post-preview__img-wrap">
							<a href="'. $gift[1] .'" rel="nofollow" target="_blank"><img class="post-preview__img" alt="" src="'. site_url().$gift[2] .'"></a>
						</figure>
						<div class="post-preview__content">
							<h2 class="post-preview__title"><a href="'. $link_in .'" rel="'.$follow.'" target="_blank">'.$gift[0] .'</a></h2>
							<p class="post-preview__txt">'. substr($gift[3], 0, $word_count).'</p>
						</div>
						<div class="post-preview__bottom">
							<div class="post-preview__bottom__col">
						</div>
						<div class="post-preview__bottom__col">';
			if($gift[4]){
				$html .='<a class="btn-alt" href="'. $gift[4] .'" rel="'.$follow.'" target="_blank">'.$inbound_button_text.'</a>';
			}else{
				$html .='<a class="btn-alt" href="'. $gift[1] .'" rel="nofollow" target="_blank">'.$afiliate_button_text.'</a>';
			}
			$html .= '
						</div>
					</article>					
				</div>
			</div>';
			$count++;
		 }
		 $html .='';
			echo json_encode(array('html'=>$html));
		die();
	}
	
	// Home page design dyn link (post)
	public function gift_guide_dyn_link(){
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$post_id = $_POST['post_id'];
		$column = $_POST['col'];
		$style = $_POST['style'];
		$site = $_POST['site'];
		
		$word_count = $_POST['count'];
		$afiliate_button_text = $_POST['afiliatetext'];
		$inbound_button_text = $_POST['inboundtext'];
		
		$d='';
		if($site == 'lookwhatscool'){$d='';}else{$d='gift-button';}
		$html = '';
			
		$post_title 	  = get_post_meta($post_id, 'gift_post_title', true);	
		$link_us 		  = get_post_meta($post_id, 'gift_post_link_us', true);
		$link_uk 		  = get_post_meta($post_id, 'gift_post_link_uk', true);
		$gift_post_image  = get_post_meta($post_id, 'gift_post_image', true);
		$post_content  	  = get_post_meta($post_id, 'gift_guide_content', true);
		$inbound_link 	  = get_post_meta($post_id, 'gift_post_inbound', true);
		
		if($countery == 'GB'){ //GB
			if(!empty($link_uk)){
				$link = $link_uk .'?tag='.get_option('amazon_uk');
			}
		}else{ //US
			if(!empty($link_us)){
				$link = $link_us .'?tag='.get_option('amazon_us');
			}
		}
		
		if($inbound_link){
			$link_in = $inbound_link;
			$follow = 'dofollow';
		}else{
			$link_in = $link;
			$follow = 'nofollow';
		}
		
		$html ='';
		$html .='<div class="'. $column .' ajax_lp"  id="ajax_lp_'. $post_id .'">
				<div class="gift-grid__item">
						<article class="post-preview">
							<figure class="post-preview__img-wrap">
								<a href="'. $link .'" rel="nofollow" target="_blank"><img class="post-preview__img" alt="" src="'. site_url().$gift_post_image .'"></a>
							</figure>
							<div class="post-preview__content">
								<h2 class="post-preview__title"><a href="'. $link_in .'" rel="'.$follow.'" target="_blank">'.$post_title .'</a></h2>
								<p class="post-preview__txt">'. substr($post_content, 0, $word_count).'</p>
							</div>
							
							<div class="post-preview__bottom">
								<div class="post-preview__bottom__col">
							</div>
							<div class="post-preview__bottom__col">
								<a href="';
		if($inbound_link){
			$html .= $link_in .'" class="btn-alt '.$style.'" rel="'.$follow.'" target="_blank">'.$inbound_button_text.'</a>';
		}else{
			$html .= $link .'" class="btn-alt '.$style.'" rel="nofollow" target="_blank">'.$afiliate_button_text.'</a>';
		}
		$html .= '
							</div>
						</article>					
					</div>';
		
		$html .='';
		echo json_encode(array('html'=>$html));
		die();
	}
	
	
	public function amazon_enqueue_frontend_style_script($post_type) {
		wp_register_style( 'amazon_wp_frontend_css', plugin_dir_url( __FILE__ ) . 'assets/css/amazon-frontend-style.css', false, '1.0.0' );
		wp_enqueue_style( 'amazon_wp_frontend_css' );
		wp_register_style( 'amazon_wp_frontend_font', plugin_dir_url( __FILE__ ) . 'assets/css/amazon-font-awesome.css', false, '1.0.0' );
		wp_enqueue_style( 'amazon_wp_frontend_font' );
		//wp_enqueue_script( 'amazon_wp_frontend_script', plugin_dir_url( __FILE__ ) . 'assets/js/amazon-echo.js', array('jquery'), '1.0' );
		//wp_enqueue_script( 'amazon_wp_frontend_script', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.lazy.min.js', array('jquery'), '1.0' );
		wp_enqueue_script( 'amazon_wp_frontend_script', plugin_dir_url( __FILE__ ) . 'assets/js/amazon-frontend-script.js', array('jquery'), '1.0' );
		global $wp_query;
		wp_localize_script( 'amazon_wp_frontend_script', 'ajax_object_frontend',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );	
	}
	

	public function amazon_shortcode_embend($args) {
		wp_register_style( 'rk-styles',  plugin_dir_url( __FILE__ ) . 'assets/css/js_composer.min.css');
		wp_enqueue_style( 'rk-styles' );
		
		wp_enqueue_script( 'rk-js',  plugin_dir_url( __FILE__ ) . 'assets/js/js_composer_front.min.js',array('jquery'), '1.0', true );
		
		
		$ip = getLocationInfoByIp1();
		$countery = $ip[country];
		
		$view = $args['view'];
		$column = $args['column'];
		
		if(isset($args['product_id'])){
			
			$product   					= get_post($args['product_id']);
			$amazon_content 			= apply_filters( 'the_content', $product->post_content );
			$amazon_title 				= $product->post_title;
			
			$amazon_name 				= $product->post_name;
			$amazon_top_pick			= get_post_meta($product->ID,'amazon_top_pick',true);
			$amazon_like_about			= get_post_meta($product->ID,'amazon_like_about',true);
			$amazon_image_alt_tag		= get_post_meta($product->ID,'amazon_image_alt_tag',true);
			$amazon_link_disable		= get_post_meta($product->ID,'amazon_link_disable',true);
			$amazon_age_range			= get_post_meta($product->ID,'amazon_age_range',true);
			
			$amazon_product_image		= get_post_meta($product->ID,'amazon_product_image',true);
			$amazon_insertPrice 		= get_post_meta($product->ID,'amazon_insertPrice',true);
			$amazon_searchtitle 		= get_post_meta($product->ID,'amazon_searchtitle',true);
			$amazon_pros 				= get_post_meta($product->ID,'amazon_pros',true);
			$amazon_cons 				= get_post_meta($product->ID,'amazon_cons',true);
			$amazon_summary 			= get_post_meta($product->ID,'amazon_summary',true);
			$amazon_link_usa 			= get_post_meta($product->ID,'amazon_link',true);
			$amazon_link_uk 			= get_post_meta($product->ID,'amazon_link_uk',true);
			$amazon_searchurl 			= get_post_meta($product->ID,'amazon_searchurl',true);
			$amazon_img_searchurl 		= get_post_meta($product->ID,'amazon_img_searchurl',true);
			$amazon_offerprice 			= get_post_meta($product->ID,'amazon_search_offer_price',true);
			
			$search_keyword 			= explode('&',$amazon_searchurl);
			$search_keyword 			= explode('field-keywords=',$search_keyword[1]);
			$search_key 				= $search_keyword[1];
			
			$categories = get_the_terms( $product->ID, 'amazon_category' );
			foreach( $categories as $category ) {
				$amazon_product_cat = $category->name;
			}
			
			
				if($countery == 'GB'){ //GB
				if(!empty($amazon_link_uk)){
					$amazon_link = $amazon_link_uk.'?tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}else{
					$amazon_url = str_replace('.com','.co.uk', $amazon_img_searchurl);
					$amazon_link = $amazon_url.'&tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}
			}else{
			$amazon_link = $amazon_link_usa.'?tag='.get_option('amazon_us');
			$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_us').'&brand='.$amazon_searchtitle;
		}
			
		// First View
		if($view == 1) { ?>
		<div class="skinset-background" style="overflow:hidden">
			<div class="product-feature type_list">
			  <div class="row">
				<div id="<?php echo $amazon_name; ?>" class="item-list vc_col-sm-12 dyncmiclink_product" data-return_product_url="<?php echo $product->ID; ?>">
			  <div class="row vc_row-o-equal-height vc_row-flex vc_row-o-content-middle">
				<div class="vc_col-sm-6 title-wrap">
				  <h2><?php echo $amazon_title; ?></h2>
				</div>
				<div class="vc_col-sm-6 image-wrap">
				  <p class="image aligncenter"><img src="<?php echo $amazon_product_image; ?>" class="product_image attachment-listing" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>"></p>
				  <div class="more-by aligncenter">View All <strong><?php echo $search_key; ?></strong> By <?php echo $amazon_searchtitle; ?> »</div>
				</div>
				<div class="vc_col-sm-6 content-wrap">
				  <div class="content">
				   <?php echo $amazon_content ; ?>
					<div class="overlay"></div>
					<span class="overlay-control"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></div>
				  <div class="meta-wrap">
					<div class="meta-header">
						<?php if(!empty($amazon_pros[0])) { $active = 'active'; ?><a class="button tab pros <?php echo $active; ?>" data-meta="pros">Pros</a><?php } ?>
						<?php if(!empty($amazon_cons[0])) { if(empty($amazon_pros[0])){$active = 'active';}else{$active = '';}?><a class="button tab cons <?php echo $active; ?>" data-meta="cons">Cons</a><?php } ?>
						<?php if(!empty($amazon_summary)) { if( empty($amazon_pros[0]) && empty($amazon_cons[0])){$active = 'active';}else{$active = '';} ?><a class="button tab summary <?php echo $active; ?>" data-meta="summary">Summary</a><?php } ?>
						
						<a class="button buy normal seeprice" href="<?php echo $amazon_link ?>" rel="nofollow" target="_blank">View Price <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
					</div>
					<div class="amazon-meta meta row">
					<?php
					if(!empty($amazon_pros[0])) { if(!empty($amazon_pros[0])){$hidden = '';}else{$hidden = 'hidden';}?>
					  <div class="pros panel vc_col-sm-12 <?php echo $hidden; ?>">
					  <?php foreach($amazon_pros as $pros){ ?>
						<div class="point"><span class="icon"><i class="fa fa-check" aria-hidden="true"></i></span> <?php echo $pros; ?></div>
						<?php } ?>
					  </div>
					  
					<?php } if(!empty($amazon_cons[0])) { if(!empty($amazon_cons[0]) && empty($amazon_pros[0] )){$hidden = '';}else{$hidden = 'hidden';}?>
					  <div class="cons panel vc_col-sm-12 <?php echo $hidden; ?>">
					   <?php  foreach($amazon_cons as $cons){ ?>
						<div class="point"><span class="icon"><i class="fa fa-times" aria-hidden="true"></i></span> <?php echo $cons ?></div>
						<?php }  ?>
					  </div>
					<?php } if(!empty($amazon_summary)) { if(empty($amazon_cons[0]) && empty($amazon_pros[0])){$hidden = '';}else{$hidden = 'hidden';}?>
					  <div class="summary panel vc_col-sm-12 <?php echo $hidden; ?>"><p><?php echo $amazon_summary; ?></p></div>
					<?php } ?>
					</div>
					<!--<div class="read_more">Read  &nbsp;&nbsp; Buyer Reviews » </div></div>-->
				</div>
			  </div>
			</div>
			  </div>
			</div>
		</div>

		<!-- Second View -->
		<?php }elseif($view == 2 && isset($column)){ 
			if($column == 2){ $column = 6;}elseif($column == 3){ $column = 4;}elseif($column == 4){$column = 3;} ?>
			
			<style>
				.amazon_grid_item .overlay {
					-webkit-transition-duration: 600ms;
					-webkit-transition-timing-function: ease-out;
					-moz-transition-duration: 600ms;
					-moz-transition-timing-function: ease-out;
					-o-transition-duration: 600ms;
					-o-transition-timing-function: ease-out;
					transition-duration: 600ms;
					transition-timing-function: ease-out;
					transition-property: all;
					background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff', GradientType=0 );
					position: absolute;
					left: 0;
					bottom: 0;
					height: 100%;
					width: 100%;
					text-align: center;
				}
				
				.amazon_grid_item .content {
					max-height: 170px;
					overflow: hidden;
					position: relative;
					margin-bottom: 1.875rem;
					webkit-transition-duration: 600ms;
					-webkit-transition-timing-function: ease-out;
					-moz-transition-duration: 600ms;
					-moz-transition-timing-function: ease-out;
					-o-transition-duration: 600ms;
					-o-transition-timing-function: ease-out;
					transition-duration: 600ms;
					transition-timing-function: ease-out;
					transition-property: all;
				}
				.amazon_grid_item .content.amazon-height {
					max-height: none;
				}
				.amazon_grid_item .content.amazon-height .overlay-control{
					transform: rotateZ(180deg);
				}
				</style>
			
			<div class="row vc_row">
				<div class="dyncmiclink_product" id="<?php echo $amazon_name; ?>" data-return_product_url="<?php echo $product->ID; ?>">
					<div class="vc_col-md-<?php echo $column; ?> amazon_grid_item">
						<div class="amazon_image-wrap grid_view" style="text-align:center; border: 1px dashed #ccc;min-height: 590px;width: 100%;">
							<?php if ( has_post_thumbnail()) {
								$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
								<div class="image-wrap">
									<p class="image aligncenter amazon_img">
										<a href="<?php echo $amazon_link; ?>" rel="nofollow"><img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : $amazon_title; ?>" src="<?php echo $large_image_url[0]; ?>"></a>
									</p>
								</div>	
								<?php }else{ ?>
								<div class="image-wrap img_grid2">
									<p class="image aligncenter amazon_img">
										<img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : $amazon_title; ?>" src="<?php echo $amazon_product_image; ?>" style="">
									</p>
								</div>
								<?php } ?>
							<div class="title-wrap inner-wrap">
								<h2 id="<?php echo $amazon_name; ?>"><?php echo $amazon_title; ?></h2>
							</div>
							<div class="inner-wrap content">
								<p style="text-align: justify;"><?php echo $amazon_summary; ?></p>
								<div class="overlay"></div>
								<span class="overlay-control"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
							</div>
							
								<div class="check_price <?php if($atts['amazon_dynamic_url'] == 1){ echo "amazon_dynamic_url"; } ?>">
									<a <?php echo ($atts['amazon_dynamic_url'] != 1) ? 'href="'. $amazon_link .'"' : ''; ?> class="btn-alt" rel="nofollow" target="_blank">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
								</div>
							
						</div>
					</div>
				</div>
			</div>
				
			<!-- 3rd view -->
			<?php } elseif($view == 3){ ?>
				
				<style>
					.product .overlay {
					-webkit-transition-duration: 600ms;
					-webkit-transition-timing-function: ease-out;
					-moz-transition-duration: 600ms;
					-moz-transition-timing-function: ease-out;
					-o-transition-duration: 600ms;
					-o-transition-timing-function: ease-out;
					transition-duration: 600ms;
					transition-timing-function: ease-out;
					transition-property: all;
					background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff', GradientType=0 );
					position: absolute;
					left: 0;
					bottom: 0;
					height: 100%;
					width: 100%;
					text-align: center;
				}
				
				.product .content {
					max-height: 170px;
					overflow: hidden;
					position: relative;
					margin-bottom: 1.875rem;
					webkit-transition-duration: 600ms;
					-webkit-transition-timing-function: ease-out;
					-moz-transition-duration: 600ms;
					-moz-transition-timing-function: ease-out;
					-o-transition-duration: 600ms;
					-o-transition-timing-function: ease-out;
					transition-duration: 600ms;
					transition-timing-function: ease-out;
					transition-property: all;
				}
				.content.amazon-height {
					max-height: none;
				}
				.product .meta .panel.hidden {
					display: none;
				}
				.product .meta .pros.panel .icon {
					color: #6cbf57;
				}
				.product .meta .cons.panel .icon {
					color: #D03134;
				}
				.product .button.cons, .product .button.pros{
					font-weight:bold;
				}
				
				</style>

				<div class="product dyncmiclink_product" id="<?php echo $amazon_name; ?>" data-return_product_url="<?php echo $product->ID; ?>">
					<?php if(!empty($amazon_top_pick)){ ?>
					<p class="badge hidden-lg-down">
						<span class="ec-1">
							<i class="icon-verified"></i><?php echo $amazon_top_pick; ?>         
						</span>
					</p>
					<?php } ?>
					<div class="inner">
						<div class="vc_row-o-equal-height vc_row-flex" style="display: block; overflow: hidden;">
							<div class="vc_col-md-5 title-wrap">
							  <div class="clearfix"></div>
							  <h2 class="rkbadge badge hidden-lg-up">
								<i class="icon-verified"></i><?php echo $amazon_title; ?>
							  </h2>
							  
							  <div class="image">
								<div class="responsive-container">
								  <div class="img-container image-wrap">
									<?php if(has_post_thumbnail()){
										$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
										<p class="image aligncenter amazon_img">
											<a href="<?php echo $amazon_link; ?>" rel="nofollow"><img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : $amazon_title; ?>" src="<?php echo $large_image_url[0]; ?>"></a>
										</p>
										
										<?php }else{ ?>
										<div class="image-wrap">
										<p class="image aligncenter amazon_img rkimg">
											<img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : $amazon_title; ?>" 
											src="<?php echo $amazon_product_image; ?>">
										</p>
										</div>
									 <?php } ?>
								  </div>
								  <?php if($amazon_link_disable != 1){  ?>
								  <div class="image-wrap">
									  <?php if(!empty($amazon_searchurl)){ ?>
									  <div class="more-by aligncenter">View More <strong><?php echo $amazon_product_cat; ?></strong> By 
										<a data-check="success" target="_blank" rel="nofollow"><?php echo $amazon_searchtitle; ?> »</a>
									  </div>
									  <?php } ?>
								  </div>
								  <?php } ?>
								  
								</div>
							  </div>
							</div>
							<div class="vc_col-md-7 vc_col-xl-8 title-wrap">
							  <h2 class="product-title hidden-md-down">
								<?php echo $amazon_title; ?>
							  </h2>
							  <div class="sec rksec">
								<!-- Rakesh -->
								<div class="amazon-product meta-wrap vc_col-md-6" id="rakesh"> 
									<div class="meta-header item-list amazon-meta meta">
										<?php if(!empty($amazon_pros[0])){ ?><a class="button tab pros active" data-meta="pros">Pros</a><?php }?>
										<?php if(!empty($amazon_cons[0])){ ?><a class="button tab cons" data-meta="cons">Cons</a><?php }?>
									
										<?php if(!empty($amazon_pros[0])){ ?>
										<div class="panel pros meta">
										  <?php foreach($amazon_pros as $pros){ ?>
											<div class="point"><span class="icon"><i class="fa fa-check" aria-hidden="true"></i></span> <?php echo $pros; ?></div>
											<?php } ?>
										</div>
										<?php }?>
										<?php if(!empty($amazon_cons[0])){ ?>
										<div class="panel cons meta hidden">
										   <?php  foreach($amazon_cons as $cons){ ?>
											<div class="point"><span class="icon"><i class="fa fa-times" aria-hidden="true"></i></span> <?php echo $cons ?></div>
											<?php }  ?>
										</div>
										<?php }?>
									</div>
								</div>
								<!-- /Rakesh -->
							  
								<div class="vc_col-md-6">
									<div class="check_price <?php if($atts['amazon_dynamic_url'] == 1){ echo "amazon_dynamic_url"; } ?>">
										<a <?php echo ($atts['amazon_dynamic_url'] != 1) ? 'href="'. $amazon_link .'"' : ''; ?> class="btn-alt" rel="nofollow" target="_blank">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
									</div>
								
									<div class="price-card" style="text-align: right;display:none">
									  <div class="r">
										<?php if($amazon_offerprice){ ?>
										<div class="price states">
										  <?php $oldPrice = $amazon_offerprice + $amazon_offersaved; $discount = ($oldPrice * ((100 - $amazon_offerprice) / 100)); ?>
										  <span class="sale"><!--<i class="icon-loyalty"></i> 1425 Purchased</span>&nbsp;|&nbsp;--><span><span class="highlight">Save: <?php echo '$'.$amazon_offersaved; ?>&nbsp;(<?php $discount = number_format($discount,2); echo $discount.'%'; ?> OFF)</span>
										</span>
									  </div>
										<?php } ?>
									  </div>
									</div>                
								  </div>
								
							  </div>
							  
							
							  <div class="sec">
								<?php if($amazon_summary){ ?>
								  <div class="line-title">
									<div class="title">
									  <span>SUMMARY</span>
									</div>
								  </div>
								<?php } ?>
								  <div class="product-description">
									<div class="content">
										<p><?php echo $amazon_summary; ?></p>
										
										<div style="margin:20px 0; display:block;clear:both"></div>
										
										<?php if($amazon_like_about){ ?>
										  <div class="line-title">
											<div class="title">
											  <span>What We Like About It</span>
											</div>
										  </div>
										<?php } ?>
										<p style="margin-bottom: 2.875rem;"><?php echo $amazon_like_about; ?></p>
										
										<?php if($amazon_summary){ ?>
										<div class="overlay"></div>
										<span class="overlay-control rk-hover-more" id="rk-control">Show more</span>
										<span class="overlay-control rk-hover-less" id="rk-control">Show less</span>
										<?php } ?>
									</div>
									<?php if(!empty($amazon_age_range)){ ?>
									<div class="age-range aligncenter">
										<strong><?php echo $amazon_age_range; ?>.</strong>
									</div>
									<?php } ?>
								  </div>
							  </div>         
							</div>
						</div>
					</div><!-- .inner -->
				</div><!-- /Product -->
			
		<?php } }
	}
	
}
 
new amazon_shortcode;

add_filter( 'script_loader_tag', function ( $tag, $handle ) {

if ( 'amazon_wp_frontend_script' !== $handle )
        return $tag;
		return str_replace( ' src', ' defer="defer" src', $tag );
}, 10, 2 );


function getLocationInfoByIp1(){
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