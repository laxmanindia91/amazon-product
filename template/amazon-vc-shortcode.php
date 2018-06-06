<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class amazon_vc_shortcode{	
	
	public function __construct(){
		add_action( 'init', array($this,  'amazon_product_vc_shortcode_callback_backend' ) );
	    add_shortcode( 'amazon_product_vc_shortcode', array($this, 'amazon_product_vc_shortcode_callback' ) );
		add_action( 'admin_footer', array($this, 'amazon_product_vc_shortcode_callback_jQuery' ) );
		
	}
	public function amazon_product_vc_shortcode_callback_jQuery(){ ?>
		<script>
		jQuery(document).ready(function($){
			$(document).on('change',".amazon_product_view",function(){
				if($(this).val()=='fill_width'){
					$(this).closest('div.vc_active').find('.amazon_product_per_row').closest('.vc_wrapper-param-type-dropdown').hide();
				}else if($(this).val() == 'pro_con_grid_view'){
					$(this).closest('div.vc_active').find('.amazon_product_per_row').closest('.vc_wrapper-param-type-dropdown').hide();
				}else if($(this).val() == 'full_grid_view'){
					$(this).closest('div.vc_active').find('.amazon_product_per_row').closest('.vc_wrapper-param-type-dropdown').hide();
				}else{
					$(this).closest('div.vc_active').find('.amazon_product_per_row').closest('.vc_wrapper-param-type-dropdown').show();
				}
			});
			$(document).on('click','a.vc_control-btn-edit',function(){
				setTimeout(function(){
					var s = $('.amazon_product_view').val();
					if( s === "grid_view"){
						jQuery('.amazon_product_per_row').parent().parent('.vc_shortcode-param').show();
					}else{
						jQuery('.amazon_product_per_row').parent().parent('.vc_shortcode-param').hide();
					}
				},1000);
				
			});
		
		});
		
		</script>
		<?php
	}
	public function amazon_product_vc_shortcode_callback_backend() {
		
		$categories_array = array( __( 'All Categories', 'js_composer' ) => 'mp-all-categories' );
		$category_list = get_terms( 'amazon_category', array( 'hide_empty' => false ) );
		if ( is_array( $category_list ) && ! empty( $category_list ) ) {
			foreach ( $category_list as $category_details ) {   
				$begin = __(' (ID: ', 'js_composer');
				$end = __(')', 'js_composer');
				$categories_array[ $category_details->name] = $category_details->slug;  
			}
		}
		vc_map(
			array(
				'name' => __( 'Amazon Product(s)' ),
				'base' => 'amazon_product_vc_shortcode',
				'category' => __( 'amazon_category' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => '',
						'heading' => 'Amazon product count',
						'param_name' => 'amazon_number',
						'value' => 10,
						'description' => 'Set how many product to display default (10)',
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Amazon Product Category',
						'param_name' => 'amazon_drop_cat',
						'value' => $categories_array,
						'description' => __( 'Select category for showing product' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => 'Product View',
						'param_name' => 'amazon_product_view',
						'class' => 'amazon_view_change',
						'value' => array(
							__( 'Full Width' ) => 'fill_width',
							__( 'Grid View' ) => 'grid_view',
							__( 'Grid View Pro and Cons' ) => 'pro_con_grid_view',
							__( 'Full Grid View' ) => 'full_grid_view',
						),
						'description' => __( 'How to show product on frontend' ),
					),
					
					array(
						'type' => 'dropdown',
						'heading' => 'Grid elements per row',
						'param_name' => 'amazon_product_per_row',
						'class' => 'amazon_product_per_row',
						'value' => array(
							__( '1' ) => 'Please Select',
							__( '2' ) => '2',
							__( '3' ) => '3',
							__( '4' ) => '4', 
						),
						'description' => __( 'How to show product on frontend' ),
					),
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'class' => '',
						'heading' => 'amazon Extra Class',
						'param_name' => 'amazon_extra_class',
						'value' => '',
						'description' => __( 'Set your extra class for custom css' ),
					),
					array(
						"type"        => "checkbox",
						"heading"     => __("Enable Check Price Links"),
						"param_name"  => "amazon_dynamic_url",
						"admin_label" => true,
						"value"       => array(
											'Is Dynamic'=> '1'
										),
						"std"         =>  1,
						"description" => __("Select to show dynamic url.")
					),
					array(
						"type"        => "checkbox",
						"heading"     => __("Enable Number count"),
						"param_name"  => "amazon_dynamic_number_count",
						"admin_label" => true,
						"value"       => array(
											'number show'=> '1'
										),
						"std"         =>  '',
						"description" => __("Select to show number in left side count.")
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
	public function amazon_product_vc_shortcode_callback( $atts, $content ) {
		
		//ob_flush();
		ob_start();
		$ip = $this->getLocationInfoByIp();
		$countery = $ip[country];
		
		$atts = shortcode_atts(
			array(
				'amazon_number' => 10,
				'amazon_drop_cat' => '',
				'amazon_product_view' => 'fill_width',
				'amazon_product_per_row' => '',
				'amazon_extra_class'=>'',
				'amazon_dynamic_url'=> 1,
				'amazon_dynamic_number_count'=> '',
			), $atts, 'amazon_product_vc_shortcode'
		);
		
		?>
        <div class="skinset-background  <?php echo $atts['amazon_extra_class'] ?>">
        <div class="product-feature type_list">
        <div class="row">
		<?php 
		
		/*-----  Full Width View -----*/
		
		if($atts['amazon_product_view'] == 'fill_width'){
       
			$args = array(
			'post_type' => 'amazon_product',
			'posts_per_page' => $atts['amazon_number'],
			//'amazon_product_view' => $atts['amazon_product_view'],
			'tax_query' => array(  
				array(  
					'taxonomy' => 'amazon_category',  
					'field' => 'slug',  
					'terms' => $atts['amazon_drop_cat'],
				),  
			),
		);

		$amazon_query = new WP_Query($args);
		if ( $amazon_query->have_posts() ) :
		$i=0;
		while ( $amazon_query->have_posts() ) : $amazon_query->the_post();
			
			$amazon_name 				= $amazon_query->posts[$i]->post_name;
			$amazon_product_image		= get_post_meta(get_the_ID(),'amazon_product_image',true);
			$amazon_insertPrice 		= get_post_meta(get_the_ID(),'amazon_insertPrice',true);
			$amazon_searchtitle 		= get_post_meta(get_the_ID(),'amazon_searchtitle',true);
			$amazon_pros 				= get_post_meta(get_the_ID(),'amazon_pros',true);
			$amazon_cons 				= get_post_meta(get_the_ID(),'amazon_cons',true);
			$amazon_summary 			= get_post_meta(get_the_ID(),'amazon_summary',true);
			$amazon_link_usa 			= get_post_meta(get_the_ID(),'amazon_link',true);
			$amazon_link_uk 			= get_post_meta(get_the_ID(),'amazon_link_uk',true);
			$amazon_searchurl 			= get_post_meta(get_the_ID(),'amazon_searchurl',true);
			$amazon_img_searchurl 		= get_post_meta(get_the_ID(),'amazon_img_searchurl',true);
			
			$search_keyword 			= explode('&',$amazon_searchurl);
			$search_keyword 			= explode('field-keywords=',$search_keyword[1]);
			$search_key 				= $search_keyword[1];
			
			$categories = get_the_terms( $product->ID, 'amazon_category' );
			foreach( $categories as $category ) {
				$amazon_product_cat = $category->name;
			}
			
			$amazon_link = $amazon_link_usa.'?tag='.get_option('amazon_us');
			if($countery == 'GB'){ //GB
				if(!empty($amazon_link_uk)){
					$amazon_link = $amazon_link_uk.'?tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}else{
					$amazon_url = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_link = $amazon_url.'&tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}
			}
			$active = '';
			$hidden = '';
		?>

		<div id="<?php echo $amazon_name; ?>" class="view1 item-list vc_col-sm-12 first_item_list dyncmiclink_product" data-return_product_url="<?php echo get_the_ID(); ?>">
		  <div class="row vc_row-o-equal-height vc_row-flex vc_row-o-content-middle">
			<div class="vc_col-sm-6 title-wrap">
			  <h2 id="<?php echo $amazon_name; ?>"><?php echo get_the_title(); ?></h2>
			  
			</div>
			<div class="vc_col-sm-6 image-wrap" style="text-align:center">
			  <p class="image aligncenter amazon_img"><img src="<?php echo $amazon_product_image; ?>" class="product_image attachment-listing" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>"></p>
			  <div class="more-by aligncenter">View More <strong><?php echo $amazon_product_cat; ?></strong> By <?php echo $amazon_searchtitle; ?> »</div>
			</div>
			<div class="vc_col-sm-6 content-wrap">
			  <?php $acontent = get_the_content(); if(!empty($acontent)){ ?>
              <div class="content">
			   <p style="text-align: justify;"><?php echo get_the_content(); ?></p>
				<div class="overlay"></div>
				<span class="overlay-control"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
			  </div>
			  <?php } ?>
			  <div class="meta-wrap">
				<div class="meta-header">
				
				<?php if(!empty($amazon_pros[0])) { $active = 'active'; ?><a class="button tab pros <?php echo $active; ?>" data-meta="pros">Pros</a><?php } ?>
				<?php if(!empty($amazon_cons[0])) { if(empty($amazon_pros[0])){$active = 'active';}else{$active = '';}?><a class="button tab cons <?php echo $active; ?>" data-meta="cons">Cons</a><?php } ?>
				<?php if(!empty($amazon_summary)) { if( empty($amazon_pros[0]) && empty($amazon_cons[0])){$active = 'active';}else{$active = '';} ?><a class="button tab summary <?php echo $active; ?>" data-meta="summary">Summary</a><?php } ?>
				<!--<a class="button buy normal seeprice btn-alt" href="<?php echo $amazon_link ?>" rel="nofollow" target="_blank">View Price <i class="fa fa-arrow-right" aria-hidden="true"></i></a>-->
				<a class="btn-alt" href="<?php echo $amazon_link ?>" rel="nofollow" target="_blank">View Price <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
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
				  <div class="summary panel vc_col-sm-12 <?php echo $hidden; ?>"><?php echo $amazon_summary; ?></div>
				<?php } ?>
				</div>
			</div>
		  </div>
		</div>
	  </div>
		<?php
		$i++;
		 endwhile;
		 endif;
		 
		 wp_reset_query();
	?>
        
		
		<!-- Grid View-2 -->
		<?php }elseif($atts['amazon_product_view'] == 'grid_view'){
		
		
			$args = array(
			'post_type' => 'amazon_product',
			'posts_per_page' => $atts['amazon_number'],
			//'amazon_product_view' => $atts['amazon_product_view'],
			'tax_query' => array(  
				array(  
					'taxonomy' => 'amazon_category',  
					'field' => 'slug',  
					'terms' => $atts['amazon_drop_cat'],
				),  
			),
		);?>
		<div  class="item-list vc_col-sm-12  amazon_second_view">	
			<div class="row vc_row-o-equal-height vc_row-flex vc_row-o-content-middle">
		<?php
			$divClass='';
			switch ($atts['amazon_product_per_row']) {
				case 1:
					$divClass = '12';
					break;
				case 2:
					$divClass = '6';
					break;
				case 3:
					$divClass = '4';
					break;
				case 4:
					$divClass = '3';
					break;
			}

		$amazon_query = new WP_Query($args);
		if ( $amazon_query->have_posts() ){
		$i=0;
		$ci = 1;
		$count = $amazon_query->post_count;
		
		while ( $amazon_query->have_posts() ) { $amazon_query->the_post();
			
			$amazon_name 				= $amazon_query->posts[$i]->post_name;
			$amazon_product_image		= get_post_meta(get_the_ID(),'amazon_product_image',true);
			$amazon_insertPrice 		= get_post_meta(get_the_ID(),'amazon_insertPrice',true);
			$amazon_searchtitle 		= get_post_meta(get_the_ID(),'amazon_searchtitle',true);
			$amazon_pros 				= get_post_meta(get_the_ID(),'amazon_pros',true);
			$amazon_cons 				= get_post_meta(get_the_ID(),'amazon_cons',true);
			$amazon_summary 			= get_post_meta(get_the_ID(),'amazon_summary',true);
			$amazon_link_usa 			= get_post_meta(get_the_ID(),'amazon_link',true);
			$amazon_link_uk 			= get_post_meta(get_the_ID(),'amazon_link_uk',true);
			$amazon_searchurl 			= get_post_meta(get_the_ID(),'amazon_searchurl',true);
			
			$search_keyword 			= explode('&',$amazon_searchurl);
			$search_keyword 			= explode('field-keywords=',$search_keyword[1]);
			$search_key 				= $search_keyword[1];
			
			$amazon_link = $amazon_link_usa.'?tag='.get_option('amazon_us');
			if($countery == 'GB'){ //GB
				if(!empty($amazon_link_uk)){
					$amazon_link = $amazon_link_uk.'?tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}else{
					$amazon_url = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_link = $amazon_url.'&tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}
			}
				
		?>	
		<div id="<?php echo $amazon_name; ?>" class="view2 dyncmiclink_product"  data-return_product_url="<?php echo get_the_ID(); ?>">
		<div class="vc_col-sm-<?php echo $divClass; ?> amazon_grid_item">
			<div class="amazon_image-wrap  grid_view" style="text-align:center; border: 1px dashed #ccc;min-height:660px;width: 100%;">
				<p class="image aligncenter amazon_img">
					<?php if ( has_post_thumbnail()) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
						<p class="image aligncenter amazon_img">
							<a href="<?php echo $amazon_link; ?>" rel="nofollow"><img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $large_image_url[0]; ?>"></a>
						</p>
								
						<?php }else{ ?>
						<div class="image-wrap img_grid2 image_height">
							<p class="image aligncenter amazon_img">
								<img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $amazon_product_image; ?>" style="">
							</p>
						</div>
						<?php } ?>
					
				</p>
				<div class="title-wrap inner-wrap">
					<h2 id="<?php echo $amazon_name; ?>"><?php echo get_the_title(); ?></h2>
				</div>
                <?php $sum = $amazon_summary; if(!empty($sum)){ ?>
				<div class="inner-wrap content">
					<?php echo $amazon_summary; ?>
					<div class="overlay"></div>
					<span class="overlay-control"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
				</div>
				<? } ?>
				<div class="check_price <?php if($atts['amazon_dynamic_url'] == 1){ echo "amazon_dynamic_url"; } ?>">
						<a <?php echo ($atts['amazon_dynamic_url'] != 1) ? 'href="'. $amazon_link .'"' : ''; ?> class="btn-alt" rel="nofollow" target="_blank">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
					</div>
			</div>
		</div>
		</div>
		<?php if(!is_float($ci/$atts['amazon_product_per_row'])){ ?>
			</div><div class="row vc_row-o-equal-height vc_row-flex vc_row-o-content-middle">
		<?php }  ?>
			
		<?php 
		$i++;
		$ci++;
		}
		}
		wp_reset_query();
		?>
		 </div>
		</div>
		
		
		<!-- View 3 -->		
		<?php } elseif($atts['amazon_product_view'] == 'pro_con_grid_view'){ ?>
			
		<?php	$args = array(
			'post_type' => 'amazon_product',
			'posts_per_page' => $atts['amazon_number'],
			'tax_query' => array(  
				array(  
					'taxonomy' => 'amazon_category',  
					'field' => 'slug',  
					'terms' => $atts['amazon_drop_cat'],
				),  
			),
		);?>
		<div class="item-list vc_col-sm-12 amazon_third_view">	
			<div class="vc_row-o-equal-height vc_row-flex vc_row-o-content-middle">
		
		<?php
		$amazon_query3 = new WP_Query($args);
		if ( $amazon_query3->have_posts() ) :
		
		$i = 1;
		$a = 0;
		while ( $amazon_query3->have_posts() ) : $amazon_query3->the_post();
			
			$amazon_name 				= $amazon_query3->posts[$a]->post_name;
			$amazon_top_pick			= get_post_meta(get_the_ID(),'amazon_top_pick',true);
			$amazon_like_about			= get_post_meta(get_the_ID(),'amazon_like_about',true);
			$amazon_image_alt_tag		= get_post_meta(get_the_ID(),'amazon_image_alt_tag',true);
			$amazon_link_disable		= get_post_meta(get_the_ID(),'amazon_link_disable',true);
			$amazon_age_range			= get_post_meta(get_the_ID(),'amazon_age_range',true);
			
			$amazon_product_image		= get_post_meta(get_the_ID(),'amazon_product_image',true);
			$amazon_insertPrice 		= get_post_meta(get_the_ID(),'amazon_insertPrice',true);
			$amazon_searchtitle 		= get_post_meta(get_the_ID(),'amazon_searchtitle',true);
			$amazon_pros 				= get_post_meta(get_the_ID(),'amazon_pros',true);
			$amazon_cons 				= get_post_meta(get_the_ID(),'amazon_cons',true);
			$amazon_summary 			= get_post_meta(get_the_ID(),'amazon_summary',true);
			$amazon_link_usa 			= get_post_meta(get_the_ID(),'amazon_link',true);
			$amazon_link_uk 			= get_post_meta(get_the_ID(),'amazon_link_uk',true);
			$amazon_searchurl 			= get_post_meta(get_the_ID(),'amazon_searchurl',true);
			
			$amazon_offerprice 			= get_post_meta(get_the_ID(),'amazon_search_offer_price',true);
			$amazon_offerprice			= str_replace('$','', $amazon_offerprice);
			$amazon_offersaved 			= get_post_meta(get_the_ID(),'amazon_offer_price_saved',true);
			$amazon_offersaved			= str_replace('$','', $amazon_offersaved);
			$search_keyword 			= explode('&',$amazon_searchurl);
			$search_keyword 			= explode('field-keywords=',$search_keyword[1]);
			$search_key 				= $search_keyword[1];
			
			$categories = get_the_terms( $product->ID, 'amazon_category' );
			foreach( $categories as $category ) {
				$amazon_product_cat = $category->name;
			}
			
			$amazon_link = $amazon_link_usa.'?tag='.get_option('amazon_us');
			if($countery == 'GB'){ //GB
				if(!empty($amazon_link_uk)){
					$amazon_link = $amazon_link_uk.'?tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}else{
					$amazon_url = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_link = $amazon_url.'&tag='.get_option('amazon_uk');
					$amazon_searchurl = str_replace('.com','.co.uk', $amazon_searchurl);
					$amazon_searchurl = $amazon_searchurl.'&tag='.get_option('amazon_uk');
				}
			}
		?>
		
		<div class="view3 product dyncmiclink_product" id="<?php echo $amazon_name; ?>"  data-return_product_url="<?php echo get_the_ID(); ?>">
			<?php if($atts['amazon_dynamic_number_count']==1){ ?><div class="score hidden-md-down"><span><?php echo $i; ?></span></div><?php } ?>
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
						<i class="icon-verified"></i><?php echo get_the_title(); ?>
					  </h2>
					  
					  <div class="image">
						<div class="responsive-container">
						  <div class="img-container">
							<?php if ( has_post_thumbnail()) {
								$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
								<p class="image aligncenter amazon_img">
									<a href="<?php echo $amazon_link; ?>" rel="nofollow"><img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $large_image_url[0]; ?>"></a>
								</p>
								
								<?php }else{ ?>
								<div class="image-wrap">
								<p class="image aligncenter amazon_img rkimg">
									
									<img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $amazon_product_image; ?>">
								</p>
								</div>
							 <?php } ?>
						  </div>
						  <?php if($amazon_link_disable != 1){  ?>
						  <div class="image-wrap">
							  
							  <?php if(!empty($amazon_searchurl)){ ?>
							  <div class="more-by aligncenter">View More <strong><?php echo $amazon_product_cat; ?></strong> By 
								<?php echo $amazon_searchtitle; ?> »
							  </div>
							  <?php } ?>
						  </div>
						  <?php } ?>
						  
						</div>
					  </div>
					</div>
					<div class="vc_col-md-7 vc_col-xl-8 title-wrap">
					  <h2 class="product-title hidden-md-down">
						<?php echo get_the_title(); ?><!--</a>-->
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
								 <?php echo ($atts['amazon_dynamic_url'] != 1) ? '<a href="'. $amazon_link .'" class="btn-alt" rel="nofollow" target="_blank">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></a>' : '<p class="btn-alt">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></p>'; ?> 
							</div>
						
							<div class="price-card" style="text-align: right;display:none">
							  <div class="r">
								<?php if($amazon_offerprice){ ?>
								<div class="price states">
								  <?php $oldPrice = $amazon_offerprice + $amazon_offersaved; $discount = ($oldPrice * ((100 - $amazon_offerprice) / 100)); ?>
								  <span class="sale"></span><span class="highlight">Save: <?php echo '$'.$amazon_offersaved; ?>&nbsp;(<?php $discount = number_format($discount,2); echo $discount.'%'; ?> OFF)</span>
								</span>
							  </div>
								<?php } ?>
							  </div>
							</div>                
						  </div>
						
					  </div>
					  
					<div class="sec" style="overflow:hidden">
					    <?php if($amazon_summary){ ?>
						  <div class="line-title">
							<div class="title">
							  <span>SUMMARY</span>
							</div>
						  </div>
						<?php } ?>
						  <div class="product-description">
							<div class="content">
								<?php echo $amazon_summary; ?>
								
								<div style="margin:20px 0; display:block;clear:both"></div>
								
								<?php if($amazon_like_about){ ?>
								  <div class="line-title">
									<div class="title">
									  <span>What We Like About It</span>
									</div>
								  </div>
                                  
								<?php } ?>
								<p style="margin-bottom: 2.875rem;"><?php echo $amazon_like_about; ?></p>
								
								<?php if($amazon_summary || $amazon_like_about){ ?>
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
		
		
		<?php 
		 $i++;
		 $a++;
		 endwhile;
		 
		 endif; wp_reset_query(); ?>
		 </div>
		</div>
		
		
		<?php } //Full width grid view 
			elseif($atts['amazon_product_view'] == 'full_grid_view'){  
        		include_once('inc/amazon-vc-full-width-third.php');
         	} 
		?>
       
		</div>
		</div>
		</div>
		<?php 
		//$output1 = ob_get_clean();
		//echo $output1; 
		return ob_get_clean();
	}


	public function getLocationInfoByIp(){
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

new amazon_vc_shortcode;

