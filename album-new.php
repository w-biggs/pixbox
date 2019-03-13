<?php
/**
 * Add/Edit Pixbox Album screen.
 * 
 * @package pixbox
 * @since 0.1.0
 */

$tax      = get_taxonomy('pixbox_albums');
$taxonomy = $tax->name;
$title    = $tax->labels->add_new_item;

$parent = null;

if (!empty( $_REQUEST['parent_ID'])) {
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

<form action="<?= plugins_url('album-new.php') ?>" class="validate">
  <table class="form-table">
    <tr class="form-field form-required">
      <th scope="row"><label for="title"><?= $tax->labels->new_item_name ?></label></th>
      <td><input type="text" name="title" id="title" size="40" aria-required="true"></td>
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
          'selected'         => $parent,
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
        <input type="checkbox" name="passcheck" id="passcheck">
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row"><label for="albumpass"><?= __('Password') ?></label></th>
      <td>
        <input type="text" name="albumpass" id="albumpass" value="<?= wp_generate_password() ?>">
        <p class="description">You can either use the generated password or type in your own.</p>
      </td>
    </tr>
  </table>
  <?php submit_button(); ?>
</form>