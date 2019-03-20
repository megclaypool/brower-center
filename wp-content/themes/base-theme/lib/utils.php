<?php

namespace Roots\Sage\Utils;

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function get_search_form() {
  $form = '';
  locate_template('/templates/searchform.php', true, false);
  return $form;
}
add_filter('get_search_form', __NAMESPACE__ . '\\get_search_form');

/**
 * Registers Nav Menus
 */
register_nav_menus( array(
	'footer_menu' => 'Footer Menu',
	'primary_navigation' => 'Primary Navigation',
	'mobile_navigation' => 'Mobile Navigation',
) );

/**
 * Registers Widget Areas
 */
register_sidebar( array(
		'name'          => 'Copyright',
		'id'            => 'sidebar-copyright',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

/**
 * Add image sizes
 */

add_image_size( 'single-banner', 1400, 310, true );
add_image_size( 'related-thumbnail', 125, 145, true );
add_image_size( 'highlighted-thumbnail', 75, 90, true );
add_image_size( 'listing-thumbnail', 200, 165, true );
add_image_size( 'campaign-thumbnail', 290, 145, true );

