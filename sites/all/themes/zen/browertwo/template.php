<?php
// $Id: template.php,v 1.1.2.4 2008/06/24 11:18:36 johnalbin Exp $

/**
 * @file
 *
 * OVERRIDING THEME FUNCTIONS
 *
 * The Drupal theme system uses special theme functions to generate HTML output
 * automatically. Often we wish to customize this HTML output. To do this, we
 * have to override the theme function. You have to first find the theme
 * function that generates the output, and then "catch" it and modify it here.
 * The easiest way to do it is to copy the original function in its entirety and
 * paste it here, changing the prefix from theme_ to phptemplate_ or zen_. For
 * example:
 *
 *   original: theme_breadcrumb()
 *   theme override: zen_breadcrumb()
 *
 * DIFFERENCES BETWEEN ZEN SUB-THEMES AND NORMAL DRUPAL SUB-THEMES
 *
 * The Zen theme allows its sub-themes to have their own template.php files. The
 * only restriction with these files is that they cannot redefine any of the
 * functions that are already defined in Zen's main template files:
 *   template.php, template-menus.php, and template-subtheme.php.
 * Every theme override function used in those files is documented below in this
 * file.
 *
 * Also remember that the "main" theme is still Zen, so your theme override
 * functions should be named as such:
 *  theme_block()      becomes  zen_block()
 *  theme_feed_icon()  becomes  zen_feed_icon()  as well
 *
 * However, there are two exceptions to the "theme override functions should use
 * 'zen' and not 'mytheme'" rule. They are as follows:
 *
 * Normally, for a theme to define its own regions, you would use the
 * THEME_regions() fuction. But for a Zen sub-theme to define its own regions,
 * use the function name
 *   STARTERKIT_regions()
 * where STARTERKIT is the name of your sub-theme. For example, the zen_classic
 * theme would define a zen_classic_regions() function.
 *
 * For a sub-theme to add its own variables, instead of _phptemplate_variables,
 * use these functions:
 *   STARTERKIT_preprocess_page(&$vars)     to add variables to the page.tpl.php
 *   STARTERKIT_preprocess_node(&$vars)     to add variables to the node.tpl.php
 *   STARTERKIT_preprocess_comment(&$vars)  to add variables to the comment.tpl.php
 *   STARTERKIT_preprocess_block(&$vars)    to add variables to the block.tpl.php
 */


/*
 * Initialize theme settings
 */
include_once 'theme-settings-init.php';


/*
 * Sub-themes with their own page.tpl.php files are seen by PHPTemplate as their
 * own theme (seperate from Zen). So we need to re-connect those sub-themes
 * with the main Zen theme.
 */
include_once './'. drupal_get_path('theme', 'zen') .'/template.php';


/*
 * Add the stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that are in the main Zen folder, use path_to_theme().
 * To add stylesheets that are in your sub-theme's folder, use path_to_subtheme().
 */

// Add any stylesheets you would like from the main Zen theme.
drupal_add_css(path_to_theme() .'/html-elements.css', 'theme', 'all');
drupal_add_css(path_to_theme() .'/tabs.css', 'theme', 'all');

// Then add styles for this sub-theme.
drupal_add_css(path_to_subtheme() .'/layout.css', 'theme', 'all');
drupal_add_css(path_to_subtheme() .'/browertwo.css', 'theme', 'all');

// Avoid IE5 bug that always loads @import print stylesheets
zen_add_print_css(path_to_subtheme() .'/print.css');


/**
 * Declare the available regions implemented by this theme.
 *
 * @return
 *   An array of regions.
 */
/* -- Delete this line if you want to use this function
function browertwo_regions() {
  return array(
    'left' => t('left sidebar'),
    'right' => t('right sidebar'),
    'navbar' => t('navigation bar'),
    'content_top' => t('content top'),
    'content_bottom' => t('content bottom'),
    'header' => t('header'),
    'footer' => t('footer'),
    'closure_region' => t('closure'),
  );
}
// */


/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
/* -- Delete this line if you want to use this function
function zen_breadcrumb($breadcrumb) {
  return '<div class="breadcrumb">'. implode(' ’Ä† ', $breadcrumb) .' ’Ä†</div>';
}
// */


function zen_views_view_list( $view, $nodes, $type)
{
	// hacky, but we need to be able to set the body class
	// based on the view list name
	$list_names_css = array(
		'tenants' => 'tenants',
		'exhibitions' => 'exhibitions',
		'exhibitions_2nd_floor' => 'exhibitions',
		'upcoming_exhibitions' => 'exhibitions',
		'programs' => 'programs',
		'upcoming_programs' => 'programs',
		'team' => 'building',
		'recent_news_archive' => 'about',
		'exhibitions_past' => 'exhibitions'
		);
	bp_set_global( 'hard_css', $list_names_css[$view->name]);
	// echo('<!--' . $view->name . '-->');
	return theme_views_view_list( $view, $nodes, $type);
}


function zen_views_view_table( $view, $nodes, $type)
{
	// hacky, but we need to be able to set the body class
	// based on the view list name
	$list_names_css = array(
		'tenants' => 'tenants',
		'exhibitions' => 'exhibitions',
		'exhibitions_2nd_floor' => 'exhibitions',
		'upcoming_exhibitions' => 'exhibitions',
		'programs' => 'programs',
		'upcoming_programs' => 'programs',
		'team' => 'building',
		'recent_news_archive' => 'about',
		'exhibitions_past' => 'exhibitions'
		);
	bp_set_global( 'hard_css', $list_names_css[$view->name]);
	return theme_views_view_table( $view, $nodes, $type);
}
/**
 * Override or insert PHPTemplate variables into all templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 * @param $hook
 *   The name of the theme function being called (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function */
function browertwo_preprocess(&$vars, $hook) {
	global $g_bp;
  $vars['g_bp'] = $g_bp;
}

/**
 * views template to output a view.
 * This code was generated by the views theming wizard
 * Date: Thu, 2009-06-25 09:14
 * View: tenants
 *
 * This function goes in your template.php file
 */
function zen_views_view_list_tenants($view, $nodes, $type) {
  $fields = _views_get_fields();

  $taken = array();

  // Set up the fields in nicely named chunks.
  foreach ($view->field as $id => $field) {
    $field_name = $field['field'];
    if (isset($taken[$field_name])) {
      $field_name = $field['queryname'];
    }
    $taken[$field_name] = true;
    $field_names[$id] = $field_name;
  }

  // Set up some variables that won't change.
  $base_vars = array(
    'view' => $view,
    'view_type' => $type,
  );

  $table_of_contents = '<div style="width: 240px; float: left; display: inline;" class="tenant-column">';
	$column_break = intval((count($nodes)+1)/2);
	$node_count = 0;
  foreach ($nodes as $i => $node) {
	if ( $node_count==$column_break)
	  $table_of_contents .= '</div><div style="width: 240px; float: left; display: inline;padding-left: 20px;" class="tenant-column">';
	$node_count++;
    $vars = $base_vars;
    $vars['node'] = $node;
    $vars['count'] = $i;
    $vars['stripe'] = $i % 2 ? 'even' : 'odd';
    foreach ($view->field as $id => $field) {
      $name = $field_names[$id];
      $vars[$name] = views_theme_field('views_handle_field', $field['queryname'], $fields, $field, $node, $view);
      if (isset($field['label'])) {
        $vars[$name . '_label'] = $field['label'];
      }
    }
	$t = node_load( $node->nid);
	$table_of_contents .= '<p><a href="node/' . $node->nid . '">' .
		$t->title . '</a></p>';
    $items[] = _phptemplate_callback('views_list_tenants', $vars, array('views-list-tenants'));
  }
	$table_of_contents .= '</div>';
  if ($items) {
	$result = theme('item_list', $items);
	$result = ''; // hack; we're just blanking out the originally designed implementation
    return '<!-- table of contents -->' . $table_of_contents . $result;
  }
}

/*
zen_views_rss_feed

Overrides views default RSS feed output. Adds any field beginning with
"field_" as "extra" RSS node, e.g.:
<brower_arbitrary_field>arbitrary value</brower_arbitrary_field>

Made necessary by kiosk's need to access arbitrary content fields.
*/

function zen_views_rss_feed($view, $nodes, $type) {
  if ($type == 'block') {
    return;
  }
  global $base_url;

  $channel = array(
    // a check_plain isn't required on these because format_rss_channel
    // already does this.
    'title'       => views_get_title($view, 'page'),
    'link'        => url($view->feed_url ? $view->feed_url : $view->real_url, NULL, NULL, true),
    'description' => $view->description,
  );

  $item_length = variable_get('feed_item_length', 'teaser');
  $namespaces = array('xmlns:dc="http://purl.org/dc/elements/1.1/"');

  // Except for the original being a while and this being a foreach, this is
  // completely cut & pasted from node.module.
  foreach ($nodes as $node) {
    // Load the specified node:
    $item = node_load($node->nid);
    $link = url("node/$node->nid", NULL, NULL, 1);
	$extra = array();
	
    if ($item_length != 'title') {
      $teaser = ($item_length == 'teaser') ? TRUE : FALSE;

      // Filter and prepare node teaser
      if (node_hook($item, 'view')) {
        node_invoke($item, 'view', $teaser, FALSE);
      }
      else {
        $item = node_prepare($item, $teaser);
  		foreach( $item as $k => $v)
		{
			if (( substr( $k, 0, 6) == 'field_') && ( isset( $v[0])))
			{
				$extra[] = array('key' => 'brower_' . substr($k, 6), 'value' => $v[0]['value']);
			}
		}
    }

      // Allow modules to change $node->teaser before viewing.
      node_invoke_nodeapi($item, 'view', $teaser, FALSE);
    }

    // Allow modules to add additional item fields
    $extra = array_merge( $extra, node_invoke_nodeapi($item, 'rss item'));
    $extra = array_merge($extra, array(array('key' => 'pubDate', 'value' =>  date('r', $item->created)), array('key' => 'dc:creator', 'value' => $item->name), array('key' => 'guid', 'value' => $item->nid . ' at ' . $base_url, 'attributes' => array('isPermaLink' => 'false'))));
    foreach ($extra as $element) {
      if ($element['namespace']) {
        $namespaces = array_merge($namespaces, $element['namespace']);
      }
    }
    
    // Prepare the item description
    switch ($item_length) {
      case 'fulltext':
        $item_text = $item->body;
        break;
      case 'teaser':
        $item_text = $item->teaser;
        if ($item->readmore) {
          $item_text .= '<p>'. l(t('read more'), 'node/'. $item->nid, NULL, NULL, NULL, TRUE) .'</p>';
        }
        break;
      case 'title':
        $item_text = '';
        break;
    }

    $items .= format_rss_item($item->title, $link, $item_text, $extra);
  }

  $channel_defaults = array(
    'version'     => '2.0',
    'title'       => variable_get('site_name', 'drupal') .' - '. variable_get('site_slogan', ''),
    'link'        => $base_url,
    'description' => variable_get('site_mission', ''),
    'language'    => $GLOBALS['locale'],
  );
  $channel = array_merge($channel_defaults, $channel);

  $output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  $output .= "<rss version=\"". $channel["version"] . "\" xml:base=\"". $base_url ."\" ". implode(' ', $namespaces) .">\n";
  $output .= format_rss_channel($channel['title'], $channel['link'], $channel['description'], $items, $channel['language']);
  $output .= "</rss>\n";

  drupal_set_header('Content-Type: text/xml; charset=utf-8');
  print $output;
  module_invoke_all('exit');
  exit; 
}
//function zen_menu_item_link( $item, $link_item)
//{
//   return l($item['title'], $link_item['path'], !empty($item['description']) ? array('title' => $item['description']) : array(), isset($item['query']) ? $item['query'] : NULL);
//}
//
//function zen_links($links, $attributes = array('class' => 'links')) {
//  $output = '';
//
//  if (count($links) > 0) {
//    $output = '<ul'. drupal_attributes($attributes) .'>';
//
//    $num_links = count($links);
//    $i = 1;
//
//    foreach ($links as $key => $link) {
//      $class = $key;
//
      // Automatically add a class to each link and also to each LI
//      if (isset($link['attributes']) && isset($link['attributes']['class'])) {
//        $link['attributes']['class'] .= ' ' . $key;
//      }
//      else {
//        $link['attributes']['class'] = $key;
//      }
//
      // Add first and last classes to the list of links to help out themers.
//      $extra_class = '';
//      if ($i == 1) {
//        $extra_class .= 'first ';
//      }
//      if ($i == $num_links) {
//        $extra_class .= 'last ';
//      }
//      $output .= '<li '. drupal_attributes(array('class' => $extra_class . $class)) .'>';
//
      // Is the title HTML?
//      $html = isset($link['html']) && $link['html'];
//
      // Initialize fragment and query variables.
//      $link['query'] = isset($link['query']) ? $link['query'] : NULL;
//      $link['fragment'] = isset($link['fragment']) ? $link['fragment'] : NULL;
//
//      if (isset($link['href'])) {
//        $output .= l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment'], FALSE, $html);
//      }
//      else if ($link['title']) {
        //Some links are actually not links, but we wrap these in <span> for adding title and class attributes
//        if (!$html) {
//          $link['title'] = check_plain($link['title']);
//        }
//        $output .= '<span'. drupal_attributes($link['attributes']) .'>'. $link['title'] .'</span>';
//      }
//		$tertiary = menu_primary_links(3, $link);
//		if ( is_array($tertiary))
//		{
//			$output .= theme('links', $tertiary, array('class' => 'links tertiary-links'));
//		} else
//		{
//			$output .= 'no tertiary';
//		}
//      $i++;
//      $output .= "</li>\n";
//    }
//
//    $output .= '</ul>';
//  }
//
//  return $output;
//}
// */

/**
 * Override or insert PHPTemplate variables into the page templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 */
/* -- Delete this line if you want to use this function
function browertwo_preprocess_page(&$vars) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert PHPTemplate variables into the node templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 */
/* -- Delete this line if you want to use this function
function browertwo_preprocess_node(&$vars) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert PHPTemplate variables into the comment templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 */
/* -- Delete this line if you want to use this function
function browertwo_preprocess_comment(&$vars) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert PHPTemplate variables into the block templates.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme template.
 */
/* -- Delete this line if you want to use this function
function browertwo_preprocess_block(&$vars) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */


/**
 * Override the theme's search form using the search-theme-form.tpl.php file.
 *
 * This is the form that appears when toggling the checkbox for "Enable or
 * disable the display of... Search Box" on the theme settings page.
 */
/* -- Delete this line if you want to use this function
function phptemplate_search_theme_form($form) {
  return _phptemplate_callback('search_theme_form', array('form' => $form), array('search-theme-form'));
}
// */

/**
 * Override the search block's form using the search-block-form.tpl.php file.
 *
 * This is the form that appears when enabling the "Search form" block.
 */
/* -- Delete this line if you want to use this function
function phptemplate_search_block_form($form) {
  return _phptemplate_callback('search_block_form', array('form' => $form), array('search-block-form'));
}
// */

/**
 * Generate the HTML representing a given menu item ID.
 *
 * An implementation of theme_menu_item_link()
 *
 * @param $item
 *   array The menu item to render.
 * @param $link_item
 *   array The menu item which should be used to find the correct path.
 * @return
 *   string The rendered menu item.
 */
/* -- Delete this line if you want to use this function
function zen_menu_item_link($item, $link_item) {
  // If an item is a LOCAL TASK, render it as a tab
  $tab = ($item['type'] & MENU_IS_LOCAL_TASK) ? TRUE : FALSE;
  return l(
    $tab ? '<span class="tab">'. check_plain($item['title']) .'</span>' : $item['title'],
    $link_item['path'],
    !empty($item['description']) ? array('title' => $item['description']) : array(),
    !empty($item['query']) ? $item['query'] : NULL,
    !empty($link_item['fragment']) ? $link_item['fragment'] : NULL,
    FALSE,
    $tab
  );
}
// */

/**
 * Duplicate of theme_menu_local_tasks() but adds clear-block to tabs.
 */
/* -- Delete this line if you want to use this function
function zen_menu_local_tasks() {
  $output = '';

  if ($primary = menu_primary_local_tasks()) {
    $output .= '<ul class="tabs primary clear-block">'. $primary .'</ul>';
  }
  if ($secondary = menu_secondary_local_tasks()) {
    $output .= '<ul class="tabs secondary clear-block">'. $secondary .'</ul>';
  }

  return $output;
}
// */

/**
 * Overriding theme_comment_wrapper to add CSS id around all comments
 * and add "Comments" title above
 */
/* -- Delete this line if you want to use this function
function zen_comment_wrapper($content) {
  return '<div id="comments"><h2 id="comments-title" class="title">'. t('Comments') .'</h2>'. $content .'</div>';
}
// */

/**
 * Duplicate of theme_username() with rel=nofollow added for commentators.
 */
/* -- Delete this line if you want to use this function
function zen_username($object) {

  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }

    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('title' => t('View user profile.')));
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if ($object->homepage) {
      $output = l($object->name, $object->homepage, array('rel' => 'nofollow'));
    }
    else {
      $output = check_plain($object->name);
    }

    $output .= ' ('. t('not verified') .')';
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }

  return $output;
}
// */

/* bp utility functions */

function bp_set_global( $key, $value)
{
	global $g_bp;
	
	if ( !is_array($g_bp)) $g_bp = array();
	
	$g_bp[$key] = $value;
}

