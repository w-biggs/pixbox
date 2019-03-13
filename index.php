<?php
/**
 * Plugin Name: Pixbox
 * Plugin URI: https://github.com/w-biggs/pixbox
 * Version: 0.1.0
 * Description: A private photo albums plugin.
 * Author: W Biggs
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pixbox
 */

// for hashing album passwords
if(!class_exists("PasswordHash")) {
  require_once ABSPATH . WPINC . '/class-phpass.php';
}
$pxbx_hasher = new PasswordHash(16, FALSE);

include plugin_dir_path( __FILE__ ) . 'menu.php';
include plugin_dir_path( __FILE__ ) . 'options.php';
include plugin_dir_path( __FILE__ ) . 'post_tax.php';
include plugin_dir_path( __FILE__ ) . 'handlers/album_new.php';
include plugin_dir_path( __FILE__ ) . 'handlers/album_delete.php';