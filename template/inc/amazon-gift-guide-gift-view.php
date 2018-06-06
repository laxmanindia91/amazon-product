<?php
$args = array(
	'p'  => $atts['gift_id'],
	'post_type' => 'gift_guide',
	'post_status'=>'publish',
);
$amazon_query1 = new WP_Query($args);
if ( $amazon_query1->have_posts() ) :
$post_count =0;
while ( $amazon_query1->have_posts() ) : $amazon_query1->the_post();
	$count_n =  $amazon_query1->post_count;
	
	$title = explode('^',get_post_meta(get_the_ID(), 'gift_title', true));	
	$link = explode('^',get_post_meta(get_the_ID(), 'gift_link', true));
	$img = explode('^',get_post_meta(get_the_ID(), 'gift_image', true));
	$description = explode('^',get_post_meta(get_the_ID(), 'gift_description', true));
	$gift_link_inbound = explode('^',get_post_meta(get_the_ID(), 'gift_link_inbound', true));
?>
<div id="<?php echo str_replace(array(" ","'",",","&","_"),'-',trim(get_the_title())); ?>_<?php echo $post_count; ?>" class="vc_row dynamic_gift_guide"  data-return_product_url="<?php echo get_the_ID(); ?>" data-product_column="<?php echo $dyncol; ?>" data-button_style="<?php echo $buttonstyle; ?>" data-which_site="<?php echo $atts['amazon_button_style']; ?>">
	<?php
	$gift_array = array();
	for($gift=0;$gift<count($title);$gift++){
		$gift_array[$gift][]=$title[$gift];
		$gift_array[$gift][]=$link[$gift];
		$gift_array[$gift][]=$img[$gift];
		$gift_array[$gift][]=$description[$gift];
		$gift_array[$gift][]=$gift_link_inbound[$gift];
	}
	$count=0;
	
?>
<!--<div class="vc_col-sm-12">-->
<?php foreach($gift_array as $gift) {
	//if(($count%3)===0){echo '</div>	<div style="height: 10px; overflow:hidden; clear:both;"></div><div class="vc_col-sm-12">';}
	 ?>
<div class="<?php echo $dyncol; ?>"> 
	<div class="gift_image-wrap gift_item">
		<div class="title-wrap gift-inner-wrap productname">
			<h3><?php echo $gift[0]; ?></h3>
		</div>
		<p class="image aligncenter gift_img">
			<img class="lazy" data-original="" alt="" src="<?php echo site_url().$gift[2]; ?>">
		</p>
		<div class="gift-inner-wrap content">
			<p style="text-align: justify;"><?php echo substr($gift[3], 0, 378); ?></p>
		</div>
		<div class="<?php if($atts['amazon_button_style'] != 'lookwhatscool'){echo 'gift-button'; } ?> check_price">
			<?php if(!empty($gift[4])){ ?>
				<a href="<?php echo $gift[4]; ?>" class="<?php echo $buttonstyle; ?>" rel="nofollow" target="_blank">Check It Out <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<?php }else{ ?>
				<span class="<?php echo $buttonstyle; ?>" rel="nofollow" target="_blank">CHECK IT OUT <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
			<?php } ?>
		</div>
	</div>
</div>
<?php $count++;} ?>
</div>
</div>
<?php
$post_count++;
endwhile; 
endif; 
//wp_reset_postdata();
wp_reset_query();