<?php
/*
Plugin Name: Accident Reports by Location
Plugin URI: http://ideations4.com/
Description: This plugin provides a shortcode to list accident reports by location, with parameters.
Version: 1.0
Author: Rachel Ideations4.com
Author URI: http://ideations4.com/
License: GPLv2
*/
// 

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
    add_options_page(
        __( 'Accident Reports by Location', 'textdomain' ),
        'Accident Reports by Location Shortcode ',
        'manage_options',
        'arbl-Plugin',
        'arblhelppage',
        'dashicons-id-alt',
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function arblhelppage(){
    require_once 'arblPlugin-admin-page.php';
}

// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'list-reports', 'arbl_shortcode' );

    global $wp_query,
        $post;
        
function arbl_shortcode( $atts ) {
 ob_start();
    // define attributes and their defaults
extract (shortcode_atts( array(
    'category' => '',
    'orderby' => '',
    'order' => '',
    'articlelimit' => ''

  ), $atts ));

 
    $args = array(
        'post_type' => 'accident_reports',
        'posts_per_page'    =>  $articlelimit,
        'orderby'           => $orderby,
        'order'             => $order,
      'tax_query' => array(
        array(
          'taxonomy' => 'accident_reports_location',
          'field' => 'slug',
          'terms' => $category,
        ),
      ),
     );
    $query = new WP_Query( $args );
    // run the loop based on the query
    echo '<div><ul class="accident-reports">';
    if ( $query->have_posts() ) {        while($query->have_posts()) : $query->the_post();
            echo '<li><a href="'.get_permalink().'">' . get_the_title().'</a></li> ';
        endwhile;
     }
     else {
            echo  'Sorry, no posts were found';
          }
    echo '</ul></div>';
   wp_reset_query();
    return ob_get_clean();
}
