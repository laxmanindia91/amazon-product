<?php
get_header();
	global $wp_query, $post; 
	
	$term_id 		= $wp_query->queried_object->term_id;
	$slug 			= $wp_query->queried_object->slug;
	$name 			= $wp_query->queried_object->name;
	$taxonomy 		= $wp_query->queried_object->taxonomy;
	
	$args = array(
		'post_type' => 'amazon_product',
		'posts_per_page' => -1,
		'tax_query' => array(  
			array(  
				'taxonomy' => $taxonomy,  
				'field' => 'slug',  
				'terms' => $slug,
			),  
		),
	);

	$amazon_query = new WP_Query($args);
	
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<pre>
<?php //print_r($amazon_query); die(); ?>
</pre>
	
<div class="container">
	
	<?php while ($amazon_query->have_posts()) : $amazon_query->the_post(); ?>
		<div class="row"><div class="col-md-6">
			<h1><?php echo the_title(); ?></h1>
			<?php echo the_content(); ?>
			
			<div id="exTab2" class="container">	
<ul class="nav nav-tabs">
			<li class="active">
        <a  href="#1" data-toggle="tab">Overview</a>
			</li>
			<li><a href="#2" data-toggle="tab">Without clearfix</a>
			</li>
			<li><a href="#3" data-toggle="tab">Solution</a>
			</li>
		</ul>

			<div class="tab-content ">
			  <div class="tab-pane active" id="1">
          <h3>Standard tab panel created on bootstrap using nav-tabs</h3>
				</div>
				<div class="tab-pane" id="2">
          <h3>Notice the gap between the content and tab after applying a background color</h3>
				</div>
        <div class="tab-pane" id="3">
          <h3>add clearfix to tab-content (see the css)</h3>
				</div>
			</div>
  </div>
		</div>
		<div class="col-md-6">
			<div class="ama_product_img">
			<?php $img = get_post_meta(get_the_ID() ,'amazon_product_image'); ?>
			<img src="<?php print_r($img[0]); ?>" style="max-width:100%">
			</div>
		</div></div>
	<?php endwhile;
	wp_reset_postdata(); ?>
	
</div>
<?php get_footer(); 
