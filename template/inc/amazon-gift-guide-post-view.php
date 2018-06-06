<?php
$post_args = array(
	'post_type' => 'post',
	'post_status'=>'publish',
	'tax_query' => array(  
		array(  
			'taxonomy' => 'gift_category',  
			'field' => 'slug',  
			'terms' => str_replace(' ','-', strtolower($atts['gift_title'])),
		)  
	)  
);

$post_query = new WP_Query($post_args);
$count_n = 0; ?>

<?php //echo '<div class="vc_col-md-12">';
if ( $post_query->have_posts() ) :
	while ( $post_query->have_posts() ) : $post_query->the_post(); 
		
		$post_title 	  = get_post_meta(get_the_ID(), 'gift_post_title', true);	
		$link_us 		  = get_post_meta(get_the_ID(), 'gift_post_link_us', true);
		$link_uk 		  = get_post_meta(get_the_ID(), 'gift_post_link_uk', true);
		$gift_post_image  = get_post_meta(get_the_ID(), 'gift_post_image', true);
		$content = get_post_meta($post->ID, 'gift_guide_content', true);
	?>
	<div id="<?php echo str_replace(array(" ","'",",","&","_"),'-',trim(get_the_title())); ?>" class="vc_row dynamic_post_gift_guide" data-return_product_url="<?php echo get_the_ID(); ?>" data-product_column="<?php echo $dyncol; ?>"     data-button_style="<?php echo $buttonstyle; ?>" data-which_site="<?php echo $atts['amazon_button_style']; ?>">
		<!--<div class="vc_col-md-4 vc_col-sm-6 vc_col-xs-12">-->
        <div class="<?php echo $dyncol; ?>">
			<div class="gift_image-wrap gift_item">
				<div class="title-wrap gift-inner-wrap productname">
					<h3><?php echo $post_title; ?></h3>
				</div>
				<p class="image aligncenter gift_img">
					<img class="lazy" data-original="" alt="" src="<?php echo site_url(). $gift_post_image; ?>">
				</p>
				<div class="gift-inner-wrap content">
					<p style="text-align: justify;"><?php echo substr($content, 0, 378); ?></p>
				</div>
				<div class="<?php if($atts['amazon_button_style'] != 'lookwhatscool'){echo 'gift-button check_price'; } ?> check_price">
				   <a href="<?php echo $link_uk; ?>" class="<?php echo $buttonstyle; ?>" rel="nofollow" target="_blank">Check It Out <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
	</div>

<?php $count_n++;
	endwhile; 
	endif;
wp_reset_query();