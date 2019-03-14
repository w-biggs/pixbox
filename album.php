<?php
/**
 * Chooses Add or Edit Pixbox Album screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

if (empty($_REQUEST['action']) || $_REQUEST['action'] === 'new') {
  include plugin_dir_path( __FILE__ ) . 'album_new.php';
} else {
  include plugin_dir_path( __FILE__ ) . 'album_edit.php';
}