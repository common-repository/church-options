<?php
function church_options_register_my_cpts_church_announcements() {

	/**
	 * Post Type: Announcements.
	 */

	$labels = array(
		"name" => __( "Announcements", "church-options" ),
		"singular_name" => __( "Announcement", "church-options" ),
	);

	$args = array(
		"label" => __( "Announcements", "church-options" ),
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
		"rewrite" => array( "slug" => "church_announcements", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor" ),
	);

	register_post_type( "church_announcements", $args );
}

add_action( 'init', 'church_options_register_my_cpts_church_announcements' );

function church_announcement_edit_form_top( $post )
{
    if( in_array( $post->post_type, array( 'church_announcements' ) ) ){
        // You want to do something here
        echo 'Please fill in the title, though it will not be shown in the widget.';
    }
}
add_action('edit_form_top', 'church_announcement_edit_form_top');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_announcements',
		'title' => __( 'Announcements', 'church-options'),
		'fields' => array (
			array (
				'key' => 'field_5b6cf528373bd',
				'label' => __('Expiration Date', 'church-options'),
				'name' => 'announcements_expiration_date',
				'type' => 'date_picker',
                'instructions' => __( 'Please choose the date you want the announcement to stop being shown, typically the day after whatever the announcement is about. The announcement will not be shown but will still be available in search.', 'church-options'),
				'required' => 1,
				'date_format' => 'yymmdd',
				'display_format' => 'dd M yy',
				'first_day' => 1,
			),
            array (
				'key' => 'field_5bc3f3cba2ff1',
				'label' => __( 'Contact Name 1', 'church-options'),
				'name' => 'church_announcements_contact_name_1',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5bc3f42ba2ff2',
				'label' => __( 'Contact Number 1', 'church-options'),
				'name' => 'church_announcements_contact_number_1',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => __( 'Start with area code, no dashes', 'church-options'),
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5bc3f4faa2ff3',
				'label' => __( 'Contact Name 2', 'church-options'),
				'name' => 'church_announcements_contact_name_2',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5bc3f513a2ff4',
				'label' => __( 'Contact Number 2', 'church-options'),
				'name' => 'church_announcements_contact_number_2',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => __( 'Start with area code, no dashes', 'church-options'),
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'church_announcements',
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