<?php
/**
 * The page template for the Pixbox front-end.
 * 
 * @package pixbox
 * @since 0.3.0
 */

get_header('pixbox');
wp_enqueue_style('pixbox_albums_css');

// Load the root unless there's a specific ID provided
$this_album = 0;
if(!empty($_REQUEST['album'])){
  $this_album = $_REQUEST['album'];
}

// Add node to admin bar
add_action( 'admin_bar_menu', 'pixbox_node', 999);

function pixbox_node($wp_admin_bar){
  $wp_admin_bar->add_node(array(
    "id" => "pixbox_node",
    "title" => "Pixbox",
    "href" => admin_url('admin.php?page=' . get_pxbx_dir() . '%2Falbums.php')
  ));
}

// nonce for ajax requests
$ajax_nonce = wp_create_nonce('pixbox');

// Load the necessary scripts
wp_localize_script('pixbox_front_js', 'phpdata', array(
  'ajaxurl' => admin_url('admin-ajax.php'),
  'dlurl' => plugins_url('../handlers/download.php', __FILE__),
  'nonce' => $ajax_nonce,
  'notFoundText' => __('No albums or photos found.','pixbox'),
  'thisAlbum' => $this_album
));
wp_enqueue_script('pixbox_front_js');
?>
<div class="<?= esc_attr(get_option('pixbox_wrapper_classes')) ?>">
  <div class="pixbox-front">
    <h1 class="pixbox-title">Albums</h1>
    <ul class="pxbx-grid">
      <span class="pxbx-nojs">JavaScript is required to load image albums.</span>
    </ul>
  </div>
</div>
<?php
get_footer('pixbox');