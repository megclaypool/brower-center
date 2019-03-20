<?php

/*
Plugin Name: Auto Submenu
Plugin URI: http://jamocreations.com/en/artikelen/auto-submenu
Description: WordPress can only automatically add new top-level pages to menus. With Auto Submenu, new child pages will also be automatically added to menus.
Version: 0.2
Author: Jamo Web Creations
Author URI: http://jamocreations.com/
License: GPL2
*/

/*  Copyright 2012  Jamo Web Creations  (email : hello@jamocreations.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class AutoSubmenu {
	
	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'publish_page', array( &$this, 'on_publish_page' ) );
	}
	
	/**
	 * When publishing a new child page, add it to the appropriate custom menu.
	 */
	function on_publish_page( $post_id ) {
		
		// Theme supports custom menus?
		if ( ! current_theme_supports( 'menus' ) ) {
			return;
		}
		
		// Published page has parent?
		$post = get_post( $post_id );
		if ( ! $post->post_parent ) {
			return;
		}
		
		// Get menus with auto_add enabled
		$auto_add = get_option( 'nav_menu_options' );
		if ( empty( $auto_add ) || ! is_array( $auto_add ) || ! isset( $auto_add['auto_add'] ) ) {
			return;
		}
		$auto_add = $auto_add['auto_add'];
		if ( empty( $auto_add ) || ! is_array( $auto_add ) ) {
			return;
		}
		
		// Loop through the menus to find page parent
		foreach ( $auto_add as $menu_id ) {
			$menu_parent = NULL;
			$menu_items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish,draft' ) );
			if ( ! is_array( $menu_items ) ) {
				continue;
			}
			foreach ( $menu_items as $menu_item ) {
				// Item already in menu?
				if ( $menu_item->object_id == $post->ID ) {
					continue 2;
				}
				if ( $menu_item->object_id == $post->post_parent ) {
					$menu_parent = $menu_item;
				}
			}
			// Add new item
			if ( $menu_parent ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-object-id' => $post->ID,
					'menu-item-object' => $post->post_type,
					'menu-item-parent-id' => $menu_parent->ID,
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish'
				) );
			}
		}
	}
	
}

$auto_submenu = new AutoSubmenu();