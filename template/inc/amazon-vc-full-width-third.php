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
); ?>

<div class="item-list vc_col-sm-12 amazon_full_view">	
	<div class="vc_row-o-content-middle">
		
		<?php
		$amazon_query4 = new WP_Query($args);
		if ( $amazon_query4->have_posts() ) :
		
		$i = 1;
		$a = 0;
		while ( $amazon_query4->have_posts() ) : $amazon_query4->the_post();
			$postcount = $amazon_query4->post_count;
			
			$amazon_name 				= $amazon_query4->posts[$a]->post_name;
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
		
		<div class="fullwidth product dyncmiclink_product" id="<?php echo $amazon_name; ?>"  data-return_product_url="<?php echo get_the_ID(); ?>" <?php if($postcount == $i){ echo 'style="margin-bottom:30px"';} ?>>
			<?php if($atts['amazon_dynamic_number_count']==1){ ?>
            	<div class="scores"><span><?php echo $i; ?></span></div><?php } ?>
			
			<?php if(!empty($amazon_top_pick)){ ?>
                <div class="badge">
                    <span><?php echo $amazon_top_pick; ?></span>
                </div>
			<?php } ?>
			<div class="vc_col-md-12 center rktitle">
				<div class="title-wrap">
					<h2><?php echo get_the_title(); ?></h2>
                </div>
            </div>
            <div class="vc_col-md-12">          
				<?php if ( has_post_thumbnail()) {
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
					<div class="image-wrap">
						<p class="image aligncenter amazon_img">
						<a href="<?php echo $amazon_link; ?>" rel="nofollow"><img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $large_image_url[0]; ?>"></a>
						</p>
					</div>	
				<?php }else{ ?>
                	<div class="image-wrap">
                        <p class="image aligncenter amazon_img rkimg">
                            <img class="lazy" data-original="" alt="<?php echo (!empty($amazon_image_alt_tag))? $amazon_image_alt_tag : get_the_title(); ?>" src="<?php echo $amazon_product_image; ?>">
                        </p>
					</div>
				<?php } ?>
			</div>
			
			<div class="vc_col-md-12">
				<div class="check_price <?php if($atts['amazon_dynamic_url'] == 1){ echo "amazon_dynamic_url"; } ?>">
					 <?php echo ($atts['amazon_dynamic_url'] != 1) ? '<a href="'. $amazon_link .'" class="btn-alt" rel="nofollow" target="_blank">Check Price Now <i class="fa fa-arrow-right" aria-hidden="true"></i></a>' : '<p class="btn-alt">Check Price Now</p>'; ?> 
				</div>
			</div>	
            <div class="vc_col-md-12">		
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
			<div class="vc_col-md-12">
            	<?php if(!empty($amazon_age_range)){ ?>
					<div class="rkage-range aligncenter">
						<strong><?php echo $amazon_age_range; ?></strong>
					</div>
				<?php } ?>
            </div>
			<div class="vc_col-md-12 sec" style="overflow:hidden">
				<?php if($amazon_summary){ ?>
				<div class="line-title">
					<div class="title">
						<span style="font-weight:600">SUMMARY</span>
					</div>
				</div>
				<?php } ?>
				<div class="product-description">
					<div class="full-content">
						<?php echo $amazon_summary; ?>
					</div>
				</div>
			</div>         
		</div>
	</div>
	<?php 
		$i++;
		$a++;
		endwhile; ?>
			<style>
				.fullwidth .post-styles p+*{margin-top:0 !important;}
				.fullwidth.product p{
					word-break: break-word;
				}
				.fullwidth.product{
					height: auto;
					display: inline-block;
					border: 1px solid #ddd;
					margin-bottom:55px;
					position:relative;
				}
				.fullwidth.product .badge{
					font-size:18px;
				}
				.fullwidth.product .scores{
					position: absolute;
					top: -40px;
					left: -42px;
					width: 90px;
					height: 90px;
					line-height: 103px;
					border-radius: 50%;
					border: solid .3em #5256FF;
					box-shadow: 0 2px 10px rgba(30,30,30,.2);
					background-color: #fff;
					text-align: center;
					z-index: 6;
				}
				.fullwidth.product .btn {
					padding: 22px 36px;
					border-radius:0;
				}
				.fullwidth.product .rkage-range{
					padding: 20px 0 0;
					font-size: 30px;
				}
				.fullwidth.product .rkage-range strong{
					font-size: 24px;
				    font-weight: 600;
				}
				.fullwidth .check_price .btn-alt {
					padding: 20px !important;
				}
				.scores span{
					font-size: 45px;
					font-weight: 600;
					color: #5256FF;
				}
				.fullwidth .amazon_img img{
					max-height:400px;
				}
				.fullwidth .center{
					text-align:center;
				}
				.fullwidth .rktitle h2{
					padding: 30px;
					font-size: 28px;
				}
				.fullwidth .btn-alt{
					box-shadow: inset 2px 2px 0 #fff, 4px 4px 0 #5256FF;
					background: #5256FF;
				}
				.fullwidth .btn-alt:hover {
					box-shadow: inset 0px 0px 0 #fff;
				}
			</style>
		<?php endif; 
		wp_reset_query(); 
	?>
	</div>
</div>

