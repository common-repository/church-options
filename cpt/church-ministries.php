<?php
function church_options_register_my_cpts_church_ministries() {

	/**
	 * Post Type: Ministries.
	 */

	$labels = array(
		"name" => __( "Ministries", "church-options" ),
		"singular_name" => __( "Ministry", "church-options" ),
	);

	$args = array(
		"label" => __( "Ministries", "church-options" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "church_ministries", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor", "thumbnail" ),
	);

	register_post_type( "church_ministries", $args );
}

add_action( 'init', 'church_options_register_my_cpts_church_ministries' );

