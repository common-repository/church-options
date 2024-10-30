<?php
function church_options_register_my_cpts_church_sermons() {

	/**
	 * Post Type: Sermons.
	 */

	$labels = array(
		"name" => __( "Sermons", "church-options" ),
		"singular_name" => __( "Sermon", "church-options" ),
	);

	$args = array(
		"label" => __( "Sermons", "church-options" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "church_sermons", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "thumbnail" ),
        "taxonomies" => array( "topics" ),
	);

	register_post_type( "church_sermons", $args );
}

add_action( 'init', 'church_options_register_my_cpts_church_sermons' );

function church_options_register_taxonomy_topics() {
	$labels = [
        'name'              => _x('Topics', 'taxonomy general name', 'church-options'),
		'singular_name'     => _x('Topic', 'taxonomy singular name', 'church-options'),
		'search_items'      => __('Search Topics', 'church-options'),
		'all_items'         => __('All Topics', 'church-options'),
		'parent_item'       => __('Parent Topic', 'church-options'),
		'parent_item_colon' => __('Parent Topic:', 'church-options'),
		'edit_item'         => __('Edit Topic', 'church-options'),
		'update_item'       => __('Update Topic', 'church-options'),
		'add_new_item'      => __('Add New Topic', 'church-options'),
		'new_item_name'     => __('New Topic Name', 'church-options'),
		'menu_name'         => __('Topics', 'church-options'),
		];
		$args = [
		'hierarchical'      => true, // make it hierarchical (like categories)
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => ['slug' => 'topics'],
		];
register_taxonomy('topics', ['church_sermons'], $args);
}
add_action( 'init', 'church_options_register_taxonomy_topics' );

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_sermon-group',
		'title' => 'sermon group',
		'fields' => array (
			array (
				'key' => 'field_5bc3f1cdd77c3',
				'label' => __( 'Preached Date', 'church-options'),
				'name' => 'church_sermon_preached_date',
				'type' => 'date_picker',
				'instructions' => __( 'Please choose the date on which the sermon was preached.', 'church-options'),
				'required' => 1,
				'date_format' => 'yymmdd',
				'display_format' => 'dd M yy',
				'first_day' => 1,
			),
            array (
				'key' => 'field_5af78c9ad2867',
				'label' => __( 'Verse Reference', 'church-options'),
				'name' => 'church_sermon_verse',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5af78cc5d2868',
				'label' => __( 'Short Summary', 'church-options'),
				'name' => 'church_sermon_summary',
				'type' => 'textarea',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_5af78d2ad2869',
				'label' => __( 'Speaker', 'church-options'),
				'name' => 'church_sermon_speaker',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5af78d5bd286a',
				'label' => __( 'Recording File', 'church-options'),
				'name' => 'church_sermon_recording',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'church_sermons',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}