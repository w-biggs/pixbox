<?php
/**
 * Plugin Name: Pixbox
 * Plugin URI: https://github.com/w-biggs/pixbox
 * Version: 0.5.0
 * Description: A private photo albums plugin.
 * Author: W Biggs
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pixbox
 */

include plugin_dir_path( __FILE__ ) . 'utils.php';
include plugin_dir_path( __FILE__ ) . 'menu.php';
include plugin_dir_path( __FILE__ ) . 'options.php';
include plugin_dir_path( __FILE__ ) . 'post_tax.php';
include plugin_dir_path( __FILE__ ) . 'front_end.php';
include plugin_dir_path( __FILE__ ) . 'notices.php';
include plugin_dir_path( __FILE__ ) . 'handlers/upload.php';
include plugin_dir_path( __FILE__ ) . 'handlers/photo_delete.php';
include plugin_dir_path( __FILE__ ) . 'handlers/album_new.php';
include plugin_dir_path( __FILE__ ) . 'handlers/album_edit.php';
include plugin_dir_path( __FILE__ ) . 'handlers/album_delete.php';
include plugin_dir_path( __FILE__ ) . 'handlers/ajax.php';