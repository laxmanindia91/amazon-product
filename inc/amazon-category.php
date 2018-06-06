<?php
class category_template
{
	public function __construct() {
		add_action( 'template_include', array( $this, 'taxonomy_template' ));
	}

function taxonomy_template( $template ){

if( is_tax('amazon_category')){
    $template =BASEPATH .'template/taxonomy-amazon_category.php';
}  

return $template;

}

}
new category_template();
