<?php

/*
Plugin Name: Church Options
Plugin URI: https://wordpress.org/plugins/church-options/
Description: An all-in-one solution for churches to add the custom post types and custom fields they need for an effective website.
Version: 1.0.3
Author: Tim Lawson @ Quixotic Studios
Author URI: http://quixotic-studios.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: church-options
*/

/* !0 Table of contents

1. Hooks
    1.1 - register plugin options
    1.2 - register custom menus
    1.3 - register public scripts
    1.4 - Advanced Custom Fields
    1.5 - Remove everything on uninstall
    1.6 - register admin notice

2. Widgets
    2.1 - 

3. Filters
    3.1 - church_admin_menus

4. External Scripts
5. Actions
    5.1 - church_uninstall_plugin()
    5.2 - church_remove_post_data()
    5.3 - church_remove_options()
    5.4 - church_check_wp_version()
    
6. Helpers
    6.1 - church_get_current_options()
    6.2 - church_get_option()
    6.3 - church_get_default_options()
    6.4 - do_announcements_shortcode()
    6.4 - church_get_admin_notice()

7. Custom Post Types
    7.1 - CPT includes

8. Admin Pages
    8.1 - church_dashboard_admin_page()
    8.2 - church_options_admin_page()

9. Settings
    9.1 - church_register_options()

10 Shortcodes
    10.1 - [church-announcements]
    10.2 - 
*/

// !1. Hooks

add_action( 'init', 'church_options_load_textdomain' );
function church_options_load_textdomain() {
	load_plugin_textdomain( 'church-options', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// 1.1
// register plugin options
add_action( 'admin_init', 'church_options_register_options' );

// 1.2
// register custom menus
add_action( 'admin_menu', 'church_options_admin_menus' );

// 1.3
// register public scripts
add_action('wp_enqueue_scripts', 'church_options_public_scripts');
add_action( 'admin_enqueue_scripts', 'church_options_get_tab_css' );

// 1.4
// Advanced Custom Fields
add_filter( 'acf/settings/path', 'church_acf_settings_path' );
add_filter( 'acf/settings/dir', 'church_acf_settings_dir' );
add_filter( 'acf/settings/show_admin', 'church_acf_show_admin' );
if( !defined( 'ACF_LITE' ) ) define( 'ACF_LITE', true ); // turns off ACF admin menu

// 1.5
// Remove everything on uninstall
// register_uninstall_hook( __FILE__, 'church_options_uninstall_plugin' );

// 1.6
// register admin notice
add_action( 'admin_notices', 'church_options_check_wp_version' );

// !2. Widgets

// 2.1


// !3. Filters

// 3.1
function church_options_admin_menus() {
    // main menu
    $top_menu_item = 'church_options_dashboard_admin_page';
    
    add_menu_page( '', 'Church Options', 'manage_options', 'church_options_dashboard_admin_page', 'church_options_dashboard_admin_page', 'dashicons-admin-settings' );
    // submenu items
    add_submenu_page( $top_menu_item, '', 'Dashboard', 'manage_options', $top_menu_item, $top_menu_item );
    // plugin settings
    add_submenu_page( $top_menu_item, '', 'Plugin Settings', 'manage_options', 'church_options_admin_page', 'church_options_admin_page' );
}

// !4. External Scripts

// 4.1
function church_options_public_scripts() {
	
	// register scripts with WordPress's internal library
    wp_register_script( 'church-options-custom-js-public', plugins_url( '/js/public/scripts.js', __FILE__ ), array( 'jquery' ),'',true );
    wp_register_style( 'church-options-custom-css-public', plugins_url( '/css/public/custom.css', __FILE__ ));
	
	// add to que of scripts that get loaded into every page
    wp_enqueue_script( 'church-options-custom-js-public' );
    wp_enqueue_style( 'church-options-custom-css-public' );
	
}

function church_options_get_tab_css() {
	
	// register styles with Wordpress
    wp_register_style( 'church_options_tab_styles', plugins_url( '/css/private/style.css', __FILE__ ));
    wp_register_script( 'church_options_custom_js_private', plugins_url( '/js/private/custom.js', __FILE__ ), array( 'jquery' ), '', true );
	
	// add styles to que of styles to load in admin
    wp_enqueue_style( 'church_options_tab_styles' );
    wp_enqueue_script( 'church_options_custom_js_private' );
}

// 4.2
include_once( plugin_dir_path( __FILE__ ) . 'lib/advanced-custom-fields/acf.php' );

// !5. Actions

// 5.1
function church_options_uninstall_plugin() {
    // Remove custom post types
    church_options_remove_post_data();
    // Remove plugin options
    church_options_remove_options();
}

// 5.2
function church_options_remove_post_data() {
    global $wpdb;
    // Set up return variable
    $data_removed = false;
    try {
        // get custom table name
        $table_name = $wpdb->prefix . "posts";
        // Set up custom post types array
        $custom_post_types = array( 'church_announcements', 'church_events', 'church_ministries', 'church_sermons', 'church_people', 'church_verses' );
        // Remove data from posts DB table where post types are equal to custom post types
        $data_removed = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE post_type = %s or post_type = %s or post_type = %s or post_type = %s or post_type = %s or post_type = %s", $custom_post_types[0], $custom_post_types[1], $custom_post_types[2], $custom_post_types[3], $custom_post_types[4], $custom_post_types[5] ) );
        // Delete orphaned metadata
        $table_name_1 = $wpdb->prefix . "_postmeta";
        $table_name_2 = $wpdb->prefix . "_posts";
        $wpdb->query( $wpdb->prepare( "DELETE pm FROM $table_name_1 pm LEFT JOIN $table_name_2 wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL" ) );
    } catch ( Exception $e ) {
        // php error
    }
    // return result
    return $data_removed;
}

// 5.3
function church_options_remove_options() {
    $options_removed = false;
    try {
        // get plugin options settings
        $options = church_options_get_options_settings();
        // Loop over settings
        foreach( $options[ 'settings' ] as $setting ):
            // Unregister the setting
            unregister_setting( $options[ 'group' ], $setting );
        endforeach;
        $options_removed = true;
    } catch ( Exception $e ) {
        // php error
    }
    // return results
    return $options_removed;
}

// 5.4
function church_options_check_wp_version() {
    global $pagenow;
    if( $pagenow = 'plugins.php' && is_plugin_active( 'church-options/church-options.php' ) ):
        // Get WP version
        $wp_version = get_bloginfo( 'version' );
        // versions plugin has been tested with
        $tested_range = array( 4.0, 5.2 );
        // If current version is not in tested range
        if( (float)$wp_version >= (float)$tested_range[0] && (float)$wp_version <= (float)$tested_range[1] ):
        else :
        // Get notice html
        $notice = church_options_get_admin_notice( __('Church Options has not been tested with your version of Wordpress. It may still work though...', 'church-options'), 'error' );
        // echo the notice html
        echo $notice;
        endif;
    endif;
}

// !6. Helpers

// 6.1
function church_options_get_current_options() {
    $current_options = array();
    try {
        // build options array
        $current_options = array(
            'church_event_option'           => church_options_get_option( 'church_event_option' ),
            'church_announcements_option'   => church_options_get_option( 'church_announcements_option' ),
            'church_ministries_option'      => church_options_get_option( 'church_ministries_option' ),
            'church_sermons_option'         => church_options_get_option( 'church_sermons_option' ),
            'church_people_option'          => church_options_get_option( 'church_people_option' ),
            'church_verses_option'          => church_options_get_option( 'church_verses_option' ),
        );
    } catch( Exception $e ) {
        // php error
    }
    return $current_options;
}

// 6.2
function church_options_get_option( $option_name ) {
    $option_value = '';
    try {
        // get default option values
        $defaults = church_options_get_default_options();
        // get requested option
        switch( $option_name ) {
            case 'church_event_option':
                $option_value = ( get_option( 'church_event_option' ) ) ? get_option( 'church_event_option' ) : $defaults[ 'church_event_option' ];
                break;
            case 'church_announcements_option':
                $option_value = ( get_option( 'church_announcements_option' ) ) ? get_option( 'church_announcements_option' ) : $defaults[ 'church_announcements_option' ];
                break;
            case 'church_ministries_option':
                $option_value = ( get_option( 'church_ministries_option' ) ) ? get_option( 'church_ministries_option' ) : $defaults[ 'church_ministries_option' ];
                break;
            case 'church_sermons_option':
                $option_value = ( get_option( 'church_sermons_option' ) ) ? get_option( 'church_sermons_option' ) : $defaults[ 'church_sermons_option' ];
                break;
            case 'church_people_option':
                $option_value = ( get_option( 'church_people_option' ) ) ? get_option( 'church_people_option' ) : $defaults[ 'church_people_option' ];
                break;
            case 'church_verses_option':
                $option_value = ( get_option( 'church_verses_option' ) ) ? get_option( 'church_verses_option' ) : $defaults[ 'church_verses_option' ];
                break;
        }
    } catch( Exception $e ) {
        // php error
    }
    return $option_value;
}

// 6.3
function church_options_get_default_options() {
    $defaults = array();
    try {
        $defaults = array(
            'church_event_option'           => '1',
            'church_announcements_option'   => '1',
            'church_ministries_option'      => '1',
            'church_sermons_option'         => '1',
            'church_people_option'          => '1',
            'church_verses_option'          => '1',
        );
    } catch( Exception $e ) {
        // php error
    }
    return $defaults;
}

// 6.4
function church_options_get_options_settings() {
    // Set up return data
    $settings = array(
        'group'     => 'church_plugin_options',
        'settings'  => array( 'church_event_option', 'church_announcements_option', 'church_ministries_option', 'church_sermons_option', 'church_people_option', 'church_verses_option' )
    );
    // return options data
    return $settings;
}

// 6.5
function church_options_get_admin_notice( $message, $class ) {
    // Set up return variable
    $output = '';
    try {
        // Create output html
        $output = '<div class="' . $class . '">
            <p>'. $message .'</p>
            </div>';
    } catch( Exception $e ) {
        // php error
    }
    // Return output
    return $output;
}



// !7. Custom Post Types

// 7.1
global $options;

if ( get_option( 'church_event_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-events.php' );
}
if ( get_option( 'church_announcements_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-announcements.php' );
}
if ( get_option( 'church_ministries_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-ministries.php' );
}
if ( get_option( 'church_sermons_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-sermons.php' );
}
if ( get_option( 'church_people_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-people.php' );
}
if ( get_option( 'church_verses_option' ) === '1' ) {
    include_once( plugin_dir_path( __FILE__ ) . 'cpt/church-verses.php' );
    include_once( plugin_dir_path( __FILE__ ) . 'widgets/church-verses-widget.php' );
}

// !8. Admin Pages

// 8.1
function church_options_dashboard_admin_page() {
    $announcementsCount = wp_count_posts( 'church_announcements' )->publish;
    $eventsCount = wp_count_posts( 'church_events' )->publish;
    $sermonsCount = wp_count_posts( 'church_sermons' )->publish;
    $ministriesCount = wp_count_posts( 'church_ministries' )->publish;
    $peopleCount = wp_count_posts( 'church_people' )->publish;
    $versesCount = wp_count_posts( 'church_verses' )->publish;
    ?>
    <div class="wrap">
        <h2>Church Options</h2>
        <p><?php esc_html_e( 'The Church Options plugin creates all of the custom post types churches need for an effective Web site.', 'church-options' ) ?></p>
    </div>
    <table>
        <tbody>
            <tr>
                <th style="text-align: center"># <?php esc_html_e( 'Announcements', 'church-options' ) ?></th>
                <th style="text-align: center"># <?php esc_html_e( 'Events', 'church-options' ) ?></th>
                <th style="text-align: center"># <?php esc_html_e( 'Sermons', 'church-options' ) ?></th>
                <th style="text-align: center"># <?php esc_html_e( 'Ministries', 'church-options' ) ?></th>
                <th style="text-align: center"># <?php esc_html_e( 'People', 'church-options' ) ?></th>
                <th style="text-align: center"># <?php esc_html_e( 'Verses', 'church-options' ) ?></th>
            </tr>
            <tr>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $announcementsCount; ?></h1></td>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $eventsCount; ?></h1></td>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $sermonsCount; ?></h1></td>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $ministriesCount; ?></h1></td>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $peopleCount; ?></h1></td>
                <td><h1 style="font-size: 72pt; text-align: center; padding: 0 30px;"><?php echo $versesCount; ?></h1></td>
            </tr>
        </tbody>
    </table>
    
    <div class="wrap">
		<h2><?php esc_html_e( 'Instructions', 'church-options' ) ?></h2>
		<p><?php esc_html_e( 'Click the tabs below for detailed instructions for using the custom post types.', 'church-options' ) ?></p>
    </div>
    
    <!-- tab links -->
    <div class="tab">
        <button class="tablinks active" onclick="coOpenTab(event, 'Announcements')"><?php esc_html_e( 'Announcements', 'church_options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'Events')"><?php esc_html_e( 'Events', 'church-options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'Sermons')"><?php esc_html_e( 'Sermons', 'church-options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'Ministries')"><?php esc_html_e( 'Ministries', 'church-options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'People')"><?php esc_html_e( 'People', 'church-options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'Verses')"><?php esc_html_e( 'Verses', 'church-options' ) ?></button>
        <button class="tablinks" onclick="coOpenTab(event, 'Contact')"><?php esc_html_e( 'Contact', 'church-options' ) ?></button>
    </div>
    
    <!-- tab content -->
    <div class="tabcontent" id="Announcements">
       <p><?php esc_html_e('Once the Announcements post type has been selected in the Church Options settings, the Announcements will appear in the sidebar under "Comments".', 'church-options') ?></p>
        <ol>
            <li><?php esc_html_e('Click on "Announcements" and choose "Add New", just like a regular post.', 'church-options') ?></li>
            <li><?php esc_html_e('Fill in the title as usual, then enter what you want the announcement to say in the content area.', 'church-options') ?></li>
            <li><?php esc_html_e('Enter the information for up to two contacts. This is not required.', 'church-options') ?></li>
            <li><?php esc_html_e('Then choose the date you want the announcement to stop showing. This uses a standard date picker: just click the field, then click the day of the month in the calendar that appears.', 'church-options') ?></li>
            <li><?php esc_html_e('Lastly, click the "Publish" button as usual.', 'church-options') ?></li>
        </ol>
        <p><?php esc_html_e('There are two ways of displaying Announcements: the Slider widget and the Bootstrap Carousel widget. The Announcements Slider widget has several options for transition and timing, while the Boostrap Carousel is a simple sliding carousel. Both will display the announcements which have not expired, according to the date entered in the "Expiration Date" field. The announcement content can be styled, but it is best to keep such styling to a minimum to prevent breaking the layout of the widget.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="Events">
        <p><?php esc_html_e('The Events post type has many fields that can be filled in for many different situations. Activating the Events post type also makes available the Events widgets. There is one for a horizontal layout and one for vertical.', 'church-options') ?></p>
        <ol>
            <li><?php esc_html_e('Select the Events in the sidebar and choose "Add New".', 'church-options') ?></li>
            <li><?php esc_html_e('Enter the event title in the title field.', 'church-options') ?></li>
            <li><?php esc_html_e('Fill in the event details in the content area.', 'church-options') ?></li>
        </ol>
        <p><?php esc_html_e('Most of the rest of the fields are optional, but it is recommended that the start date and time be filled in as well as the contact persons. Categories and tags can be added, and can be searched, but they do not appear on the front end.', 'church-options') ?></p>
        <p><?php esc_html_e('The Events widgets will display different amounts of information depending on whether it is the vertical or horizontal version. The vertical version displays less due to the need to conserve space in the sidebar and footer areas.', 'church-options') ?></p>
        <h2><?php esc_html_e('Events page', 'church-options') ?></h2>
        <p><?php esc_html_e('Create a new page and choose the "Events" template from the "Template" dropdown on the right side under "Page Attributes". Any text entered will be displayed above the events.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="Sermons">
        <p><?php esc_html_e('The Sermons post type is intended for making available recordings of the sermons in the MP3 format. The MP3s files can be downloaded or listened to right on the Web site. Other audio formats can be used, but MP3 is recommended for the greatest compatibilty.', 'church-options') ?></p>
        <p><?php esc_html_e('To make a Sermons post', 'church-options') ?>:</p>
        <ol>
            <li><?php esc_html_e('Select the "Add New" link under the "Sermons" post type in the sidebar.', 'church-options') ?></li>
            <li><?php esc_html_e('Enter the sermon title in the title field.', 'church-options') ?></li>
            <li><?php esc_html_e('Enter a short summary of the sermon in the content area.', 'church-options') ?></li>
            <li><?php esc_html_e('Choose the MP3 file from the field selector under the content area.', 'church-options') ?></li>
            <li><?php esc_html_e('The categories can be used to add topics of the sermons which can be searched.', 'church-options') ?></li>
        </ol>
        <h2><?php esc_html_e('Sermons page', 'church-options') ?></h2>
        <p><?php esc_html_e('Create a new page and choose the "Sermons" template from the "Template" dropdown on the right side under "Page Attributes". Any text entered will be displayed above the sermons.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="Ministries">
        <p><?php esc_html_e('The Ministries post type is used to present the church\'s several ministries. Since these don\'t change very often, unlike events, they are expected to be a little more flamboyant in their design. The Ministries widgets supprt featured images, and can withstand more formatting of the descriptive text.', 'church-options') ?></p>
        <h2><?php esc_html_e('Ministries page', 'church-options') ?></h2>
        <p><?php esc_html_e('Create a new page and choose the "Ministries" template from the "Template" dropdown on the right side under "Page Attributes". Any text entered will be displayed above the ministries.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="People">
        <p><?php esc_html_e('The People post type is used for the church staff. They should be entered in the order they are to be displayed. The "Staff" widget only displays the name and title, but the Staff page displays the full content entered in the People post.', 'church-options') ?></p>
        <p><?php esc_html_e('If the person\'s email address and phone number are entered into the appropriate fields in the "Add New People" screen, tapping on the person\'s name will begin an email and tapping the title will initiate a phone call in the widgets.', 'church-options') ?></p>
        <h2><?php esc_html_e('Staff page', 'church-options') ?></h2>
        <p><?php esc_html_e('Create a new page and choose the "Staff" template from the "Template" dropdown on the right side under "Page Attributes". Any text entered will be displayed above the staff.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="Verses">
        <p><?php esc_html_e('The Verses post type is intended only for use with the Random Verses widget that comes with this plugin. They can also be used with any widget that allows selection of post types. Since the widget simply displays a random verse post, there are no settings and no dedicated page for displaying them.', 'church-options') ?></p>
    </div>
    <div class="tabcontent" id="Contact">
        <p><?php esc_html_e('Below you will find the code we used with Contact Form 7 to create the contact form used on the Contact page in the HTML mockup. Simply copy all of the code and paste it into the "Form" section.', 'church-options') ?></p><br>
        <pre>
    &ltdiv class="row"&gt
    &ltdiv class="col-sm-6"&gt
    &ltdiv class="form-group"&gt
    &ltlabel class="sr-only" for="contact-name"&gtName&lt/label&gt
    [text* your-name class:form-control class:input-lg id:contact-name placeholder "Your name"]
    &lt/div&gt
    &lt/div&gt

    &ltdiv class="col-sm-6"&gt
    &ltdiv class="form-group"&gt
    &ltlabel class="sr-only" for="contact-email"&gtEmail&lt/label&gt
    [email* your-email class:form-control class:input-lg id:contact-email placeholder "Your Email"]
    &lt/div&gt
    &lt/div&gt

    &ltdiv class="col-sm-12"&gt
    &ltdiv class="form-group"&gt
    &ltlabel class="sr-only" for="contact-words"&gtMessage&lt/label&gt
    [textarea* your-message class:form-control class:input-lg id:contact-words 5 placeholder "Your Message"]
    &lt/div&gt
    &lt/div&gt
    &lt/div&gt
    [submit class:btn class:btn-info class:btn-lg class:pull-right "Get in touch &raquo;"]
        </pre>
    </div>
    
    <script>
    jQuery(function($) {
        // Get all elements with class="tablinks" and remove the "active" class
        linkbutton = document.getElementsByClassName("tablinks");
        for (i = 0; i < linkbutton.length; i++) {
            linkbutton[i].className = linkbutton[i].className.replace(" active", "");
        }
    });
    </script>
    <?php
    echo $output;
}

// 8.2
function church_options_admin_page(){
    $options = church_options_get_current_options();

    echo '<div class="wrap">
            <h2>Church Options</h2>
            <form action="options.php" method="post">';
    
    settings_fields( 'church_plugin_options' ); ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="church_announcements_option"><?php esc_html_e( 'Announcements', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_announcements_option" value="1" id="church-announcements-option" <?php checked('1', get_option( 'church_announcements_option' ), true); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the Announcements post type.', 'church-options' ) ?></p>
                </td>
                <th scope="row"><label for="church_event_option"><?php esc_html_e( 'Events', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_event_option" value="1" <?php checked('1', get_option( 'church_event_option' ), true); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the Events post type.', 'church-options' ) ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="church_ministries_option"><?php esc_html_e( 'Ministries', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_ministries_option" value="1" id="church-ministries-option" <?php checked('1', get_option( 'church_ministries_option' ), true); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the Ministries post type.', 'church-options' ) ?></p>
                </td>
                <th scope="row"><label for="church_sermons_option"><?php esc_html_e( 'Sermons', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_sermons_option" value="1" id="church-sermons-option" <?php checked('1', get_option( 'church_sermons_option' ), true); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the Sermons post type.', 'church-options' ) ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="church_people_option"><?php esc_html_e( 'People', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_people_option" value="1" id="church-people-option" <?php checked('1', get_option( 'church_people_option' ), true); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the People post type.', 'church-options' ) ?></p>
                </td>
                <th scope="row"><label for="church_verses_option"><?php esc_html_e( 'Verses', 'church-options' ) ?></label></th>
                <td>
                    <input type="checkbox" name="church_verses_option" value="1" id="church_verses_option" <?php checked( '1', get_option( 'church_verses_option' ), true ); ?>>
                    <p class="description"><?php esc_html_e( 'Check this box to use the Verses post type.', 'church-options' ) ?></p>
                </td>
            </tr>
        </tbody>
    </table> <?php
    @submit_button();
    echo '</form>
    </div>';
}

// !9. Settings

// 9.1
function church_options_register_options() {
    $options = church_options_get_options_settings();
    // Loop over settings
    foreach( $options[ 'settings' ] as $setting ):
        // register the settings
        register_setting( $options[ 'group' ], $setting );
    endforeach;
}

// !10 Shortcodes

