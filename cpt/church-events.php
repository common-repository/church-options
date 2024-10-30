<?php
function church_options_register_my_cpts_church_events() {

	/**
	 * Post Type: Events.
	 */

	$labels = array(
		"name" => __( "Events", "church-options" ),
		"singular_name" => __( "Event", "church-options" ),
	);

	$args = array(
		"label" => __( "Events", "church-options" ),
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
		"rewrite" => array( "slug" => "church_events", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor", "thumbnail" ),
	);

	register_post_type( "church_events", $args );
}

add_action( 'init', 'church_options_register_my_cpts_church_events' );

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_event-options',
		'title' => __( 'Event Options', 'church-options'),
		'fields' => array (
			array (
				'key' => 'field_5aebab688d26b',
				'label' => __( 'Start Date', 'church-options' ),
				'name' => 'church_event_start_date',
				'type' => 'date_picker',
                'instructions' => __( 'Please choose the day on which this event will begin', 'church-options' ),
				'date_format' => 'yymmdd',
				'display_format' => 'dd M yy',
				'first_day' => 7,
			),
			array (
				'key' => 'field_5aebab988d26c',
				'label' => __( 'End Date', 'church-options'),
				'name' => 'church_event_end_date',
				'type' => 'date_picker',
                'instructions' => __( 'Please choose the day on which this event will end. If it occurs on only one day feel free to leave this field empty.', 'church-options'),
				'date_format' => 'yymmdd',
				'display_format' => 'dd M yy',
				'first_day' => 7,
			),
			array (
				'key' => 'field_5aebabbe8d26d',
				'label' => __( 'Start Time', 'church-options'),
				'name' => 'church_event_start_time',
				'type' => 'text',
                'instructions' => __( 'Please enter the time at which this event starts.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebabdd8d26e',
				'label' => __( 'End Time', 'church-options'),
				'name' => 'church_event_end_time',
				'type' => 'text',
                'instructions' => __( 'Please enter the time at which this event ends. If it is an open-ended event, just leave this field empty.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebabf98d26f',
				'label' => __( 'Location', 'church-options'),
				'name' => 'church_event_location',
				'type' => 'text',
                'instructions' => __( 'Please enter the place at which this event will take place.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebaca08d274',
				'label' => __( 'Google Map Link', 'church-options'),
				'name' => 'church_event_map',
				'type' => 'text',
                'instructions' => __( 'Go to <a href="http://maps.google.com" target="_blank">maps.google.com</a> and find the location, click on "Share", copy the link, then paste it into this field.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
			),
            array (
				'key' => 'field_5aebb10bf1cba',
				'label' => __( 'Expiration', 'church-options'),
				'name' => 'church_event_expiration',
				'type' => 'date_picker',
				'instructions' => __( 'Please choose the date you want the event stop being shown, typically the day after the event. The event will not be shown on the Events page after that date but will still be available in search.', 'church-options'),
				'date_format' => 'yymmdd',
				'display_format' => 'dd M yy',
				'first_day' => 7,
			),
			array (
				'key' => 'field_5aebac198d270',
				'label' => __( 'Contact Name', 'church-options'),
				'name' => 'church_event_contact_name',
				'type' => 'text',
                'instructions' => __( 'Please enter the name of the person to contact about this event.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebac318d271',
				'label' => __( 'Contact Number', 'church-options'),
				'name' => 'church_event_contact_number',
				'type' => 'text',
                'instructions' => __( 'Please enter the phone number of the contact person.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebac558d272',
				'label' => __( '2nd Contact Name', 'church-options'),
				'name' => 'church_second_contact_name',
				'type' => 'text',
                'instructions' => __( 'If there is a second contact person, enter their name here.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5aebac758d273',
				'label' => __( '2nd Contact Number', 'church-options'),
				'name' => 'church_second_contact_number',
				'type' => 'text',
                'instructions' => __( 'Please enter the phone number of the second contact person.', 'church-options'),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'church_events',
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


