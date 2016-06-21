<?php
/**
 * Plugin Name: OD Profiles
 * Description: Creates new post type for Professional and Business users including a new templater for front-end profile pages.
 * Version: 0.1.0
 * Author: Scott Adrian
 *
 * @copyright 2016
 * @author Scott Adrian
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/profile-pages.php');

