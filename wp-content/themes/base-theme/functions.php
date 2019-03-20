<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = array(
  'lib/utils.php',                 // Utility functions
  'lib/init.php',                  // Initial theme setup and constants
  'lib/wrapper.php',               // Theme wrapper class
  'lib/conditional-tag-check.php', // ConditionalTagCheck class
  'lib/config.php',                // Configuration
  'lib/assets.php',                // Scripts and stylesheets
  'lib/titles.php',                // Page titles
  'lib/extras.php',                // Custom functions
);

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/lib/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 */
function my_theme_register_required_plugins() {
  /*
   * Array of plugin arrays. Required keys are name and slug.
   * If the source is NOT from the .org repo, then source is also required.
   */
  $plugins = array(
    array(
      'name'      => 'Advanced Custom Fields',
      'slug'      => 'advanced-custom-fields',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'Custom Facebook Feed',
      'slug'      => 'custom-facebook-feed',
      'required'  => false,
    ),
    array(
      'name'      => 'PHP Code Widget',
      'slug'      => 'php-code-widget',
      'required'  => false,
    ),
    array(
      'name'      => 'WordPress SEO by Yoast',
      'slug'      => 'wordpress-seo',
      'required'  => true,
    ),
    array(
      'name'      => 'Yet Another Related Posts Plugin (YARPP)',
      'slug'      => 'yet-another-related-posts-plugin',
      'required'  => false,
    ),
    array(
      'name'      => 'MailChimp for Wordpress',
      'slug'      => 'mailchimp-for-wp',
      'required'  => false,
    ),
    array(
      'name'      => 'Simple Page Ordering',
      'slug'      => 'simple-page-ordering',
      'required'  => false,
    ),
    array(
      'name'      => 'EWWW Image Optimizer',
      'slug'      => 'ewww-image-optimizer',
      'required'  => false,
    ),
    array(
      'name'      => 'Post Thumbnail Editor',
      'slug'      => 'post-thumbnail-editor',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'Auto Submenu',
      'slug'      => 'auto-submenu',
      'required'  => false,
    ),
    array(
      'name'      => 'Shortcodes Ultimate',
      'slug'      => 'shortcodes-ultimate',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'TinyMCE Advanced',
      'slug'      => 'tinymce-advanced',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'Ultimate Posts Widget',
      'slug'      => 'ultimate-posts-widget',
      'required'  => false,
    ),
    array(
      'name'      => 'Custom Post Type UI',
      'slug'      => 'custom-post-type-ui',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'Wordpress Importer',
      'slug'      => 'wordpress-importer',
      'force_activation' => true,
      'required'  => true,
    ),
    array(
      'name'      => 'mobble',
      'slug'      => 'mobble',
      'force_activation' => true,
      'required'  => true,
      ),
    array(
      'name'      => 'W3 Total Cache',
      'slug'      => 'w3-total-cache',
      'required'  => false,
      ),
    array(
      'name'         => 'Wordpress ACF Importer',
      'slug'         => 'wp-acf-importer',
      'source'       => 'https://github.com/maciej-gurban/wp_acf_importer/archive/master.zip',
      'force_activation' => true,
      'required'     => true,
    ),
    array(
      'name'         => 'Advanced Custom Fields Repeater',
      'slug'         => 'acf-repeater',
      'source'       => 'https://s3-us-west-1.amazonaws.com/hostedplugins/acf-repeater.zip',
      'force_activation' => true,
      'required'     => true,
    ),
    array(
      'name'         => 'Gravity Forms',
      'slug'         => 'gravityforms',
      'source'       => 'https://s3-us-west-1.amazonaws.com/hostedplugins/gravityforms.zip',
      'force_activation' => true,
      'required'     => false,
    ),
    array(
      'name'         => 'Google Analytics by Yoast',
      'slug'         => 'google-analytics-yoast',
      'source'       => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.latest-stable.zip',
      'required'     => false,
    ),
    array(
      'name'         => 'Really Simple CSV Importer',
      'slug'         => 'really-simple-csv-importer',
      'source'       => 'https://s3-us-west-1.amazonaws.com/hostedplugins/really-simple-csv-importer.zip',
      'required'     => false,
    ),
    array(
      'name'         => 'Search and Filter Pro',
      'slug'         => 'search-filter-pro',
      'source'       => 'https://s3-us-west-1.amazonaws.com/hostedplugins/search-filter-pro.zip',
      'required'     => false,
    ),

  );
  /*
   * Array of configuration settings. Amend each line as needed.
   *
   * TGMPA will start providing localized text strings soon. If you already have translations of our standard
   * strings available, please help us make TGMPA even better by giving us access to these translations or by
   * sending in a pull-request with .po file(s) with the translations.
   *
   * Only uncomment the strings in the config array if you want to customize the strings.
   */
  $config = array(
    'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to bundled plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'parent_slug'  => 'themes.php',            // Parent menu slug.
    'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.
  );
  tgmpa( $plugins, $config );
}

/*
if (is_plugin_active('wp-acf-importer')) {
  $acf_import = WP_ACF_Importer::get_instance();

  $acf_field = file_get_contents('advanced-custom-field-export.xml');

  if( $acf_import->acf_create_field( $acf_field) ) {
      echo 'Field created successfully.';
  }
  else {
      echo 'Import has not been performed.';
  }
}
*/


/*---------------
  CREATE PAGES
 ---------------*/

//create About page
if (isset($_GET['activated']) && is_admin()){

  $new_page_title = 'About';
  $new_page_content = "<p>Welcome to your new website's about page!</p>";
  $new_page_template = '';

  //this can all just be left the way it is
  $page_check = get_page_by_title($new_page_title);
  $new_page = array(
    'post_type' => 'page',
    'post_title' => $new_page_title,
    'post_content' => $new_page_content,
    'post_status' => 'publish',
    'post_author' => 1,
  );
  if(!isset($page_check->ID)){
    $new_page_id = wp_insert_post($new_page);
    if(!empty($new_page_template)){
      update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
    }
  }
}

//create Home page
if (isset($_GET['activated']) && is_admin()){

  $new_page_title = 'Home';
  $new_page_content = '<p>Bacon ipsum dolor amet biltong short ribs pork loin, jerky shoulder bacon corned beef pig ham cupim filet mignon turducken doner. Andouille brisket chuck, landjaeger beef pork tongue ball tip fatback beef ribs capicola. Pork chop chicken beef ribs drumstick ham bacon pancetta short ribs strip steak capicola tri-tip pig fatback salami bresaola. Pork belly cupim jowl tail pancetta meatloaf biltong drumstick pork chop filet mignon meatball ham hock.</p><p>Bacon ipsum dolor amet biltong short ribs pork loin, jerky shoulder bacon corned beef pig ham cupim filet mignon turducken doner. Andouille brisket chuck, landjaeger beef pork tongue ball tip fatback beef ribs capicola. Pork chop chicken beef ribs drumstick ham bacon pancetta short ribs strip steak capicola tri-tip pig fatback salami bresaola. Pork belly cupim jowl tail pancetta meatloaf biltong drumstick pork chop filet mignon meatball ham hock.</p>';
  $new_page_template = 'template-home.php';

  //this can all just be left the way it is
  $page_check = get_page_by_title($new_page_title);
  $new_page = array(
    'post_type' => 'page',
    'post_title' => $new_page_title,
    'post_content' => $new_page_content,
    'post_status' => 'publish',
    'post_author' => 1,
  );
  if(!isset($page_check->ID)){
    $new_page_id = wp_insert_post($new_page);
    if(!empty($new_page_template)){
      update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
    }
  }
}

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Roots_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Roots_Nav_Walker extends Walker_Nav_Menu {
  function check_current($classes) {
    return preg_match('/(current[-_])|active|dropdown/', $classes);
  }

  function start_lvl(&$output, $depth = 0, $args = array()) {
    $output .= "\n<ul class=\"dropdown-menu\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);

    if ($item->is_dropdown && ($depth === 0)) {
      $item_html = str_replace('<a', '<a', $item_html);
      $item_html = str_replace('</a>', '</a>', $item_html);
    }
    elseif (stristr($item_html, 'li class="divider')) {
      $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
    }
    elseif (stristr($item_html, 'li class="dropdown-header')) {
      $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
    }

    $item_html = apply_filters('roots/wp_nav_menu_item', $item_html);
    $output .= $item_html;
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    if ($element->is_dropdown) {
      $element->classes[] = 'dropdown';
    }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
}

class Bootstrap_Nav_Walker extends Walker_Nav_Menu {

   function start_lvl(&$output, $depth = 0, $args = array()) {
      $output .= "\n<ul class=\"submenu\">\n";
   }

   function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
       $item_html = '';
       parent::start_el($item_html, $item, $depth, $args);

       if ( $item->is_dropdown && $depth === 0 ) {
           //$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
           $item_html = str_replace( '</a>', ' </a><span class="fa fa-angle-down"></span><div class="submenuGroup">', $item_html );
       }

       $output .= $item_html;
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        if ( $element->current )
        $element->classes[] = 'active';

        $element->is_dropdown = !empty( $children_elements[$element->ID] );

        if ( $element->is_dropdown ) {
            if ( $depth === 0 ) {
                $element->classes[] = 'dropdown';
            } elseif ( $depth === 1 ) {
                // Extra level of dropdown menu,
                // as seen in http://twitter.github.com/bootstrap/components.html#dropdowns
                $element->classes[] = 'dropdown-submenu';
            }
        }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}


/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Roots_Nav_Walker() by default
 */
function roots_nav_menu_args($args = '') {
  $roots_nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  if (!$args['depth']) {
    $roots_nav_menu_args['depth'] = 3;
  }

  if (!$args['walker']) {
    $roots_nav_menu_args['walker'] = new Roots_Nav_Walker();
  }

  return array_merge($args, $roots_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'roots_nav_menu_args');

/**
 *
 *Get the excerpt custom function
 */
function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 35; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
    endif;

    $the_excerpt = '<p>' . $the_excerpt . '</p>';

    return $the_excerpt;
}

function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt).'...';
      }
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }

    function content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      }
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }

function get_long_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID
    $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 50; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
    endif;

    $the_excerpt = '<p>' . $the_excerpt . '</p>';

    return $the_excerpt;
}

/**
 * Check whether we are on this page or a sub page
 *
 * @param int $pid Page ID to check against.
 * @return bool True if we are on this page or a sub page of this page.
 */
function is_tree( $pid ) {      // $pid = The ID of the page we're looking for pages underneath
    $post = get_post();               // load details about this page

    $is_tree = false;
    if ( is_page( $pid ) ) {
        $is_tree = true;            // we're at the page or at a sub page
    }

    $anc = get_post_ancestors( $post->ID );
    foreach ( $anc as $ancestor ) {
        if ( is_page() && $ancestor == $pid ) {
            $is_tree = true;
        }
    }
    return $is_tree;  // we arn't at the page, and the page is not an ancestor
}

//Async Loading Function
function java_async($url)
{
    if ( strpos( $url, '#asyncload') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#asyncload', '', $url );
    else
  return str_replace( '#asyncload', '', $url )."' async='async"; 
    }
add_filter( 'clean_url', 'java_async', 11, 1 );

//image sizes
add_image_size( 'single-banner', 1400, 310, true);

//disable admin bar for all users except admins
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
