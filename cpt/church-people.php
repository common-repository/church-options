<?php
function church_options_register_my_cpts_church_people() {

	/**
	 * Post Type: People.
	 */

	$labels = array(
		"name" => __( "People", "church-options" ),
		"singular_name" => __( "People", "church-options" ),
	);

	$args = array(
		"label" => __( "People", "church-options" ),
		"labels" => $labels,
		"description" => __( "Enter information for your church staff.", "church-options"),
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
		"rewrite" => array( "slug" => "church_people", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "thumbnail" ),
	);

	register_post_type( "church_people", $args );
}

add_action( 'init', 'church_options_register_my_cpts_church_people' );

function church_people_edit_form_top( $post )
{
    if( in_array( $post->post_type, array( 'church_people' ) ) ){
        // You want to do something here
        echo __( 'Staff will be displayed in the same order they are entered, i.e. the first entered will be the first shown.', 'church-options');
    }
}
add_action('edit_form_top', 'church_people_edit_form_top');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_person-info',
		'title' => 'Person Info',
		'fields' => array (
			array (
				'key' => 'field_5ac441788e1ed',
				'label' => _x( 'Title', 'The person\'s position within the church', 'church-options'),
				'name' => 'church-people-title',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5ac4412220efe',
				'label' => __( 'Staff Phone Number', 'church-options'),
				'name' => 'church-people-phone',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5ac440ff20efd',
				'label' => __( 'Staff Email', 'church-options'),
				'name' => 'church-people-email',
				'type' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5ac4408320efc',
				'label' => __( 'Profile', 'church-options'),
				'name' => 'church-people-profile',
				'type' => 'textarea',
				'instructions' => __( 'Please provide a short profile for this person.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'church_people',
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

