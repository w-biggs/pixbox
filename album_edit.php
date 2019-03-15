<?php
/**
 * Edit Pixbox Album screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$tax      = get_taxonomy('pixbox_albums');
$taxonomy = $tax->name;
$title    = $tax->labels->edit_item;

$parent = null;

if (empty($_REQUEST['album_ID'])) {
  $redir = add_query_arg(array( 
    'page' => 'pixbox%2Falbum_new.php',
  ), 'admin.php');
  wp_redirect(esc_url($redir));
  exit;
}

$term = get_term($_REQUEST['album_ID'], $taxonomy);
$term_meta = get_term_meta($term->term_id);

if (!empty($_REQUEST['parent_ID'])) {
  $parent = $_REQUEST['parent_ID'];
}

if (!current_user_can('edit_posts')){
	wp_die(
		'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
		'<p>' . __( 'Sorry, you are not allowed to edit this item.' ) . '</p>',
		403
	);
}

?>
<h1><?= $title ?></h1>

<form action="<?= admin_url('admin-post.php') ?>" class="validate" method="post">
  <input type="hidden" name="action" value="pxbx_album_edit">
  <input type="hidden" name="album_ID" value="<?= $term->term_id ?>">
  <table class="form-table">
    <tr class="form-field form-required">
      <th scope="row"><label for="title"><?= __( 'Album Name', 'pixbox' ) ?></label></th>
      <td><input type="text" name="title" id="title" size="40" aria-required="true" value="<?= esc_attr($term->name); ?>"></td>
    </tr>
    <tr class="form-field">
      <th scope="row"><label for="parent"><?= $tax->labels->parent_item ?></label></th>
      <td>
        <?php
        $dropdown_args = array(
          'hide_empty'       => 0,
          'hide_if_empty'    => false,
          'taxonomy'         => $taxonomy,
          'name'             => 'parent',
          'orderby'          => 'name',
          'selected'         => $term->parent,
          'exclude_tree'     => $term->term_id,
          'hierarchical'     => true,
          'show_option_none' => __( 'None' ),
        );
        /** This filter is documented in wp-admin/edit-tags.php */
        $dropdown_args = apply_filters( 'taxonomy_parent_dropdown_args', $dropdown_args, $taxonomy, 'edit' );
        wp_dropdown_categories( $dropdown_args );
        ?>
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row"><label for="passcheck"><?= __('Password Protected?','pixbox') ?></label></th>
      <td>
        <input type="checkbox" name="passcheck" id="passcheck" <?= array_key_exists('album_pass', $term_meta) ? 'checked' : '' ?>>
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row"><label for="album_pass"><?= __('Password') ?></label></th>
      <td>
        <?php if(array_key_exists('album_pass', $term_meta)): ?>
          <input type="text" name="album_pass" id="album_pass" value="<?= $term_meta['album_pass'][0] ?>">
          <p class="description">To change the password, type in a new one here.</p>
        <?php else: ?>
          <input type="text" name="album_pass" id="album_pass" value="<?= wp_generate_password(); ?>">
          <p class="description">You can either use the generated password or type in your own.</p>
      </td>
      <?php endif; ?>
    </tr>
  </table>
  <?php submit_button(); ?>
</form>