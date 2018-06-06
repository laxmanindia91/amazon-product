<div class="product-feature type_toc content amazon-content amazon_table_content <?php echo $atts['amazon_extra_class'] ?>" style="margin-bottom: 30px;">
	<h2 class="title"><?php echo $atts['amazon_title']; ?></h2>
	<ol>
	<?php 
		$args = array(
			'post_type' => 'amazon_product',
			'posts_per_page' => $atts['amazon_number'],
			'tax_query' => array(
				array(
					'taxonomy' => 'amazon_category',  
					'field' => 'slug',  
					'terms' => $atts['amazon_drop_cat'],
				),  
			),
		);

	$table_query = new WP_Query($args);
	if ( $table_query->have_posts() ) :
	$i=0;
	while ( $table_query->have_posts() ) : $table_query->the_post(); 
		$amazon_name = $table_query->posts[$i]->post_name;
	?>
	<li><a href="#<?php echo $amazon_name; ?>" alt="<?php echo $amazon_name; ?>" ><?php echo get_the_title(); ?></a></li>
	
	<?php
	$i++;
	 endwhile;
	 wp_reset_query(); 
	 endif;
	 ?>
	 </ol>
	
	<div class="overlay"></div>
	<style>
		.amazon_table_content .overlay-control {
		text-transform: uppercase;
		position: absolute;
		left: 8%;
		bottom: 0;
		font-size: 12px;
		line-height: 29px;
		height: 29px;
		width: 85%;
		cursor: pointer;
		text-align: center;
		vertical-align: middle;
		background-color: #F9F9F9;
		border-radius: 5px;
		color: #3292d0;
		margin: 0 0 10px;
		border: 1px solid #ddd;
		-webkit-transition-duration: 600ms;
		-webkit-transition-timing-function: ease-out;
		-moz-transition-duration: 600ms;
		-moz-transition-timing-function: ease-out;
		-o-transition-duration: 600ms;
		-o-transition-timing-function: ease-out;
		transition-duration: 600ms;
		transition-timing-function: ease-out;
		transition-property: all;
	}
	
	.amazon_table_content .overlay-control:hover{
		background-color: #ddd;
		-webkit-transition-duration: 600ms;
		-webkit-transition-timing-function: ease-out;
		-moz-transition-duration: 600ms;
		-moz-transition-timing-function: ease-out;
		-o-transition-duration: 600ms;
		-o-transition-timing-function: ease-out;
		transition-duration: 600ms;
		transition-timing-function: ease-out;
		transition-property: all;
	}
	
	.amazon_table_content .amazon-height .overlay-control .fa-chevron-down {
		transform: rotateZ(180deg);
	}
	.amazon_table_content.amazon-height .overlay-control {
		transform: none !important;
	}
	
	.amazon_table_content .amazon-height .rk-hover-more{
		display:none;
	}
	.amazon_table_content .content:not(.amazon-height) .rk-hover-less{
		display:none;
	}
	
	.amazon_table_content .overlay{height: 65%;}
	
	</style>
	<span class="overlay-control rk-hover-more">Show more</span>
	<span class="overlay-control rk-hover-less">Show less</span>
	
</div>

<?php


