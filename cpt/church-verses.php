<?php
function church_options_register_my_cpts_church_verses() {

	/**
	 * Post Type: Verses.
	 */

	$labels = array(
		"name" => __( "Verses", "church-options" ),
		"singular_name" => __( "Verse", "church-options" ),
	);

	$args = array(
		"label" => __( "Verses", "church-options" ),
		"labels" => $labels,
		"description" => __( "Please place the reference in the title field", "church-options"),
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "church_verses", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor", "description" ),
	);

	register_post_type( "church_verses", $args );
}
add_action( 'init', 'church_options_register_my_cpts_church_verses' );

function church_verses_edit_form_top( $post )
{
    if( in_array( $post->post_type, array( 'church_verses' ) ) ){
        // You want to do something here
        echo __( 'Please place the verse reference citation in the title field and the verse text in the main text area.', 'church-options');
    }
}
add_action('edit_form_top', 'church_verses_edit_form_top');
?>