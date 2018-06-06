<div class="rktoggle">
<div class="type_toc_2 amazon-content amazon_table_content <?php echo $atts['amazon_extra_class'] ?>">
	<div class="vc_col-md-9">
	    <h3 class="title"><?php echo $atts['amazon_title']; ?></h3> 
	</div>
    <div class="vc_col-md-3">
	    <a class="show-toggle">[Contents]</a>
    </div>
</div>   
<div class="toggle-content">
	<div class="vc_col-md-12">
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
		$b=1;
		echo '<div class="vc_col-md-6">';
		$rkcount = $table_query->post_count;
		$x = floor($rkcount) / 2;
		$x = round($x)+1;
        while ( $table_query->have_posts() ) : $table_query->the_post();
			if($x == $b){echo '</div><div class="vc_col-md-6">';}
			$amazon_name = $table_query->posts[$i]->post_name; ?>
            <div class="content-link">
            	<span class="digit"><?php echo $b. '. '; ?></span>
                <span class="dlink"><a href="#<?php echo $amazon_name; ?>" alt="<?php echo $amazon_name; ?>" ><?php echo get_the_title(); ?></a></span>
            </div>
        <?php
        $i++;$b++;
        endwhile;
		echo '</div>';
		wp_reset_query();
        endif;
        ?>
     </div>
         
     </div>
	
</div>
<script>
	jQuery(document).ready(function($){
		$('.show-toggle').click(function(){
			$('.toggle-content').toggleClass("show");
		});
	});
</script>
 
<style>
	@media screen and (max-width: 991px){
		.type_toc_2{
			text-align: center !important;
		}
		.type_toc_2 .vc_col-md-3 {
			text-align: center !important;
		}
	}
	@media screen and (min-width: 992px){
		.type_toc_2{
			overflow: hidden;
			padding: 20px 0;	
		}
		.type_toc_2 .vc_col-md-9{
			padding-left:0px;
		}
		.type_toc_2 .vc_col-md-3{
			padding-right:0px;
		}
	}
	@media screen and (min-width: 320px) and (max-width: 991px){
		.type_toc_2 .vc_col-md-9 {
			padding-left: 15px; 
		}
		.type_toc_2{
			overflow: hidden;
			padding: 0;	
		}
		.type_toc_2 .vc_col-md-3 {
			padding-right: 15px;
		}
		.product .scores {
			top: -55px !important;
			left: 0px !important;
			right: 0px !important;
			width: 60px !important;
			height: 60px !important;
			line-height: 55px !important;
			margin: 0 auto;
		}	
	}
	.digit{
		width: 4%;
		float:left;
		clear:both;
	}
	.dlink{
		float:left;
		padding-left:10px;
		width: 96%;
	}
	.content-link{
		margin-bottom: 5px;
		display: block;
		overflow: hidden;
	}
	.rktoggle{
		margin-bottom:60px;
		width: 100%;
	}
 	.toggle-content{
		display:none;
		position: relative;
		height: auto;
		overflow: hidden;
		padding: 20px 10px;
		top:-2px;
		border: 1px solid #ddd;
	    background: #eee;
	}
	.toggle-content a{
		text-decoration:none;
		color: #333;
		box-shadow:none;
	}
	.toggle-content a:hover{
		text-decoration:underline;
		color: #333;
		box-shadow:none;
	}
	.toggle-content span{
		color:#333;
	}
	.type_toc_2{
		overflow: hidden;
		padding: 20px 0;
	}
	
	.type_toc_2 h3{
		font-size: 30px;
		font-weight: 600;
		font-style: normal;
		margin: 0 !important;
		color: #000;
	}
	.type_toc_2 .vc_col-md-3{
		line-height: 36px;
		text-align: right;
	}
	a.show-toggle{
		cursor:pointer;
		color: #000;
	    font-weight: bold;
	}
	a.show-toggle:hover{
		text-decoration:underline;
		color: #000;
	}
	.show{
		display: block !important;
	}
</style>
 <?php
 


